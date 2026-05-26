<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoQuy extends Model
{
    use HasFactory;

    public function loaiQuy()
    {
        return $this->belongsTo(LoaiQuy::class, 'loai_quy_id', 'id');
    }

    public function nhomQuy()
    {
        return $this->belongsTo(NhomQuy::class, 'nhom_quy_id', 'id');
    }
}
