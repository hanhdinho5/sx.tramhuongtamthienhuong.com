<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiSanPham;
use App\Http\Controllers\Controller;
use App\Models\SanPham;
use Illuminate\Http\Request;

class AdminSanPhamController extends Controller
{
    public function index()
    {
        $datas = SanPham::where('trang_thai', '!=', TrangThaiSanPham::DELETED())
            ->orderByDesc('id')
            ->get();

        $code = $this->generateCode();

        return view('admin.pages.san_pham.index', compact('datas', 'code'));
    }

    public function detail($id)
    {
        $item = SanPham::find($id);
        if (!$item || $item->trang_thai == TrangThaiSanPham::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy dữ liệu');
        }

        $code = $item->ma_san_pham;
        if (!$item->ma_san_pham) {
            $code = $this->generateCode();
        }

        return view('admin.pages.san_pham.detail', compact('item', 'code'));
    }

    public function store(Request $request)
    {
        try {
            $item = new SanPham();

            $item = $this->saveData($item, $request);
            $item->save();

            return redirect()->back()->with('success', 'Thêm mới dữ liệu thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function saveData(SanPham $item, Request $request)
    {
        $old_trang_thai = $item->trang_thai ?? TrangThaiSanPham::ACTIVE();

        $ma_san_pham = $request->input('ma_san_pham');
        $ma_vach = $request->input('ma_vach') ?? '';
        $ten_san_pham = $request->input('ten_san_pham');
        $don_vi_tinh = $request->input('don_vi_tinh');
        $khoi_luong_rieng = $request->input('khoi_luong_rieng');
        $gia_xuat_kho = $request->input('gia_xuat_kho');
        $gia_ban = $request->input('gia_ban');
        $ton_kho = !empty($request->input('ton_kho'))
            ? $request->input('ton_kho')
            : (!empty($item->ton_kho) ? $item->ton_kho : 0);
        $mo_ta = $request->input('mo_ta');
        $trang_thai = $request->input('trang_thai') ?? $old_trang_thai;

        $item->ma_san_pham = $ma_san_pham;
        $item->ma_vach = $ma_vach;
        $item->ten_san_pham = $ten_san_pham;
        $item->don_vi_tinh = $don_vi_tinh;
        $item->khoi_luong_rieng = $khoi_luong_rieng;
        $item->gia_xuat_kho = $gia_xuat_kho;
        $item->gia_ban = $gia_ban;
        $item->ton_kho = $ton_kho;
        $item->mo_ta = $mo_ta;
        $item->trang_thai = $trang_thai;

        return $item;
    }

    public function update($id, Request $request)
    {
        try {
            $item = SanPham::find($id);
            if (!$item || $item->trang_thai == TrangThaiSanPham::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy dữ liệu');
            }

            $item = $this->saveData($item, $request);
            $item->save();

            return redirect()->route('admin.san.pham.index')->with('success', 'Chỉnh sửa dữ liệu thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $item = SanPham::find($id);
            if (!$item || $item->trang_thai == TrangThaiSanPham::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy dữ liệu');
            }

            $item->delete();
            return redirect()->back()->with('success', 'Đã xoá dữ liệu thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function generateCode()
    {
        $lastItem = SanPham::orderByDesc('id')
            ->first();

        $lastId = $lastItem?->id;
        return generateProductCode($lastId + 1);
    }
}
