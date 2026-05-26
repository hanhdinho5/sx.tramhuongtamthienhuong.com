<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiPhieuSanXuat;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuSanXuat;
use App\Models\PhieuSanXuat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminNguyenLieuSanXuatController extends Controller
{
    public function index(Request $request)
    {
        $ngay_search = $request->input('ngay');
        $keyword = $request->input('keyword');
        $phieu_san_xuat_id = $request->input('phieu_san_xuat_id');

        $queries = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED());

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (!$keyword && !$phieu_san_xuat_id && !$start_date && !$end_date) {
            $start_date2 = Carbon::now()->startOfMonth()->toDateString();
            $end_date2 = Carbon::now()->endOfMonth()->toDateString();

            $queries->whereBetween('ngay', [
                \Carbon\Carbon::parse($start_date2)->format('Y-m-d'),
                Carbon::parse($end_date2)->format('Y-m-d')
            ]);
        }

        if ($start_date && $end_date) {
            $queries->whereBetween('ngay', [
                Carbon::parse($start_date)->format('Y-m-d'),
                Carbon::parse($end_date)->format('Y-m-d')
            ]);
        } elseif ($start_date) {
            $queries->whereDate('ngay', '>=', Carbon::parse($start_date)->format('Y-m-d'));
        } elseif ($end_date) {
            $queries->whereDate('ngay', '<=', Carbon::parse($end_date)->format('Y-m-d'));
        }

        if ($keyword) {
            $queries->where('ten_nguyen_lieu', 'like', '%' . $keyword . '%');
        }

        if ($phieu_san_xuat_id) {
            $queries->where('phieu_san_xuat_id', $phieu_san_xuat_id);
        }

        $datas = $queries->orderByRaw('(COALESCE(khoi_luong, 0) - COALESCE(khoi_luong_da_dung, 0)) DESC')
            ->orderByDesc('id')
            ->get();
        $phieu_san_xuats = PhieuSanXuat::where('trang_thai', '!=', TrangThaiPhieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $nsus = User::where('status', '!=', UserStatus::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_san_xuat.index', compact('datas', 'phieu_san_xuats',
            'ngay_search', 'keyword', 'phieu_san_xuat_id', 'nsus', 'start_date', 'end_date'));
    }

    public function detail($id)
    {
        $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
        if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy');
        }

        $others = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->where('phieu_san_xuat_id', $nguyen_lieu_san_xuat->phieu_san_xuat_id)
            ->where('id', '!=', $nguyen_lieu_san_xuat->id)
            ->orderByDesc('id')
            ->get();

        $phieu_san_xuats = PhieuSanXuat::where('trang_thai', '!=', TrangThaiPhieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $nsus = User::where('status', '!=', UserStatus::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_san_xuat.detail', compact('nguyen_lieu_san_xuat', 'phieu_san_xuats', 'nsus', 'others'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $nguyen_lieu_san_xuat = new NguyenLieuSanXuat();

            $nguyen_lieu_san_xuat = $this->saveDataCreate($nguyen_lieu_san_xuat, $request);
            if (!$nguyen_lieu_san_xuat) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Số lượng không đủ')->withInput();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Thêm mới thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function saveDataCreate(NguyenLieuSanXuat $nguyenLieuSanXuat, Request $request)
    {
        $nhan_vien_san_xuat = $request->input('nhan_vien_san_xuat');
        $phieu_san_xuat_id = $request->input('phieu_san_xuat_id');
        $don_vi_tinh = $request->input('don_vi_tinh') ?? '';
        $mau_sac = $request->input('mau_sac') ?? '';
        $mui_thom = $request->input('mui_thom') ?? '';
        $bao_quan = $request->input('bao_quan') ?? '';
        $type_submit = $request->input('type_submit') ?? '';

        $ngays = $request->input('ngay');
        $ten_nguyen_lieus = $request->input('ten_nguyen_lieu');
        $khoi_luongs = $request->input('khoi_luong');;
        $don_gias = $request->input('gia_lo_san_xuat');
        $tong_tiens = $request->input('tong_tien');
        $chi_tiet_khacs = $request->input('chi_tiet_khac');
        $idxs = $request->input('idx');
        $trang_thai = TrangThaiPhieuSanXuat::ACTIVE();

        foreach ($ten_nguyen_lieus as $key => $ten_nguyen_lieu) {
            $idx = $idxs[$key];
            $ngay = $ngays[$key];
            $khoi_luong = $khoi_luongs[$key];
            $don_gia = $don_gias[$key];
            $tong_tien = $tong_tiens[$key];
            $chi_tiet_khac = $chi_tiet_khacs[$key] ?? '';

            $nguyenLieuSanXuat = new NguyenLieuSanXuat();
            if ($idx) {
                $nguyenLieuSanXuat = NguyenLieuSanXuat::find($idx);
            }

            $oldPhieuSanXuatId = $nguyenLieuSanXuat->phieu_san_xuat_id;
            $oldKhoiLuong = $nguyenLieuSanXuat->khoi_luong;

            $phieuSanXuat = PhieuSanXuat::find($phieu_san_xuat_id);
            if (!$phieuSanXuat || $phieuSanXuat->trang_thai == TrangThaiPhieuSanXuat::DELETED()) {
                return false;
            }

            if ($oldPhieuSanXuatId != $phieu_san_xuat_id) {
                $ton = $phieuSanXuat->tong_khoi_luong - $phieuSanXuat->khoi_luong_da_dung;
                if ($khoi_luong > $ton) {
                    return false;
                }
            } else {
                $ton = $phieuSanXuat->tong_khoi_luong - $phieuSanXuat->khoi_luong_da_dung + $oldKhoiLuong;
                if ($khoi_luong > $ton) {
                    return false;
                }
            }

            if (!$nguyenLieuSanXuat->code) {
                do {
                    $code = generateRandomString(8);
                } while (NguyenLieuSanXuat::where('code', $code)->where('id', '!=', $nguyenLieuSanXuat->id)->exists());

                $nguyenLieuSanXuat->code = $code;
            }

//            if (!$tong_tien || $tong_tien <= 0 || !is_numeric($tong_tien) || $tong_tien == '') {
//                $don_gia = 0;
//                $tong_tien = $don_gia * $khoi_luong;
//            } else {
//                $don_gia = $tong_tien / $khoi_luong;
//            }

            $tong_tien = $phieuSanXuat->tong_tien;

            $don_gia = 0;
//            $tong_tien = $don_gia * $khoi_luong;

            $nguyenLieuSanXuat->ten_nguyen_lieu = $ten_nguyen_lieu;
            $nguyenLieuSanXuat->don_gia = $don_gia;
            $nguyenLieuSanXuat->tong_tien = $tong_tien;
            $nguyenLieuSanXuat->ngay = Carbon::parse($ngay)->format('Y-m-d');
            $nguyenLieuSanXuat->phieu_san_xuat_id = $phieu_san_xuat_id;
            $nguyenLieuSanXuat->khoi_luong = $khoi_luong;
            $nguyenLieuSanXuat->don_vi_tinh = $don_vi_tinh;
            $nguyenLieuSanXuat->mau_sac = $mau_sac ?? '';
            $nguyenLieuSanXuat->mui_thom = $mui_thom ?? '';
            $nguyenLieuSanXuat->chi_tiet_khac = $chi_tiet_khac ?? '';
            $nguyenLieuSanXuat->bao_quan = $bao_quan ?? '';
            $nguyenLieuSanXuat->trang_thai = $trang_thai;
            $nguyenLieuSanXuat->nhan_vien_san_xuat = $nhan_vien_san_xuat;

            if ($oldPhieuSanXuatId != $phieu_san_xuat_id) {
                if ($phieuSanXuat) {
                    $phieuSanXuat->khoi_luong_da_dung += $khoi_luong;
                    $phieuSanXuat->gia_tri_ton_kho += $khoi_luong * $don_gia;
                    $phieuSanXuat->save();
                }

                $phieuSanXuat = PhieuSanXuat::find($oldPhieuSanXuatId);
                if ($phieuSanXuat) {
                    $phieuSanXuat->khoi_luong_da_dung -= $oldKhoiLuong;
                    $phieuSanXuat->gia_tri_ton_kho -= $oldKhoiLuong * $don_gia;
                    $phieuSanXuat->is_completed = false;
                    $phieuSanXuat->save();
                }
            } else {
                $phieuSanXuat = PhieuSanXuat::find($phieu_san_xuat_id);
                if ($phieuSanXuat) {
                    $phieuSanXuat->khoi_luong_da_dung += $khoi_luong - $oldKhoiLuong;
                    $phieuSanXuat->gia_tri_ton_kho += ($khoi_luong - $oldKhoiLuong) * $don_gia;
                    $phieuSanXuat->save();
                }
            }

            if ($type_submit == 'save') {
                $nguyenLieuSanXuat->is_completed = true;
            } else {
                $nguyenLieuSanXuat->is_completed = false;
            }

            $nguyenLieuSanXuat->save();
        }

        if ($type_submit == 'save') {
            $phieuSanXuat = PhieuSanXuat::find($phieu_san_xuat_id);
            $phieuSanXuat->khoi_luong_da_dung = $phieuSanXuat->tong_khoi_luong;
            $phieuSanXuat->gia_tri_ton_kho = 0;
            $phieuSanXuat->is_completed = true;
            $phieuSanXuat->save();

            $nguyenLieuSanXuats = NguyenLieuSanXuat::where('phieu_san_xuat_id', $phieu_san_xuat_id)
                ->where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
                ->get();

            $tong = 0;

            foreach ($nguyenLieuSanXuats as $nguyenLieuSanXuat) {
                $tong += $nguyenLieuSanXuat->khoi_luong;
            }

            $don_gia = $phieuSanXuat->tong_tien / $tong;

            foreach ($nguyenLieuSanXuats as $nguyenLieuSanXuat) {
                $nguyenLieuSanXuat->don_gia = $don_gia;
                $nguyenLieuSanXuat->tong_tien = $phieuSanXuat->tong_tien;

                $nguyenLieuSanXuat->save();
            }
        }

        return true;
    }

    public function delete($id)
    {
        try {
            $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
            if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy');
            }

            if ($nguyen_lieu_san_xuat->khoi_luong_da_dung > 0) {
                return redirect()->back()->with('error', 'Không thể xóa nguyên liệu đã dùng');
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

            return redirect()->back()->with('success', 'Đã xoá thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
            if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy');
            }

            $nguyen_lieu_san_xuat = $this->saveDataCreate($nguyen_lieu_san_xuat, $request);
            if (!$nguyen_lieu_san_xuat) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Số lượng không đủ');
            }

            DB::commit();
            return redirect()->route('admin.nguyen.lieu.san.xuat.index')->with('success', 'Chỉnh sửa thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function saveData(NguyenLieuSanXuat $nguyenLieuSanXuat, Request $request)
    {
        $ten_nguyen_lieu = $request->input('ten_nguyen_lieu');
        $ngay = $request->input('ngay');
        $phieu_san_xuat_id = $request->input('phieu_san_xuat_id');
        $khoi_luong = $request->input('khoi_luong');
        $don_vi_tinh = $request->input('don_vi_tinh') ?? '';
        $don_gia = $request->input('don_gia');
        $mau_sac = $request->input('mau_sac');
        $mui_thom = $request->input('mui_thom');
        $chi_tiet_khac = $request->input('chi_tiet_khac');
        $bao_quan = $request->input('bao_quan');
        $trang_thai = TrangThaiPhieuSanXuat::ACTIVE();
        $nhan_vien_san_xuat = $request->input('nhan_vien_san_xuat');

        $oldPhieuSanXuatId = $nguyenLieuSanXuat->phieu_san_xuat_id;
        $oldKhoiLuong = $nguyenLieuSanXuat->khoi_luong;

        $phieuSanXuat = PhieuSanXuat::find($phieu_san_xuat_id);
        if (!$phieuSanXuat || $phieuSanXuat->trang_thai == TrangThaiPhieuSanXuat::DELETED()) {
            return false;
        }

        if ($oldPhieuSanXuatId != $phieu_san_xuat_id) {
            $ton = $phieuSanXuat->tong_khoi_luong - $phieuSanXuat->khoi_luong_da_dung;
            if ($khoi_luong > $ton) {
                return false;
            }
        } else {
            $ton = $phieuSanXuat->tong_khoi_luong - $phieuSanXuat->khoi_luong_da_dung + $oldKhoiLuong;
            if ($khoi_luong > $ton) {
                return false;
            }
        }

        if (!$nguyenLieuSanXuat->code) {
            do {
                $code = generateRandomString(8);
            } while (NguyenLieuSanXuat::where('code', $code)->where('id', '!=', $nguyenLieuSanXuat->id)->exists());

            $nguyenLieuSanXuat->code = $code;
        }

        if (!$don_gia || $don_gia <= 0 || !is_numeric($don_gia) || $don_gia == '') {
            $don_gia = $phieuSanXuat->don_gia;
        }

        $nguyenLieuSanXuat->ten_nguyen_lieu = $ten_nguyen_lieu;
        $nguyenLieuSanXuat->don_gia = $don_gia;
        $nguyenLieuSanXuat->tong_tien = $don_gia * $khoi_luong;
        $nguyenLieuSanXuat->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $nguyenLieuSanXuat->phieu_san_xuat_id = $phieu_san_xuat_id;
        $nguyenLieuSanXuat->khoi_luong = $khoi_luong;
        $nguyenLieuSanXuat->don_vi_tinh = $don_vi_tinh;
        $nguyenLieuSanXuat->mau_sac = $mau_sac ?? '';
        $nguyenLieuSanXuat->mui_thom = $mui_thom ?? '';
        $nguyenLieuSanXuat->chi_tiet_khac = $chi_tiet_khac ?? '';
        $nguyenLieuSanXuat->bao_quan = $bao_quan ?? '';
        $nguyenLieuSanXuat->trang_thai = $trang_thai;
        $nguyenLieuSanXuat->nhan_vien_san_xuat = $nhan_vien_san_xuat;

        if ($oldPhieuSanXuatId != $phieu_san_xuat_id) {
            if ($phieuSanXuat) {
                $phieuSanXuat->khoi_luong_da_dung += $khoi_luong;
                $phieuSanXuat->save();
            }

            $phieuSanXuat = PhieuSanXuat::find($oldPhieuSanXuatId);
            if ($phieuSanXuat) {
                $phieuSanXuat->khoi_luong_da_dung -= $oldKhoiLuong;
                $phieuSanXuat->save();
            }
        } else {
            $phieuSanXuat = PhieuSanXuat::find($phieu_san_xuat_id);
            if ($phieuSanXuat) {
                $phieuSanXuat->khoi_luong_da_dung += $khoi_luong - $oldKhoiLuong;
                $phieuSanXuat->save();
            }
        }
        return $nguyenLieuSanXuat;
    }
}
