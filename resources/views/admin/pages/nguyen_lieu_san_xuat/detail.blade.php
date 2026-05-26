@php @endphp
@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho Thành phẩm sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho Thành phẩm sản xuất</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho Thành phẩm sản xuất</li>
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
                    <h5 class="card-title">Chi tiết Kho Thành phẩm sản xuất</h5>
                    @php
                        use App\Enums\RoleName; use App\Models\RoleUser; use App\Models\Role;
                        $__ruD4 = RoleUser::where('user_id', auth()->id())->first();
                        $__rlD4 = $__ruD4 ? Role::find($__ruD4->role_id) : null;
                        $__isSX = $__rlD4 && $__rlD4->name === RoleName::NHAN_VIEN_SX;
                    @endphp
                    @if($nguyen_lieu_san_xuat->khoi_luong_da_dung <= 0 && !$__isSX)
                        <form method="post" id="form-update"
                              action="{{ route('admin.nguyen.lieu.san.xuat.update', $nguyen_lieu_san_xuat->id) }}">
                            @method('PUT')
                            @csrf
                            @endif
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nhan_vien_san_xuat">Nhân viên SX</label>
                                    <select id="nhan_vien_san_xuat" name="nhan_vien_san_xuat"
                                            class="form-control selectCustom">
                                        @foreach($nsus as $nsu)
                                            <option value="{{ $nsu->id }}"
                                                {{ old('nhan_vien_san_xuat', $nguyen_lieu_san_xuat->nhan_vien_san_xuat) == $nsu->id ? 'selected' : '' }}>
                                                {{ $nsu->full_name }}/{{ $nsu->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="phieu_san_xuat_id">Lô Sản Xuất</label>
                                    <select id="phieu_san_xuat_id" name="phieu_san_xuat_id"
                                            class="form-control selectCustom">
                                        @foreach($phieu_san_xuats as $phieu_san_xuat)
                                            <option
                                                {{ $phieu_san_xuat->id == $nguyen_lieu_san_xuat->phieu_san_xuat_id ? 'selected' : '' }}
                                                value="{{ $phieu_san_xuat->id }}">
                                                {{ $phieu_san_xuat->so_lo_san_xuat }}
                                                : {{ parseNumber($phieu_san_xuat->tong_khoi_luong - $phieu_san_xuat->khoi_luong_da_dung) }}
                                                kg
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if(!$__isSX)
                            <div class="w-100 d-flex mt-3 justify-content-end">
                                <button class="btn btn-primary btn-sm" type="button" onclick="init_form_product();">
                                    Thêm
                                </button>
                            </div>
                            @endif

                            <div class="table-responsive pt-3">
                                <table class="table table-hover table-bordered table-sm">
                                    <colgroup>
                                        <col width="50px">
                                        <col width="10%">
                                        <col width="x">
                                        <col width="15%">
                                        <col width="15%">
                                        <col width="20%">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        @if(!$__isSX)
                                        <th scope="col"></th>
                                        @endif
                                        <th scope="col">Ngày</th>
                                        <th scope="col">Tên thành phẩm</th>
                                        <th scope="col">Khối lượng(kg)</th>
                                        <th scope="col">Tổng tiền lô SX</th>
                                        <th scope="col">Chi tiết khác</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyFormCreate">
                                    <tr>
                                        @if(!$__isSX)
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="remove_items(this)" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                        @endif
                                        <td>
                                            <input type="date" class="form-control" name="ngay[]"
                                                   value="{{ Carbon\Carbon::parse($nguyen_lieu_san_xuat->ngay)->format('Y-m-d') }}"
                                                   required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                   name="ten_nguyen_lieu[]"
                                                   value="{{ $nguyen_lieu_san_xuat->ten_nguyen_lieu }}" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control onlyNumber"
                                                   name="khoi_luong[]" value="{{ $nguyen_lieu_san_xuat->khoi_luong }}"
                                                   required>

                                            <input type="hidden" name="gia_lo_san_xuat[]"
                                                   value="{{ $nguyen_lieu_san_xuat->don_gia }}">
                                            <input type="hidden" name="idx[]"
                                                   value="{{ $nguyen_lieu_san_xuat->id }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control onlyNumber"
                                                   name="tong_tien[]" value="{{ $nguyen_lieu_san_xuat->tong_tien }}">
                                        </td>
                                        <td>

                                            <input type="text" name="chi_tiet_khac[]" class="form-control"
                                                   value="{{ $nguyen_lieu_san_xuat->chi_tiet_khac }}">
                                        </td>
                                    </tr>

                                    @foreach($others as $other)
                                        <tr>
                                            @if(!$__isSX)
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="remove_items(this)" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                            @endif
                                            <td>
                                                <input type="date" class="form-control" name="ngay[]"
                                                       value="{{ Carbon\Carbon::parse($other->ngay)->format('Y-m-d') }}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                       name="ten_nguyen_lieu[]"
                                                       value="{{ $other->ten_nguyen_lieu }}" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control onlyNumber"
                                                       name="khoi_luong[]" value="{{ $other->khoi_luong }}" required>

                                                <input type="hidden" name="gia_lo_san_xuat[]"
                                                       value="{{ $other->don_gia }}">
                                                <input type="hidden" name="idx[]"
                                                       value="{{ $other->id }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control onlyNumber"
                                                       name="tong_tien[]" value="{{ $other->tong_tien }}">
                                            </td>
                                            <td>
                                                <input type="text" name="chi_tiet_khac[]" class="form-control"
                                                       value="{{ $other->chi_tiet_khac }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <input type="hidden" name="type_submit" id="type_submit">

                            @if($nguyen_lieu_san_xuat->khoi_luong_da_dung <= 0 && !$__isSX)
                                <div class="">
                                    <button type="button" onclick="pre_submit_form(-1)" class="btn btn-primary mt-2">Lưu
                                        tạm
                                    </button>
                                    <button type="button" onclick="pre_submit_form(1)" class="btn btn-warning mt-2">Hoàn
                                        thành
                                    </button>
                                    <button type="reset" class="btn btn-danger mt-2">Huỷ</button>
                                </div>
                        </form>
                    @endif

                </div>

            </div>
        </div>
    </section>
    <script>
        $(document).ready(function () {
            // init_form_product();
        })

        function remove_items(elm) {
            $(elm).parent().closest('tr').remove();
        }

        function init_form_product() {
            let html = `<tr>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="remove_items(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="ngay[]"
                                               value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                               name="ten_nguyen_lieu[]" value="" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control onlyNumber"
                                               name="khoi_luong[]" value="" required>

                                        <input type="hidden" name="gia_lo_san_xuat[]"
                                               value="">
                                        <input type="hidden" name="idx[]"
                                               value="">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control onlyNumber"
                                               name="tong_tien[]" value="" readonly>
                                    </td>
                                    <td>

                                                  <input type="text" name="chi_tiet_khac[]" class="form-control"
                                               value="">
                                    </td>
                                </tr>`;

            $('#tbodyFormCreate').append(html);

            init_number_format_input();
        }

        function pre_submit_form(num) {
            num = parseInt(num);
            let type_submit = num === 1 ? 'save' : 'temp';
            $('#type_submit').val(type_submit);

            let valid = true;

            $('input[name="ten_nguyen_lieu[]"]').each(function () {
                let name = $(this).val();
                if (!name) {
                    valid = false;
                    return false;
                }
            })

            if (!valid) {
                alert('Vui lòng nhập tên nguyên liệu!');
                return false;
            }

            $('input[name="khoi_luong[]"]').each(function () {
                let qty = $(this).val();
                if (!qty) {
                    valid = false;
                    return false;
                }
            })

            if (!valid) {
                alert('Vui lòng nhập khối lượng!');
                return false;
            }


            $('#form-update').submit();
        }
    </script>
@endsection
