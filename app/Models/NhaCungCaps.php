<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhaCungCaps extends Model
{
    use HasFactory;

    public function NguyenLieuThos()
    {
        return $this->hasMany(NguyenLieuTho::class);
    }
}
