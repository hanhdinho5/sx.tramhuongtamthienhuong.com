<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ThongTin;
use Illuminate\Http\Request;

class AdminThongTinController extends Controller
{
    public function index()
    {
        $datas = ThongTin::where('is_deleted', null)
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.thong_tin.index', compact('datas'));
    }

    public function detail($id)
    {
        $item = ThongTin::find($id);
        if (!$item || $item->is_deleted != null) {
            return redirect()->back()->with('error', 'Không tìm thấy dữ liệu');
        }
        return view('admin.pages.thong_tin.detail', compact('item'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required',
            ]);

            $display_name = $request->input('display_name');
            $item = new ThongTin();

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $itemPath = $file->store('thong_tin', 'public');
            $filePath = asset('storage/' . $itemPath);

            $item->display_name = $display_name;
            $item->file_name = $fileName;
            $item->file_path = $filePath;
            $item->save();

            return redirect()->back()->with('success', 'Thêm mới dữ liệu thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $display_name = $request->input('display_name');

            $item = ThongTin::find($id);
            if (!$item || $item->is_deleted != null) {
                return redirect()->back()->with('error', 'Không tìm thấy dữ liệu');
            }

            $fileName = $item->file_name;
            $filePath = $item->file_path;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $itemPath = $file->store('thong_tin', 'public');
                $filePath = asset('storage/' . $itemPath);
            }
            $item->display_name = $display_name;
            $item->file_name = $fileName;
            $item->file_path = $filePath;
            $item->save();

            return redirect()->route('admin.thong.tin.index')->with('success', 'Chỉnh sửa dữ liệu thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $item = ThongTin::find($id);
            if (!$item || $item->is_deleted != null) {
                return redirect()->back()->with('error', 'Không tìm thấy dữ liệu');
            }

            $item->delete();
            return redirect()->back()->with('success', 'Đã xoá dữ liệu thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
