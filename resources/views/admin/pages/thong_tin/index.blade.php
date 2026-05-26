@extends('admin.layouts.master')
@section('title')
    Thông tin Lương + OKR
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Thông tin Lương + OKR</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Thông tin Lương + OKR</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm theo tên...</label></h5>
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="inlineFormInputGroup"
                                   placeholder="Tìm kiếm theo tên...">

                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <h5 class="card-title">Thêm mới Thông tin Lương + OKR</h5>
                    <form method="post" action="{{ route('admin.thong.tin.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="display_name">Tên hiển thị</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="file">File upload</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex mb-4 mt-3 justify-content-end">
                        <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('thong_tin')">Xoá tất cả</button>
                    </div>
                   <div class="table-responsive pt-3">
                        <table class="table datatable_wrapper table-hover">
                            <colgroup>
                                <col width="5%">
                                <col width="x">
                                <col width="30%">
                                <col width="10%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" name="check_all" id="check_all">
                                </th>
                                <th scope="col">Tên hiển thị</th>
                                <th scope="col">File</th>
                                <th scope="col">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <th scope="row"><input type="checkbox" name="check_item[]"
                                                           id="check_item{{ $data->id }}"
                                                           value="{{ $data->id }}"></th>
                                    <td>{{ $data->display_name }}</td>
                                    <td>
                                        <a href="{{ $data->file_path }}"
                                           download="{{ $data->file_name }}">
                                            <i class="bi bi-download"></i> {{ $data->file_name }}</a>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ $data->file_path }}" target="_blank"
                                               class="btn btn-success btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.thong.tin.detail', $data->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.thong.tin.delete', $data->id) }}"
                                                  method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
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
