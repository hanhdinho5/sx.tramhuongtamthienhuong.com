@extends('admin.layouts.master')
@section('title')
    Quản lý sản phẩm
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Quản lý sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Quản lý sản phẩm</li>
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
                        <h5 class="card-title">Thêm mới Thông tin Sản phẩm</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.san.pham.store') }}" enctype="multipart/form-data"
                          class="d-none">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ma_san_pham">Mã sản phẩm</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_san_pham"
                                       name="ma_san_pham" value="{{ old('ma_san_pham', $code) }}" readonly required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ma_vach">Mã vạch</label>
                                <input type="text" class="form-control" id="ma_vach" name="ma_vach"
                                       value="{{ old('ma_vach') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ten_san_pham">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="ten_san_pham" name="ten_san_pham"
                                   value="{{ old('ten_san_pham') }}" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="don_vi_tinh">Đơn vị tính</label>
                                <input type="text" class="form-control" id="don_vi_tinh" name="don_vi_tinh"
                                       value="{{ old('don_vi_tinh') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="khoi_luong_rieng">Khối lượng riêng(gram)</label>
                                <input type="text" class="form-control onlyNumber" id="khoi_luong_rieng"
                                       name="khoi_luong_rieng"
                                       value="{{ old('khoi_luong_rieng') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="gia_xuat_kho">Giá xuất kho (Giá nhập)</label>
                                <input type="text" class="form-control onlyNumber" id="gia_xuat_kho" name="gia_xuat_kho"
                                       value="{{ old('gia_xuat_kho') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="gia_ban">Giá bán (giá bán ra cho Khách hàng)</label>
                                <input type="text" class="form-control onlyNumber" id="gia_ban" name="gia_ban"
                                       value="{{ old('gia_ban') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="d-flex mb-4 mt-3 justify-content-end">
                <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('san_pham')">Xoá tất
                    cả
                </button>
            </div>
            <div class="card recent-sales overflow-auto">

                <div class="card-body pt-3">
                   <div class="table-responsive pt-3">
                        <table class="table table-hover datatable_wrapper w-100">
                            <colgroup>
                                <col width="3%">
                                <col width="8%">
                                <col width="10%">
                                <col width="10%">
                                <col width="x">
                                <col width="8%">
                                <col width="8%">
                                <col width="15%">
                                <col width="15%">
                                <col width="15%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" name="check_all" id="check_all">
                                </th>
                                <th scope="col">Hành động</th>
                                <th scope="col">Mã sản phẩm</th>
                                <th scope="col">Mã vạch</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Đơn vị tính</th>
                                <th scope="col">Khối lượng riêng(gram)</th>
                                <th scope="col">Giá xuất kho ( Giá nhập)</th>
                                <th scope="col">Giá bán (giá bán ra cho KHách hàng)</th>
                                <th scope="col">Tồn kho</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <th scope="row"><input type="checkbox" name="check_item[]"
                                                           id="check_item{{ $data->id }}"
                                                           value="{{ $data->id }}"></th>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.san.pham.detail', $data->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.san.pham.delete', $data->id) }}"
                                                  method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>{{ $data->ma_san_pham }}</td>
                                    <td>{{ $data->ma_vach }}</td>
                                    <td>{{ $data->ten_san_pham }}</td>
                                    <td>{{ $data->don_vi_tinh }}</td>
                                    <td>{{ $data->khoi_luong_rieng }}</td>
                                    <td>{{ parseNumber($data->gia_xuat_kho, 0) }} VND</td>
                                    <td>{{ parseNumber($data->gia_ban, 0) }} VND</td>
                                    <td>{{ parseNumber($data->ton_kho) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
