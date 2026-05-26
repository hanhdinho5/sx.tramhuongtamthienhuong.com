<?php

namespace App\Http\Controllers;

use App\Enums\LoaiSanPham;
use App\Enums\TrangThaiKhachHang;
use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiNguyenLieuThanhPham;
use App\Enums\TrangThaiNguyenLieuTho;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Enums\TrangThaiNhaCungCap;
use App\Enums\TrangThaiSanPham;
use App\Enums\UserStatus;
use App\Models\KhachHang;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuThanhPham;
use App\Models\NguyenLieuTho;
use App\Models\NguyenLieuTinh;
use App\Models\NhaCungCaps;
use App\Models\SanPham;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect(route('admin.home'));
        }
        return redirect(route('auth.processLogin'));
    }

    public function listNguyenLieuTho()
    {
        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();

        $data = returnMessage(1, $nlthos, 'Success!');
        return response()->json($data);
    }

    public function listNguyenLieuPhanLoai()
    {
        $nlphanloais = NguyenLieuPhanLoai::where('nguyen_lieu_phan_loais.trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->join('nguyen_lieu_thos', 'nguyen_lieu_thos.id', '=', 'nguyen_lieu_phan_loais.nguyen_lieu_tho_id')
            ->orderByDesc('nguyen_lieu_phan_loais.id')
            ->select('nguyen_lieu_phan_loais.*', 'nguyen_lieu_thos.ten_nguyen_lieu as ten_nguyen_lieu_tho', 'nguyen_lieu_thos.code as ma_don_hang')
            ->get();

        $data = returnMessage(1, $nlphanloais, 'Success!');
        return response()->json($data);
    }

    public function listNguyenLieuTinh()
    {
        $nltinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
            ->orderByDesc('id')
            ->get();

        $data = returnMessage(1, $nltinhs, 'Success!');
        return response()->json($data);
    }

    public function listNguyenLieuSanXuat()
    {
        $nlsanxuats = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->with('phieuSanXuat')
            ->get();

        $data = returnMessage(1, $nlsanxuats, 'Success!');
        return response()->json($data);
    }

    public function listNguyenLieuThanhPham()
    {
        $nhthanhphams = NguyenLieuThanhPham::where('nguyen_lieu_thanh_phams.trang_thai', '!=', TrangThaiNguyenLieuThanhPham::DELETED())
            ->join('san_phams', 'san_phams.id', '=', 'nguyen_lieu_thanh_phams.san_pham_id')
            ->join('nguyen_lieu_san_xuats', 'nguyen_lieu_san_xuats.id', '=', 'nguyen_lieu_thanh_phams.nguyen_lieu_san_xuat_id')
            ->join('phieu_san_xuats', 'phieu_san_xuats.id', '=', 'nguyen_lieu_san_xuats.phieu_san_xuat_id')
            ->orderByDesc('nguyen_lieu_thanh_phams.id')
            ->select('nguyen_lieu_thanh_phams.*', 'san_phams.ten_san_pham as ten_san_pham', 'san_phams.gia_ban as gia_ban', 'san_phams.don_vi_tinh as don_vi_tinh', 'phieu_san_xuats.so_lo_san_xuat as so_lo_san_xuat')
            ->get();

        $data = returnMessage(1, $nhthanhphams, 'Success!');
        return response()->json($data);
    }

    public function thongTinSanPham(Request $request)
    {
        $id = $request->get('id');
        $sanpham = SanPham::where('trang_thai', '!=', TrangThaiSanPham::DELETED())
            ->where('id', $id)
            ->first();

        $data = returnMessage(1, $sanpham, 'Success!');
        return response()->json($data);
    }

    public function thongTinKhachHang(Request $request)
    {
        $id = $request->get('id');
        $khachhang = KhachHang::where('trang_thai', '!=', TrangThaiKhachHang::DELETED())
            ->where('id', $id)
            ->first();

        $data = returnMessage(1, $khachhang, 'Success!');
        return response()->json($data);
    }

    public function chiTietNguyenLieu(Request $request)
    {
        $id = $request->get('id');
        $type = $request->get('type');
        $data = null;
        switch ($type) {
            case LoaiSanPham::NGUYEN_LIEU_THO():
                $data = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
                    ->where('id', $id)
                    ->first();
                break;
            case LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                $data = NguyenLieuPhanLoai::where('nguyen_lieu_phan_loais.trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
                    ->join('nguyen_lieu_thos', 'nguyen_lieu_thos.id', '=', 'nguyen_lieu_phan_loais.nguyen_lieu_tho_id')
                    ->where('nguyen_lieu_phan_loais.id', $id)
                    ->select('nguyen_lieu_phan_loais.*', 'nguyen_lieu_thos.ten_nguyen_lieu as ten_nguyen_lieu_tho', 'nguyen_lieu_thos.code as ma_don_hang')
                    ->first();
                break;
            case LoaiSanPham::NGUYEN_LIEU_TINH():
                $data = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
                    ->where('id', $id)
                    ->first();
                break;
            case LoaiSanPham::NGUYEN_LIEU_SAN_XUAT():
                $data = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
                    ->where('id', $id)
                    ->first();
                break;
            case LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                $data = NguyenLieuThanhPham::where('nguyen_lieu_thanh_phams.trang_thai', '!=', TrangThaiNguyenLieuThanhPham::DELETED())
                    ->join('san_phams', 'san_phams.id', '=', 'nguyen_lieu_thanh_phams.san_pham_id')
                    ->join('nguyen_lieu_san_xuats', 'nguyen_lieu_san_xuats.id', '=', 'nguyen_lieu_thanh_phams.nguyen_lieu_san_xuat_id')
                    ->join('phieu_san_xuats', 'phieu_san_xuats.id', '=', 'nguyen_lieu_san_xuats.phieu_san_xuat_id')
                    ->where('nguyen_lieu_thanh_phams.id', $id)
                    ->select('nguyen_lieu_thanh_phams.*', 'san_phams.ten_san_pham as ten_san_pham', 'san_phams.gia_ban as gia_ban', 'phieu_san_xuats.so_lo_san_xuat as so_lo_san_xuat')
                    ->first();
                break;
        }
        $res = returnMessage(1, $data, 'Success!');
        return response()->json($res);
    }

    public function get_nguon_hang_ban_hang(Request $request)
    {
        $loai_nguon_hang = $request->get('loai_nguon_hang');
        $nguon_hang = null;
        if ($loai_nguon_hang == 'ncc') {
            $nguon_hang = NhaCungCaps::where('trang_thai', '!=', TrangThaiNhaCungCap::DELETED())->get();
        } else if ($loai_nguon_hang == 'nv') {
            $nguon_hang = User::where('status', '!=', UserStatus::DELETED())->get();
        } else if ($loai_nguon_hang == 'kh') {
            $nguon_hang = KhachHang::where('trang_thai', '!=', TrangThaiKhachHang::DELETED())->get();
        }
        $res = returnMessage(1, $nguon_hang, 'Success!');
        return response()->json($res);
    }
}
