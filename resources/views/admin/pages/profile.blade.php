@extends('admin.layouts.master')
@section('title')
    Trang cá nhân
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Trang cá nhân</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{ __('base.Homepage') }}</a></li>
                <li class="breadcrumb-item active">Trang cá nhân</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
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

        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="{{ Auth::user()->avatar }}" alt="Profile" class="rounded-circle">
                        <h2>{{ Auth::user()->full_name }}</h2>
                        <h3>{{ Auth::user()->email }}</h3>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">
                                    Tổng quan
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">
                                    Chỉnh sửa thông tin
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">
                                    Cài đặt chung
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">
                                    Đổi mật khẩu
                                </button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">Giới thiệu</h5>
                                <p class="small fst-italic">
                                    {{ Auth::user()->about }}
                                </p>

                                <h5 class="card-title">Thông tin cá nhân</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Họ và tên</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->full_name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Số điện thoại</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->phone }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->email }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phòng ban</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->room }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Địa chỉ</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->address }}</div>
                                </div>

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form method="post" action="{{ route('admin.profile.change.info') }}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">
                                            Ảnh đại diện
                                        </label>
                                        <div class="col-md-8 col-lg-9">
                                            <img src="{{ Auth::user()->avatar }}" alt="Profile" id="imagePreview">
                                            <div class="pt-2">
                                                <a href="#" class="btn btn-primary btn-sm btnUpload"
                                                   title="Upload new profile image"><i class="bi bi-upload"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm btnRemoveImage"
                                                   title="Remove my profile image"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-none">
                                        <input type="file" accept="image/*" name="avatar" id="avatar">
                                    </div>

                                    <div class="row mb-3">
                                        <label for="full_name" class="col-md-4 col-lg-3 col-form-label">Họ và
                                            tên</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="full_name" type="text" class="form-control" id="full_name"
                                                   value="{{ Auth::user()->full_name }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="about" class="col-md-4 col-lg-3 col-form-label">Giới thiệu</label>
                                        <div class="col-md-8 col-lg-9">
                                            <textarea name="about" class="form-control" id="about"
                                                      style="height: 100px">{{ Auth::user()->about }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="phone" class="col-md-4 col-lg-3 col-form-label">Số điện
                                            thoại</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="phone" type="text" class="form-control" id="phone"
                                                   value="{{ Auth::user()->phone }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="email"
                                                   value="{{ Auth::user()->email }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="room" class="col-md-4 col-lg-3 col-form-label">Phòng ban</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="room" type="text" class="form-control" id="room"
                                                   value="{{ Auth::user()->room }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="address" class="col-md-4 col-lg-3 col-form-label">Địa chỉ</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="address" type="text" class="form-control" id="address"
                                                   value="{{ Auth::user()->address }}">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form><!-- End Profile Edit Form -->

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-settings">

                                <!-- Settings Form -->
                                <form>

                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email
                                            Notifications</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="changesMade"
                                                       checked>
                                                <label class="form-check-label" for="changesMade">
                                                    Changes made to your account
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="newProducts"
                                                       checked>
                                                <label class="form-check-label" for="newProducts">
                                                    Information on new products and services
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="proOffers">
                                                <label class="form-check-label" for="proOffers">
                                                    Marketing and promo offers
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="securityNotify"
                                                       checked disabled>
                                                <label class="form-check-label" for="securityNotify">
                                                    Security alerts
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form><!-- End settings Form -->

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form method="post" action="{{ route('admin.profile.change.password') }}">
                                    @csrf
                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">
                                            Mật khẩu hiện tại
                                        </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password" type="password" class="form-control"
                                                   id="currentPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">
                                            Mật khẩu mới
                                        </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="newpassword" type="password" class="form-control"
                                                   id="newPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">
                                            Nhập lại mật khẩu mới</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="renewpassword" type="password" class="form-control"
                                                   id="renewPassword">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form><!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>
    <script>
        $(document).ready(function () {
            $('.btnUpload').click(function () {
                $('#avatar').click();
            })

            const imgInp = document.getElementById('avatar');
            const imagePreview = document.getElementById('imagePreview');

            imgInp.onchange = evt => {
                const [file] = imgInp.files
                if (file) {
                    imagePreview.src = URL.createObjectURL(file)
                }
            }

            $('.btnRemoveImage').click(function () {
                imagePreview.src = '{{ Auth::user()->avatar }}';
                $('#avatar').val('');
            })
        })
    </script>
@endsection
