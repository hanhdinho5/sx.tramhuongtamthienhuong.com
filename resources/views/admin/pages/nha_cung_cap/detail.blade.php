@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa nhà cung cấp
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa nhà cung cấp</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa nhà cung cấp</li>
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
                    <form method="post" action="{{ route('admin.nha.cung.cap.update', $ncc->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="ten">Họ và tên</label>
                            <input type="text" class="form-control" id="ten" name="ten" value="{{ $ncc->ten }}">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="tinh_thanh">Tỉnh thành</label>
                                <input type="text" class="form-control" id="tinh_thanh" value="{{ $ncc->tinh_thanh }}"
                                       name="tinh_thanh">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="so_dien_thoai">Số điện thoại</label>
                                <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai"
                                       value="{{ $ncc->so_dien_thoai }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{ $ncc->trang_thai == \App\Enums\TrangThaiNhaCungCap::ACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNhaCungCap::ACTIVE() }}">{{ \App\Enums\TrangThaiNhaCungCap::ACTIVE() }}</option>
                                    <option
                                        {{ $ncc->trang_thai == \App\Enums\TrangThaiNhaCungCap::INACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNhaCungCap::INACTIVE() }}">{{ \App\Enums\TrangThaiNhaCungCap::INACTIVE() }}</option>
                                    <option
                                        {{ $ncc->trang_thai == \App\Enums\TrangThaiNhaCungCap::BLOCKED() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNhaCungCap::BLOCKED() }}">{{ \App\Enums\TrangThaiNhaCungCap::BLOCKED() }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dia_chi">Địa chỉ chi tiết</label>
                            <input type="text" class="form-control" id="dia_chi"
                                   name="dia_chi" value="{{ $ncc->dia_chi }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
