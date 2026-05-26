<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiBanHang;
use App\Enums\TrangThaiKhachHang;
use App\Enums\TrangThaiNhaCungCap;
use App\Http\Controllers\Controller;
use App\Models\BanHang;
use App\Models\KhachHang;
use App\Models\NguyenLieuTho;
use App\Models\NhaCungCaps;
use App\Models\NhomKhachHang;
use App\Models\SoQuy;
use Illuminate\Http\Request;

class AdminKhachHangController extends Controller
{
    public function index()
    {
        $datas = KhachHang::where('trang_thai', '!=', TrangThaiKhachHang::DELETED())
            ->orderByDesc('id')
            ->get();

        $nhom_khach_hangs = NhomKhachHang::orderByDesc('id')->get();
        return view('admin.pages.khach_hang.index', compact('datas', 'nhom_khach_hangs'));
    }

    public function detail($id)
    {
        $khachhang = KhachHang::find($id);
        if (!$khachhang || $khachhang->trang_thai == TrangThaiKhachHang::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
        }

        $nhom_khach_hangs = NhomKhachHang::orderByDesc('id')->get();
        return view('admin.pages.khach_hang.detail', compact('khachhang', 'nhom_khach_hangs'));
    }

    public function store(Request $request)
    {
        try {
            $ten = $request->input('ten');
            $dia_chi = $request->input('dia_chi');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $tinh_thanh = $request->input('tinh_thanh');
            $trang_thai = $request->input('trang_thai');
            $nhom_khach_hang_id = $request->input('nhom_khach_hang_id');

            $khachhang = new KhachHang();

            $khachhang->ten = $ten;
            $khachhang->dia_chi = $dia_chi ?? '';
            $khachhang->so_dien_thoai = $so_dien_thoai ?? '';
            $khachhang->tinh_thanh = $tinh_thanh ?? '';
            $khachhang->trang_thai = $trang_thai;
            $khachhang->nhom_khach_hang_id = $nhom_khach_hang_id;
            $khachhang->save();

            return redirect()->back()->with('success', 'Thêm mới nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $ten = $request->input('ten');
            $dia_chi = $request->input('dia_chi');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $tinh_thanh = $request->input('tinh_thanh');
            $trang_thai = $request->input('trang_thai');
            $nhom_khach_hang_id = $request->input('nhom_khach_hang_id');

            $khachhang = KhachHang::find($id);
            if (!$khachhang || $khachhang->trang_thai == TrangThaiKhachHang::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
            }

            $khachhang->ten = $ten;
            $khachhang->dia_chi = $dia_chi ?? '';
            $khachhang->so_dien_thoai = $so_dien_thoai ?? '';
            $khachhang->tinh_thanh = $tinh_thanh ?? '';
            $khachhang->trang_thai = $trang_thai;
            $khachhang->nhom_khach_hang_id = $nhom_khach_hang_id;
            $khachhang->save();

            return redirect()->route('admin.khach.hang.index')->with('success', 'Chỉnh sửa nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Request $request)
    {
        $id = $request->input('id');

        $khach_hang = KhachHang::find($id);
        if (!$khach_hang) {
            return response()->json(null, 400);
        }

        $histories = BanHang::where('khach_hang_id', $id)
            ->where('trang_thai', '!=', TrangThaiBanHang::DELETED())
            ->orderByDesc('id')
            ->get();

        $html = view('admin.pages.khach_hang.show', compact('histories'))->render();

        $data = [
            'html' => $html,
        ];

        $res = returnMessage('1', $data, 'Lấy dữ liệu thành công');
        return response()->json($res, 200);
    }

    public function delete($id)
    {
        try {
            $khachhang = KhachHang::find($id);
            if (!$khachhang || $khachhang->trang_thai == TrangThaiKhachHang::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
            }

            $khachhang->trang_thai = TrangThaiKhachHang::DELETED();
            $khachhang->save();

            return redirect()->back()->with('success', 'Đã xoá nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
