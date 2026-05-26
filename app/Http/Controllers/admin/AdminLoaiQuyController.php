<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiQuy;
use Illuminate\Http\Request;

class AdminLoaiQuyController extends Controller
{
    public function index()
    {
        $datas = LoaiQuy::where('deleted_at', null)
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.loai_quy.index', compact('datas'));
    }

    public function detail($id)
    {
        $item = LoaiQuy::find($id);
        if (!$item || $item->deleted_at != null) {
            return redirect()->back()->with('error', 'Không tìm thấy loại quỹ');
        }
        return view('admin.pages.loai_quy.detail', compact('item'));
    }

    public function store(Request $request)
    {
        try {

            $ten_loai_quy = $request->input('ten_loai_quy');
            $item = new LoaiQuy();
            $item->ten_loai_quy = $ten_loai_quy;
            $item->save();

            return redirect()->back()->with('success', 'Thêm mới loại quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $ten_loai_quy = $request->input('ten_loai_quy');

            $item = LoaiQuy::find($id);
            if (!$item || $item->deleted_at != null) {
                return redirect()->back()->with('error', 'Không tìm thấy loại quỹ');
            }

            $item->ten_loai_quy = $ten_loai_quy;
            $item->save();

            return redirect()->route('admin.loai.quy.index')->with('success', 'Chỉnh sửa loại quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $item = LoaiQuy::find($id);
            if (!$item || $item->deleted_at != null) {
                return redirect()->back()->with('error', 'Không tìm thấy loại quỹ');
            }

            if ($item->tong_tien_quy > 0) {
                return redirect()->back()->with('error', 'Không thể xóa quỹ vẫn còn tiền!');
            }

            $item->delete();
            return redirect()->back()->with('success', 'Đã xoá loại quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
