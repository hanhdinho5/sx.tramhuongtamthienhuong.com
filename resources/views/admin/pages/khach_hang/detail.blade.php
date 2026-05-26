@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa khách hàng
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa khách hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa khách hàng</li>
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
                    <h5 class="card-title">Chỉnh sửa khách hàng</h5>
                    <form method="post" action="{{ route('admin.khach.hang.update', $khachhang->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="ten">Họ và tên</label>
                            <input type="text" class="form-control" id="ten" name="ten" value="{{ $khachhang->ten }}"
                                   required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="tinh_thanh">Tỉnh thành</label>
                                <input type="text" class="form-control" id="tinh_thanh"
                                       value="{{ $khachhang->tinh_thanh }}"
                                       name="tinh_thanh">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="so_dien_thoai">Số điện thoại</label>
                                <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai"
                                       value="{{ $khachhang->so_dien_thoai }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nhom_khach_hang_id">Nhóm khách hàng</label>
                                <select id="nhom_khach_hang_id" name="nhom_khach_hang_id" class="form-control">
                                    @foreach($nhom_khach_hangs as $nhom_khach_hang)
                                        <option {{ old('nhom_khach_hang_id', $khachhang->nhom_khach_hang_id) == $nhom_khach_hang->id ? 'selected' : ''}}
                                                value="{{ $nhom_khach_hang->id }}">{{ $nhom_khach_hang->ten_nhom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{ $khachhang->trang_thai == \App\Enums\TrangThaiKhachHang::ACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiKhachHang::ACTIVE() }}">{{ \App\Enums\TrangThaiKhachHang::ACTIVE() }}</option>
                                    <option
                                        {{ $khachhang->trang_thai == \App\Enums\TrangThaiKhachHang::INACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiKhachHang::INACTIVE() }}">{{ \App\Enums\TrangThaiKhachHang::INACTIVE() }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dia_chi">Địa chỉ chi tiết</label>
                            <input type="text" class="form-control" id="dia_chi"
                                   name="dia_chi" value="{{ $khachhang->dia_chi }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
