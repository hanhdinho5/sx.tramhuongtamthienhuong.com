<?php

namespace App\Console\Commands;

use App\Enums\TrangThaiPhieuSanXuat;
use App\Models\NguyenLieuTinh;
use App\Models\PhieuSanXuat;
use App\Models\PhieuSanXuatChiTiet;
use Illuminate\Console\Command;

class UpdatePhieuSanXuat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-phieu-san-xuat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phieu_san_xuats = PhieuSanXuat::where('trang_thai', '!=', TrangThaiPhieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        foreach ($phieu_san_xuats as $phieu_san_xuat) {
            $chiTiets = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieu_san_xuat->id)
                ->get();

            $tong = 0;
            foreach ($chiTiets as $chiTiet) {
                $tinh = NguyenLieuTinh::find($chiTiet->nguyen_lieu_id);
                if ($tinh) {
                    $so_tien = $chiTiet->khoi_luong * $tinh->gia_tien;
                    $tong += $so_tien;
                }
            }

            if ($phieu_san_xuat->tong_khoi_luong > 0) {
                $gia_tien = $tong / $phieu_san_xuat->tong_khoi_luong;
                $ton_kho = $phieu_san_xuat->tong_khoi_luong - $phieu_san_xuat->khoi_luong_da_dung;

                $phieu_san_xuat->tong_tien = $tong;
                $phieu_san_xuat->don_gia = $gia_tien;
                $phieu_san_xuat->gia_tri_ton_kho = $gia_tien * $ton_kho;
                $phieu_san_xuat->save();

                $this->info("✅ Đã cập nhật nguyên liệu ID {$phieu_san_xuat->id} | Đơn giá: {$gia_tien} | KL: {$phieu_san_xuat->tong_khoi_luong} | Tổng tiền: " . ($tong));
            } else {
                \Log::warning("PhieuSanXuat ID {$phieu_san_xuat->id} có tong_khoi_luong = 0");
            }
        }
    }
}
