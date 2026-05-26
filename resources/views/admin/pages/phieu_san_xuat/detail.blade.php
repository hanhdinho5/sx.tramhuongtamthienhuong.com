@php use App\Enums\TrangThaiPhieuSanXuat; @endphp
@php @endphp
@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Phiếu sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Phiếu sản xuất</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Phiếu sản xuất</li>
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
                    <h5 class="card-title">Chi tiết Phiếu sản xuất</h5>
                    @php
                        use App\Enums\RoleName; use App\Models\RoleUser; use App\Models\Role;
                        $__ruD3 = RoleUser::where('user_id', auth()->id())->first();
                        $__rlD3 = $__ruD3 ? Role::find($__ruD3->role_id) : null;
                        $__isSX = $__rlD3 && $__rlD3->name === RoleName::NHAN_VIEN_SX;
                    @endphp
                    @if($phieu_san_xuat->khoi_luong_da_dung <= 0 && !$__isSX)
                        <form method="post" action="{{ route('admin.phieu.san.xuat.update', $phieu_san_xuat) }}">
                            @method('PUT')
                            @csrf
                            @endif
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="code">Mã Phiếu</label>
                                    <input type="text" class="form-control bg-secondary bg-opacity-10" id="code"
                                           name="code"
                                           value="{{ old('code', $code) }}" readonly required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ten_phieu">Tên nguyên liệu</label>
                                    <input type="text" class="form-control" id="ten_phieu"
                                           name="ten_phieu" value="{{ old('ten_phieu', $phieu_san_xuat->ten_phieu) }}"
                                           required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="so_lo_san_xuat">Số LÔ SX</label>
                                    <input type="text" class="form-control bg-secondary bg-opacity-10"
                                           id="so_lo_san_xuat"
                                           name="so_lo_san_xuat" value="{{ old('so_lo_san_xuat', $so_lo_san_xuat) }}"
                                           readonly required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="tong_khoi_luong">Khối lượng</label>
                                    <input type="text" class="form-control onlyNumber bg-secondary bg-opacity-10"
                                           id="tong_khoi_luong" readonly
                                           name="tong_khoi_luong" value="{{ $phieu_san_xuat->tong_khoi_luong }}"
                                           readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nhan_su_xu_li">Nhân sự xử lý</label>
                                    <select id="nhan_su_xu_li" name="nhan_su_xu_li" class="form-control">
                                        @foreach($nsus as $nsu)
                                            <option
                                                {{ $nsu->id == $phieu_san_xuat->nhan_su_xu_li_id ? 'selected' : '' }}
                                                value="{{ $nsu->id }}">{{ $nsu->full_name }}
                                                /{{ $nsu->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="ngay">Ngày</label>
                                    <input type="date" class="form-control" id="ngay" name="ngay"
                                           value="{{ \Illuminate\Support\Carbon::parse($phieu_san_xuat->ngay)->format('Y-m-d') }}"
                                           required>
                                </div>


                                <div class="form-group col-md-6">
                                    <label for="thoi_gian_hoan_thanh_san_xuat">Thời gian dự kiến hoàn thành SX</label>
                                    <input type="date" class="form-control" id="thoi_gian_hoan_thanh_san_xuat"
                                           name="thoi_gian_hoan_thanh_san_xuat"
                                           value="{{ \Illuminate\Support\Carbon::parse($phieu_san_xuat->thoi_gian_hoan_thanh_san_xuat)->format('Y-m-d') }}"
                                           required>
                                </div>
                            </div>

                            <div class="mt-2">
                                <div class="w-100 d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Danh sách nguyên liệu</h4>

                                    @if(!$__isSX)
                                    <button type="button" class="btn btn-success btn-sm" onclick="plusItem()">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                    @endif
                                </div>
                                <table class="table table-bordered">
                                    <colgroup>
                                        <col width="40%">
                                        <col width="40%">
                                        <col width="15%">
                                        <col width="x">
                                    </colgroup>
                                    <thead>
                                    <tr class="text-center">
                                        <th scope="col">THÀNH PHẦN TRỘN TỪ MÃ ĐƠN HÀNG</th>
                                        <th scope="col">Tên NVL</th>
                                        <th scope="col">TỔNG KL</th>
                                        @if(!$__isSX)
                                        <th scope="col"></th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyListNL" class="text-center">
                                    @foreach($dsNLSXChiTiets as $dsNLSXChiTiet)
                                        <tr>
                                            <td>
                                                <select class="form-control selectCustom"
                                                        name="nguyen_lieu_ids[]">
                                                    @foreach($nltinhs as $nltinh)
                                                        <option
                                                            {{ $dsNLSXChiTiet->nguyen_lieu_id == $nltinh->id ? 'selected' : '' }}
                                                            value="{{ $nltinh->id }}">
                                                            {{ $nltinh->code }} - {{ $nltinh->ten_nguyen_lieu }}
                                                            - {{ $nltinh->tong_khoi_luong - $nltinh->so_luong_da_dung }}
                                                            kg
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="ten_nguyen_lieus[]" class="form-control"
                                                       value="{{ $dsNLSXChiTiet->ten_nguyen_lieu }}" required>
                                            </td>
                                            <td>
                                                <input type="text" name="khoi_luongs[]" class="form-control onlyNumber"
                                                       value="{{ $dsNLSXChiTiet->khoi_luong }}" required>
                                            </td>
                                            @if(!$__isSX)
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="removeItems(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($phieu_san_xuat->khoi_luong_da_dung <= 0 && !$__isSX)
                                <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                        </form>
                    @endif
                </div>

            </div>
        </div>

        <script>
            const baseHtml = `<tr><td>
                                        <select class="form-control selectCustom"
                                                name="nguyen_lieu_ids[]">
                                            @foreach($nltinhs as $nltinh)
            <option value="{{ $nltinh->id }}">{{ $nltinh->code }} - {{ $nltinh->ten_nguyen_lieu }}
            - {{ $nltinh->tong_khoi_luong - $nltinh->so_luong_da_dung }} kg
            </option>
@endforeach
            </select>
        </td>
        <td>
            <input type="text" name="ten_nguyen_lieus[]" class="form-control" required>
        </td>
        <td>
            <input type="text" min="0" name="khoi_luongs[]" class="form-control onlyNumber" required>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeItems(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;

            function plusItem() {
                $('#tbodyListNL').append(baseHtml);
                appendSelect2();
                init_number_format_input();
            }

            function appendSelect2() {
                $('.selectCustom').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Lựa chọn...',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: 0
                });
            }

            function removeItems(el) {
                $(el).parent().closest('tr').remove();
            }
        </script>
    </section>
@endsection
