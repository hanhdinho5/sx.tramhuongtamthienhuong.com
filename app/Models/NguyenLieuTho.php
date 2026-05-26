<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguyenLieuTho extends Model
{
    use HasFactory;

    public function NhaCungCap()
    {
        return $this->belongsTo(NhaCungCaps::class, 'nha_cung_cap_id', 'id');
    }

    public function loaiQuy()
    {
        return $this->belongsTo(LoaiQuy::class, 'phuong_thuc_thanh_toan', 'id');
    }
}
