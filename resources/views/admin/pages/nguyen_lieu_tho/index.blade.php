@extends('admin.layouts.master')
@section('title')
    Kho nguyên liệu Thô
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Kho nguyên liệu Thô</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Kho nguyên liệu Thô</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm</label>
                    </h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="row w-100">
                            <div class="col-md-3 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="start_date">Từ ngày: </label>
                                    <input type="date" class="form-control" id="start_date"
                                           value="{{ $start_date }}" name="start_date">
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="end_date">Đến ngày: </label>
                                    <input type="date" class="form-control" id="end_date"
                                           value="{{ $end_date }}" name="end_date">
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="keyword" name="keyword"
                                           onkeypress="handleEnter(event)"
                                           placeholder="Tên nguyên liệu thô, mã đơn hàng" value="{{ $keyword }}">

                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="form-group">
                                    <select name="nha_cung_cap_id" id="nha_cung_cap_id_search" class="form-control selectCustom">
                                        <option value="">Lựa chọn NCC</option>
                                        @foreach($nccs as $ncc)
                                            <option {{ $ncc->id == $nha_cung_cap_id ? 'selected' : '' }}
                                                    value="{{ $ncc->id }}">{{ $ncc->ten }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end align-items-center gap-2">
                            <button class="btn btn-outline-primary btn_reload" type="button">Làm mới</button>
                            <button class="btn btn-primary" onclick="searchTable()" type="button">Tìm kiếm</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script>
            function searchTable() {
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();
                const keyword = $('#keyword').val();
                const nha_cung_cap_id = $('#nha_cung_cap_id_search').val();
                window.location.href = "{{ route('admin.nguyen.lieu.tho.index') }}?start_date=" + start_date + "&end_date=" + end_date + "&keyword=" + keyword + "&nha_cung_cap_id=" + nha_cung_cap_id;
            }

            function handleEnter(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    searchTable();
                }
            }
        </script>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới Kho nguyên liệu Thô</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.nguyen.lieu.tho.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="code">Mã đơn hàng</label>
                                <input type="text" readonly class="form-control bg-secondary bg-opacity-10" id="code"
                                       name="code" value="{{ old('code', $code) }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ old('ngay', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nha_cung_cap_id">Nhà cung cấp</label>
                                <select name="nha_cung_cap_id" id="nha_cung_cap_id" class="form-control selectCustom">
                                    @foreach($nccs as $ncc)
                                        <option
                                            value="{{ $ncc->id }}" {{ old('nha_cung_cap_id') == $ncc->id ? 'selected' : '' }}>
                                            {{ $ncc->ten }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                                <input type="text" class="form-control" id="ten_nguyen_lieu"
                                       name="ten_nguyen_lieu" value="{{ old('ten_nguyen_lieu') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="loai">Loại</label>
                                <input type="text" class="form-control" id="loai" name="loai" value="{{ old('loai') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nguon_goc">Nguồn gốc</label>
                                <input type="text" class="form-control" id="nguon_goc" name="nguon_goc"
                                       value="{{ old('nguon_goc') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="khoi_luong">KL(kg)</label>
                                <input type="text" min="0" class="form-control onlyNumber" id="khoi_luong"
                                       name="khoi_luong" value="{{ old('khoi_luong') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kich_thuoc">Kích thước</label>
                                <input type="text" class="form-control" id="kich_thuoc" name="kich_thuoc"
                                       value="{{ old('kich_thuoc') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="do_kho">Độ khô</label>
                                <input type="text" class="form-control" id="do_kho" name="do_kho"
                                       value="{{ old('do_kho') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dieu_kien_luu_tru">Điều kiện lưu trữ</label>
                                <input type="text" class="form-control" id="dieu_kien_luu_tru" name="dieu_kien_luu_tru"
                                       value="{{ old('dieu_kien_luu_tru') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="chi_phi_mua">Chi phí mua</label>
                                <input type="text" class="form-control onlyNumber" id="chi_phi_mua"
                                       name="chi_phi_mua" value="{{ old('chi_phi_mua') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phuong_thuc_thanh_toan">Phương thức thanh toán</label>
                                <select class="form-control selectCustom" name="phuong_thuc_thanh_toan"
                                        id="phuong_thuc_thanh_toan">
                                    @foreach($loai_quies as $loai_quy)
                                        <option
                                            value="{{ $loai_quy->id }}" {{ old('phuong_thuc_thanh_toan') == $loai_quy->id ? 'selected' : '' }}>
                                            {{ $loai_quy->ten_loai_quy }}: {{ parseNumber($loai_quy->tong_tien_quy, 0) }}
                                            VND
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="so_tien_thanh_toan">Số tiền thanh toán</label>
                                <input type="text" class="form-control onlyNumber" id="so_tien_thanh_toan"
                                       name="so_tien_thanh_toan" value="{{ old('so_tien_thanh_toan') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="cong_no">Công nợ</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10 onlyNumber"
                                       id="cong_no" name="cong_no" value="{{ old('cong_no') }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="nhan_su_xu_li">Nhân sự xử lý</label>
                                <select id="nhan_su_xu_li" name="nhan_su_xu_li" class="form-control selectCustom">
                                    @foreach($nsus as $nsu)
                                        <option
                                            value="{{ $nsu->full_name }}" {{ old('nhan_su_xu_li') == $nsu->full_name ? 'selected' : '' }}>
                                            {{ $nsu->full_name }}/{{ $nsu->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="thoi_gian_phan_loai">Thời gian phân loại</label>
                                <input type="date" class="form-control" id="thoi_gian_phan_loai"
                                       name="thoi_gian_phan_loai" value="{{ old('thoi_gian_phan_loai') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option value="{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}"
                                        {{ old('trang_thai') == \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() ? 'selected' : '' }}>
                                        {{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}
                                    </option>
                                    <option value="{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}"
                                        {{ old('trang_thai') == \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() ? 'selected' : '' }}>
                                        {{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ghi_chu">Ghi chú</label>
                            <textarea name="ghi_chu" id="ghi_chu" class="form-control"
                                      rows="5">{{ old('ghi_chu') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="d-flex mb-4 mt-3 justify-content-end">
                <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('tho')">Xoá tất cả</button>
            </div>
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="table-responsive pt-3">
                        <table class="table datatable_wrapper table-hover small position-relative"
                               style="min-width: 3000px; max-height: 400px; overflow-y: auto;">
                            <colgroup>
                                <col width="50px">
                                <col width="100px">
                                <col width="150px">
                                <col width="150px">
                                <col width="200px">
                                <col width="250px">
                                <col width="200px">
                                <col width="150px">
                                <col width="150px">
                                <col width="150px">
                                <col width="150px">
                                <col width="150px">
                                <col width="150px">
                                <col width="150px">
                                <col width="150px">
                                <col width="250px">
                                <col width="150px">
                                <col width="250px">
                                <col width="250px">
                                <col width="150px">
                                <col width="150px">
                                <col width="250px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                            <tr class="sticky-top top-0 position-sticky" style="z-index: 100">
                                <th scope="col">
                                    <input type="checkbox" name="check_all" id="check_all">
                                </th>
                                <th scope="col">Hành động</th>
                                <th scope="col">Ngày</th>
                                <th scope="col">Mã đơn hàng</th>
                                <th scope="col">Nhà cung cấp</th>
                                <th scope="col">Tên nguyên liệu</th>
                                <th scope="col">Loại (đã làm sạch và phơi khô)</th>
                                <th scope="col">Nguồn gốc</th>
                                <th scope="col">KL(kg)</th>
                                <th scope="col">KL đã phân loại(kg)</th>
                                <th scope="col">KL đã bán(kg)</th>
                                <th scope="col">KL tồn(kg)</th>
                                <th scope="col">Kích thước</th>
                                <th scope="col">Độ khô</th>
                                <th scope="col">Điều kiện lưu trữ</th>
                                <th scope="col">Chi phí mua</th>
                                <th scope="col">Phương thức thanh toán</th>
                                <th scope="col">Số tiền thanh toán</th>
                                <th scope="col">Công nợ</th>
                                <th scope="col">Giao nhân sự xử lý</th>
                                <th scope="col">Thời gian phân loại</th>
                                <th scope="col">GHI CHÚ</th>
                                <th scope="col">Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <th scope="row">
                                        @if(!$data->allow_change)
                                            <input type="checkbox" disabled>
                                        @else
                                            <input type="checkbox" name="check_item[]"
                                                   id="check_item{{ $data->id }}"
                                                   value="{{ $data->id }}">
                                        @endif
                                    </th>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.nguyen.lieu.tho.detail', $data->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @if($data->allow_change)
                                                <form action="{{ route('admin.nguyen.lieu.tho.delete', $data->id) }}"
                                                      method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                                    <td>{{ $data->code }}</td>
                                    <td>{{ $data->NhaCungCap->ten }}</td>
                                    <td>{{ $data->ten_nguyen_lieu }}</td>
                                    <td>{{ $data->loai }}</td>
                                    <td>
                                        @php
                                            $nguonGoc = $data->nguon_goc;
                                            $nguonGoc = preg_replace_callback('/LH(\d+)/', function($matches) {
                                                $code = $matches[0];
                                                $id = (int)$matches[1];
                                                return '<a href="' . route('admin.nguyen.lieu.tinh.detail', $id) . '">' . $code . '</a>';
                                            }, (string)$nguonGoc);
                                        @endphp
                                        {!! $nguonGoc !!}
                                    </td>
                                    <td>{{ parseNumber($data->khoi_luong, 3) }} kg</td>
                                    <td>{{ parseNumber($data->khoi_luong_da_phan_loai, 3) }} kg</td>
                                    <td>{{ parseNumber($data->khoi_luong_da_ban, 3) }} kg</td>
                                    <td>{{ parseNumber($data->khoi_luong - $data->khoi_luong_da_phan_loai - $data->khoi_luong_da_ban, 3) }}
                                        kg
                                    </td>
                                    <td>{{ $data->kich_thuoc }}</td>
                                    <td>{{ $data->do_kho }}</td>
                                    <td>{{ $data->dieu_kien_luu_tru }}</td>
                                    <td>{{ parseNumber($data->chi_phi_mua, 0) }} VND</td>
                                    <td>{{ $data->loaiQuy->ten_loai_quy }}</td>
                                    <td>{{ parseNumber(floatval($data->so_tien_thanh_toan) ?? 0, 0) }} VND</td>
                                    <td>{{ parseNumber($data->cong_no, 0) }} VND</td>
                                    <td>{{ $data->nhan_su_xu_li }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->thoi_gian_phan_loai)->format('d-m-Y') }}</td>
                                    <td>{{ $data->ghi_chu }}</td>
                                    <td>{{ $data->trang_thai }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="bg-primary bg-opacity-10">
                            <tr>
                                <th scope="col">Tổng:</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col">{{ parseNumber($datas->sum('khoi_luong'), 0) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('khoi_luong_da_phan_loai'), 0) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('khoi_luong_da_ban'), 0) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('khoi_luong') - $datas->sum('khoi_luong_da_phan_loai') - $datas->sum('khoi_luong_da_ban'), 0) }}
                                    kg
                                </th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

            </div>

        </div>
    </section>
@endsection
