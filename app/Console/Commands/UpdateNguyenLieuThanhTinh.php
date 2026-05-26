<?php

namespace App\Console\Commands;

use App\Enums\TrangThaiNguyenLieuTinh;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuTinh;
use App\Models\NguyenLieuTinhChiTiet;
use Illuminate\Console\Command;

class UpdateNguyenLieuThanhTinh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-nguyen-lieu-thanh-tinh';

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
        $nguyen_lieu_tinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
            ->orderByDesc('id')
            ->get();

        foreach ($nguyen_lieu_tinhs as $nguyen_lieu_tinh) {
            $chiTiets = NguyenLieuTinhChiTiet::where('nguyen_lieu_tinh_id', $nguyen_lieu_tinh->id)
                ->get();

            $tong = 0;
            foreach ($chiTiets as $chiTiet) {
                $phanLoai = NguyenLieuPhanLoai::find($chiTiet->nguyen_lieu_phan_loai_id);
                if ($phanLoai) {
                    $so_tien = $chiTiet->khoi_luong * $phanLoai->gia_sau_phan_loai;
                    $tong += $so_tien;
                }
            }

            if ($nguyen_lieu_tinh->tong_khoi_luong > 0) {
                $gia_tien = $tong / $nguyen_lieu_tinh->tong_khoi_luong;
                $ton_kho = $nguyen_lieu_tinh->tong_khoi_luong - $nguyen_lieu_tinh->so_luong_da_dung;

                $nguyen_lieu_tinh->tong_tien = $tong;
                $nguyen_lieu_tinh->gia_tien = $gia_tien;
                $nguyen_lieu_tinh->gia_tri_ton_kho = $gia_tien * $ton_kho;
                $nguyen_lieu_tinh->save();

                $this->info("✅ Đã cập nhật nguyên liệu ID {$nguyen_lieu_tinh->id} | Đơn giá: {$gia_tien} | KL: {$nguyen_lieu_tinh->tong_khoi_luong} | Tổng tiền: " . ($tong));
            } else {
                \Log::warning("NguyenLieuTinh ID {$nguyen_lieu_tinh->id} có tong_khoi_luong = 0");
            }
        }
    }
}
