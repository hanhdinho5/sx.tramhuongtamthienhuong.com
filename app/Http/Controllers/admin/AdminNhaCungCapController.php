<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuTho;
use App\Enums\TrangThaiNhaCungCap;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuTho;
use App\Models\NhaCungCaps;
use App\Models\SoQuy;
use Illuminate\Http\Request;

class AdminNhaCungCapController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $q = NhaCungCaps::where('trang_thai', '!=', TrangThaiNhaCungCap::DELETED());

        if (!empty($keyword)) {
            $q->where(function ($query) use ($keyword) {
                $query->where('ten', 'like', '%' . $keyword . '%')
                    ->orWhere('so_dien_thoai', 'like', '%' . $keyword . '%');
            });
        }

        $datas = $q->orderByDesc('id')->get();

        return view('admin.pages.nha_cung_cap.index', compact('datas', 'keyword'));
    }

    public function detail($id)
    {
        $ncc = NhaCungCaps::find($id);
        if (!$ncc || $ncc->trang_thai == TrangThaiNhaCungCap::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
        }
        return view('admin.pages.nha_cung_cap.detail', compact('ncc'));
    }

    public function store(Request $request)
    {
        try {
            $ten = $request->input('ten');
            $dia_chi = $request->input('dia_chi');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $tinh_thanh = $request->input('tinh_thanh');
            $trang_thai = $request->input('trang_thai');

            $ncc = new NhaCungCaps();

            $ncc->ten = $ten;
            $ncc->dia_chi = $dia_chi ?? '';
            $ncc->so_dien_thoai = $so_dien_thoai ?? '';
            $ncc->tinh_thanh = $tinh_thanh ?? '';
            $ncc->trang_thai = $trang_thai;
            $ncc->save();

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

            $ncc = NhaCungCaps::find($id);
            if (!$ncc || $ncc->trang_thai == TrangThaiNhaCungCap::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
            }

            $ncc->ten = $ten;
            $ncc->dia_chi = $dia_chi ?? '';
            $ncc->so_dien_thoai = $so_dien_thoai ?? '';
            $ncc->tinh_thanh = $tinh_thanh ?? '';
            $ncc->trang_thai = $trang_thai;
            $ncc->save();

            return redirect()->route('admin.nha.cung.cap.index')->with('success', 'Chỉnh sửa nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Request $request)
    {
        $id = $request->input('id');

        $nha_cung_cap = NhaCungCaps::find($id);
        if (!$nha_cung_cap || $nha_cung_cap->trang_thai == TrangThaiNhaCungCap::DELETED()) {
            return response()->json(null, 400);
        }

        $order_histories = NguyenLieuTho::where('nha_cung_cap_id', $id)
            ->where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();

        $payment_histories = SoQuy::query()
            ->join('nguyen_lieu_thos', 'nguyen_lieu_thos.id', '=', 'so_quies.gia_tri_id')
            ->where('nguyen_lieu_thos.nha_cung_cap_id', $id)
            ->where('so_quies.deleted_at', null)
            ->orderByDesc('so_quies.id')
            ->select('so_quies.*')
            ->get();
        return view('admin.pages.nha_cung_cap.show', compact('order_histories', 'payment_histories'));
    }

    public function delete($id)
    {
        try {
            $ncc = NhaCungCaps::find($id);
            if (!$ncc || $ncc->trang_thai == TrangThaiNhaCungCap::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
            }

            $ncc->trang_thai = TrangThaiNhaCungCap::DELETED();
            $ncc->save();

            return redirect()->back()->with('success', 'Đã xoá nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
