<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguyenLieuPhanLoai extends Model
{
    use HasFactory;

    public function nguyenLieuTho()
    {
        return $this->belongsTo(NguyenLieuTho::class, 'nguyen_lieu_tho_id', 'id');
    }
}
