@extends('admin.layouts.master')
@section('title')
    Nhóm khách hàng
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Nhóm khách hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Nhóm khách hàng</li>
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
                        <h5 class="card-title">Thêm mới Nhóm khách hàng</h5>
                    </div>
                    <form method="post" action="{{ route('admin.nhom.khach.hang.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="ten_nhom">Tên nhóm</label>
                            <input type="text" id="ten_nhom" name="ten_nhom" class="form-control"
                                   value="{{ old('ten_nhom') }}" required>
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
                        <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('nhom_khach_hang')">
                            Xoá tất
                            cả
                        </button>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table datatable_wrapper table-hover">
                            <colgroup>
                                <col width="5%">
                                <col width="10%">
                                <col width="15%">
                                <col width="x">
                            </colgroup>
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" name="check_all" id="check_all">
                                </th>
                                <th scope="col">Hành động</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tên nhóm</th>
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
                                            <a href="{{ route('admin.nhom.khach.hang.detail', $data->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.nhom.khach.hang.delete', $data->id) }}"
                                                  method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ $data->ten_nhom }}</td>
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
