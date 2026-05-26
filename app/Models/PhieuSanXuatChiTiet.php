<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhieuSanXuatChiTiet extends Model
{
    use HasFactory;

    protected $fillable = [
        'phieu_san_xuat_id',
        'type',
        'nguyen_lieu_id',
        'ten_nguyen_lieu',
        'khoi_luong',
        'so_tien',
    ];
}
