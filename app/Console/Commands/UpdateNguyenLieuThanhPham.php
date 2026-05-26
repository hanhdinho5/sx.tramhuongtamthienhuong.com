<?php

namespace App\Console\Commands;

use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Models\NguyenLieuSanXuat;
use App\Models\PhieuSanXuat;
use App\Models\PhieuSanXuatChiTiet;
use Illuminate\Console\Command;

class UpdateNguyenLieuThanhPham extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-nguyen-lieu-thanh-pham';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật đơn giá và tổng tiền của nguyên liệu thành phẩm theo phiếu sản xuất';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nguyen_lieu_thanh_phams = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->get();

        if ($nguyen_lieu_thanh_phams->isEmpty()) {
            $this->info('Không có nguyên liệu nào cần cập nhật.');
            return;
        }

        foreach ($nguyen_lieu_thanh_phams as $nguyen_lieu_thanh_pham) {
            $phieu_san_xuat_id = $nguyen_lieu_thanh_pham->phieu_san_xuat_id;
            $khoi_luong = $nguyen_lieu_thanh_pham->khoi_luong;

            $phieuSanXuat = PhieuSanXuat::find($phieu_san_xuat_id);

            if (!$phieuSanXuat) {
                $this->warn("Không tìm thấy phiếu sản xuất ID: {$phieu_san_xuat_id}");
                continue;
            }

            if ($phieuSanXuat->tong_khoi_luong == 0) {
                $this->warn("Phiếu sản xuất ID {$phieu_san_xuat_id} có tổng khối lượng bằng 0.");
                continue;
            }


            $don_gia = $phieuSanXuat->don_gia;
            $nguyen_lieu_thanh_pham->don_gia = $don_gia;
            $nguyen_lieu_thanh_pham->tong_tien = $don_gia * $khoi_luong;
            $nguyen_lieu_thanh_pham->save();

            $this->info("✅ Đã cập nhật nguyên liệu ID {$nguyen_lieu_thanh_pham->id} | Đơn giá: {$don_gia} | Tổng tiền: " . ($don_gia * $khoi_luong));
        }

        $this->info('🎉 Hoàn tất cập nhật!');
    }
}
