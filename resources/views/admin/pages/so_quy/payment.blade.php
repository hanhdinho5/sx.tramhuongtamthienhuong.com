@php @endphp
@extends('admin.layouts.master')
@section('title')
    Thanh toán
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Thanh toán</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Thanh toán</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thanh toán</h5>
                    </div>
                    <form method="post" action="{{ route('admin.so.quy.store.payment') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ old('ngay', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="loai_quy_id">Tên quỹ</label>
                                <select class="form-control" name="loai_quy_id" id="loai_quy_id" required>
                                    @foreach($loai_quies as $loai_quy)
                                        <option
                                            value="{{ $loai_quy->id }}" {{ old('loai_quy_id') == $loai_quy->id ? 'selected' : '' }}>
                                            {{ $loai_quy->ten_loai_quy }} - Tổng
                                            tiền: {{ parseNumber($loai_quy->tong_tien_quy, 0) }} VND
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="so_tien">Số tiền thanh toán</label>
                                <input type="text" class="form-control onlyNumber" id="so_tien" name="so_tien"
                                       value="{{ old('so_tien') }}" required>
                            </div>
                        </div>
                        <div class="row" id="show_nha_cung_cap">
                            <div class="col-md-12 form-group">
                                <label for="nguyen_lieu_tho_id">Nhà cung cấp</label>
                                <select class="form-control selectCustom" name="nguyen_lieu_tho_id"
                                        id="nguyen_lieu_tho_id">
                                    <option value="">Tất cả</option>
                                    @foreach($nguyenLieuThos as $nguyenLieuTho)
                                        <option
                                            value="{{ $nguyenLieuTho->id }}" {{ old('nguyen_lieu_tho_id') == $nguyenLieuTho->id ? 'selected' : '' }}>
                                            {{ $nguyenLieuTho->NhaCungCap->ten }} - Mã đơn
                                            hàng: {{ $nguyenLieuTho->code }} - Công
                                            nợ: {{ parseNumber($nguyenLieuTho->cong_no, 0) }} VND
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        {{--                        <div class="form-group">--}}
                        {{--                            <label for="noi_dung">Nội dung</label>--}}
                        {{--                            <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5"--}}
                        {{--                                      required>{{ old('noi_dung') }}</textarea>--}}
                        {{--                        </div>--}}
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
