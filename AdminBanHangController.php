<?php

namespace App\Http\Controllers\admin;

use App\Enums\LoaiSanPham;
use App\Enums\TrangThaiBanHang;
use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuThanhPham;
use App\Enums\TrangThaiNguyenLieuTho;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Http\Controllers\Controller;
use App\Models\BanHang;
use App\Models\BanHangChiTiet;
use App\Models\KhachHang;
use App\Models\LoaiQuy;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuThanhPham;
use App\Models\NguyenLieuTho;
use App\Models\NguyenLieuTinh;
use App\Models\SanPham;
use App\Models\SoQuy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBanHangController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = BanHang::where('trang_thai', '!=', TrangThaiBanHang::DELETED());

        $query->when($start_date, function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        });
        $query->when($end_date, function ($query) use ($end_date) {
            return $query->whereDate('created_at', '<=', $end_date);
        });

        $datas = $query->orderByDesc('id')->get();

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();

        return view('admin.pages.ban_hang.index', compact('datas', 'loai_quies', 'start_date', 'end_date'));;
    }

    public function detail($id)
    {
        $banhang = BanHang::find($id);
        if (!$banhang || $banhang->trang_thai == TrangThaiBanHang::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy hóa đơn bán hàng');
        }

        $khachhangs = KhachHang::where('trang_thai', '!=', TrangThaiBanHang::DELETED())
            ->orderByDesc('id')
            ->get();

        $chiTietBanHangs = BanHangChiTiet::where('ban_hang_id', $id)
            ->orderByDesc('id')
            ->get();

        switch ($banhang->loai_san_pham) {
            case LoaiSanPham::NGUYEN_LIEU_THO():
                $nguyenlieus = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
                    ->orderByDesc('id')
                    ->get();
                break;
            case LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                $nguyenlieus = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
                    ->orderByDesc('id')
                    ->get();
                break;
            case LoaiSanPham::NGUYEN_LIEU_TINH():
                $nguyenlieus = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
                    ->orderByDesc('id')
                    ->get();
                break;
            case LoaiSanPham::NGUYEN_LIEU_SAN_XUAT():
                $nguyenlieus = [];
                break;
            case LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                $nguyenlieus = NguyenLieuThanhPham::where('nguyen_lieu_thanh_phams.trang_thai', '!=', TrangThaiNguyenLieuThanhPham::DELETED())
                    ->join('san_phams', 'san_phams.id', '=', 'nguyen_lieu_thanh_phams.san_pham_id')
                    ->join('nguyen_lieu_san_xuats', 'nguyen_lieu_san_xuats.id', '=', 'nguyen_lieu_thanh_phams.nguyen_lieu_san_xuat_id')
                    ->join('phieu_san_xuats', 'phieu_san_xuats.id', '=', 'nguyen_lieu_san_xuats.phieu_san_xuat_id')
                    ->orderByDesc('nguyen_lieu_thanh_phams.id')
                    ->select('nguyen_lieu_thanh_phams.*', 'san_phams.ten_san_pham as ten_san_pham', 'san_phams.gia_ban as gia_ban', 'san_phams.don_vi_tinh as don_vi_tinh', 'phieu_san_xuats.so_lo_san_xuat as so_lo_san_xuat')
                    ->get();
                break;
        }

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.pages.ban_hang.detail', compact('banhang', 'chiTietBanHangs', 'khachhangs', 'nguyenlieus', 'loai_quies'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $lastItem = BanHang::orderByDesc('id')->first();
            $lastId = $lastItem?->id;
            $ma_don_hang = generateCodeBanHang($lastId + 1);

            $khach_hang_id = $request->input('khach_hang_id');

            $tong_tien = $request->input('tong_tien');
            $type_discount = $request->input('type_discount');

            $tong_giam_gia = $request->input('tong_giam_gia');

            $banhang = new BanHang([
                'khach_hang_id' => $khach_hang_id != 0 ? $khach_hang_id : null,
                'ban_le' => $khach_hang_id == 0,
                'ma_don_hang' => $ma_don_hang,
                'khach_le' => $request->input('ten_khach_hang') ?? '',
                'so_dien_thoai' => $request->input('so_dien_thoai') ?? '',
                'dia_chi' => $request->input('dia_chi') ?? '',
                'loai_san_pham' => $request->input('loai_san_pham'),
                'phuong_thuc_thanh_toan' => $request->input('loai_quy_id'),
                'tong_tien' => $request->input('tong_tien') ?? 0,
                'da_thanht_toan' => $request->input('da_thanht_toan') ?? '',
                'giam_gia' => $request->input('tong_giam_gia') ?? 0,
                'type_discount' => $request->input('type_discount') ?? 0,
                'cong_no' => $request->input('cong_no') ?? 0,
                'note' => $request->input('note'),
                'loai_nguon_hang' => $request->input('loai_nguon_hang') ?? '',
                'nguon_hang' => $request->input('nguon_hang') ?? 0,
                'trang_thai' => $request->input('trang_thai') ?? TrangThaiBanHang::ACTIVE(),
            ]);

            if ($request->filled('created_at')) {
                $banhang->created_at = $request->input('created_at') . ' ' . \Carbon\Carbon::now()->format('H:i:s');
            }

            if ($type_discount == 'percent') {
                $tong_giam_gia = $tong_tien * $tong_giam_gia / 100;
            }

            $loaiQuy = LoaiQuy::find($banhang->phuong_thuc_thanh_toan);
            if (!$loaiQuy) {
                return back()->with('error', 'Không tìm thấy loại quý');
            }

            $banhang->save();

            $sanPhamIds = $request->input('san_pham_id');
            $giaBans = $request->input('gia_bans');
            $soLuongs = $request->input('so_luong');
            $giam_gias = $request->input('giam_gia');

            $total = 0;

            foreach ($sanPhamIds as $i => $sanPhamId) {
                $giaBan = $giaBans[$i];
                $soLuong = $soLuongs[$i];
                $giam_gia = $giam_gias[$i];
                $tongTien = $giaBan * $soLuong - $giam_gia;

                BanHangChiTiet::create([
                    'ban_hang_id' => $banhang->id,
                    'san_pham_id' => $sanPhamId,
                    'gia_ban' => $giaBan,
                    'so_luong' => $soLuong,
                    'discount_amount' => $giam_gia,
                    'tong_tien' => $tongTien,
                ]);

                if (!$this->capNhatKho($banhang->loai_san_pham, $sanPhamId, $soLuong)) {
                    $banhang->delete();
                    DB::rollBack();
                    return back()->with('error', 'Số lượng không đủ!');
                }

                $total += $tongTien;
            }

            $banhang->update([
                'tong_tien' => $total,
                'cong_no' => $total - $tong_giam_gia - $banhang->da_thanht_toan,
            ]);

            $this->insertBanHang($banhang, false, null, $banhang->phuong_thuc_thanh_toan);

            DB::commit();
            return back()->with('success', 'Thêm mới hóa đơn bán hàng thành công');
        } catch (\Throwable $e) {
            dd($e);
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function create(Request $request)
    {
        $lastItem = BanHang::orderByDesc('id')->first();
        $lastId = $lastItem?->id;
        $ma_don_hang = generateCodeBanHang($lastId + 1);

        $khachhangs = KhachHang::where('trang_thai', '!=', TrangThaiBanHang::DELETED())
            ->orderByDesc('id')
            ->get();

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.pages.ban_hang.create', compact('ma_don_hang', 'khachhangs', 'loai_quies'));
    }

    private function capNhatKho($loaiSanPham, $sanPhamId, $soLuong)
    {
        switch ($loaiSanPham) {
            case LoaiSanPham::NGUYEN_LIEU_THO():
                $item = NguyenLieuTho::find($sanPhamId);
                $tonKho = $item?->khoi_luong - $item?->khoi_luong_da_phan_loai - $item?->khoi_luong_da_ban;
                if ($item && round($tonKho, 3) >= round($soLuong, 3)) {
                    $item->khoi_luong_da_ban += $soLuong;
                    $item->save();
                    return true;
                }
                break;

            case LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                $item = NguyenLieuPhanLoai::find($sanPhamId);
                $tonKho = $item?->tong_khoi_luong - $item?->khoi_luong_da_phan_loai;
                if ($item && round($tonKho, 3) >= round($soLuong, 3)) {
                    $item->khoi_luong_da_phan_loai += $soLuong;
                    $item->save();
                    return true;
                }
                break;

            case LoaiSanPham::NGUYEN_LIEU_TINH():
                $item = NguyenLieuTinh::find($sanPhamId);
                $tonKho = $item?->tong_khoi_luong - $item?->so_luong_da_dung;
                if ($item && round($tonKho, 3) >= round($soLuong, 3)) {
                    $item->so_luong_da_dung += $soLuong;
                    $item->save();
                    return true;
                }
                break;

            case LoaiSanPham::NGUYEN_LIEU_SAN_XUAT():
                $item = NguyenLieuSanXuat::find($sanPhamId);
                $tonKho = $item?->khoi_luong - $item?->khoi_luong_da_dung;
                if ($item && round($tonKho, 3) >= round($soLuong, 3)) {
                    $item->khoi_luong_da_dung += $soLuong;
                    $item->save();
                    return true;
                }
                break;

            case LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                $item = NguyenLieuThanhPham::find($sanPhamId);
                $tonKho = $item?->so_luong - $item?->so_luong_da_ban;
                if ($item && round($tonKho, 3) >= round($soLuong, 3)) {
                    $item->so_luong_da_ban += $soLuong;
                    $item->save();

                    $sanPham = SanPham::find($item->san_pham_id);
                    $sanPham->ton_kho -= $soLuong;
                    $sanPham->save();
                    return true;
                }
                break;
        }

        return false;
    }

    public function delete($id)
    {
        try {
            $banhang = BanHang::find($id);
            if (!$banhang || $banhang->trang_thai == TrangThaiBanHang::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy hóa đơn bán hàng');
            }

            $banhang->trang_thai = TrangThaiBanHang::DELETED();
            $banhang->save();

            $loaiSanPham = $banhang->loai_san_pham;

            $ban_hang_chi_tiet = BanHangChiTiet::where('ban_hang_id', $banhang->id)->get();
            foreach ($ban_hang_chi_tiet as $bh) {
                $sanPhamId = $bh->san_pham_id;
                $soLuong = $bh->so_luong;

                $this->rollback_item($loaiSanPham, $sanPhamId, $soLuong);

                $bh->delete();
            }

            $soquy = SoQuy::where('gia_tri_id', $banhang->id)
                ->where('loai', 1)
                ->first();

            if ($soquy) {
                $loai_quy = LoaiQuy::find($soquy->loai_quy_id);
                if ($loai_quy) {
                    if ($soquy->loai == 1) {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $soquy->so_tien;
                        $loai_quy->save();
                    } else {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy + $soquy->so_tien;
                        $loai_quy->save();
                    }
                }


                $soquy->delete();
            }

            return redirect()->back()->with('success', 'Đã xoá hóa đơn bán hàng thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function rollback_item($loaiSanPham, $sanPhamId, $soLuong)
    {
        switch ($loaiSanPham) {
            case LoaiSanPham::NGUYEN_LIEU_THO():
                $item = NguyenLieuTho::find($sanPhamId);
                $item->khoi_luong_da_ban -= $soLuong;
                $item->save();
                break;

            case LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                $item = NguyenLieuPhanLoai::find($sanPhamId);
                $item->khoi_luong_da_phan_loai -= $soLuong;
                $item->save();
                break;

            case LoaiSanPham::NGUYEN_LIEU_TINH():
                $item = NguyenLieuTinh::find($sanPhamId);
                $item->so_luong_da_dung -= $soLuong;
                $item->save();
                break;

            case LoaiSanPham::NGUYEN_LIEU_SAN_XUAT():
                $item = NguyenLieuSanXuat::find($sanPhamId);
                $item->khoi_luong_da_dung -= $soLuong;
                $item->save();
                break;

            case LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                $item = NguyenLieuThanhPham::find($sanPhamId);
                $item->so_luong_da_ban -= $soLuong;
                $item->save();

                $sanPham = SanPham::find($item->san_pham_id);
                $sanPham->ton_kho += $soLuong;
                $sanPham->save();
                break;
        }
    }

    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $banhang = BanHang::find($id);
            if (!$banhang || $banhang->trang_thai == TrangThaiBanHang::DELETED()) {
                return back()->with('error', 'Không tìm thấy hóa đơn bán hàng');
            }

            $khach_hang_id = $request->input('khach_hang_id');
            $ten_khach_hang = $request->input('ten_khach_hang');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $dia_chi = $request->input('dia_chi');
            $loai_san_pham = $request->input('loai_san_pham');
            $da_thanht_toan = $request->input('da_thanht_toan');
            $loai_quy_id = $request->input('loai_quy_id');
            $tong_tien = $request->input('tong_tien');
            $note = $request->input('note');
            $trang_thai = $request->input('trang_thai');
            $giam_gia = $request->input('tong_giam_gia') ?? 0;
            $type_discount = $request->input('type_discount') ?? 0;
            $cong_no = $request->input('cong_no');

            if ($type_discount == 'percent') {
                $tong_giam_gia = $tong_tien * $giam_gia / 100;
            } else {
                $tong_giam_gia = $giam_gia;
            }

            $banhang->update([
                'khach_hang_id' => $khach_hang_id != 0 ? $khach_hang_id : null,
                'ban_le' => $khach_hang_id == 0,
                'khach_le' => $ten_khach_hang,
                'so_dien_thoai' => $so_dien_thoai,
                'dia_chi' => $dia_chi,
                'loai_san_pham' => $loai_san_pham,
                'phuong_thuc_thanh_toan' => $loai_quy_id,
                'tong_tien' => $tong_tien ?? 0,
                'da_thanht_toan' => $da_thanht_toan ?? 0,
                'giam_gia' => $giam_gia ?? 0,
                'type_discount' => $type_discount ?? 0,
                'cong_no' => $cong_no ?? 0,
                'note' => $note,
                'loai_nguon_hang' => $request->input('loai_nguon_hang') ?? '',
                'nguon_hang' => $request->input('nguon_hang') ?? 0,
                'trang_thai' => $trang_thai ?? TrangThaiBanHang::ACTIVE()
            ]);

            if ($request->filled('created_at')) {
                $oldTime = \Carbon\Carbon::parse($banhang->created_at)->format('H:i:s');
                $banhang->created_at = \Carbon\Carbon::parse($request->input('created_at'))->format('Y-m-d') . ' ' . $oldTime;
            }

            $banhang->save();

            $sanPhamIds = $request->input('san_pham_id');
            $giaBans = $request->input('gia_bans');
            $soLuongs = $request->input('so_luong');
            $giam_gias = $request->input('giam_gia');

            $ban_hang_chi_tiet = BanHangChiTiet::where('ban_hang_id', $banhang->id)->get();
            foreach ($ban_hang_chi_tiet as $bh) {
                $sanPhamId = $bh->san_pham_id;
                $soLuong = $bh->so_luong;

                $this->rollback_item($loai_san_pham, $sanPhamId, $soLuong);

                $bh->delete();
            }

            $total = 0;

            foreach ($sanPhamIds as $i => $sanPhamId) {
                $giaBan = $giaBans[$i];
                $soLuong = $soLuongs[$i];
                $giamGia = $giam_gias[$i];

                $tongTien = $giaBan * $soLuong - $giamGia;

                $banHangChiTiet = BanHangChiTiet::updateOrCreate(
                    ['ban_hang_id' => $id, 'san_pham_id' => $sanPhamId],
                    ['gia_ban' => $giaBan, 'so_luong' => $soLuong, 'tong_tien' => $tongTien, 'discount_amount' => $giamGia]
                );

                if (!$this->capNhatKho($banhang->loai_san_pham, $sanPhamId, $soLuong)) {
                    $banhang->delete();
                    DB::rollBack();
                    return back()->with('error', 'Số lượng không đủ!');
                }

                $total += $tongTien;
            }

            $banhang->update([
                'tong_tien' => $total,
                'cong_no' => $total - $tong_giam_gia - $banhang->da_thanht_toan,
            ]);

            $soQuy = SoQuy::where('gia_tri_id', $banhang->id)->where('loai', 1)->first();
            $this->insertBanHang($banhang, true, $soQuy->id, $request->input('loai_quy_id'));
            DB::commit();

            return redirect()->route('admin.ban.hang.index')->with('success', 'Chỉnh sửa hóa đơn bán hàng thành công');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function insertBanHang(BanHang $banhang, $isUpdate, $idUpdate, $loai_quy_id)
    {
        if (!$isUpdate) {
            $code = $this->generateCode();
            $soquy = new SoQuy();
            $soquy->loai = 1;
            $soquy->so_tien = $banhang->da_thanht_toan;
            $soquy->gia_tri_id = $banhang->id;
            $soquy->ngay = Carbon::now();
            $soquy->noi_dung = 'Phiếu thu bán hàng cho đơn hàng: #' . $banhang->id;
            $soquy->ma_phieu = $code;
            $soquy->loai_quy_id = $loai_quy_id;
            $soquy->save();

            $loaiQuy = LoaiQuy::find($loai_quy_id);
            if ($loaiQuy) {
                $loaiQuy->tong_tien_quy = $loaiQuy->tong_tien_quy + $banhang->da_thanht_toan;
                $loaiQuy->save();
            }
        } else {
            $soquy = SoQuy::find($idUpdate);
            if ($loai_quy_id != $soquy->loai_quy_id) {
                $loaiQuy = LoaiQuy::find($soquy->loai_quy_id);
                if ($loaiQuy) {
                    $loaiQuy->tong_tien_quy = $loaiQuy->tong_tien_quy - $soquy->so_tien;
                    $loaiQuy->save();
                }

                $soquy->delete();

                $code = $this->generateCode();
                $soquy = new SoQuy();
                $soquy->loai = 1;
                $soquy->so_tien = $banhang->da_thanht_toan;
                $soquy->gia_tri_id = $banhang->id;
                $soquy->ngay = Carbon::now();
                $soquy->noi_dung = 'Phiếu thu bán hàng cho đơn hàng: #' . $banhang->id;
                $soquy->ma_phieu = $code;
                $soquy->loai_quy_id = $loai_quy_id;
                $soquy->save();

                $loaiQuy = LoaiQuy::find($loai_quy_id);
                if ($loaiQuy) {
                    $loaiQuy->tong_tien_quy = $loaiQuy->tong_tien_quy + $banhang->da_thanht_toan;
                    $loaiQuy->save();
                }
            } else {
                $oldTien = $soquy->so_tien;
                $soquy->loai = 1;
                $soquy->so_tien = $banhang->da_thanht_toan;
                $soquy->ngay = Carbon::now();
                $soquy->gia_tri_id = $banhang->id;
                $soquy->noi_dung = 'Phiếu thu bán hàng cho đơn hàng: #' . $banhang->id;
                $soquy->loai_quy_id = $loai_quy_id;
                $soquy->save();

                $loaiQuy = LoaiQuy::find($loai_quy_id);
                if ($loaiQuy) {
                    $loaiQuy->tong_tien_quy = $loaiQuy->tong_tien_quy + $banhang->da_thanht_toan - $oldTien;
                    $loaiQuy->save();
                }
            }
        }
    }

    private function generateCode()
    {
        $lastItem = SoQuy::orderByDesc('id')->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }

    public function store_bk(Request $request)
    {
        try {
            DB::beginTransaction();

            $khach_hang_id = $request->input('khach_hang_id');
            $ten_khach_hang = $request->input('ten_khach_hang');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $dia_chi = $request->input('dia_chi');
            $loai_san_pham = $request->input('loai_san_pham');
            $da_thanht_toan = $request->input('da_thanht_toan');
            $loai_quy_id = $request->input('loai_quy_id');

            $loaiQuy = LoaiQuy::find($loai_quy_id);
            if (!$loaiQuy) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy loại quý');
            }

            $banhang = new BanHang();

            $banhang->khach_hang_id = $khach_hang_id != 0 ? $khach_hang_id : null;
            $banhang->ban_le = $khach_hang_id == 0;
            $banhang->khach_le = $ten_khach_hang;
            $banhang->so_dien_thoai = $so_dien_thoai;
            $banhang->dia_chi = $dia_chi;
            $banhang->loai_san_pham = $loai_san_pham;
            $banhang->phuong_thuc_thanh_toan = $loai_quy_id;
            $banhang->tong_tien = 0;
            $banhang->da_thanht_toan = $da_thanht_toan ?? 0;
            $banhang->cong_no = 0;
            $banhang->trang_thai = TrangThaiBanHang::ACTIVE();

            $banhang->save();

            $id = $banhang->id;

            $san_pham_ids = $request->input('san_pham_id');
            $gia_bans = $request->input('gia_bans');
            $so_luongs = $request->input('so_luong');

            $total = 0;
            for ($i = 0; $i < count($san_pham_ids); $i++) {
                $san_pham_id = $san_pham_ids[$i];
                $ban_hang_chi_tiet = new BanHangChiTiet();
                $ban_hang_chi_tiet->ban_hang_id = $id;
                $ban_hang_chi_tiet->san_pham_id = $san_pham_id;
                $ban_hang_chi_tiet->gia_ban = $gia_bans[$i];
                $ban_hang_chi_tiet->so_luong = $so_luongs[$i];
                $ban_hang_chi_tiet->tong_tien = $ban_hang_chi_tiet->gia_ban * $ban_hang_chi_tiet->so_luong;
                $ban_hang_chi_tiet->save();

                switch ($banhang->loai_san_pham) {
                    case LoaiSanPham::NGUYEN_LIEU_THO():
                        $nguyenLieuTho = NguyenLieuTho::find($san_pham_id);
                        if ($nguyenLieuTho) {
                            $kl = $nguyenLieuTho->khoi_luong - $nguyenLieuTho->khoi_luong_da_ban;
                            if ($kl > $ban_hang_chi_tiet->so_luong) {
                                $banhang->delete();
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Số lượng không đủ!');
                            }
                            $nguyenLieuTho->khoi_luong_da_ban = $ban_hang_chi_tiet->so_luong;
                            $nguyenLieuTho->save();
                        }
                        break;
                    case LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                        $nguyenLieuPhanLoai = NguyenLieuPhanLoai::find($san_pham_id);
                        if ($nguyenLieuPhanLoai) {
                            $kl = $nguyenLieuPhanLoai->tong_khoi_luong - $nguyenLieuPhanLoai->khoi_luong_da_phan_loai;
                            if ($kl > $ban_hang_chi_tiet->so_luong) {
                                $banhang->delete();
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Số lượng không đủ!');
                            }
                            $nguyenLieuPhanLoai->khoi_luong_da_phan_loai = $ban_hang_chi_tiet->so_luong;
                            $nguyenLieuPhanLoai->save();
                        }
                        break;
                    case LoaiSanPham::NGUYEN_LIEU_TINH():
                        $nguyenLieuTinh = NguyenLieuTinh::find($san_pham_id);
                        if ($nguyenLieuTinh) {
                            $kl = $nguyenLieuTinh->tong_khoi_luong - $nguyenLieuTinh->so_luong_da_dung;
                            if ($kl > $ban_hang_chi_tiet->so_luong) {
                                $banhang->delete();
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Số lượng không đủ!');
                            }
                            $nguyenLieuTinh->so_luong_da_dung = $ban_hang_chi_tiet->so_luong;
                            $nguyenLieuTinh->save();
                        }
                        break;
                    case LoaiSanPham::NGUYEN_LIEU_SAN_XUAT():
                        $nguyenLieuSanXuat = NguyenLieuSanXuat::find($san_pham_id);
                        if ($nguyenLieuSanXuat) {
                            $kl = $nguyenLieuSanXuat->khoi_luong - $nguyenLieuSanXuat->khoi_luong_da_dung;
                            if ($kl > $ban_hang_chi_tiet->so_luong) {
                                $banhang->delete();
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Số lượng không đủ!');
                            }
                            $nguyenLieuSanXuat->khoi_luong_da_dung = $ban_hang_chi_tiet->so_luong;
                            $nguyenLieuSanXuat->save();
                        }
                        break;
                    case LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                        $nguyenLieuThanhPham = NguyenLieuThanhPham::find($san_pham_id);
                        if ($nguyenLieuThanhPham) {
                            $kl = $nguyenLieuThanhPham->so_luong - $nguyenLieuThanhPham->so_luong_da_ban;
                            if ($kl > $ban_hang_chi_tiet->so_luong) {
                                $banhang->delete();
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Số lượng không đủ!');
                            }
                            $nguyenLieuThanhPham->so_luong_da_ban = $ban_hang_chi_tiet->so_luong;
                            $nguyenLieuThanhPham->save();
                        }
                        break;
                }

                $total += $ban_hang_chi_tiet->gia_ban * $ban_hang_chi_tiet->so_luong;
            }

            $banhang->tong_tien = $total;
            $banhang->da_thanht_toan = $da_thanht_toan;
            $banhang->cong_no = $total - $da_thanht_toan;
            $banhang->save();

            $this->insertBanHang($banhang, false, null, $loai_quy_id);

            DB::commit();
            return redirect()->back()->with('success', 'Thêm mới hóa đơn bán hàng thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update_bk($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $khach_hang_id = $request->input('khach_hang_id');
            $ten_khach_hang = $request->input('ten_khach_hang');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $dia_chi = $request->input('dia_chi');
            $loai_san_pham = $request->input('loai_san_pham');
            $da_thanht_toan = $request->input('da_thanht_toan');
            $loai_quy_id = $request->input('loai_quy_id');

            $banhang = BanHang::find($id);
            if (!$banhang || $banhang->trang_thai == TrangThaiBanHang::DELETED()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy hóa đơn bán hàng');
            }

            $banhang->khach_hang_id = $khach_hang_id != 0 ? $khach_hang_id : null;
            $banhang->ban_le = $khach_hang_id == 0;
            $banhang->khach_le = $ten_khach_hang;
            $banhang->so_dien_thoai = $so_dien_thoai;
            $banhang->dia_chi = $dia_chi;
            $banhang->loai_san_pham = $loai_san_pham;
            $banhang->phuong_thuc_thanh_toan = $loai_quy_id;

            $san_pham_ids = $request->input('san_pham_id');
            $gia_bans = $request->input('gia_bans');
            $so_luongs = $request->input('so_luong');

            BanHangChiTiet::where('ban_hang_id', $id)
                ->whereNotIn('san_pham_id', $san_pham_ids)
                ->delete();

            $total = 0;
            for ($i = 0; $i < count($san_pham_ids); $i++) {
                $san_pham_id = $san_pham_ids[$i];

                $oldData = BanHangChiTiet::where('ban_hang_id', $id)
                    ->where('san_pham_id', $san_pham_id)
                    ->first();

                if ($oldData) {
                    $ban_hang_chi_tiet = $oldData;
                } else {
                    $ban_hang_chi_tiet = new BanHangChiTiet();
                }

                $ban_hang_chi_tiet->ban_hang_id = $id;
                $ban_hang_chi_tiet->san_pham_id = $san_pham_id;
                $ban_hang_chi_tiet->gia_ban = $gia_bans[$i];
                $ban_hang_chi_tiet->so_luong = $so_luongs[$i];
                $ban_hang_chi_tiet->tong_tien = $ban_hang_chi_tiet->gia_ban * $ban_hang_chi_tiet->so_luong;
                $ban_hang_chi_tiet->save();

                $total += $ban_hang_chi_tiet->gia_ban * $ban_hang_chi_tiet->so_luong;
            }

            $banhang->tong_tien = $total;
            $banhang->da_thanht_toan = $da_thanht_toan;
            $banhang->cong_no = $total - $da_thanht_toan;
            $banhang->save();

            $idUpdate = SoQuy::where('gia_tri_id', $banhang->id)->where('loai', 1)->first();
            $this->insertBanHang($banhang, true, $idUpdate->id, $loai_quy_id);

            DB::commit();
            return redirect()->route('admin.ban.hang.index')->with('success', 'Chỉnh sửa hóa đơn bán hàng thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
