@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Nhóm quỹ
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Nhóm quỹ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Nhóm quỹ</li>
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
                    <h5 class="card-title">Chỉnh sửa Nhóm quỹ</h5>
                    <form method="post" action="{{ route('admin.nhom.quy.update', $nhom_quy->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ten_nhom">Tên nhóm</label>
                                <input type="text" id="ten_nhom" name="ten_nhom" class="form-control"
                                       value="{{ $nhom_quy->ten_nhom }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
