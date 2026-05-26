@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho nguyên liệu phân loại
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho nguyên liệu phân loại</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho nguyên liệu phân loại</li>
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
                    <h5 class="card-title">Chi tiết Kho nguyên liệu phân loại</h5>
                    @php
                        use App\Enums\RoleName; use App\Models\RoleUser; use App\Models\Role;
                        $__ruD = RoleUser::where('user_id', auth()->id())->first();
                        $__rlD = $__ruD ? Role::find($__ruD->role_id) : null;
                        $__isSX = $__rlD && $__rlD->name === RoleName::NHAN_VIEN_SX;
                    @endphp

                    @if($nguyen_lieu_phan_loai->khoi_luong_da_phan_loai <= 0 && !$__isSX)
                        <form method="post"
                              action="{{ route('admin.nguyen.lieu.phan.loai.update', $nguyen_lieu_phan_loai->id) }}">
                            @method('PUT')
                            @csrf
                            @endif
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nguyen_lieu_tho_id">Mã đơn hàng</label>
                                    <select name="nguyen_lieu_tho_id" id="nguyen_lieu_tho_id"
                                            class="form-control selectCustom">
                                        @foreach($nlthos as $nltho)
                                            <option
                                                {{ $nltho->id == $nguyen_lieu_phan_loai->nguyen_lieu_tho_id ? 'selected' : '' }}
                                                value="{{ $nltho->id }}">{{ $nltho->code }}
                                                - {{ $nltho->ten_nguyen_lieu }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="ngay">Ngày</label>
                                    <input type="date" class="form-control" id="ngay" name="ngay"
                                           value="{{ \Carbon\Carbon::parse($nguyen_lieu_phan_loai->ngay)->format('Y-m-d') }}"
                                           required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="nu_cao_cap">NL nụ cao cấp (NCC)</label>
                                    <input type="text" class="form-control onlyNumber" id="nu_cao_cap" name="nu_cao_cap"
                                           value="{{ $nguyen_lieu_phan_loai->nu_cao_cap }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nu_vip">NL nụ VIP (NVIP)</label>
                                    <input type="text" class="form-control onlyNumber" id="nu_vip" name="nu_vip"
                                           value="{{ $nguyen_lieu_phan_loai->nu_vip }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nhang">NL nhang (NLN)</label>
                                    <input type="text" class="form-control onlyNumber" id="nhang" name="nhang"
                                           value="{{ $nguyen_lieu_phan_loai->nhang }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="vong">NL vòng (NLV)</label>
                                    <input type="text" class="form-control onlyNumber" id="vong" name="vong"
                                           value="{{ $nguyen_lieu_phan_loai->vong }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="keo">Keo</label>
                                    <input type="text" class="form-control onlyNumber" id="keo" name="keo"
                                           value="{{ $nguyen_lieu_phan_loai->keo }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="nau_dau">Nấu dầu</label>
                                    <input type="text" class="form-control onlyNumber" id="nau_dau" name="nau_dau"
                                           value="{{ $nguyen_lieu_phan_loai->nau_dau }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="tam_nhanh_sao">Tăm nhanh sào</label>
                                    <input type="text" class="form-control onlyNumber" id="tam_nhanh_sao" name="tam_nhanh_sao"
                                           value="{{ $nguyen_lieu_phan_loai->tam_nhanh_sao }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="tam_dai">NL Tăm dài</label>
                                    <input type="text" class="onlyNumber form-control" id="tam_dai" name="tam_dai"
                                           value="{{ old('tam_dai') ?? $nguyen_lieu_phan_loai->tam_dai }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tam_ngan">NL Tăm ngắn</label>
                                    <input type="text" class="onlyNumber form-control" id="tam_ngan" name="tam_ngan"
                                           value="{{ old('tam_ngan') ?? $nguyen_lieu_phan_loai->tam_ngan }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nuoc_cat">Nước cất</label>
                                    <input type="text" class="onlyNumber form-control" id="nuoc_cat" name="nuoc_cat"
                                           value="{{ old('nuoc_cat') ?? $nguyen_lieu_phan_loai->nuoc_cat }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ghi_chu">Ghi chú</label>
                                <textarea name="ghi_chu" id="ghi_chu" class="form-control"
                                          rows="5">{{ $nguyen_lieu_phan_loai->ghi_chu }}</textarea>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="tong_khoi_luong">Tổng khối lượng</label>
                                    <input type="text" class="form-control" id="tong_khoi_luong" readonly disabled
                                           value="{{ parseNumber($nguyen_lieu_phan_loai->tong_khoi_luong,3) }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="khoi_luong_hao_hut">Khối lượng hao hụt</label>
                                    <input type="text" class="form-control" id="khoi_luong_hao_hut" readonly
                                           disabled
                                           value="{{ parseNumber($nguyen_lieu_phan_loai->khoi_luong_hao_hut, 3) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="chi_phi_mua">Chi phí mua</label>
                                    <input type="text" class="form-control" id="chi_phi_mua" readonly disabled
                                           value="{{ parseNumber($nguyen_lieu_phan_loai->chi_phi_mua, 3) }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="gia_sau_phan_loai">Giá sau phân loại</label>
                                    <input type="text" class="form-control" id="gia_sau_phan_loai" readonly
                                           disabled
                                           value="{{ parseNumber($nguyen_lieu_phan_loai->gia_sau_phan_loai, 3) }}">
                                </div>
                            </div>
                            @if($nguyen_lieu_phan_loai->khoi_luong_da_phan_loai <= 0 && !$__isSX)
                                <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                        </form>
                    @endif

                </div>

            </div>
        </div>
    </section>
@endsection
