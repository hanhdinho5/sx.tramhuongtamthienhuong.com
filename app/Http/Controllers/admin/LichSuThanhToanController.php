<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BanHang;
use App\Models\LichSuThanhToan;
use App\Models\SoQuy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LichSuThanhToanController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $so_quy_id = $request->input('so_quy_id');
            $ban_hang_id = $request->input('ban_hang_id');
            $so_tien_thanh_toan = $request->input('so_tien_thanh_toan');
            $nguoi_thanh_toan = Auth::user()->id;
            $ghi_chu = $request->input('ghi_chu');

            $banhang = BanHang::find($ban_hang_id);

            if (!$banhang || $banhang->cong_no < $so_tien_thanh_toan) {
                DB::rollBack();
                return back()->with('error', 'Thanh toán không hợp lệ!');
            }

            $banhang->da_thanht_toan += $so_tien_thanh_toan;
            $banhang->cong_no -= $so_tien_thanh_toan;
            $banhang->save();

            $code = $this->generateCode();

            $soquy = new SoQuy();
            $soquy->loai = 1;
            $soquy->so_tien = $so_tien_thanh_toan;
            $soquy->gia_tri_id = $ban_hang_id;
            $soquy->ngay = Carbon::now();
            $soquy->noi_dung = 'Phiếu thu bán hàng cho đơn hàng: #' . $banhang->ma_don_hang;
            $soquy->ma_phieu = $code;
            $soquy->loai_quy_id = $so_quy_id;
            $soquy->save();

            $lichSuThanhToan = new LichSuThanhToan();
            $lichSuThanhToan->so_quy_id = $soquy->id;
            $lichSuThanhToan->ban_hang_id = $ban_hang_id;
            $lichSuThanhToan->so_tien_thanh_toan = $so_tien_thanh_toan;
            $lichSuThanhToan->nguoi_thanh_toan = $nguoi_thanh_toan;
            $lichSuThanhToan->ghi_chu = $ghi_chu;
            $lichSuThanhToan->save();

            DB::commit();
            return back()->with('success', 'Thêm mới hóa đơn bán hàng thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            toast($e->getMessage(), 'error', 'top-right');
            return redirect()->back();
        }
    }

    private function generateCode()
    {
        $lastItem = SoQuy::orderByDesc('id')->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }
}
