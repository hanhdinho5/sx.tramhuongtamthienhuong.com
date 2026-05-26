<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    use HasFactory;

    public function nhom_khach_hang()
    {
        return $this->belongsTo(NhomKhachHang::class, 'nhom_khach_hang_id', 'id');
    }
}
