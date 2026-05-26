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
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title"><label for="date_">Tìm kiếm </label></h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center gap-4 w-100">
                                        <div class="col-md-4 form-group">
                                            <div class="d-flex justify-content-start align-items-center gap-2">
                                                <label for="start_date">Từ ngày: </label>
                                                <input type="date" class="form-control" id="start_date"
                                                       value="{{ $start_date }}" name="start_date">
                                            </div>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <div class="d-flex justify-content-start align-items-center gap-2">
                                                <label for="end_date">Đến ngày: </label>
                                                <input type="date" class="form-control" id="end_date"
                                                       value="{{ $end_date }}" name="end_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex justify-content-end align-items-center gap-2">
                                        <button class="btn btn-outline-primary btn_reload" type="button">Làm mới
                                        </button>
                                        <button class="btn btn-primary" onclick="searchTable()" type="button">Tìm kiếm
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex mb-4 mt-3 justify-content-end">
                            <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('ban_hang')">
                                Xoá tất cả
                            </button>
                        </div>
                        <div class="card recent-sales overflow-auto">

                            <div class="card-body pt-5">
                                <table class="table table-hover datatable_wrapper small min-vw-100 pt">
                                    <colgroup>
                                        <col width="50px">
                                        <col width="100px">
                                        <col width="100px">
                                        <col width="100px">
                                        <col width="150px">
                                        <col width="300px">
                                        <col width="200px">
                                        <col width="300px">
                                        <col width="250px">
                                        <col width="150px">
                                        <col width="250px">
                                        <col width="150px">
                                        <col width="250px">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th scope="col">
                                            <input type="checkbox" name="check_all" id="check_all">
                                        </th>
                                        <th scope="col">Hành động</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Mã đơn hàng</th>
                                        <th scope="col">Trạng thái đơn hàng</th>
                                        <th scope="col">Khách hàng</th>
                                        <th scope="col">Số điện thoại</th>
                                        <th scope="col">Địa chỉ</th>
                                        <th scope="col">Tổng tiền</th>
                                        <th scope="col">Giảm giá</th>
                                        <th scope="col">Đã thanh toán</th>
                                        <th scope="col">Phương thức thanh toán</th>
                                        <th scope="col">Công nợ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($datas as $data)
                                        <tr>
                                            <th scope="row"><input type="checkbox" name="check_item[]"
                                                                   id="check_item{{ $data->id }}"
                                                                   value="{{ $data->id }}"></th>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="{{ route('admin.ban.hang.detail', $data->id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <form action="{{ route('admin.ban.hang.delete', $data->id) }}"
                                                          method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm btnDelete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-nowrap">{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</span>
                                            </td>
                                            <td>{{ $data->ma_don_hang }}</td>
                                            <td>
                                                @if($data->trang_thai == \App\Enums\TrangThaiBanHang::ACTIVE())
                                                    {{ $data->trang_thai }}
                                                @else
                                                    <span class="text-warning">{{ $data->trang_thai }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->ban_le)
                                                    {{ $data->khach_le }}
                                                @else
                                                    {{ $data->khachHang->ten }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->ban_le)
                                                    {{ $data->so_dien_thoai }}
                                                @else
                                                    {{ $data->khachHang->so_dien_thoai }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->ban_le)
                                                    {{ $data->dia_chi }}
                                                @else
                                                    {{ $data->khachHang->dia_chi }}
                                                @endif
                                            </td>
                                            <td>{{ parseNumber($data->tong_tien, 0) }} VND</td>
                                            <td>{{ parseNumber($data->giam_gia, 0) }} VND</td>
                                            <td>{{ parseNumber($data->da_thanht_toan, 0) }} VND</td>
                                            <td>{{ $data->loaiQuy->ten_loai_quy }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <span>{{ parseNumber($data->cong_no, 0) }} VND</span>
                                                    @if($data->cong_no > 0)
                                                        <button class="btn btn-danger btn-sm" type="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalThanhToan{{ $data->id }}">
                                                            Thanh toán
                                                        </button>
                                                    @endif
                                                </div>

                                                @if($data->cong_no > 0)
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modalThanhToan{{ $data->id }}"
                                                         tabindex="-1"
                                                         aria-labelledby="modalThanhToanLabel{{ $data->id }}"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form method="post"
                                                                      action="{{ route('admin.thanh.toan.store') }}">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5"
                                                                            id="modalThanhToanLabel{{ $data->id }}">
                                                                            Thanh toán công nợ đơn
                                                                            hàng: {{ $data->ma_don_hang }}</h1>
                                                                        <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="so_quy_id">Loại
                                                                                    quỹ</label>
                                                                                <select
                                                                                    class="form-control selectCustom"
                                                                                    name="so_quy_id" id="so_quy_id">
                                                                                    @foreach($loai_quies as $loai_quy)
                                                                                        <option
                                                                                            value="{{ $loai_quy->id }}">
                                                                                            {{ $loai_quy->ten_loai_quy }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="so_tien_thanh_toan">Số tiền
                                                                                    thanh toán</label>
                                                                                <input type="text"
                                                                                       class="form-control onlyNumber"
                                                                                       id="so_tien_thanh_toan"
                                                                                       name="so_tien_thanh_toan">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="ghi_chu">Ghi chú</label>
                                                                            <input type="text" class="form-control"
                                                                                   id="ghi_chu" name="ghi_chu">
                                                                        </div>

                                                                        <input type="hidden" name="ban_hang_id"
                                                                               value="{{ $data->id }}">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Huỷ
                                                                        </button>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            Xác nhận thanh toán
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <script>
        function searchTable() {
            const start_date = $('#start_date').val();
            const end_date = $('#end_date').val();
            window.location.href = "{{ route('admin.ban.hang.index') }}?start_date=" + start_date + "&end_date=" + end_date;
        }

    </script>
@endsection
