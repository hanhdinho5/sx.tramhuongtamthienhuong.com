@extends('admin.layouts.master')
@section('title')
    Thêm mới nhân viên
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Thêm mới nhân viên</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Thêm mới nhân viên</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="post" action="{{ route('admin.nhan.vien.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                       value="{{ old('full_name') }}" required>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="{{ old('email') }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                           value="{{ old('phone') }}" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="password">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="password_confirm">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address"
                       value="{{ old('address') }}">
            </div>
            <div class="form-group">
                <label for="about">Giới thiệu</label>
                <input type="text" class="form-control" id="about" name="about"
                       value="{{ old('about') }}">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="avatar">Ảnh</label>
                    <input type="file" accept="image/*" class="form-control" id="avatar" name="avatar" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="room">Phòng ban</label>
                    <input type="text" class="form-control" id="room" name="room"
                           value="{{ old('room') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Trạng thái</label>
                    <select id="status" name="status" class="form-control">
                        <option value="{{ \App\Enums\UserStatus::ACTIVE() }}"
                            {{ old('status') == \App\Enums\UserStatus::ACTIVE() ? 'selected' : '' }}>
                            {{ \App\Enums\UserStatus::ACTIVE() }}
                        </option>
                        <option value="{{ \App\Enums\UserStatus::INACTIVE() }}"
                            {{ old('status') == \App\Enums\UserStatus::INACTIVE() ? 'selected' : '' }}>
                            {{ \App\Enums\UserStatus::INACTIVE() }}
                        </option>
                        <option value="{{ \App\Enums\UserStatus::BLOCKED() }}"
                            {{ old('status') == \App\Enums\UserStatus::BLOCKED() ? 'selected' : '' }}>
                            {{ \App\Enums\UserStatus::BLOCKED() }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="role_name">Phân quyền</label>
                <select id="role_name" name="role_name" class="form-control" required>
                    <option value="{{ \App\Enums\RoleName::NHAN_VIEN_SX }}"
                        {{ old('role_name') == \App\Enums\RoleName::NHAN_VIEN_SX ? 'selected' : '' }}>
                        Nhân viên Sản xuất
                    </option>
                    <option value="{{ \App\Enums\RoleName::KE_TOAN }}"
                        {{ old('role_name') == \App\Enums\RoleName::KE_TOAN ? 'selected' : '' }}>
                        Kế toán
                    </option>
                    <option value="{{ \App\Enums\RoleName::GIAM_DOC }}"
                        {{ old('role_name') == \App\Enums\RoleName::GIAM_DOC ? 'selected' : '' }}>
                        Giám đốc
                    </option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Tạo người dùng</button>

        </form>
    </section>
@endsection
