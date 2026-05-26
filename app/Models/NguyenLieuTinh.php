<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguyenLieuTinh extends Model
{
    use HasFactory;

    protected $fillable = [
        'ngay',
        'code',
        'trang_thai',
        'tong_khoi_luong',
        'gia_tien',
        'ten_nguyen_lieu',
        'ma_phieu',
        'so_luong_da_dung',
    ];

    public function get_list_child(NguyenLieuTinh $nguyenLieuTinh): string
    {
        $nguyenLieuTinhChiTiets = NguyenLieuTinhChiTiet::where('nguyen_lieu_tinh_id', $nguyenLieuTinh->id)->get();

        $arr = array();

        foreach ($nguyenLieuTinhChiTiets as $nguyenLieuTinhChiTiet) {
            $nguyenLieuPhanLoai = NguyenLieuPhanLoai::find($nguyenLieuTinhChiTiet->nguyen_lieu_phan_loai_id);

            if ($nguyenLieuPhanLoai) {
                $url = route('admin.nguyen.lieu.tho.detail', $nguyenLieuPhanLoai->nguyenLieuTho->id);
                $arr[] = '<a href="' . $url . '">' . $nguyenLieuPhanLoai->nguyenLieuTho->code . '</a>';
            }
        }

        return implode(', ', $arr);
    }
}
