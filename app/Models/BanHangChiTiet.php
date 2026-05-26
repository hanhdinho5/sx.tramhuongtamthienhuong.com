<?php

namespace App\Models;

use App\Enums\LoaiSanPham;
use App\Enums\TrangThaiNguyenLieuThanhPham;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanHangChiTiet extends Model
{
    use HasFactory;

    protected $fillable = [
        'ban_hang_id',
        'san_pham_id',
        'gia_ban',
        'so_luong',
        'tong_tien',
        'discount_percent',
        'discount_amount',
    ];

    public function nguyenLieuTho()
    {
        return $this->belongsTo(NguyenLieuTho::class, 'san_pham_id', 'id');
    }

    public function nguyenLieuPhanLoai()
    {
        return $this->belongsTo(NguyenLieuPhanLoai::class, 'san_pham_id', 'id');
    }

    public function nguyenLieuTinh()
    {
        return $this->belongsTo(NguyenLieuTinh::class, 'san_pham_id', 'id');
    }

    public function nguyenLieuSanXuat()
    {
        return $this->belongsTo(NguyenLieuSanXuat::class, 'san_pham_id', 'id');
    }

    public function nguyenLieuThanhpham()
    {
        return $this->belongsTo(NguyenLieuThanhPham::class, 'san_pham_id', 'id');
    }

    public function getNguyenLieu($loai, $id)
    {
        $gia = null;

        switch ($loai) {
            case LoaiSanPham::NGUYEN_LIEU_THO():
                $nguyenlieu = NguyenLieuTho::find($id);

                $con_lai = ($nguyenlieu->khoi_luong ?? 0) - ($nguyenlieu->khoi_luong_da_phan_loai ?? 0);
                $label = ($nguyenlieu->code ?? '') . ' : ' . $con_lai . 'kg';
                $gia = ($nguyenlieu->khoi_luong ?? 0) > 0 ? ($nguyenlieu->chi_phi_mua / $nguyenlieu->khoi_luong) : null;
                break;

            case LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                $nguyenlieu = NguyenLieuPhanLoai::find($id);

                $con_lai = ($nguyenlieu->tong_khoi_luong ?? 0) - ($nguyenlieu->khoi_luong_da_phan_loai ?? 0);
                $label = ($nguyenlieu->nguyenLieuTho->code ?? '') . ' : ' . $con_lai . 'kg';
                $gia = $nguyenlieu->gia_sau_phan_loai ?? null;
                break;

            case LoaiSanPham::NGUYEN_LIEU_TINH():
                $nguyenlieu = NguyenLieuTinh::find($id);

                $con_lai = ($nguyenlieu->tong_khoi_luong ?? 0) - ($nguyenlieu->so_luong_da_dung ?? 0);
                $label = ($nguyenlieu->code ?? '') . ' : ' . $con_lai . 'kg';
                $gia = $nguyenlieu->gia_tien ?? null;
                break;

            case LoaiSanPham::NGUYEN_LIEU_SAN_XUAT():
                $nguyenlieu = NguyenLieuSanXuat::find($id);

                $con_lai = ($nguyenlieu->khoi_luong ?? 0) - ($nguyenlieu->khoi_luong_da_dung ?? 0);
                $label = ($nguyenlieu->ten_nguyen_lieu ?? '') . ' : ' . $con_lai . 'kg';
                $gia = $nguyenlieu->don_gia ?? null;
                break;

            case LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                $nguyenlieu = NguyenLieuThanhPham::where('nguyen_lieu_thanh_phams.trang_thai', '!=', TrangThaiNguyenLieuThanhPham::DELETED())
                    ->join('san_phams', 'san_phams.id', '=', 'nguyen_lieu_thanh_phams.san_pham_id')
                    ->join('nguyen_lieu_san_xuats', 'nguyen_lieu_san_xuats.id', '=', 'nguyen_lieu_thanh_phams.nguyen_lieu_san_xuat_id')
                    ->join('phieu_san_xuats', 'phieu_san_xuats.id', '=', 'nguyen_lieu_san_xuats.phieu_san_xuat_id')
                    ->where('nguyen_lieu_thanh_phams.id', $id)
                    ->select('nguyen_lieu_thanh_phams.*', 'san_phams.ten_san_pham as ten_san_pham', 'san_phams.gia_ban as gia_ban', 'san_phams.don_vi_tinh as don_vi_tinh', 'phieu_san_xuats.so_lo_san_xuat as so_lo_san_xuat')
                    ->first();

                $con_lai = ($nguyenlieu->so_luong ?? 0) - ($nguyenlieu->so_luong_da_ban ?? 0);
                $label = ($nguyenlieu->ten_san_pham ?? '') . ' - ' . ($nguyenlieu->so_lo_san_xuat ?? '') . ' : ' . $con_lai . ' ' . $nguyenlieu->don_vi_tinh;
                $gia = $nguyenlieu->price ?? null;
                break;

            default:
                $label = '';
        }

        return [
            'label' => $label,
            'gia' => $gia,
        ];
    }
}
