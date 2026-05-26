<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguyenLieuSanXuat extends Model
{
    use HasFactory;

    public function PhieuSanXuat()
    {
        return $this->belongsTo(PhieuSanXuat::class, 'phieu_san_xuat_id', 'id');
    }

    public function NhanVien()
    {
        return $this->belongsTo(User::class, 'nhan_vien_san_xuat', 'id');
    }
}
