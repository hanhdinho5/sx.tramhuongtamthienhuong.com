<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\NhomKhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNhomKhachHangController extends Controller
{
    public function index()
    {
        $datas = NhomKhachHang::orderByDesc('id')
            ->get();
        return view('admin.pages.nhom_khach_hang.index', compact('datas'));
    }

    public function detail($id)
    {
        $nhom_khach_hang = NhomKhachHang::find($id);

        if (!$nhom_khach_hang) {
            return redirect()->back()->with('error', 'Không tìm thấy nhóm khách hàng')->withInput();
        }

        return view('admin.pages.nhom_khach_hang.detail', compact('nhom_khach_hang'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $ten_nhom = $request->input('ten_nhom');

            $old_nhom_khach_hang = NhomKhachHang::where('ten_nhom', $ten_nhom)->first();
            if ($old_nhom_khach_hang) {
                return redirect()->back()->with('error', 'Nhom khach hang nay da ton tai')->withInput();
            };

            $nhom_khach_hang = new NhomKhachHang();
            $nhom_khach_hang->ten_nhom = $ten_nhom;
            $nhom_khach_hang->save();
            DB::commit();
            return redirect()->route('admin.nhom.khach.hang.index')->with('success', 'Thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $nhom_khach_hang = NhomKhachHang::find($id);

            $ten_nhom = $request->input('ten_nhom');

            if (!$nhom_khach_hang) {
                return redirect()->back()->with('error', 'Không tìm thấy nhóm khách hàng')->withInput();
            }

            $nhom_khach_hang->ten_nhom = $ten_nhom;
            $nhom_khach_hang->save();
            DB::commit();
            return redirect()->route('admin.nhom.khach.hang.index')->with('success', 'Thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $nhom_khach_hang = NhomKhachHang::find($id);

            if (!$nhom_khach_hang) {
                return redirect()->back()->with('error', 'Không tìm thấy nhóm khách hàng')->withInput();
            }

            $nhom_khach_hang->delete();

            DB::commit();
            return redirect()->route('admin.nhom.khach.hang.index')->with('success', 'Thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
