<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Enums\TrangThaiPhieuSanXuat;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuTinh;
use App\Models\PhieuSanXuat;
use App\Models\PhieuSanXuatChiTiet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminPhieuSanXuatController extends Controller
{
    public function index(Request $request)
    {
        $ngay = $request->input('ngay');
        $keyword = $request->input('keyword');
        $nguyen_lieu_id = $request->input('nguyen_lieu_id');

        $queries = PhieuSanXuat::where('trang_thai', '!=', TrangThaiPhieuSanXuat::DELETED());

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (!$nguyen_lieu_id && !$keyword && !$start_date && !$end_date) {
            $start_date2 = Carbon::now()->startOfMonth()->toDateString();
            $end_date2 = Carbon::now()->endOfMonth()->toDateString();

            $queries->whereBetween('ngay', [
                Carbon::parse($start_date2)->format('Y-m-d'),
                Carbon::parse($end_date2)->format('Y-m-d')
            ]);
        }

        if ($start_date && $end_date) {
            $queries->whereBetween('ngay', [
                \Carbon\Carbon::parse($start_date)->format('Y-m-d'),
                Carbon::parse($end_date)->format('Y-m-d')
            ]);
        } elseif ($start_date) {
            $queries->whereDate('ngay', '>=', Carbon::parse($start_date)->format('Y-m-d'));
        } elseif ($end_date) {
            $queries->whereDate('ngay', '<=', Carbon::parse($end_date)->format('Y-m-d'));
        }

        if ($keyword) {
            $queries->where(function ($q) use ($keyword) {
                $q->where('so_lo_san_xuat', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%');
            });
        }

        if ($nguyen_lieu_id) {
            $queries->where('nguyen_lieu_id', $nguyen_lieu_id);
        }

        $datas = $queries->orderByRaw('(COALESCE(tong_khoi_luong, 0) - COALESCE(khoi_luong_da_dung, 0)) DESC')
            ->orderByDesc('id')
            ->get();

        $nltinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
            ->orderByDesc('id')
            ->get();

        $code = $this->generateCode();
        $so_lo_san_xuat = $this->generateLoSanXuat();

        $nsus = User::where('status', '!=', UserStatus::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.phieu_san_xuat.index', compact('datas', 'code', 'so_lo_san_xuat', 'nltinhs', 'ngay', 'keyword',
            'nguyen_lieu_id', 'nsus', 'start_date', 'end_date'));
    }

    private function generateCode()
    {
        $lastItem = PhieuSanXuat::orderByDesc('id')
            ->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }

    private function generateLoSanXuat()
    {
        $lastItem = PhieuSanXuat::orderByDesc('id')
            ->first();

        $lastId = $lastItem?->id;
        return generateLSXCode($lastId + 1);
    }

    public function detail($id)
    {
        $phieu_san_xuat = PhieuSanXuat::find($id);
        if (!$phieu_san_xuat || $phieu_san_xuat->trang_thai == TrangThaiPhieuSanXuat::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy phiếu sản xuất');
        }

        $nltinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
            ->orderByDesc('id')
            ->get();
        $code = $phieu_san_xuat->code;
        if (empty($code)) {
            $code = $this->generateCode();
        }

        $so_lo_san_xuat = $phieu_san_xuat->so_lo_san_xuat;
        if (empty($so_lo_san_xuat)) {
            $so_lo_san_xuat = $this->generateLoSanXuat();
        }

        $nlphanloais = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();

        $dsNLSXChiTiets = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $id)
            ->orderByDesc('id')
            ->get();

        $nsus = User::where('status', '!=', UserStatus::DELETED())
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.phieu_san_xuat.detail', compact('phieu_san_xuat', 'nlphanloais', 'dsNLSXChiTiets', 'nltinhs', 'code', 'so_lo_san_xuat', 'nsus'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $phieu_san_xuat = new PhieuSanXuat();

            $phieu_san_xuat = $this->saveData($phieu_san_xuat, $request);
            $phieu_san_xuat->save();

            $success = $this->saveDataChiTiet($phieu_san_xuat, $request);

            if (!$success) {
                PhieuSanXuat::where('id', $phieu_san_xuat->id)->delete();

                $phieuSanXuatChiTiets = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieu_san_xuat->id)->get();
                foreach ($phieuSanXuatChiTiets as $phieuSanXuatChiTiet) {
                    $nguyenLieuTinh = NguyenLieuTinh::find($phieuSanXuatChiTiet->nguyen_lieu_id);
                    $nguyenLieuTinh->so_luong_da_dung -= $phieuSanXuatChiTiet->khoi_luong;
                    $nguyenLieuTinh->save();
                }
                DB::rollBack();
                return redirect()->route('admin.phieu.san.xuat.index')->with('error', 'Không có đủ nguyên liệu.')->withInput();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Thêm mới phiếu sản xuất thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * @throws \Throwable
     */
    private function saveData(PhieuSanXuat $phieuSanXuat, Request $request)
    {
        $ngay = $request->input('ngay');
        $code = $request->input('code');
        $tong_khoi_luong = $request->input('tong_khoi_luong') ?? 0;
        $so_lo_san_xuat = $request->input('so_lo_san_xuat');
        $thoi_gian_hoan_thanh_san_xuat = $request->input('thoi_gian_hoan_thanh_san_xuat');
        $nguyen_lieu_id = 0;
        $nhan_su_xu_li = $request->input('nhan_su_xu_li');
        $ten_phieu = $request->input('ten_phieu');

        if (!$phieuSanXuat->code) {
            $phieuSanXuat->code = $code;
        }

        $trang_thai = TrangThaiPhieuSanXuat::ACTIVE();

        $phieuSanXuat->so_lo_san_xuat = $so_lo_san_xuat;
        $phieuSanXuat->nguyen_lieu_id = $nguyen_lieu_id;
        $phieuSanXuat->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $phieuSanXuat->trang_thai = $trang_thai;
        $phieuSanXuat->tong_khoi_luong = $tong_khoi_luong;
        $phieuSanXuat->nhan_su_xu_li_id = $nhan_su_xu_li;
        $phieuSanXuat->thoi_gian_hoan_thanh_san_xuat = $thoi_gian_hoan_thanh_san_xuat;
        $phieuSanXuat->ten_phieu = $ten_phieu;

        $phieuSanXuat->don_gia = 0;
        $phieuSanXuat->tong_tien = 0;
        $phieuSanXuat->gia_tri_ton_kho = 0;

        return $phieuSanXuat;
    }

    /**
     * @throws \Throwable
     */
    private function saveDataChiTiet(PhieuSanXuat $phieuSanXuat, Request $request)
    {
        DB::beginTransaction();
        try {
            $nguyen_lieu_ids = $request->input('nguyen_lieu_ids');
            $ten_nguyen_lieus = $request->input('ten_nguyen_lieus');
            $khoi_luongs = $request->input('khoi_luongs');

            $tong_khoi_luong = 0;

            $nguyen_lieu_ids = $nguyen_lieu_ids ?? [];

            $phieuSanXuatChiTiets = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieuSanXuat->id)->get();
            $old_nguyen_lieu_ids = $phieuSanXuatChiTiets->pluck('nguyen_lieu_id')->toArray();

            $tong_tien = 0;

            for ($i = 0; $i < count($nguyen_lieu_ids); $i++) {
                $nguyen_lieu_id = $nguyen_lieu_ids[$i];
                $ten_nguyen_lieu = $ten_nguyen_lieus[$i];
                $khoi_luong = $khoi_luongs[$i];

                $phieuSanXuatChiTiet = new PhieuSanXuatChiTiet();

                $nguyenLieuTinh = NguyenLieuTinh::find($nguyen_lieu_id);

                $tonkho = $nguyenLieuTinh->tong_khoi_luong - $nguyenLieuTinh->so_luong_da_dung;

                if (!is_numeric($khoi_luong)) {
                    return false;
                }

                if (round($tonkho, 3) < round((float)$khoi_luong, 3)) {
                    return false;
                }

                $phieuSanXuatChiTiet->type = '';
                $phieuSanXuatChiTiet->phieu_san_xuat_id = $phieuSanXuat->id;
                $phieuSanXuatChiTiet->nguyen_lieu_id = $nguyen_lieu_id;
                $phieuSanXuatChiTiet->ten_nguyen_lieu = $ten_nguyen_lieu;
                $phieuSanXuatChiTiet->khoi_luong = $khoi_luong;
                $phieuSanXuatChiTiet->so_tien = $khoi_luong * $nguyenLieuTinh->gia_tien;

                $phieuSanXuatChiTiet->save();

                $tong_khoi_luong += $khoi_luong;

                $nguyenLieuTinh->so_luong_da_dung += $khoi_luong;
                $nguyenLieuTinh->save();

                $tong_tien += $khoi_luong * $nguyenLieuTinh->gia_tien;
            }

            $phieuSanXuat->tong_khoi_luong = $tong_khoi_luong;
            $phieuSanXuat->tong_tien = $tong_tien;
            $phieuSanXuat->don_gia = $tong_tien / $tong_khoi_luong;
            $phieuSanXuat->gia_tri_ton_kho = $tong_tien / $tong_khoi_luong * ($tong_khoi_luong - $phieuSanXuat->khoi_luong_da_dung);

            foreach ($phieuSanXuatChiTiets as $phieuSanXuatChiTiet) {
                $nguyenLieuTinh = NguyenLieuTinh::find($phieuSanXuatChiTiet->nguyen_lieu_id);
                $nguyenLieuTinh->so_luong_da_dung -= $phieuSanXuatChiTiet->khoi_luong;
                $nguyenLieuTinh->save();
            }
            PhieuSanXuatChiTiet::whereIn('id', $old_nguyen_lieu_ids)->delete();

            DB::commit();
            return $phieuSanXuat->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $phieuSanXuat = PhieuSanXuat::find($id);
            if (!$phieuSanXuat || $phieuSanXuat->trang_thai == TrangThaiphieuSanXuat::DELETED()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy phiếu sản xuất');
            }

            if ($phieuSanXuat->khoi_luong_da_dung > 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Phiếu sản xuất đã dùng không được xoá!');
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

            DB::commit();
            return redirect()->back()->with('success', 'Đã xoá phiếu sản xuất thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            try {
                DB::beginTransaction();

                $phieuSanXuat = PhieuSanXuat::find($id);
                if (!$phieuSanXuat || $phieuSanXuat->trang_thai == TrangThaiphieuSanXuat::DELETED()) {
                    return redirect()->back()->with('error', 'Không tìm thấy phiếu sản xuất');
                }

                $trang_thai = TrangThaiPhieuSanXuat::ACTIVE();

                $phieuSanXuat->fill([
                    'code' => $phieuSanXuat->code ?: $request->input('code'),
                    'ngay' => Carbon::parse($request->input('ngay'))->format('Y-m-d'),
                    'so_lo_san_xuat' => $request->input('so_lo_san_xuat'),
                    'nguyen_lieu_id' => 0,
                    'trang_thai' => $trang_thai,
                    'tong_khoi_luong' => 0, // Tạm thời
                    'nhan_su_xu_li_id' => $request->input('nhan_su_xu_li'),
                    'thoi_gian_hoan_thanh_san_xuat' => $request->input('thoi_gian_hoan_thanh_san_xuat'),
                    'ten_phieu' => $request->input('ten_phieu'),
                ]);
                $phieuSanXuat->save();

                $tong_khoi_luong = 0;
                $tong_tien = 0;
                $nguyen_lieu_ids = $request->input('nguyen_lieu_ids') ?? [];
                $ten_nguyen_lieus = $request->input('ten_nguyen_lieus');
                $khoi_luongs = $request->input('khoi_luongs');

                $phieuSanXuatChiTiets = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieuSanXuat->id)->get();
                $old_nguyen_lieu_ids = $phieuSanXuatChiTiets->pluck('nguyen_lieu_id')->toArray();

                $no_ids = array_diff($old_nguyen_lieu_ids, $nguyen_lieu_ids);

                for ($i = 0; $i < count($nguyen_lieu_ids); $i++) {
                    $nguyen_lieu_id = $nguyen_lieu_ids[$i];
                    $ten_nguyen_lieu = $ten_nguyen_lieus[$i];
                    $khoi_luong = $khoi_luongs[$i];

                    $nguyenLieu = NguyenLieuTinh::find($nguyen_lieu_id);
                    if (!$nguyenLieu) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh.')->withInput();
                    }

                    $chiTietCu = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieuSanXuat->id)
                        ->where('nguyen_lieu_id', $nguyen_lieu_id)
                        ->first();
                    $tong_khoi_luong_cu = 0;
                    if ($chiTietCu) {
                        $tong_khoi_luong_cu = $chiTietCu->khoi_luong;
                    }

                    $tonkho = $nguyenLieu->tong_khoi_luong - $nguyenLieu->so_luong_da_dung + $tong_khoi_luong_cu;
                    if (round($tonkho, 3) < round((float)$khoi_luong, 3)) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Khối lượng nguyên liệu tinh không đủ')->withInput();
                    }

                    PhieuSanXuatChiTiet::create([
                        'type' => '',
                        'phieu_san_xuat_id' => $phieuSanXuat->id,
                        'nguyen_lieu_id' => $nguyen_lieu_id,
                        'ten_nguyen_lieu' => $ten_nguyen_lieu,
                        'khoi_luong' => $khoi_luong,
                        'so_tien' => $khoi_luong * $nguyenLieu->gia_tien,
                    ]);

                    $tong_khoi_luong += $khoi_luong;

                    $nguyenLieu->so_luong_da_dung += $khoi_luong - $tong_khoi_luong_cu;
                    $nguyenLieu->save();

                    if ($chiTietCu) {
                        $chiTietCu->delete();
                    }

                    $tong_tien += $khoi_luong * $nguyenLieu->gia_tien;
                }

                $phieuSanXuat->tong_khoi_luong = $tong_khoi_luong;
                $phieuSanXuat->tong_tien = $tong_tien;
                $phieuSanXuat->don_gia = $tong_tien / $tong_khoi_luong;
                $phieuSanXuat->gia_tri_ton_kho = $tong_tien / $tong_khoi_luong * ($tong_khoi_luong - $phieuSanXuat->khoi_luong_da_dung);
                $phieuSanXuat->save();

                foreach ($no_ids as $no_id) {
                    $nguyenLieu = NguyenLieuTinh::find($no_id);

                    $phieuSanXuatChiTiet = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieuSanXuat->id)
                        ->where('nguyen_lieu_id', $no_id)
                        ->first();

                    if ($nguyenLieu) {
                        $nguyenLieu->so_luong_da_dung -= $phieuSanXuatChiTiet->khoi_luong;
                        $nguyenLieu->save();
                    }
                }

                if (count($no_ids) > 0) {
                    PhieuSanXuatChiTiet::whereIn('nguyen_lieu_id', $no_ids)->delete();
                }

                DB::commit();
                return redirect()->route('admin.phieu.san.xuat.index')->with('success', 'Chỉnh sửa phiếu sản xuất thành công');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
