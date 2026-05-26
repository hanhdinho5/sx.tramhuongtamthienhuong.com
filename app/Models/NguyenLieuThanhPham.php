<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguyenLieuThanhPham extends Model
{
    use HasFactory;

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'san_pham_id', 'id');
    }

    public function nguyenLieuSanXuat()
    {
        return $this->belongsTo(NguyenLieuSanXuat::class, 'nguyen_lieu_san_xuat_id', 'id');
    }
}
