@extends('admin.layouts.master')
@section('title')
    Danh sách nhân viên
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Danh sách nhân viên</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Danh sách nhân viên</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="d-flex w-100 mb-4 mt-3 justify-content-end gap-2">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.nhan.vien.create') }}" class="btn btn-primary btn-sm">Tạo nhân viên</a>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('user')">Xoá tất cả</button>
            </div>
        </div>
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body pt-3">

                    <div class="table-responsive pt-3">
                        <table class="table datatable_wrapper table-hover">
                            <colgroup>
                                <col width="5%">
                                <col width="10%">
                                <col width="10%">
                                <col width="x">
                                <col width="10%">
                                <col width="10%">
                                <col width="20%">
                                <col width="10%">
                                <col width="10%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" name="check_all" id="check_all">
                                </th>
                                <th scope="col">Hành động</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Họ và tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Số điện thoại</th>
                                <th scope="col">Địa chỉ</th>
                                <th scope="col">Phòng</th>
                                <th scope="col">Quyền hạn</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <th scope="row"><input type="checkbox" name="check_item[]"
                                                           id="check_item{{ $user->id }}"
                                                           value="{{ $user->id }}"></th>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.nhan.vien.detail', $user) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @if($user->role_name != \App\Enums\RoleName::ADMIN)
                                                <form action="{{ route('admin.nhan.vien.delete', $user) }}"
                                                      method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <img class="rounded-circle" src="{{ $user->avatar }}"
                                             alt="{{ $user->full_name }}"
                                             width="100px"
                                             height="100px">
                                    </td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->room }}</td>
                                    <td>{{ $user->role_name == \App\Enums\RoleName::NHAN_VIEN_SX ? 'Nhân viên' : 'Admin' }}</td>
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
