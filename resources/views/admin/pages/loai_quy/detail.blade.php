@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Loại Sổ quỹ
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Loại Sổ quỹ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Loại Sổ quỹ</li>
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
                    <h5 class="card-title">Chỉnh sửa Loại Sổ quỹ</h5>
                    <form method="post" action="{{ route('admin.loai.quy.update', $item->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ten_loai_quy">Tên quỹ</label>
                                <input type="text" id="ten_loai_quy" name="ten_loai_quy" class="form-control"
                                       value="{{ $item->ten_loai_quy }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tong_tien_quy">Tổng số tiền</label>
                                <input type="text" id="tong_tien_quy" name="tong_tien_quy" class="form-control"
                                       value="{{ number_format($item->tong_tien_quy) }}" readonly disabled>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
