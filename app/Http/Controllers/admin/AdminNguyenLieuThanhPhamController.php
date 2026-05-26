<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiNguyenLieuThanhPham;
use App\Enums\TrangThaiSanPham;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuThanhPham;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminNguyenLieuThanhPhamController extends Controller
{
    public function index(Request $request)
    {
        $ngay_search = $request->input('ngay');
        $nguyen_lieu_san_xuat_id_search = $request->input('nguyen_lieu_san_xuat_id');
        $san_pham_id_search = $request->input('san_pham_id');

        $queries = NguyenLieuThanhPham::where('trang_thai', '!=', TrangThaiNguyenLieuThanhPham::DELETED());

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (!$san_pham_id_search && !$nguyen_lieu_san_xuat_id_search && !$start_date && !$end_date) {
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

        if ($nguyen_lieu_san_xuat_id_search) {
            $queries->where('nguyen_lieu_san_xuat_id', $nguyen_lieu_san_xuat_id_search);
        }

        if ($san_pham_id_search) {
            $queries->where('san_pham_id', $san_pham_id_search);
        }

        $datas = $queries->orderByRaw('(COALESCE(so_luong, 0) - COALESCE(so_luong_da_ban, 0)) DESC')
            ->orderByDesc('id')
            ->get();

        $nlsanxuats = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->where('is_completed', true)
            ->orderByDesc('id')
            ->get();

        $products = SanPham::where('trang_thai', '!=', TrangThaiSanPham::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_thanh_pham.index', compact('datas', 'nlsanxuats', 'products',
            'ngay_search', 'nguyen_lieu_san_xuat_id_search', 'san_pham_id_search', 'start_date', 'end_date'));
    }

    public function detail($id)
    {
        $nguyenLieuThanhPham = NguyenLieuThanhPham::find($id);
        if (!$nguyenLieuThanhPham || $nguyenLieuThanhPham->trang_thai == TrangThaiNguyenLieuThanhPham::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thành phẩm');
        }

        $nlsanxuats = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->where('is_completed', true)
            ->orderByDesc('id')
            ->get();

        $products = SanPham::where('trang_thai', '!=', TrangThaiSanPham::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_thanh_pham.detail', compact('nguyenLieuThanhPham', 'nlsanxuats', 'products'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $nguyenLieuThanhPham = new NguyenLieuThanhPham();

            $nguyenLieuThanhPham = $this->saveData($nguyenLieuThanhPham, $request);
            if (!$nguyenLieuThanhPham) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Số lượng không đủ!')->withInput();
            }
            $nguyenLieuThanhPham->save();

            DB::commit();
            return redirect()->back()->with('success', 'Thêm mới nguyên liệu thành phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function saveData(NguyenLieuThanhPham $nguyenLieuThanhPham, Request $request)
    {
        $ngay = $request->input('ngay');
        $nguyen_lieu_san_xuat_id = $request->input('nguyen_lieu_san_xuat_id');
        $san_pham_id = $request->input('san_pham_id');
        $ten_san_pham = $request->input('ten_san_pham');
        $so_luong = $request->input('so_luong');
        $price = $request->input('price');
        $total_price = $request->input('total_price');
        $ngay_san_xuat = $request->input('ngay_san_xuat');
        $trang_thai = $request->input('trang_thai');
        $khoi_luong_da_dung = $request->input('khoi_luong_da_dung');

        $oldTonKho = $nguyenLieuThanhPham->so_luong;
        $oldNguyenLieuSanXuatId = $nguyenLieuThanhPham->nguyen_lieu_san_xuat_id;
        $oldKhoiLuongDaDung = $nguyenLieuThanhPham->khoi_luong_da_dung;

        if ($oldNguyenLieuSanXuatId != $nguyen_lieu_san_xuat_id) {
            $nguyenLieuSanXuat = NguyenLieuSanXuat::find($nguyen_lieu_san_xuat_id);
            if ($nguyenLieuSanXuat) {
                $khoi_luong = $nguyenLieuSanXuat->khoi_luong - $nguyenLieuSanXuat->khoi_luong_da_dung;
                if ($khoi_luong < $khoi_luong_da_dung) {
                    return false;
                }
            }
        } else {
            $nguyenLieuSanXuat = NguyenLieuSanXuat::find($nguyen_lieu_san_xuat_id);
            if ($nguyenLieuSanXuat) {
                $khoi_luong = $nguyenLieuSanXuat->khoi_luong - $nguyenLieuSanXuat->khoi_luong_da_dung + $oldKhoiLuongDaDung;
                if ($khoi_luong < $khoi_luong_da_dung) {
                    return false;
                }
            }
        }

        $nguyenLieuThanhPham->ten_san_pham = $ten_san_pham;
        $nguyenLieuThanhPham->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $nguyenLieuThanhPham->so_luong = $so_luong;
        $nguyenLieuThanhPham->price = $price;
        $nguyenLieuThanhPham->total_price = $total_price;
        $nguyenLieuThanhPham->nguyen_lieu_san_xuat_id = $nguyen_lieu_san_xuat_id;
        $nguyenLieuThanhPham->san_pham_id = $san_pham_id;
        $nguyenLieuThanhPham->ngay_san_xuat = Carbon::parse($ngay_san_xuat)->format('Y-m-d');
        $nguyenLieuThanhPham->trang_thai = $trang_thai;
        $nguyenLieuThanhPham->khoi_luong_da_dung = $khoi_luong_da_dung;

        if ($oldNguyenLieuSanXuatId != $nguyen_lieu_san_xuat_id) {
            $nguyenLieuSanXuat = NguyenLieuSanXuat::find($nguyen_lieu_san_xuat_id);
            if ($nguyenLieuSanXuat) {
                $nguyenLieuSanXuat->khoi_luong_da_dung += $khoi_luong_da_dung;
                $nguyenLieuSanXuat->save();
            }

            $oldNguyenLieuSanXuat = NguyenLieuSanXuat::find($oldNguyenLieuSanXuatId);
            if ($oldNguyenLieuSanXuat) {
                $oldNguyenLieuSanXuat->khoi_luong_da_dung -= $oldKhoiLuongDaDung;
                $oldNguyenLieuSanXuat->save();
            }
        } else {
            $nguyenLieuSanXuat = NguyenLieuSanXuat::find($nguyen_lieu_san_xuat_id);
            if ($nguyenLieuSanXuat) {
                $nguyenLieuSanXuat->khoi_luong_da_dung += $khoi_luong_da_dung - $oldKhoiLuongDaDung;
                $nguyenLieuSanXuat->save();
            }
        }

        $sanPham = SanPham::find($san_pham_id);
        if ($sanPham) {
            $sanPham->ton_kho = $sanPham->ton_kho - $oldTonKho + $so_luong;
            $sanPham->save();
        }

        return $nguyenLieuThanhPham;
    }

    public function delete($id)
    {
        try {
            $nguyenLieuThanhPham = NguyenLieuThanhPham::find($id);
            if (!$nguyenLieuThanhPham || $nguyenLieuThanhPham->trang_thai == TrangThaiNguyenLieuThanhPham::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thành phẩm');
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

            return redirect()->back()->with('success', 'Đã xoá nguyên liệu thành phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $nguyenLieuThanhPham = NguyenLieuThanhPham::find($id);
            if (!$nguyenLieuThanhPham || $nguyenLieuThanhPham->trang_thai == TrangThaiNguyenLieuThanhPham::DELETED()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thành phẩm');
            }

            $nguyenLieuThanhPham = $this->saveData($nguyenLieuThanhPham, $request);
            if (!$nguyenLieuThanhPham) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Số lượng không đủ!');
            }
            $nguyenLieuThanhPham->save();

            DB::commit();
            return redirect()->route('admin.nguyen.lieu.thanh.pham.index')->with('success', 'Chỉnh sửa nguyên liệu thành phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
