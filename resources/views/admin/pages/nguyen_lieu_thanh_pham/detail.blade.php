@php use App\Enums\TrangThaiNguyenLieuThanhPham; @endphp
@extends('admin.layouts.master')
@section('title')
    Chi tiết Kho đã đóng gói
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chi tiết Kho đã đóng gói</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chi tiết Kho đã đóng gói</li>
            </ol>
        </nav>
    </div>
    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @php
        use App\Enums\RoleName; use App\Models\RoleUser; use App\Models\Role;
        $__ruD = RoleUser::where('user_id', auth()->id())->first();
        $__rlD = $__ruD ? Role::find($__ruD->role_id) : null;
        $__isSX = $__rlD && $__rlD->name === RoleName::NHAN_VIEN_SX;
    @endphp
    <section class="section">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <h5 class="card-title">{{ $__isSX ? 'Thông tin Kho đã đóng gói' : 'Chỉnh sửa Kho đã đóng gói' }}</h5>
                    @if(!$__isSX)
                    <form method="post"
                          action="{{ route('admin.nguyen.lieu.thanh.pham.update', $nguyenLieuThanhPham->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ \Carbon\Carbon::parse($nguyenLieuThanhPham->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="nguyen_lieu_san_xuat_id">Lô SX</label>
                                <select name="nguyen_lieu_san_xuat_id" id="nguyennguyen_lieu_san_xuat_id_lieu_id"
                                        class="form-control selectCustom">
                                    @foreach($nlsanxuats as $nlsanxuat)
                                        <option
                                            {{ $nlsanxuat->id == $nguyenLieuThanhPham->nguyen_lieu_san_xuat_id ? 'selected' : '' }}
                                            value="{{ $nlsanxuat->id }}">
                                            {{ $nlsanxuat->PhieuSanXuat->so_lo_san_xuat }}
                                            : {{ $nlsanxuat->ten_nguyen_lieu }}
                                            : {{ parseNumber($nlsanxuat->khoi_luong - $nlsanxuat->khoi_luong_da_dung) }}
                                            kg
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="san_pham_id">Mã sản phẩm</label>
                                <select name="san_pham_id" id="san_pham_id" class="form-control selectCustom"
                                        onchange="changeSanPham();">
                                    @foreach($products as $product)
                                        <option
                                            {{ $product->id == $nguyenLieuThanhPham->san_pham_id ? 'selected' : '' }}
                                            value="{{ $product->id }}">
                                            {{ $product->ma_san_pham}} : {{ $product->ten_san_pham }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="ten_san_pham">Tên sản phẩm</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ten_san_pham"
                                       name="ten_san_pham" readonly required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="khoi_luong_rieng">KL rieng gr</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="khoi_luong_rieng"
                                       name="khoi_luong_rieng" readonly required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="don_vi_tinh">Đơn vị tính</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="don_vi_tinh"
                                       name="don_vi_tinh" readonly required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="price">Giá xuất kho thương mại</label>
                                <input type="text" class="form-control onlyNumber" id="price" name="price"
                                       value="{{ $nguyenLieuThanhPham->price }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="so_luong">Số lượng (cái/hộp)</label>
                                <input type="text" class="form-control" id="so_luong" name="so_luong"
                                       value="{{ $nguyenLieuThanhPham->so_luong }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="khoi_luong_da_dung">Khối lượng đã dùng</label>
                                <input type="text" class="form-control onlyNumber" id="khoi_luong_da_dung"
                                       name="khoi_luong_da_dung"
                                       value="{{ $nguyenLieuThanhPham->khoi_luong_da_dung }}"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="total_price">Tổng tiền</label>
                                <input type="text" class="form-control onlyNumber bg-secondary bg-opacity-10"
                                       value="{{ $nguyenLieuThanhPham->total_price }}" id="total_price"
                                       name="total_price" readonly required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{ $nguyenLieuThanhPham->trang_thai == TrangThaiNguyenLieuThanhPham::ACTIVE() ? 'selected' : '' }}
                                        value="{{ TrangThaiNguyenLieuThanhPham::ACTIVE() }}">{{ TrangThaiNguyenLieuThanhPham::ACTIVE() }}</option>
                                    <option
                                        {{ $nguyenLieuThanhPham->trang_thai == TrangThaiNguyenLieuThanhPham::INACTIVE() ? 'selected' : '' }}
                                        value="{{ TrangThaiNguyenLieuThanhPham::INACTIVE() }}">{{ TrangThaiNguyenLieuThanhPham::INACTIVE() }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ngay_san_xuat">Hạn sử dụng</label>
                                <input type="date" class="form-control" id="ngay_san_xuat" name="ngay_san_xuat"
                                       value="{{ \Carbon\Carbon::parse($nguyenLieuThanhPham->ngay_san_xuat)->format('Y-m-d') }}"
                                       required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ghi_chu">Ghi chú</label>
                            <textarea name="ghi_chu" id="ghi_chu" class="form-control"
                                      rows="5">{{ $nguyenLieuThanhPham->ghi_chu }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>
                    @else
                    {{-- Chế độ chỉ xem cho Nhân viên SX --}}
                    <table class="table table-bordered mt-2">
                        <tr><th>Ngày</th><td>{{ \Carbon\Carbon::parse($nguyenLieuThanhPham->ngay)->format('d-m-Y') }}</td></tr>
                        <tr><th>Lô SX</th><td>{{ $nguyenLieuThanhPham->nguyenLieuSanXuat->PhieuSanXuat->so_lo_san_xuat ?? '-' }}</td></tr>
                        <tr><th>Số lượng</th><td>{{ parseNumber($nguyenLieuThanhPham->so_luong) }}</td></tr>
                        <tr><th>Tổng tiền</th><td>{{ parseNumber($nguyenLieuThanhPham->total_price) }} VND</td></tr>
                        <tr><th>Ghi chú</th><td>{{ $nguyenLieuThanhPham->ghi_chu }}</td></tr>
                        <tr><th>Trạng thái</th><td>{{ $nguyenLieuThanhPham->trang_thai }}</td></tr>
                    </table>
                    @endif

                </div>

            </div>
        </div>
    </section>
    <script>
        changeSanPham();

        async function changeSanPham() {
            const product = $('#san_pham_id').val();
            await thongTinSanPham(product);
        }

        async function thongTinSanPham(id) {
            let url = `{{ route('api.thong.tin.san.pham.detail') }}?id=${id}`;

            $.ajax({
                url: url,
                type: 'GET',
                async: false,
                success: function (data, textStatus) {
                    renderData(data.data);
                },
                error: function (request, status, error) {
                    let data = JSON.parse(request.responseText);
                    alert(data.message);
                }
            });
        }

        function renderData(data) {
            const ten_san_pham = data.ten_san_pham;
            const khoi_luong_rieng = data.khoi_luong_rieng;
            const don_vi_tinh = data.don_vi_tinh;

            $('#ten_san_pham').val(ten_san_pham);
            $('#khoi_luong_rieng').val(khoi_luong_rieng);
            $('#don_vi_tinh').val(don_vi_tinh);
        }

        $('#so_luong').on('input', function () {
            const price = $('#price').val();
            const so_luong = $(this).val();
            const total = price * so_luong;
            $('#total_price').val(total);

            const khoi_luong_rieng = $('#khoi_luong_rieng').val();
            const khoi_luong_da_dung = so_luong * khoi_luong_rieng / 1000;
            $('#khoi_luong_da_dung').val(khoi_luong_da_dung);
        })

        $('#price').on('input', function () {
            const price = $(this).val();
            const so_luong = $('#so_luong').val();
            const total = price * so_luong;
            $('#total_price').val(total);
        })
    </script>
@endsection
