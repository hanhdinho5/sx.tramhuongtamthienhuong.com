@extends('admin.layouts.master')
@section('title')
    Bán hàng
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Bán hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Bán hàng</li>
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
                        <h5 class="card-title">Thêm mới bán hàng</h5>
                        <!-- Button trigger modal -->
                        <a href="{{ route('admin.ban.hang.index') }}" class="btn btn-primary">
                            Xem lịch sử bán hàng
                        </a>
                    </div>
                    <form method="post" action="{{ route('admin.ban.hang.store') }}" class="" id="form_submit_order">
                        @csrf
                        <div class="row">
                            <div class="col-md-8 col-sm-12 border-end">
                                <div class="mt-3" id="formSanPham">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-group col-md-4 mb-2">
                                            <label for="select_kho">Chọn kho</label>
                                            <select id="select_kho" name="select_kho" class="form-control"
                                                    onchange="change_loai_san_pham()">
                                                <option value="">Lựa chọn kho</option>
                                                <option
                                                    value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_THO }}">
                                                    Kho Nguyên liệu Thô
                                                </option>
                                                <option
                                                    value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI }}">
                                                    Kho Nguyên liệu Phân loại
                                                </option>
                                                <option
                                                    value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_TINH }}">
                                                    Kho Nguyên liệu Tinh
                                                </option>
                                                <option
                                                    value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_SAN_XUAT }}">
                                                    Kho Thành phẩm sản xuất
                                                </option>
                                                <option
                                                    value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_THANH_PHAM }}">
                                                    Kho đã Đóng gói
                                                </option>
                                            </select>
                                        </div>

                                    </div>
                                    <table class="table table-bordered showForm">
                                        <colgroup>
                                            <col width="x">
                                            <col width="25%">
                                            <col width="15%">
                                            <col width="25%">
                                            <col width="5%">
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th scope="col">Tên sản phẩm</th>
                                            <th scope="col">Giá bán</th>
                                            <th scope="col">SL/KL</th>
                                            <th scope="col">Tổng tiền</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbodySanPham">

                                        </tbody>
                                    </table>

                                    <h4>Các sản phẩm đã chọn</h4>
                                    <table class="table table-bordered">
                                        <colgroup>
                                            <col width="x">
                                            <col width="25%">
                                            <col width="15%">
                                            <col width="25%">
                                            <col width="5%">
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th scope="col">Tên sản phẩm</th>
                                            <th scope="col">Giá bán</th>
                                            <th scope="col">SL/KL</th>
                                            <th scope="col">Tổng tiền</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbodySanPhamSelected">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group col-md-12">
                                    <label for="ma_don_hang">Mã đơn hàng</label>
                                    <input type="text" class="form-control" id="ma_don_hang"
                                           name="ma_don_hang" readonly disabled
                                           value="{{ old('ma_don_hang', $ma_don_hang) }}">
                                </div>

                                <div class="form-group">
                                    <label for="khach_hang_id">Khách hàng</label>
                                    <select id="khach_hang_id" name="khach_hang_id" class="form-control selectCustom"
                                            onchange="changeKhachHang()">
                                        <option value="0" {{ old('khach_hang_id') == 0 ? 'selected' : '' }}>Khách lẻ
                                        </option>
                                        @foreach($khachhangs as $khachhang)
                                            <option
                                                value="{{ $khachhang->id }}" {{ old('khach_hang_id') == $khachhang->id ? 'selected' : '' }}>
                                                {{ $khachhang->ten }} : {{ $khachhang->so_dien_thoai }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row" id="formKhachLe">
                                    <div class="form-group col-md-12">
                                        <label for="ten_khach_hang">Tên khách hàng</label>
                                        <input type="text" class="form-control" id="ten_khach_hang"
                                               name="ten_khach_hang" required
                                               value="{{ old('ten_khach_hang') }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="so_dien_thoai">Số điện thoại</label>
                                        <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai"
                                               value="{{ old('so_dien_thoai') }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="dia_chi">Địa chỉ chi tiết</label>
                                        <input type="text" class="form-control" id="dia_chi" name="dia_chi"
                                               value="{{ old('dia_chi') }}">
                                    </div>
                                </div>

                                <div class="pt-3 pb-2 border-top border-bottom mt-3 mb-3">
                                    <table class="table table-bordered">
                                        <colgroup>
                                            <col width="50%">
                                            <col width="50%">
                                        </colgroup>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <label for="tong_tien">Tổng tiền</label>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control bg-secondary bg-opacity-10 onlyNumber"
                                                       id="tong_tien" name="tong_tien" value="{{ old('tong_tien') }}"
                                                       readonly required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="giam_gia">Giảm giá</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control onlyNumber" id="giam_gia"
                                                       oninput="calc_total_item()" name="giam_gia"
                                                       value="{{ old('giam_gia', 0) }}" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="tong_thanh_toan">Tổng thanh toán</label>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control bg-secondary bg-opacity-10 onlyNumber"
                                                       id="tong_thanh_toan"
                                                       name="tong_thanh_toan" value="{{ old('tong_thanh_toan') }}"
                                                       readonly required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="da_thanht_toan">Khách đưa</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control onlyNumber" id="da_thanht_toan"
                                                       name="da_thanht_toan" value="{{ old('da_thanht_toan', 0) }}"
                                                       oninput="calc_total_item()" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="cong_no">Công nợ</label>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control bg-secondary bg-opacity-10 onlyNumber"
                                                       id="cong_no" name="cong_no" value="{{ old('cong_no') }}" readonly
                                                       required>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="form-group col-md-12">
                                    <label for="loai_nguon_hang">Loại nguồn hàng</label>
                                    <select class="form-control" name="loai_nguon_hang" id="loai_nguon_hang"
                                            onchange="change_loai_nguon_hang();">
                                        <option value="">Lựa chọn</option>
                                        <option value="ncc">Nhà cung cấp</option>
                                        <option value="kh">Khách hàng</option>
                                        <option value="nv">Nhân viên</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="nguon_hang">Nguồn hàng</label>
                                    <select class="form-control selectCustom" name="nguon_hang" id="nguon_hang">

                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="trang_thai">Trạng thái đơn hàng</label>
                                    <select class="form-control" name="trang_thai" id="trang_thai">
                                        <option
                                            value="{{ \App\Enums\TrangThaiBanHang::ACTIVE() }}">{{ \App\Enums\TrangThaiBanHang::ACTIVE() }}</option>
                                        <option
                                            value="{{ \App\Enums\TrangThaiBanHang::PENDING() }}">{{ \App\Enums\TrangThaiBanHang::PENDING() }}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="loai_quy_id">Loại quỹ</label>
                                    <select class="form-control selectCustom" name="loai_quy_id" id="loai_quy_id">
                                        @foreach($loai_quies as $loai_quy)
                                            <option
                                                value="{{ $loai_quy->id }}" {{ old('loai_quy_id') == $loai_quy->id ? 'selected' : '' }}>
                                                {{ $loai_quy->ten_loai_quy }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="note">Ghi chú</label>
                                    <textarea name="note" class="form-control" id="note" rows="5"></textarea>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="loai_san_pham" id="loai_san_pham">

                        <div class="w-100 d-flex justify-content-end mt-3 gap-2">
                            <button type="submit" class="btn btn-primary">Thanh toán</button>
                            <button type="reset" class="btn btn-danger">Hủy</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>

        <script>
            async function changeKhachHang() {
                const khachHangId = $('#khach_hang_id').val();
                if (khachHangId !== 0) {
                    await selectKhachHang(khachHangId);
                }
            }

            async function selectKhachHang(id) {
                const url = `{{ route('api.khach.hang.detail') }}?id=${id}`;
                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        renderKhachHnag(data.data);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            function renderKhachHnag(data) {
                const tenKhachHang = data.ten;
                const soDienThoai = data.so_dien_thoai;
                const diaChi = data.dia_chi;
                $('#ten_khach_hang').val(tenKhachHang);
                $('#so_dien_thoai').val(soDienThoai);
                $('#dia_chi').val(diaChi);
            }

            async function change_loai_san_pham() {
                const select_kho = $('#select_kho');
                const loaiSanPham = select_kho.val();
                $('#loai_san_pham').val(loaiSanPham);
                await get_list_san_pham(loaiSanPham);
            }

            async function get_list_san_pham(loaiSanPham) {
                let url = '';
                switch (loaiSanPham) {
                    case 'NGUYEN_LIEU_THO':
                        url = `{{ route('api.nguyen.lieu.tho.list') }}`;
                        break;
                    case 'NGUYEN_LIEU_PHAN_LOAI':
                        url = `{{ route('api.nguyen.lieu.phan.loai.list') }}`;
                        break;
                    case 'NGUYEN_LIEU_TINH':
                        url = `{{ route('api.nguyen.lieu.tinh.list') }}`;
                        break;
                    case 'NGUYEN_LIEU_SAN_XUAT':
                        url = `{{ route('api.nguyen.lieu.san.xuat.list') }}`;
                        break;
                    case 'NGUYEN_LIEU_THANH_PHAM':
                        url = `{{ route('api.nguyen.lieu.thanh.pham.list') }}`;
                        break;
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        render_san_pham(data.data, loaiSanPham);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            function render_san_pham(data, loaiSanPham) {
                let html = '';
                let gia_ = null;

                data.forEach((item) => {
                    let ten_;

                    switch (loaiSanPham) {
                        case 'NGUYEN_LIEU_THO':
                            ten_ = item.code + ' : ' +
                                (Number(item.khoi_luong) - Number(item.khoi_luong_da_phan_loai)).toFixed(3) + 'kg';
                            if (!gia_) {
                                gia_ = Number(item.chi_phi_mua) / Number(item.khoi_luong || 1);
                                gia_ = Number(gia_.toFixed(3));
                            }
                            break;

                        case 'NGUYEN_LIEU_PHAN_LOAI':
                            ten_ = item.ma_don_hang + ' : ' +
                                (Number(item.tong_khoi_luong) - Number(item.khoi_luong_da_phan_loai ?? 0)).toFixed(3) + 'kg';
                            if (!gia_) {
                                gia_ = Number(item.gia_sau_phan_loai ?? 0);
                                gia_ = Number(gia_.toFixed(3));
                            }
                            break;

                        case 'NGUYEN_LIEU_TINH':
                            ten_ = item.code + ' : ' +
                                (Number(item.tong_khoi_luong) - Number(item.so_luong_da_dung ?? 0)).toFixed(3) + 'kg';
                            if (!gia_) {
                                gia_ = Number(item.gia_tien ?? 0);
                                gia_ = Number(gia_.toFixed(3));
                            }
                            break;

                        case 'NGUYEN_LIEU_SAN_XUAT':
                            ten_ = item.ten_nguyen_lieu + ' - ' + item.phieu_san_xuat.so_lo_san_xuat + ' : ' +
                                (Number(item.khoi_luong) - Number(item.khoi_luong_da_dung ?? 0)).toFixed(3) + (item.don_vi_tinh || 'kg');
                            if (!gia_) {
                                gia_ = Number(item.gia_tien ?? 0);
                                gia_ = Number(gia_.toFixed(3));
                            }
                            break;

                        case 'NGUYEN_LIEU_THANH_PHAM':
                            ten_ = item.ten_san_pham + ' - ' + item.so_lo_san_xuat + ' : ' +
                                (Number(item.so_luong) - Number(item.so_luong_da_ban ?? 0)).toFixed(3) + ' ' + item.don_vi_tinh;
                            if (!gia_) {
                                gia_ = Number(item.gia_ban ?? 0);
                                gia_ = Number(gia_.toFixed(3));
                            }
                            break;
                    }

                    html += `<option value="${item.id}">${ten_}</option>`;
                });

                const listSanPham = $('#listSanPham');
                listSanPham.find('select').empty().append(html);
                listSanPham.find('input#gia_ban').val(gia_);
                listSanPham.find('input#so_luong').val(1);
                listSanPham.find('input#tong_tien').val(gia_);
            }

            function change_gia_san_pham_temp(el) {
                const totalEl = $(el).closest('tr').find('input#tong_tien');
                const gia_ = $(el).closest('tr').find('input#gia_ban').val();
                const so_luong = $(el).closest('tr').find('input#so_luong').val();
                totalEl.val(gia_ * so_luong);
            }

            function change_thong_tin_san_pham(el) {
                const loaiSanPham = $('#loai_san_pham').val();
                const id = $(el).val();
                lay_thong_tin_nguyen_lieu(id, el, loaiSanPham);
            }

            function lay_thong_tin_nguyen_lieu(id, el, loaiSanPham) {
                const url = `{{ route('api.chi.tiet.nguyen.lieu') }}?id=${id}&type=${loaiSanPham}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        render_chi_tiet_san_pham(data.data, el, loaiSanPham);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            function render_chi_tiet_san_pham(item, element, loaiSanPham) {
                let gia_ = null;
                switch (loaiSanPham) {
                    case 'NGUYEN_LIEU_THO':
                        gia_ = Number(item.chi_phi_mua) / Number(item.khoi_luong || 1);
                        gia_ = Number(gia_.toFixed(3));
                        break;
                    case 'NGUYEN_LIEU_PHAN_LOAI':
                        gia_ = Number(item.gia_sau_phan_loai ?? 0);
                        gia_ = Number(gia_.toFixed(3));
                        break;
                    case 'NGUYEN_LIEU_TINH':
                        gia_ = Number(item.gia_tien ?? 0);
                        gia_ = Number(gia_.toFixed(3));
                        break;
                    case 'NGUYEN_LIEU_SAN_XUAT':
                        gia_ = Number(item.gia_tien ?? 0);
                        gia_ = Number(gia_.toFixed(3));
                        break;
                    case 'NGUYEN_LIEU_THANH_PHAM':
                        gia_ = Number(item.gia_ban ?? 0);
                        gia_ = Number(gia_.toFixed(3));
                        break;
                }

                $(element).closest('tr').find('input#gia_ban').val(gia_);

                change_gia_san_pham_temp(element);
            }

            function add_new_items(elm) {
                let tr = $(elm).closest('tr');

                let select = tr.find('#san_pham_id');
                let elm_gia_ban = tr.find('#gia_ban');
                let elm_so_luong = tr.find('#so_luong');

                let txt = $('#san_pham_id option:selected').text();
                let san_pham_id = select.val();

                if (!san_pham_id) {
                    alert('Vui lòng chọn sản phẩm!');
                    return false;
                }

                let gia_ban = elm_gia_ban.val();
                let so_luong = elm_so_luong.val();

                let total = gia_ban * so_luong;

                let html = `<tr>
                                            <td>
                                                <span class="h6">${txt}</span>
                                                <input type="hidden" name="san_pham_id[]" value="${san_pham_id}">
                                            </td>
                                            <td>
                                                <input type="text" min="0" name="gia_bans[]"
                                                       class="form-control gia_bans" value="${gia_ban}"
                                                       oninput="change_gia_san_pham(this)" required>
                                            </td>
                                            <td>
                                                <input type="number" min="1" name="so_luong[]"
                                                       class="form-control so_luong" value="${so_luong}"
                                                       oninput="change_gia_san_pham(this)" required>
                                            </td>
                                            <td>
                                                <input type="text" name="tong_tien[]" class="form-control tong_tien"
                                                       disabled readonly value="${total}">
                                            </td>
                                            <td>
                                                <button type="button" onclick="remove_items(this)"
                                                        class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>`;

                $('#tbodySanPham').append(html);

                elm_gia_ban.val('');
                so_luong.val(1);
            }

            function searchTable() {
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();
                window.location.href = "{{ route('admin.ban.hang.index') }}?start_date=" + start_date + "&end_date=" + end_date;
            }

            $(document).ready(function () {
                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();
                if (start_date || end_date) {
                    const modal = new bootstrap.Modal(document.getElementById('orderHistoryModal'));
                    modal.show();
                }
            });

            function change_thanh_toan() {
                calc_total_item();

                let tong_thanh_toan = $('#tong_thanh_toan').val() || 0;
                $('#da_thanht_toan').val(0);

                $('#cong_no').val(tong_thanh_toan);
            }

            function calc_total_item() {
                let total = 0;

                $('#form_submit_order input[name="tong_tien[]"]').each(function () {
                    total += parseFloat(this.value) || 0;
                });

                $('#tong_tien').val(total);

                let giam_gia = $('#giam_gia').val() || 0;
                let da_thanht_toan = $('#da_thanht_toan').val() || 0;

                let tong_thanh_toan = total - giam_gia;
                let cong_no = tong_thanh_toan - da_thanht_toan;

                $('#tong_thanh_toan').val(tong_thanh_toan);
                $('#cong_no').val(cong_no);
            }

            async function change_loai_nguon_hang() {
                let loai_nguon_hang = $('#loai_nguon_hang').val();

                if (loai_nguon_hang) {
                    await nguon_hang(loai_nguon_hang);
                } else {
                    $('#nguon_hang').empty().append('<option value="">Lựa chọn...</option>');
                }
            }

            async function nguon_hang(loai_nguon_hang) {
                let url = `{{ route('api.nguon.hang.ban.hang') }}?loai_nguon_hang=${loai_nguon_hang}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        render_nguon_hang(data.data, loai_nguon_hang);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            function render_nguon_hang(data, loai_nguon_hang) {
                let html = '<option value="">Lựa chọn...</option>';
                for (let i = 0; i < data.length; i++) {
                    if (loai_nguon_hang == 'ncc') {
                        html += `<option value="${data[i].id}">${data[i].ten}</option>`;
                    } else if (loai_nguon_hang == 'kh') {
                        html += `<option value="${data[i].id}">${data[i].ten}</option>`;
                    } else {
                        html += `<option value="${data[i].id}">${data[i].full_name}</option>`;
                    }
                }

                $('#nguon_hang').empty().append(html);
            }
        </script>

        {{--        <script>--}}

        {{--            function change_gia_san_pham(el) {--}}
        {{--                const totalEl = $(el).closest('tr').find('input.tong_tien');--}}
        {{--                const gia_ = $(el).closest('tr').find('input.gia_bans').val();--}}
        {{--                const so_luong = $(el).closest('tr').find('input.so_luong').val();--}}

        {{--                totalEl.val(gia_ * so_luong);--}}

        {{--                change_thanh_toan();--}}
        {{--            }--}}

        {{--            function change_thong_tin_san_pham(el) {--}}
        {{--                const loaiSanPham = $('#loai_san_pham').val();--}}
        {{--                const id = $(el).val();--}}
        {{--                layThongTinNguyenLieu(id, el, loaiSanPham);--}}
        {{--            }--}}

        {{--            function render_chi_tiet_san_pham(item, element, loaiSanPham) {--}}
        {{--                let gia_ = null;--}}
        {{--                switch (loaiSanPham) {--}}
        {{--                    case 'NGUYEN_LIEU_THO':--}}
        {{--                        gia_ = Number(item.chi_phi_mua) / Number(item.khoi_luong || 1);--}}
        {{--                        gia_ = Number(gia_.toFixed(3));--}}
        {{--                        break;--}}
        {{--                    case 'NGUYEN_LIEU_PHAN_LOAI':--}}
        {{--                        gia_ = Number(item.gia_sau_phan_loai ?? 0);--}}
        {{--                        gia_ = Number(gia_.toFixed(3));--}}
        {{--                        break;--}}
        {{--                    case 'NGUYEN_LIEU_TINH':--}}
        {{--                        gia_ = Number(item.gia_tien ?? 0);--}}
        {{--                        gia_ = Number(gia_.toFixed(3));--}}
        {{--                        break;--}}
        {{--                    case 'NGUYEN_LIEU_SAN_XUAT':--}}
        {{--                        gia_ = Number(item.gia_tien ?? 0);--}}
        {{--                        gia_ = Number(gia_.toFixed(3));--}}
        {{--                        break;--}}
        {{--                    case 'NGUYEN_LIEU_THANH_PHAM':--}}
        {{--                        gia_ = Number(item.gia_ban ?? 0);--}}
        {{--                        gia_ = Number(gia_.toFixed(3));--}}
        {{--                        break;--}}
        {{--                }--}}

        {{--                $(element).closest('tr').find('input.gia_bans').val(gia_);--}}

        {{--                change_gia_san_pham(element);--}}
        {{--            }--}}

        {{--            function layThongTinNguyenLieu(id, el, loaiSanPham) {--}}
        {{--                const url = `{{ route('api.chi.tiet.nguyen.lieu') }}?id=${id}&type=${loaiSanPham}`;--}}

        {{--                $.ajax({--}}
        {{--                    url: url,--}}
        {{--                    type: 'GET',--}}
        {{--                    async: false,--}}
        {{--                    success: function (data, textStatus) {--}}
        {{--                        render_chi_tiet_san_pham(data.data, el, loaiSanPham);--}}
        {{--                    },--}}
        {{--                    error: function (request, status, error) {--}}
        {{--                        let data = JSON.parse(request.responseText);--}}
        {{--                        alert(data.message);--}}
        {{--                    }--}}
        {{--                });--}}
        {{--            }--}}

        {{--            $(document).ready(function () {--}}
        {{--                add_items();--}}
        {{--            })--}}

        {{--            function add_items(el) {--}}
        {{--                // const tbody = $('#tbodySanPham');--}}
        {{--                // const tr = $('#listSanPham').clone();--}}
        {{--                // tbody.append(tr);--}}

        {{--                render_select_custom();--}}

        {{--                change_thanh_toan();--}}
        {{--            }--}}

        {{--            function remove_items(el) {--}}
        {{--                $(el).parent().closest('tr').remove();--}}
        {{--                change_thanh_toan();--}}
        {{--            }--}}

        {{--            function render_select_custom() {--}}
        {{--                $('#tbodySanPham select').select2({--}}
        {{--                    theme: 'bootstrap-5',--}}
        {{--                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',--}}
        {{--                    placeholder: $(this).data('placeholder') ?? 'Lựa chọn...',--}}
        {{--                    allowClear: Boolean($(this).data('allow-clear')) || true,--}}
        {{--                    minimumResultsForSearch: $(this).data('minimum-results-for-search') ? $(this).data('minimum-results-for-search') : 0,--}}
        {{--                    containerCssClass: $(this).data('container-css-class') ? $(this).data('container-css-class') : '',--}}
        {{--                    dropdownCssClass: $(this).data('dropdown-css-class') ? $(this).data('dropdown-css-class') : '',--}}
        {{--                    dropdownAutoWidth: $(this).data('dropdown-auto-width'),--}}
        {{--                    dropdownParent: $(this).data('dropdown-parent'),--}}
        {{--                    dropdownPosition: $(this).data('dropdown-position'),--}}
        {{--                    initSelection: function (element, callback) {--}}
        {{--                        const id = element.val();--}}
        {{--                        layThongTinNguyenLieu(id, element, $('#loai_san_pham').val());--}}
        {{--                    }--}}
        {{--                });--}}
        {{--            }--}}
        {{--        </script>--}}
    </section>
@endsection
