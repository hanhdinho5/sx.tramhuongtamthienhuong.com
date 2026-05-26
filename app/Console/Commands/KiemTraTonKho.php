<?php

namespace App\Console\Commands;

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuTho;
use App\Models\NguyenLieuTinh;
use App\Models\NguyenLieuTinhChiTiet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class KiemTraTonKho extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kiem-tra-ton-kho';

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
        $loai = $this->choice(
            'Bạn muốn kiểm tra tồn kho theo gì?',
            ['Nguyên liệu thô', 'Nguyên liệu phân loại', 'Nguyên liệu tinh', 'Phiếu sản xuất', 'Nguyên liệu sản xuất', 'Nguyên liệu thành phầm'],
            0
        );

        switch ($loai) {
            case 'Nguyên liệu thô':
                Log::info('Nguyên liệu thô');
                $this->info("Nguyên liệu thô");
                $nguyenLieuPhanLoais = NguyenLieuPhanLoai::with('nguyenLieuTho')
                    ->where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
                    ->get();

                foreach ($nguyenLieuPhanLoais as $nguyenLieuPhanLoai) {
                    $nguyenLieuTho = $nguyenLieuPhanLoai->nguyenLieuTho;

                    if ($nguyenLieuTho) {
                        $nguyenLieuTho->khoi_luong_da_phan_loai = $nguyenLieuTho->khoi_luong;
                        $nguyenLieuTho->save();

                        $nguyenLieuPhanLoai->khoi_luong_ban_dau = $nguyenLieuTho->khoi_luong;
                        $nguyenLieuPhanLoai->khoi_luong_hao_hut = $nguyenLieuTho->khoi_luong - $nguyenLieuPhanLoai->tong_khoi_luong;
                        $nguyenLieuPhanLoai->save();
                    }
                }
                break;
            case 'Nguyên liệu phân loại':
                Log::info('Nguyên liệu phân loại');
                $nguyenLieuTinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())->get();

                foreach ($nguyenLieuTinhs as $nguyenLieuTinh) {
                    $chiTietNguyenLieuTinhs = NguyenLieuTinhChiTiet::where('nguyen_lieu_tinh_id', $nguyenLieuTinh->id)->get();

                    foreach ($chiTietNguyenLieuTinhs as $chiTietNguyenLieuTinh) {
                        $nguyenLieuPhanLoai = NguyenLieuPhanLoai::find($chiTietNguyenLieuTinh->nguyen_lieu_phan_loai_id);

                        if ($nguyenLieuPhanLoai) {
                            $mapping = [
                                'Nguyên liệu nụ cao cấp (NCC)' => 'nu_cao_cap',
                                'Nguyên liệu nụ VIP (NVIP)' => 'nu_vip',
                                'Nguyên liệu nhang (NLN)' => 'nhang',
                                'Nguyên liệu vòng (NLV)' => 'vong',
                                'Tăm dài' => 'tam_dai',
                                'Tăm ngắn' => 'tam_ngan',
                                'Nước cất' => 'nuoc_cat',
                                'Keo' => 'keo',
                                'Nấu dầu' => 'nau_dau',
                            ];
                            $ten = $chiTietNguyenLieuTinh->ten_nguyen_lieu;
                            $khoi_luong = $chiTietNguyenLieuTinh->khoi_luong;
                            if (isset($mapping[$ten])) {
                                $field = $mapping[$ten];
                                $nguyenLieuPhanLoai->$field -= $khoi_luong;
                            }

                            $nguyenLieuPhanLoai->khoi_luong_da_phan_loai += $khoi_luong;
                            $nguyenLieuPhanLoai->save();
                        }
                    }
                }
                break;
            case 'Nguyên liệu tinh':
                Log::info('Nguyên liệu tinh');
                break;
            case 'Phiếu sản xuất':
                Log::info('Phiếu sản xuất');
                break;
            case 'Nguyên liệu sản xuất':
                Log::info('Kiểm tra tồn kho Nguyên liệu sản xuất');
                break;
            case 'Nguyên liệu thành phầm':
                Log::info('Kiểm tra tồn kho Nguyên liệu thành phầm');
                break;
        }
    }
}
