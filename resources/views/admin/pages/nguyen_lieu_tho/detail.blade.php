@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho nguyên liệu Thô
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho nguyên liệu Thô</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho nguyên liệu Thô</li>
            </ol>
        </nav>
    </div>
    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <section class="section">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    @if($nguyen_lieu_tho->allow_change)
                        <h5 class="card-title">Chỉnh sửa Kho nguyên liệu Thô</h5>
                        <form method="post" action="{{ route('admin.nguyen.lieu.tho.update', $nguyen_lieu_tho->id) }}">
                            @method('PUT')
                            @csrf
                            @else
                                <h5 class="card-title">Xem Kho nguyên liệu Thô</h5>
                            @endif
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="code">Mã đơn hàng</label>
                                    <input type="text" readonly class="form-control bg-secondary bg-opacity-10"
                                           id="code"
                                           name="code" value="{{ $code }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ngay">Ngày</label>
                                    <input type="date" class="form-control" id="ngay" name="ngay"
                                           value="{{ \Carbon\Carbon::parse($nguyen_lieu_tho->ngay)->format('Y-m-d') }}"
                                           required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nha_cung_cap_id">Nhà cung cấp</label>
                                    <select name="nha_cung_cap_id" id="nha_cung_cap_id"
                                            class="form-control selectCustom">
                                        @foreach($nccs as $ncc)
                                            <option
                                                {{ $nguyen_lieu_tho->nha_cung_cap_id == $ncc->id ? 'selected' : '' }}
                                                value="{{ $ncc->id }}">{{ $ncc->ten }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                                    <input type="text" class="form-control" id="ten_nguyen_lieu" name="ten_nguyen_lieu"
                                           value="{{ $nguyen_lieu_tho->ten_nguyen_lieu }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="loai">Loại</label>
                                    <input type="text" class="form-control" id="loai" name="loai"
                                           value="{{ $nguyen_lieu_tho->loai }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nguon_goc">Nguồn gốc</label>
                                    <input type="text" class="form-control" id="nguon_goc" name="nguon_goc"
                                           value="{{ $nguyen_lieu_tho->nguon_goc }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="khoi_luong">Khối lượng(kg)</label>
                                    <input type="text" min="0" class="form-control onlyNumber" id="khoi_luong"
                                           name="khoi_luong" value="{{ $nguyen_lieu_tho->khoi_luong }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="kich_thuoc">Kích thước</label>
                                    <input type="text" class="form-control" id="kich_thuoc" name="kich_thuoc"
                                           value="{{ $nguyen_lieu_tho->kich_thuoc }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="do_kho">Độ khô</label>
                                    <input type="text" class="form-control" id="do_kho" name="do_kho"
                                           value="{{ $nguyen_lieu_tho->do_kho }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="dieu_kien_luu_tru">Điều kiện lưu trữ</label>
                                    <input type="text" class="form-control" id="dieu_kien_luu_tru"
                                           name="dieu_kien_luu_tru"
                                           value="{{ $nguyen_lieu_tho->dieu_kien_luu_tru }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="chi_phi_mua">Chi phí mua </label>
                                    <input type="text" class="form-control onlyNumber" id="chi_phi_mua"
                                           name="chi_phi_mua"
                                           value="{{ $nguyen_lieu_tho->chi_phi_mua }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phuong_thuc_thanh_toan">Phương thức thanh toán</label>
                                    <select class="form-control selectCustom" name="phuong_thuc_thanh_toan"
                                            id="phuong_thuc_thanh_toan">
                                        @foreach($loai_quies as $loai_quy)
                                            <option
                                                {{ $nguyen_lieu_tho->phuong_thuc_thanh_toan == $loai_quy->id ? 'selected' : '' }}
                                                value="{{ $loai_quy->id }}">{{ $loai_quy->ten_loai_quy }}
                                                : {{ parseNumber($loai_quy->tong_tien_quy, 0) }} VND
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="so_tien_thanh_toan">Số tiền thanh toán</label>
                                    <input type="text" class="form-control onlyNumber" id="so_tien_thanh_toan"
                                           value="{{ $nguyen_lieu_tho->so_tien_thanh_toan }}" name="so_tien_thanh_toan"
                                           required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cong_no">Công nợ</label>
                                    <input type="text" class="form-control bg-secondary bg-opacity-10 onlyNumber"
                                           value="{{ $nguyen_lieu_tho->cong_no }}" id="cong_no" name="cong_no" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="nhan_su_xu_li">Nhân sự xử lý</label>
                                    <select id="nhan_su_xu_li" name="nhan_su_xu_li" class="form-control selectCustom">
                                        @foreach($nsus as $nsu)
                                            <option
                                                {{ $nsu->full_name == $nguyen_lieu_tho->nhan_su_xu_li ? 'selected' : '' }}
                                                value="{{ $nsu->full_name }}">{{ $nsu->full_name }}
                                                /{{ $nsu->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="thoi_gian_phan_loai">Thời gian phân loại </label>
                                    <input type="date" class="form-control" id="thoi_gian_phan_loai"
                                           name="thoi_gian_phan_loai"
                                           value="{{ \Carbon\Carbon::parse($nguyen_lieu_tho->thoi_gian_phan_loai)->format('yY-m-d') }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="trang_thai">Trạng thái</label>
                                    <select id="trang_thai" name="trang_thai" class="form-control">
                                        <option
                                            {{ $nguyen_lieu_tho->trang_thai == \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() ? 'selected' : '' }}
                                            value="{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}</option>
                                        <option
                                            {{ $nguyen_lieu_tho->trang_thai == \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() ? 'selected' : '' }}
                                            value="{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ghi_chu">Ghi chú</label>
                                <textarea name="ghi_chu" id="ghi_chu" class="form-control"
                                          rows="5">{{ $nguyen_lieu_tho->ghi_chu }}</textarea>
                            </div>

                            @if($nguyen_lieu_tho->allow_change)
                                <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </section>
@endsection
