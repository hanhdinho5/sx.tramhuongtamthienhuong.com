@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa sản phẩm
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa sản phẩm</li>
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
                    <h5 class="card-title">Chỉnh sửa nhà cung cấp</h5>
                    <form method="post" action="{{ route('admin.san.pham.update', $item) }}"
                          enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ma_san_pham">Mã sản phẩm</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_san_pham"
                                       name="ma_san_pham" value="{{ $code }}" readonly required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ma_vach">Mã vạch</label>
                                <input type="text" class="form-control" id="ma_vach" name="ma_vach"
                                       value="{{ $item->ma_vach }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ten_san_pham">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="ten_san_pham" name="ten_san_pham"
                                   value="{{ $item->ten_san_pham }}" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="don_vi_tinh">Đơn vị tính</label>
                                <input type="text" class="form-control" id="don_vi_tinh" name="don_vi_tinh"
                                       value="{{ $item->don_vi_tinh }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="khoi_luong_rieng">Khối lượng riêng(gram)</label>
                                <input type="text" class="form-control onlyNumber" id="khoi_luong_rieng"
                                       name="khoi_luong_rieng"
                                       value="{{ $item->khoi_luong_rieng }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="gia_xuat_kho">Giá xuất kho ( Giá nhập)</label>
                                <input type="text" class="form-control onlyNumber" id="gia_xuat_kho" name="gia_xuat_kho"
                                       value="{{ $item->gia_xuat_kho }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="gia_ban">Giá bán (giá bán ra cho Khách hàng)</label>
                                <input type="text" class="form-control onlyNumber" id="gia_ban" name="gia_ban"
                                       value="{{ $item->gia_ban }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ton_kho">Tồn kho</label>
                                <input type="text" class="form-control onlyNumber" id="ton_kho" name="ton_kho"
                                       value="{{ $item->ton_kho }}" readonly disabled>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
