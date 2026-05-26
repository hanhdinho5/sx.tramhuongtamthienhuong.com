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
                    <form method="post" action="{{ route('admin.thong.tin.update', $item) }}"
                          enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="display_name">Tên hiển thị</label>
                                <input type="text" class="form-control" id="display_name" name="display_name"
                                       value="{{ $item->display_name }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="file">File upload</label>
                                <input type="file" class="form-control" id="file" name="file">

                                <a href="{{ $item->file_path }}" class="btn btn-primary btn-sm mt-2"
                                   download="{{ $item->file_name }}">
                                    <i class="bi bi-download"></i> {{ $item->file_name }}
                                </a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
