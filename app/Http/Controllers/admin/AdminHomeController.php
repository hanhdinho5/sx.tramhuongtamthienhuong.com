<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiBanHang;
use App\Enums\TrangThaiKhachHang;
use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiNguyenLieuThanhPham;
use App\Enums\TrangThaiNguyenLieuTho;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Enums\TrangThaiNhaCungCap;
use App\Enums\TrangThaiPhieuSanXuat;
use App\Enums\TrangThaiSanPham;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\BanHang;
use App\Models\KhachHang;
use App\Models\LoaiQuy;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuThanhPham;
use App\Models\NguyenLieuTho;
use App\Models\NguyenLieuTinh;
use App\Models\NguyenLieuTinhChiTiet;
use App\Models\NhaCungCaps;
use App\Models\PhieuSanXuat;
use App\Models\PhieuSanXuatChiTiet;
use App\Models\SanPham;
use App\Models\SoQuy;
use App\Models\ThongTin;
use App\Models\User;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    public function index(Request $request)
    {
        return $this->get_data_so_quy_index($request, 'admin.index');
    }

    public function deleteItem(Request $request)
    {
        try {
            $list_id = $request->input('list_id');
            $type = $request->input('type');

            switch ($type) {
                case "tho":
                    foreach ($list_id as $id) {
                        $nguyen_lieu_tho = NguyenLieuTho::find($id);
                        if (!$nguyen_lieu_tho || $nguyen_lieu_tho->trang_thai == TrangThaiNguyenLieuTho::DELETED()) {
                            continue;
                        }

                        if ($nguyen_lieu_tho->khoi_luong_da_phan_loai) {
                            continue;
                        }

                        NguyenLieuTho::where('id', $id)
                            ->update(['trang_thai' => TrangThaiNguyenLieuTho::DELETED()]);

                        $soquy = SoQuy::where('gia_tri_id', $id)->where('loai', 0)->first();
                        if ($soquy) {
                            $loai_quy = LoaiQuy::find($soquy->loai_quy_id);;
                            if ($loai_quy) {
                                if ($soquy->loai == 1) {
                                    $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $soquy->so_tien;
                                    $loai_quy->save();
                                } else {
                                    $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy + $soquy->so_tien;
                                    $loai_quy->save();
                                }
                            }

                            $soquy->delete();
                        }
                    }
                    break;
                case "phan_loai":
                    foreach ($list_id as $id) {
                        $nguyen_lieu_phan_loai = NguyenLieuPhanLoai::find($id);
                        if (!$nguyen_lieu_phan_loai || $nguyen_lieu_phan_loai->trang_thai == TrangThaiNguyenLieuPhanLoai::DELETED()) {
                            continue;
                        }

                        NguyenLieuPhanLoai::where('id', $id)
                            ->where('khoi_luong_da_phan_loai', null)
                            ->orWhere('khoi_luong_da_phan_loai', 0)
                            ->update(['trang_thai' => TrangThaiNguyenLieuPhanLoai::DELETED()]);

                        $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_phan_loai->nguyen_lieu_tho_id)->first();
                        if ($nguyenLieuTho) {
                            $nguyenLieuTho->khoi_luong_da_phan_loai = $nguyenLieuTho->khoi_luong_da_phan_loai - $nguyen_lieu_phan_loai->khoi_luong_ban_dau;
                            $nguyenLieuTho->save();

                            $otherNguyenLieuTho = NguyenLieuPhanLoai::where('nguyen_lieu_tho_id', $nguyenLieuTho->id)
                                ->where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
                                ->first();

                            if (!$otherNguyenLieuTho) {
                                $nguyenLieuTho->allow_change = true;
                                $nguyenLieuTho->save();
                            }
                        }
                    }
                    break;
                case "tinh":

                    foreach ($list_id as $id) {
                        $nguyen_lieu_tinh = NguyenLieuTinh::find($id);
                        if (!$nguyen_lieu_tinh || $nguyen_lieu_tinh->trang_thai == TrangThaiNguyenLieuTinh::DELETED()) {
                            continue;
                        }

                        NguyenLieuTinh::where('id', $id)
                            ->where('so_luong_da_dung', null)
                            ->orWhere('so_luong_da_dung', 0)
                            ->update(['trang_thai' => TrangThaiNguyenLieuTinh::DELETED()]);

                        if ($nguyen_lieu_tinh->so_luong_da_dung) {
                            continue;
                        }

                        $chiTiets = NguyenLieuTinhChiTiet::where('nguyen_lieu_tinh_id', $id)->get();
                        foreach ($chiTiets as $chiTiet) {
                            $nguyenLieuPhanLoai = NguyenLieuPhanLoai::find($chiTiet->nguyen_lieu_phan_loai_id);
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
                                    'Tăm nhanh sào' => 'tam_nhanh_sao',
                                ];

                                $ten = $chiTiet->ten_nguyen_lieu;

                                if (isset($mapping[$ten])) {
                                    $field = $mapping[$ten];
                                    $nguyenLieuPhanLoai->$field += $chiTiet->khoi_luong;
                                }

                                $nguyenLieuPhanLoai->khoi_luong_da_phan_loai -= $chiTiet->khoi_luong;
                                $nguyenLieuPhanLoai->save();
                            }
                        }
                    }
                    break;
                case "phieu_san_xuat":
                    foreach ($list_id as $id) {
                        $phieuSanXuat = PhieuSanXuat::find($id);
                        if (!$phieuSanXuat || $phieuSanXuat->trang_thai == TrangThaiphieuSanXuat::DELETED()) {
                            continue;
                        }

                        if ($phieuSanXuat->khoi_luong_da_dung > 0) {
                            continue;
                        }

                        if ($phieuSanXuat) {
                            if (!$phieuSanXuat->khoi_luong_da_dung || $phieuSanXuat->khoi_luong_da_dung == 0) {
                                $phieuSanXuat->trang_thai = TrangThaiPhieuSanXuat::DELETED();
                                $phieuSanXuat->save();
                                $phieuSanXuatChiTiets = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $id)->get();
                                foreach ($phieuSanXuatChiTiets as $phieuSanXuatChiTiet) {
                                    $nguyenLieuTinh = NguyenLieuTinh::find($phieuSanXuatChiTiet->nguyen_lieu_id);
                                    $nguyenLieuTinh->so_luong_da_dung -= $phieuSanXuatChiTiet->khoi_luong;
                                    $nguyenLieuTinh->save();
                                }
                            }
                        }
                    }
                    break;
                case "thanh_pham":
                    foreach ($list_id as $id) {
                        $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
                        if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
                            continue;
                        }

                        if ($nguyen_lieu_san_xuat->khoi_luong_da_dung > 0) {
                            continue;
                        }

                        $nguyen_lieu_san_xuat->trang_thai = TrangThaiNguyenLieuSanXuat::DELETED();
                        $success = $nguyen_lieu_san_xuat->save();

                        if ($success) {
                            $phieuSanXuat = PhieuSanXuat::find($nguyen_lieu_san_xuat->phieu_san_xuat_id);
                            if ($phieuSanXuat) {
                                $phieuSanXuat->khoi_luong_da_dung -= $nguyen_lieu_san_xuat->khoi_luong;

                                if ($phieuSanXuat->is_completed == 1) {
                                    $phieuSanXuat->is_completed = 0;

                                    $ngLieus = NguyenLieuSanXuat::where('phieu_san_xuat_id', $phieuSanXuat->id)
                                        ->where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
                                        ->get();

                                    $kl = 0;
                                    foreach ($ngLieus as $ngLieu) {
                                        $kl += $ngLieu->khoi_luong;
                                    }

                                    $phieuSanXuat->khoi_luong_da_dung = $kl;
                                }

                                $phieuSanXuat->save();
                            }
                        }
                    }
                    break;
                case "dong_goi":
                    foreach ($list_id as $id) {
                        $nguyenLieuThanhPham = NguyenLieuThanhPham::find($id);
                        if (!$nguyenLieuThanhPham || $nguyenLieuThanhPham->trang_thai == TrangThaiNguyenLieuThanhPham::DELETED()) {
                            continue;
                        }

                        $nguyenLieuThanhPham->trang_thai = TrangThaiNguyenLieuThanhPham::DELETED();
                        $success = $nguyenLieuThanhPham->save();

                        if ($success) {
                            $sanPham = SanPham::find($nguyenLieuThanhPham->san_pham_id);
                            if ($sanPham) {
                                $sanPham->ton_kho = $sanPham->ton_kho - $nguyenLieuThanhPham->so_luong;
                                $sanPham->save();
                            }

                            $nguyenLieuSanXuat = NguyenLieuSanXuat::find($nguyenLieuThanhPham->nguyen_lieu_san_xuat_id);
                            if ($nguyenLieuSanXuat) {
                                $nguyenLieuSanXuat->khoi_luong_da_dung -= $nguyenLieuThanhPham->khoi_luong_da_dung;
                                $nguyenLieuSanXuat->save();
                            }
                        }
                    }
                    break;
                case "san_pham":
                    SanPham::whereIn('id', $list_id)
                        ->update(['trang_thai' => TrangThaiSanPham::DELETED()]);
                    break;
                case "ban_hang":
                    BanHang::whereIn('id', $list_id)
                        ->update(['trang_thai' => TrangThaiBanHang::DELETED()]);
                    break;
                case "nha_cung_cap":
                    NhaCungCaps::whereIn('id', $list_id)
                        ->update(['trang_thai' => TrangThaiNhaCungCap::DELETED()]);
                    break;
                case "khach_hang":
                    KhachHang::whereIn('id', $list_id)
                        ->update(['trang_thai' => TrangThaiKhachHang::DELETED()]);
                    break;
                case "user":
                    User::whereIn('id', $list_id)
                        ->update(['trang_thai' => UserStatus::DELETED()]);
                    break;
                case "thong_tin":
                    ThongTin::whereIn('id', $list_id)->delete();
                    break;
                case "loai_quy":
                    LoaiQuy::whereIn('id', $list_id)
                        ->where('tong_tien_quy', 0)
                        ->delete();
                    break;
            }
            $data = returnMessage(1, '', 'Success, delete successfully!');
            return response()->json($data)->setStatusCode(200);
        } catch (\Exception $e) {
            $data = returnMessage(0, null, 'Error, please try again!');
            return response()->json($data)->setStatusCode(400);
        }
    }
}
