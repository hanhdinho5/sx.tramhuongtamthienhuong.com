@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa nhân viên
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa nhân viên</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa nhân viên</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="post" action="{{ route('admin.nhan.vien.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @php
                use App\Models\RoleUser; use App\Models\Role;
                $__userRoleUser = RoleUser::where('user_id', $user->id)->first();
                $__currentRoleName = $__userRoleUser ? Role::find($__userRoleUser->role_id)?->name : null;
            @endphp
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                       value="{{ $user->full_name }}" required>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="{{ $user->email }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                           value="{{ $user->phone }}" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="password">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="form-group col-md-6">
                    <label for="password_confirm">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                </div>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $user->address }}">
            </div>
            <div class="form-group">
                <label for="about">Giới thiệu</label>
                <input type="text" class="form-control" id="about" name="about" value="{{ $user->about }}">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="avatar">Ảnh</label>
                    <input type="file" accept="image/*" class="form-control" id="avatar" name="avatar">
                    <img src="{{ $user->avatar }}" alt="" width="100px" height="100px" class="mt-2">
                </div>
                <div class="form-group col-md-4">
                    <label for="room">Phòng ban</label>
                    <input type="text" class="form-control" id="room" name="room"
                           value="{{ $user->room }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Trạng thái</label>
                    <select id="status" name="status" class="form-control">
                        <option {{ $user->status == \App\Enums\UserStatus::ACTIVE() ? 'selected' : '' }}
                                value="{{ \App\Enums\UserStatus::ACTIVE() }}">{{ \App\Enums\UserStatus::ACTIVE() }}</option>
                        <option {{ $user->status == \App\Enums\UserStatus::INACTIVE() ? 'selected' : '' }}
                                value="{{ \App\Enums\UserStatus::INACTIVE() }}">{{ \App\Enums\UserStatus::INACTIVE() }}</option>
                        <option {{ $user->status == \App\Enums\UserStatus::BLOCKED() ? 'selected' : '' }}
                                value="{{ \App\Enums\UserStatus::BLOCKED() }}">{{ \App\Enums\UserStatus::BLOCKED() }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="role_name">Phân quyền</label>
                <select id="role_name" name="role_name" class="form-control">
                    <option value="{{ \App\Enums\RoleName::NHAN_VIEN_SX }}"
                        {{ ($__currentRoleName ?? '') == \App\Enums\RoleName::NHAN_VIEN_SX ? 'selected' : '' }}>
                        Nhân viên Sản xuất
                    </option>
                    <option value="{{ \App\Enums\RoleName::KE_TOAN }}"
                        {{ ($__currentRoleName ?? '') == \App\Enums\RoleName::KE_TOAN ? 'selected' : '' }}>
                        Kế toán
                    </option>
                    <option value="{{ \App\Enums\RoleName::GIAM_DOC }}"
                        {{ ($__currentRoleName ?? '') == \App\Enums\RoleName::GIAM_DOC ? 'selected' : '' }}>
                        Giám đốc
                    </option>
                    <option value="{{ \App\Enums\RoleName::ADMIN }}"
                        {{ ($__currentRoleName ?? '') == \App\Enums\RoleName::ADMIN ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
        </form>
    </section>
@endsection
