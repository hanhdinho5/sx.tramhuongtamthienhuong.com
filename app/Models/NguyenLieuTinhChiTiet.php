<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguyenLieuTinhChiTiet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nguyen_lieu_tinh_id',
        'nguyen_lieu_phan_loai_id',
        'ten_nguyen_lieu',
        'khoi_luong',
        'so_tien',
    ];
}
