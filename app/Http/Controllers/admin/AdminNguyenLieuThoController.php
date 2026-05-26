<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuTho;
use App\Enums\TrangThaiNhaCungCap;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\LoaiQuy;
use App\Models\NguyenLieuTho;
use App\Models\NhaCungCaps;
use App\Models\SoQuy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminNguyenLieuThoController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $nha_cung_cap_id = $request->input('nha_cung_cap_id');

        $queries = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED());

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (!$keyword && !$nha_cung_cap_id && !$start_date && !$end_date) {
            $start_date2 = Carbon::now()->startOfMonth()->toDateString();
            $end_date2 = Carbon::now()->endOfMonth()->toDateString();

            $queries->whereBetween('ngay', [
                Carbon::parse($start_date2)->format('Y-m-d'),
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
            $queries->where(function ($q) use ($keyword) {
                $q->where('ten_nguyen_lieu', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%');
            });
        }

        if ($nha_cung_cap_id) {
            $queries->where('nha_cung_cap_id', $nha_cung_cap_id);
        }

        $datas = $queries->orderByRaw('(COALESCE(khoi_luong, 0) - COALESCE(khoi_luong_da_phan_loai, 0) - COALESCE(khoi_luong_da_ban, 0)) DESC')
            ->orderByDesc('id')
            ->get();
        $nccs = NhaCungCaps::where('trang_thai', '!=', TrangThaiNhaCungCap::DELETED())
            ->orderByDesc('id')
            ->get();

        $code = $this->generateCode();

        $nsus = User::where('status', '!=', UserStatus::DELETED())
            ->orderByDesc('id')
            ->get();

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.pages.nguyen_lieu_tho.index', compact('datas', 'nccs', 'code',
            'nsus', 'start_date', 'end_date', 'keyword', 'nha_cung_cap_id', 'loai_quies'));
    }

    private function generateCode()
    {
        $lastItem = NguyenLieuTho::orderByDesc('id')->first();

        $lastId = $lastItem?->id;
        return generateCode($lastId + 1);
    }

    public function detail($id)
    {
        $nguyen_lieu_tho = NguyenLieuTho::find($id);
        if (!$nguyen_lieu_tho || $nguyen_lieu_tho->trang_thai == TrangThaiNguyenLieuTho::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thô');
        }

        $nccs = NhaCungCaps::where('trang_thai', '!=', TrangThaiNhaCungCap::DELETED())
            ->orderByDesc('id')
            ->get();

        $code = $nguyen_lieu_tho->code;
        if (!$code) {
            $code = $this->generateCode();
        }

        $nsus = User::where('status', '!=', UserStatus::DELETED())
            ->orderByDesc('id')
            ->get();

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.pages.nguyen_lieu_tho.detail', compact('nguyen_lieu_tho', 'nccs', 'code', 'nsus', 'loai_quies'));
    }

    public function store(Request $request)
    {
        try {
            $nguyen_lieu_tho = new NguyenLieuTho();

            $nguyen_lieu_tho = $this->saveData($nguyen_lieu_tho, $request);

            if (!$nguyen_lieu_tho) {
                return redirect()->back()->with('error', 'Số tiền thanh toán không hợp lệ hoặc tiền quỹ không đủ!')->withInput();
            }

            $nguyen_lieu_tho->save();

            $new_id = $nguyen_lieu_tho->phuong_thuc_thanh_toan;

            $this->insertSoQuy($nguyen_lieu_tho, false, null, $new_id);

            return redirect()->back()->with('success', 'Thêm mới nguyên liệu thô thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * @throws \Throwable
     */
    private function saveData(NguyenLieuTho $nguyenLieuTho, Request $request)
    {
        $ngay = $request->input('ngay');
        $nha_cung_cap_id = $request->input('nha_cung_cap_id');
        $ten_nguyen_lieu = $request->input('ten_nguyen_lieu');
        $loai = $request->input('loai');
        $nguon_goc = $request->input('nguon_goc');
        $khoi_luong = $request->input('khoi_luong');
        $kich_thuoc = $request->input('kich_thuoc');
        $do_kho = $request->input('do_kho');
        $dieu_kien_luu_tru = $request->input('dieu_kien_luu_tru');
        $chi_phi_mua = $request->input('chi_phi_mua');
        $phuong_thuc_thanh_toan = $request->input('phuong_thuc_thanh_toan');
        $so_tien_thanh_toan = $request->input('so_tien_thanh_toan');
        $nhan_su_xu_li = $request->input('nhan_su_xu_li');
        $thoi_gian_phan_loai = $request->input('thoi_gian_phan_loai');
        $ghi_chu = $request->input('ghi_chu');
        $trang_thai = $request->input('trang_thai');
        $code = $request->input('code');

        $nguyenLieuTho->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $nguyenLieuTho->nha_cung_cap_id = $nha_cung_cap_id ?? '';
        $nguyenLieuTho->ten_nguyen_lieu = $ten_nguyen_lieu ?? '';
        $nguyenLieuTho->loai = $loai ?? '';
        $nguyenLieuTho->nguon_goc = $nguon_goc ?? '';
        $nguyenLieuTho->khoi_luong = $khoi_luong ?? '';
        $nguyenLieuTho->kich_thuoc = $kich_thuoc ?? '';
        $nguyenLieuTho->do_kho = $do_kho ?? '';
        $nguyenLieuTho->dieu_kien_luu_tru = $dieu_kien_luu_tru ?? '';
        $nguyenLieuTho->chi_phi_mua = $chi_phi_mua ?? '';
        $nguyenLieuTho->phuong_thuc_thanh_toan = $phuong_thuc_thanh_toan ?? '';
        $nguyenLieuTho->so_tien_thanh_toan = $so_tien_thanh_toan ?? '';

        $loaiQuy = LoaiQuy::find($phuong_thuc_thanh_toan);
        if (!$loaiQuy) {
            return false;
        }

        $tong_tien = $loaiQuy->tong_tien_quy;

        if ($so_tien_thanh_toan < 0) {
            return false;
        }

        if ($so_tien_thanh_toan > $chi_phi_mua) {
            return false;
        }

        if ($so_tien_thanh_toan > $tong_tien) {
            return false;
        }

        $nguyenLieuTho->cong_no = $chi_phi_mua - $so_tien_thanh_toan;
        $nguyenLieuTho->nhan_su_xu_li = $nhan_su_xu_li;
        $nguyenLieuTho->thoi_gian_phan_loai = Carbon::parse($thoi_gian_phan_loai)->format('Y-m-d');
        $nguyenLieuTho->ghi_chu = $ghi_chu ?? '';
        $nguyenLieuTho->trang_thai = $trang_thai;
        if ($code) {
            $nguyenLieuTho->code = $code;
        }

        return $nguyenLieuTho;
    }

    /**
     * @throws \Throwable
     */
    private function insertSoQuy(NguyenLieuTho $nguyenLieuTho, $so_quy_id, $old_quy_id, $new_quy_id, $old_thanh_toan = null)
    {
        if (!$so_quy_id) {
            $code = $this->generateSoQuyCode();
            $soquy = new SoQuy();
            $soquy->loai = 0;
            $soquy->so_tien = $nguyenLieuTho->so_tien_thanh_toan;
            $soquy->gia_tri_id = $nguyenLieuTho->id;
            $soquy->ngay = Carbon::now();
            $soquy->noi_dung = 'Phiếu chi mua hàng cho nguyên liệu thô: #' . $nguyenLieuTho->id . ' - MDH: ' . $nguyenLieuTho->code;
            $soquy->ma_phieu = $code;
            $soquy->loai_quy_id = $new_quy_id;
            $soquy->loai_noi_nhan = 'ncc';
            $soquy->save();

            $loai_quy = LoaiQuy::find($new_quy_id);
            if ($loai_quy) {
                $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $soquy->so_tien;
                $loai_quy->save();
            }
        } else {
            $soquy = SoQuy::where('gia_tri_id', $nguyenLieuTho->id)
                ->where('loai', 0)
                ->first();

            $old_so_tien = $soquy->so_tien;

            $new_thanh_toan = $nguyenLieuTho->so_tien_thanh_toan;

            if ($soquy) {
                $soquy->loai = 0;
                $soquy->so_tien = $new_thanh_toan;
                $soquy->ngay = Carbon::now();
                $soquy->gia_tri_id = $nguyenLieuTho->id;
                $soquy->loai_noi_nhan = 'ncc';
                $soquy->noi_dung = 'Phiếu chi mua hàng cho nguyên liệu thô: #' . $nguyenLieuTho->id . ' - MDH: ' . $nguyenLieuTho->code;
                $soquy->save();

                if ($soquy->loai_quy_id != $new_quy_id) {
                    $loai_quy = LoaiQuy::find($soquy->loai_quy_id);

                    if ($loai_quy) {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy + $old_thanh_toan;
                        $loai_quy->save();
                    }

                    $loai_quy = LoaiQuy::find($new_quy_id);

                    if ($loai_quy) {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $soquy->so_tien;
                        $loai_quy->save();
                    }
                } else {
                    $loai_quy = LoaiQuy::find($new_quy_id);
                    if ($loai_quy) {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $soquy->so_tien + $old_thanh_toan;
                        $loai_quy->save();
                    }
                }

                $soquy->loai_quy_id = $new_quy_id;
                $soquy->save();
            }
        }
    }

    private function generateSoQuyCode()
    {
        $lastItem = SoQuy::orderByDesc('id')->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }

    public function delete($id)
    {
        try {
            $nguyen_lieu_tho = NguyenLieuTho::find($id);
            if (!$nguyen_lieu_tho || $nguyen_lieu_tho->trang_thai == TrangThaiNguyenLieuTho::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thô');
            }

            if ($nguyen_lieu_tho->khoi_luong_da_phan_loai) {
                return redirect()->back()->with('error', 'Không thể xoá xoá nguyên liệu thô đã phân loại');
            }

            $success = NguyenLieuTho::where('id', $id)
//                ->where('khoi_luong_da_phan_loai', null)
//                ->orWhere('khoi_luong_da_phan_loai', 0)
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

            if ($success) {
                return redirect()->back()->with('success', 'Đã xoá nguyên liệu thô thành công');
            }

            return redirect()->back()->with('error', 'Đã xảy ra lỗi trong quá trình xoá nguyên liệu thô');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $nguyen_lieu_tho = NguyenLieuTho::find($id);
            if (!$nguyen_lieu_tho || $nguyen_lieu_tho->trang_thai == TrangThaiNguyenLieuTho::DELETED()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thô');
            }

            $old_id = $nguyen_lieu_tho->phuong_thuc_thanh_toan;
            $old_thanh_toan = $nguyen_lieu_tho->so_tien_thanh_toan;

            $nguyen_lieu_tho = $this->saveData($nguyen_lieu_tho, $request);

            if (!$nguyen_lieu_tho) {
                return redirect()->back()->with('error', 'Số tiền thanh toán không hợp lệ hoặc tiền quỹ không đủ!');
            }

            $nguyen_lieu_tho->save();

            $new_id = $nguyen_lieu_tho->phuong_thuc_thanh_toan;

            $this->insertSoQuy($nguyen_lieu_tho, true, $old_id, $new_id, $old_thanh_toan);

            return redirect()->route('admin.nguyen.lieu.tho.index')->with('success', 'Chỉnh sửa nguyên liệu thô thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
