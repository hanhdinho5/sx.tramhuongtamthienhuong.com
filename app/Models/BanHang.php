<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanHang extends Model
{
    use HasFactory;

    protected $fillable = [
        'khach_hang_id',
        'ban_le',
        'khach_le',
        'so_dien_thoai',
        'dia_chi',
        'loai_san_pham',
        'phuong_thuc_thanh_toan',
        'tong_tien',
        'da_thanht_toan',
        'cong_no',
        'trang_thai',
        'giam_gia',
        'note',
        'ma_don_hang',
        'nguon_hang',
        'loai_nguon_hang',
        'type_discount',
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id', 'id');
    }

    public function loaiQuy()
    {
        return $this->belongsTo(LoaiQuy::class, 'phuong_thuc_thanh_toan', 'id');
    }
}
