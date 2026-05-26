<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\NhomQuy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNhomQuyController extends Controller
{
    public function index()
    {
        $datas = NhomQuy::orderByDesc('id')
            ->get();
        return view('admin.pages.nhom_quy.index', compact('datas'));
    }

    public function detail($id)
    {
        $nhom_quy = NhomQuy::find($id);

        if (!$nhom_quy) {
            return redirect()->back()->with('error', 'Không tìm thấy nhóm quỹ')->withInput();
        }

        return view('admin.pages.nhom_quy.detail', compact('nhom_quy'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $ten_nhom = $request->input('ten_nhom');

            $old_nhom_quy = NhomQuy::where('ten_nhom', $ten_nhom)->first();
            if ($old_nhom_quy) {
                return redirect()->back()->with('error', 'Nhóm quỹ này đã tồn tại!')->withInput();
            };

            $nhom_quy = new NhomQuy();
            $nhom_quy->ten_nhom = $ten_nhom;
            $nhom_quy->save();
            DB::commit();
            return redirect()->route('admin.nhom.quy.index')->with('success', 'Thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $nhom_quy = NhomQuy::find($id);

            $ten_nhom = $request->input('ten_nhom');

            if (!$nhom_quy) {
                return redirect()->back()->with('error', 'Không tìm thấy nhóm quỹ')->withInput();
            }

            $nhom_quy->ten_nhom = $ten_nhom;
            $nhom_quy->save();
            DB::commit();
            return redirect()->route('admin.nhom.quy.index')->with('success', 'Thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $nhom_quy = NhomQuy::find($id);

            if (!$nhom_quy) {
                return redirect()->back()->with('error', 'Không tìm thấy nhóm quỹ')->withInput();
            }

            $nhom_quy->delete();

            DB::commit();
            return redirect()->route('admin.nhom.quy.index')->with('success', 'Thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
