<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuTho;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuTho;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminNguyenLieuPhanLoaiController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $queries = NguyenLieuPhanLoai::where('nguyen_lieu_phan_loais.trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED());

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (!$keyword && !$start_date && !$end_date) {
            $start_date2 = Carbon::now()->startOfMonth()->toDateString();
            $end_date2 = Carbon::now()->endOfMonth()->toDateString();

            $queries->whereBetween('nguyen_lieu_phan_loais.ngay', [
                Carbon::parse($start_date2)->format('Y-m-d'),
                Carbon::parse($end_date2)->format('Y-m-d')
            ]);
        }

        if ($start_date && $end_date) {
            $queries->whereBetween('nguyen_lieu_phan_loais.ngay', [
                Carbon::parse($start_date)->format('Y-m-d'),
                Carbon::parse($end_date)->format('Y-m-d')
            ]);
        } elseif ($start_date) {
            $queries->whereDate('nguyen_lieu_phan_loais.ngay', '>=', Carbon::parse($start_date)->format('Y-m-d'));
        } elseif ($end_date) {
            $queries->whereDate('nguyen_lieu_phan_loais.ngay', '<=', Carbon::parse($end_date)->format('Y-m-d'));
        }

        if ($keyword) {
            $queries->join('nguyen_lieu_thos', 'nguyen_lieu_phan_loais.nguyen_lieu_tho_id', '=', 'nguyen_lieu_thos.id');
            $queries->where(function ($q) use ($keyword) {
                $q->where('nguyen_lieu_thos.ten_nguyen_lieu', 'like', '%' . $keyword . '%')
                    ->orWhere('nguyen_lieu_thos.code', 'like', '%' . $keyword . '%');
            });
        }

        $datas = $queries->orderByRaw('(COALESCE(tong_khoi_luong, 0) - COALESCE(khoi_luong_da_phan_loai, 0)) DESC')
            ->orderByDesc('nguyen_lieu_phan_loais.id')
            ->select('nguyen_lieu_phan_loais.*')
            ->get();
        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.nguyen_lieu_phan_loai.index', compact('datas', 'nlthos', 'start_date', 'end_date', 'keyword'));
    }

    public function detail($id)
    {
        $nguyen_lieu_phan_loai = NguyenLieuPhanLoai::find($id);
        if (!$nguyen_lieu_phan_loai || $nguyen_lieu_phan_loai->trang_thai == TrangThaiNguyenLieuPhanLoai::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu phân loại');
        }

        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.nguyen_lieu_phan_loai.detail', compact('nguyen_lieu_phan_loai', 'nlthos'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $nguyen_lieu_phan_loai = new NguyenLieuPhanLoai();

            $nguyen_lieu_phan_loai = $this->saveData($nguyen_lieu_phan_loai, $request);
            if (!$nguyen_lieu_phan_loai) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Khối lượng ban đầu không đủ')->withInput();
            }
            $nguyen_lieu_phan_loai->save();

            $this->updateNguyenLieuTho();

            DB::commit();
            return redirect()->back()->with('success', 'Thêm mới nguyên liệu phân loại thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * @throws \Throwable
     */
    private function saveData(NguyenLieuPhanLoai $NguyenLieuPhanLoai, Request $request)
    {
        DB::beginTransaction();
        $is_update = false;
        if ($NguyenLieuPhanLoai->nguyen_lieu_tho_id) {
            $is_update = true;
            $old_nguyen_lieu_tho_id = $NguyenLieuPhanLoai->nguyen_lieu_tho_id;
            $old_khoi_luong_ban_dau = $NguyenLieuPhanLoai->khoi_luong_ban_dau;
        }
        $ten_nguyen_lieu = $request->input('ten_nguyen_lieu');
        $ngay = $request->input('ngay');
        $nguyen_lieu_tho_id = $request->input('nguyen_lieu_tho_id');
        $nu_cao_cap = $request->input('nu_cao_cap');
        $nu_vip = $request->input('nu_vip');
        $nhang = $request->input('nhang');
        $vong = $request->input('vong');
        $tam_tre = $request->input('tam_tre');
        $keo = $request->input('keo');
        $nau_dau = $request->input('nau_dau');
        $tam_dai = $request->input('tam_dai');
        $tam_ngan = $request->input('tam_ngan');
        $tam_nhanh_sao = $request->input('tam_nhanh_sao');
        $nuoc_cat = $request->input('nuoc_cat');
        $ghi_chu = $request->input('ghi_chu');
        $trang_thai = $request->input('trang_thai') ?? TrangThaiNguyenLieuPhanLoai::ACTIVE();

        $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_tho_id)->first();
        $khoi_luong_ban_dau = $nguyenLieuTho->khoi_luong;

        if ($khoi_luong_ban_dau <= 0) {
            DB::rollBack();
            return false;
        }

        $NguyenLieuPhanLoai->ten_nguyen_lieu = $ten_nguyen_lieu;
        $NguyenLieuPhanLoai->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $NguyenLieuPhanLoai->nguyen_lieu_tho_id = $nguyen_lieu_tho_id;
        $NguyenLieuPhanLoai->nu_cao_cap = $nu_cao_cap ?? 0;
        $NguyenLieuPhanLoai->nu_vip = $nu_vip ?? 0;
        $NguyenLieuPhanLoai->nhang = $nhang ?? 0;
        $NguyenLieuPhanLoai->vong = $vong ?? 0;
        $NguyenLieuPhanLoai->tam_tre = $tam_tre ?? 0;
        $NguyenLieuPhanLoai->keo = $keo ?? 0;
        $NguyenLieuPhanLoai->nau_dau = $nau_dau ?? 0;
        $NguyenLieuPhanLoai->tam_dai = $tam_dai ?? 0;
        $NguyenLieuPhanLoai->tam_ngan = $tam_ngan ?? 0;
        $NguyenLieuPhanLoai->nuoc_cat = $nuoc_cat ?? 0;
        $NguyenLieuPhanLoai->tam_nhanh_sao = $tam_nhanh_sao ?? 0;
        $NguyenLieuPhanLoai->khoi_luong_ban_dau = $khoi_luong_ban_dau;
        $NguyenLieuPhanLoai->ghi_chu = $ghi_chu;
        $NguyenLieuPhanLoai->trang_thai = $trang_thai;

        $NguyenLieuPhanLoai->tong_khoi_luong = $nu_cao_cap + $nu_vip + $nhang + $vong + $tam_tre + $keo + $nau_dau + $tam_dai + $tam_ngan + $nuoc_cat + $tam_nhanh_sao;

        $cp = compareNumbers($khoi_luong_ban_dau, $NguyenLieuPhanLoai->tong_khoi_luong);
        if ($khoi_luong_ban_dau < $NguyenLieuPhanLoai->tong_khoi_luong) {
            DB::rollBack();
            return false;
        }

        if ($is_update) {
            if (isset($old_nguyen_lieu_tho_id) && isset($old_khoi_luong_ban_dau) && $old_nguyen_lieu_tho_id != $nguyen_lieu_tho_id) {
                $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_tho_id)->first();
                if ($nguyenLieuTho) {
                    $NguyenLieuPhanLoai->chi_phi_mua = $nguyenLieuTho->chi_phi_mua / $nguyenLieuTho->khoi_luong * $khoi_luong_ban_dau;

                    $NguyenLieuPhanLoai->khoi_luong_hao_hut = $khoi_luong_ban_dau - $NguyenLieuPhanLoai->tong_khoi_luong;
                    $NguyenLieuPhanLoai->gia_truoc_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->khoi_luong_ban_dau, 2);
                    $NguyenLieuPhanLoai->gia_sau_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->tong_khoi_luong, 2);

                    $nguyenLieuTho->khoi_luong_da_phan_loai = $khoi_luong_ban_dau;
                    $nguyenLieuTho->save();
                }

                $nguyenLieuTho = NguyenLieuTho::where('id', $old_nguyen_lieu_tho_id)->first();
                if ($nguyenLieuTho) {
                    $nguyenLieuTho->khoi_luong_da_phan_loai = $nguyenLieuTho->khoi_luong_da_phan_loai - $old_khoi_luong_ban_dau;
                    $nguyenLieuTho->save();
                }
            } else {
                $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_tho_id)->first();

                if ($nguyenLieuTho) {
                    $NguyenLieuPhanLoai->chi_phi_mua = $nguyenLieuTho->chi_phi_mua / $nguyenLieuTho->khoi_luong * $khoi_luong_ban_dau;

                    $NguyenLieuPhanLoai->khoi_luong_hao_hut = $khoi_luong_ban_dau - $NguyenLieuPhanLoai->tong_khoi_luong;
                    $NguyenLieuPhanLoai->gia_truoc_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->khoi_luong_ban_dau, 2);
                    $NguyenLieuPhanLoai->gia_sau_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->tong_khoi_luong, 2);

                    $nguyenLieuTho->khoi_luong_da_phan_loai = $khoi_luong_ban_dau;
                    $nguyenLieuTho->save();
                }
            }
        } else {
            $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_tho_id)->first();
            if ($nguyenLieuTho) {
                $NguyenLieuPhanLoai->chi_phi_mua = $nguyenLieuTho->chi_phi_mua / $nguyenLieuTho->khoi_luong * $khoi_luong_ban_dau;

                $NguyenLieuPhanLoai->khoi_luong_hao_hut = $khoi_luong_ban_dau - $NguyenLieuPhanLoai->tong_khoi_luong;
                $NguyenLieuPhanLoai->gia_truoc_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->khoi_luong_ban_dau, 2);
                $NguyenLieuPhanLoai->gia_sau_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->tong_khoi_luong, 2);

                $nguyenLieuTho->khoi_luong_da_phan_loai = $khoi_luong_ban_dau;
                $nguyenLieuTho->save();
            }
        }
        DB::commit();

        return $NguyenLieuPhanLoai;
    }

    /**
     * @throws \Throwable
     */
    private function updateNguyenLieuTho()
    {
        DB::beginTransaction();
        $datas = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();
        NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->update(['allow_change' => true]);
        foreach ($datas as $data) {
            $nguyenLieuTho = NguyenLieuTho::where('id', $data->nguyen_lieu_tho_id)->first();
            if ($nguyenLieuTho) {
                $nguyenLieuTho->allow_change = false;
                $nguyenLieuTho->save();
            }
        }
        DB::commit();
    }

    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $nguyen_lieu_phan_loai = NguyenLieuPhanLoai::find($id);
            if (!$nguyen_lieu_phan_loai || $nguyen_lieu_phan_loai->trang_thai == TrangThaiNguyenLieuPhanLoai::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu phân loại');
            }

            $nguyen_lieu_phan_loai = $this->saveData($nguyen_lieu_phan_loai, $request);
            if (!$nguyen_lieu_phan_loai) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Khối lượng ban đầu không đủ');
            }
            $nguyen_lieu_phan_loai->save();

            $this->updateNguyenLieuTho();

            DB::commit();
            return redirect()->route('admin.nguyen.lieu.phan.loai.index')->with('success', 'Chỉnh sửa nguyên liệu phân loại thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $nguyen_lieu_phan_loai = NguyenLieuPhanLoai::find($id);
            if (!$nguyen_lieu_phan_loai || $nguyen_lieu_phan_loai->trang_thai == TrangThaiNguyenLieuPhanLoai::DELETED()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu phân loại');
            }

            NguyenLieuPhanLoai::where('id', $id)
                ->where('khoi_luong_da_phan_loai', null)
                ->orWhere('khoi_luong_da_phan_loai', 0)
                ->update(['trang_thai' => TrangThaiNguyenLieuPhanLoai::DELETED()]);

            $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_phan_loai->nguyen_lieu_tho_id)->first();
            if ($nguyenLieuTho) {
                $nguyenLieuTho->khoi_luong_da_phan_loai = $nguyenLieuTho->khoi_luong_da_phan_loai - $nguyen_lieu_phan_loai->khoi_luong_ban_dau;
                $nguyenLieuTho->save();

                $otherNguyenLieuTho = NguyenLieuPhanLoai::where('nguyen_lieu_tho_id', $nguyenLieuTho->id)
                    ->where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
                    ->first();

                if (!$otherNguyenLieuTho) {
                    $nguyenLieuTho->allow_change = true;
                    $nguyenLieuTho->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Đã xoá nguyên liệu phân loại thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
