<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuTho;
use App\Http\Controllers\Controller;
use App\Models\LoaiQuy;
use App\Models\NguyenLieuTho;
use App\Models\NhomQuy;
use App\Models\SoQuy;
use Illuminate\Http\Request;

class AdminSoQuyController extends Controller
{
    public function index(Request $request)
    {
        return $this->get_data_so_quy_index($request, 'admin.pages.so_quy.index');
    }

    public function payment(Request $request)
    {
        $nguyenLieuThos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->where('cong_no', '>', 0)
            ->orderByDesc('id')
            ->get();

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.pages.so_quy.payment', compact('nguyenLieuThos', 'loai_quies'));
    }

    public function payment_store(Request $request)
    {
        try {
            $ma_phieu = $this->generateCode();
            $ngay = $request->input('ngay');
            $so_tien = $request->input('so_tien');
            $loai_quy_id = $request->input('loai_quy_id');

            $nguyen_lieu_tho_id = $request->input('nguyen_lieu_tho_id');

            $nguyenLieuTho = NguyenLieuTho::find($nguyen_lieu_tho_id);

            if (!$nguyenLieuTho || $nguyenLieuTho->trang_thai == TrangThaiNguyenLieuTho::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tho');
            }

            if ($nguyenLieuTho->cong_no < $so_tien) {
                return redirect()->back()->with('error', 'Số tiền thanh toán không hợp lệ!');
            }

            $loai_quy = LoaiQuy::find($loai_quy_id);

            if ($loai_quy->tong_tien_quy < $so_tien) {
                return redirect()->back()->with('error', 'Số tiền thanh toán không hợp lệ!');
            }

            $loai = 0;

            $noi_dung = 'Thanh toán công nợ nhà cung cấp: ' . $nguyenLieuTho->NhaCungCap->ten . ' - Mã đơn hàng: ' . $nguyenLieuTho->code;

            $soquy = new SoQuy();

            $soquy->ma_phieu = $ma_phieu;
            $soquy->ngay = $ngay;
            $soquy->so_tien = $so_tien;
            $soquy->noi_dung = $noi_dung;
            $soquy->loai = $loai;
            $soquy->loai_quy_id = $loai_quy_id;
            $soquy->gia_tri_id = $nguyen_lieu_tho_id;
            $soquy->save();

            $nguyenLieuTho->cong_no = $nguyenLieuTho->cong_no - $so_tien;
            $nguyenLieuTho->so_tien_thanh_toan = $nguyenLieuTho->so_tien_thanh_toan + $so_tien;
            $nguyenLieuTho->save();

            $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $so_tien;
            $loai_quy->save();

            return redirect()->route('admin.so.quy.index')->with('success', 'Thanh toán nguyên liệu thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function generateCode()
    {
        $lastItem = SoQuy::orderByDesc('id')
            ->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }

    public function detail($id)
    {
        $soquy = SoQuy::find($id);
        if (!$soquy || $soquy->deleted_at != null) {
            return redirect()->back()->with('error', 'Không tìm thấy sổ quỹ');
        }
        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        $nhom_quies = NhomQuy::orderByDesc('id')->get();
        return view('admin.pages.so_quy.detail', compact('soquy', 'nhom_quies', 'loai_quies'));
    }

    public function store(Request $request)
    {
        try {
            $loai = $request->input('loai');
            $so_tien = $request->input('so_tien');
            $noi_dung = $request->input('noi_dung');
            $ngay = $request->input('ngay');
            $loai_quy_id = $request->input('loai_quy_id');
            $nhom_quy_id = $request->input('nhom_quy_id') ?? null;
            $loai_noi_nhan = $request->input('loai_noi_nhan') ?? '';
            $noi_nhan = $request->input('noi_nhan') ?? '';

            $soquy = new SoQuy();

            $ma_phieu = $request->input('ma_phieu');

            $soquy->ma_phieu = $ma_phieu;
            $soquy->loai = $loai;
            $soquy->so_tien = $so_tien;
            $soquy->noi_dung = $noi_dung;
            $soquy->ngay = $ngay;
            $soquy->loai_quy_id = $loai_quy_id;
            $soquy->nhom_quy_id = $nhom_quy_id;
            $soquy->loai_noi_nhan = $loai_noi_nhan;
            $soquy->noi_nhan = $noi_nhan;
            $soquy->allow_change = true;

            $soquy->save();

            $loai_quy = LoaiQuy::find($loai_quy_id);
            if ($loai_quy) {
                if ($loai == 1) {
                    $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy + $so_tien;
                    $loai_quy->save();
                } else {
                    $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $so_tien;
                    $loai_quy->save();
                }
            }

            return redirect()->back()->with('success', 'Thêm mới sổ quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $loai = $request->input('loai');
            $so_tien = $request->input('so_tien');
            $noi_dung = $request->input('noi_dung');
            $ngay = $request->input('ngay');
            $loai_quy_id = $request->input('loai_quy_id');
            $nhom_quy_id = $request->input('nhom_quy_id');
            $loai_noi_nhan = $request->input('loai_noi_nhan');
            $noi_nhan = $request->input('noi_nhan');

            $soquy = SoQuy::find($id);
            if (!$soquy || $soquy->deleted_at != null) {
                return redirect()->back()->with('error', 'Không tìm thấy sổ quỹ');
            }

            $old_id = $soquy->loai_quy_id;
            $old_tien = $soquy->so_tien;
            $old_loai = $soquy->loai;

            $soquy->loai = $loai;
            $soquy->so_tien = $so_tien;
            $soquy->noi_dung = $noi_dung;
            $soquy->ngay = $ngay;
            $soquy->loai_quy_id = $loai_quy_id;
            $soquy->nhom_quy_id = $nhom_quy_id;
            $soquy->loai_noi_nhan = $loai_noi_nhan;
            $soquy->noi_nhan = $noi_nhan;
            $soquy->save();

            if ($old_id != $loai_quy_id) {
                $new_quy = LoaiQuy::find($loai_quy_id);
                if ($new_quy) {
                    $new_quy->tong_tien_quy += ($loai == 1) ? $so_tien : -$so_tien;
                    $new_quy->save();
                }

                $old_quy = LoaiQuy::find($old_id);
                if ($old_quy) {
                    $delta = 0;

                    if ($old_loai != $loai) {
                        $delta += ($old_loai == 1) ? -$old_tien : $old_tien;
                    } else {
                        if ($loai == 1) {
                            $delta += (0 - $old_tien);
                        } else {
                            $delta += (0 + $old_tien);
                        }
                    }

                    $old_quy->tong_tien_quy += $delta;
                    $old_quy->save();
                }

            } else {
                $loai_quy = LoaiQuy::find($loai_quy_id);
                if ($loai_quy) {
                    $delta = 0;

                    if ($old_loai != $loai) {
                        $delta += ($old_loai == 1) ? -$old_tien : $old_tien;
                        $delta += ($loai == 1) ? $so_tien : -$so_tien;
                    } else {
                        if ($loai == 1) {
                            $delta += ($so_tien - $old_tien);
                        } else {
                            $delta += (-$so_tien + $old_tien);
                        }
                    }

                    $loai_quy->tong_tien_quy += $delta;
                    $loai_quy->save();
                }
            }

            return redirect()->route('admin.so.quy.index')->with('success', 'Chỉnh sửa sổ quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $soquy = SoQuy::find($id);
            if (!$soquy || $soquy->deleted_at != null) {
                return redirect()->back()->with('error', 'Không tìm thấy sổ quỹ');
            }

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

            return redirect()->back()->with('success', 'Đã xoá sổ quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
