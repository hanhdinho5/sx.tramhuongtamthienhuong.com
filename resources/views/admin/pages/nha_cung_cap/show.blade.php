@extends('admin.layouts.master')
@section('title')
    Nhà cung cấp
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Nhà cung cấp</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Nhà cung cấp</li>
            </ol>
        </nav>
    </div>
    <section class="section">

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">

                    <nav class="mt-3">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-home" type="button"
                                    role="tab" aria-controls="nav-home" aria-selected="true">
                                Lịch sử thanh toán
                            </button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button"
                                    role="tab" aria-controls="nav-profile" aria-selected="false">
                                Lịch sử đơn hàng
                            </button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                             aria-labelledby="nav-home-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover datatable_wrapper" id="table-payment-history">
                                    <colgroup>
                                        <col width="120px">
                                        <col width="120px">
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="x">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th scope="col">Hành động</th>
                                        <th scope="col">Ngày</th>
                                        <th scope="col">Tên quỹ</th>
                                        <th scope="col">Số tiền</th>
                                        <th scope="col">Nội dung</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payment_histories as $payment_)
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="{{ route('admin.so.quy.detail', $payment_->id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form action="{{ route('admin.so.quy.delete', $payment_->id) }}"
                                                          method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($payment_->ngay)->format('d-m-Y') }}</td>
                                            <td>{{ $payment_->loaiQuy->ten_loai_quy }}</td>
                                            <td>{{ parseNumber($payment_->so_tien) }} VND</td>
                                            <td>{{ $payment_->noi_dung }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th scope="col" colspan="3">Tổng:</th>
                                        <th scope="col"
                                            colspan="2">{{ parseNumber($payment_histories->sum('so_tien'), 0) }} VND
                                        </th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover datatable_wrapper" id="table-order-history"
                                       style="width: 100vw">
                                    <thead>
                                    <tr class="sticky-top top-0 position-sticky" style="z-index: 100">
                                        <th scope="col">
                                            <input type="checkbox" name="check_all" id="check_all">
                                        </th>
                                        <th scope="col">Hành động</th>
                                        <th scope="col">Ngày</th>
                                        <th scope="col">Mã đơn hàng</th>
                                        <th scope="col">Tên nguyên liệu</th>
                                        <th scope="col">KL(kg)</th>
                                        <th scope="col">KL đã phân loại(kg)</th>
                                        <th scope="col">KL tồn(kg)</th>
                                        <th scope="col">Chi phí mua</th>
                                        <th scope="col">Phương thức thanh toán</th>
                                        <th scope="col">Số tiền thanh toán</th>
                                        <th scope="col">Công nợ</th>
                                        <th scope="col">Giao nhân sự xử lý</th>
                                        <th scope="col">Thời gian phân loại</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order_histories as $data)
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
                                                        <form
                                                            action="{{ route('admin.nguyen.lieu.tho.delete', $data->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                    class="btn btn-danger btn-sm btnDelete">
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
                                            <td>{{ $data->ten_nguyen_lieu }}</td>
                                            <td>{{ parseNumber($data->khoi_luong, 0) }} kg</td>
                                            <td>{{ parseNumber($data->khoi_luong_da_phan_loai, 0) }} kg</td>
                                            <td>{{ parseNumber($data->khoi_luong - $data->khoi_luong_da_phan_loai, 0) }}
                                                kg
                                            </td>
                                            <td>{{ parseNumber($data->chi_phi_mua, 0) }} VND</td>
                                            <td>{{ $data->loaiQuy->ten_loai_quy }}</td>
                                            <td>{{ parseNumber(floatval($data->so_tien_thanh_toan) ?? 0, 0) }} VND</td>
                                            <td>{{ parseNumber($data->cong_no, 0) }} VND</td>
                                            <td>{{ $data->nhan_su_xu_li }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->thoi_gian_phan_loai)->format('d-m-Y') }}</td>
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
                                        <th scope="col">{{ parseNumber($order_histories->sum('khoi_luong'), 0) }}kg
                                        </th>
                                        <th scope="col">{{ parseNumber($order_histories->sum('khoi_luong_da_phan_loai'), 0) }}
                                            kg
                                        </th>
                                        <th scope="col">{{ parseNumber($order_histories->sum('khoi_luong') - $order_histories->sum('khoi_luong_da_phan_loai'), 0) }}
                                            kg
                                        </th>
                                        <th scope="col">{{ parseNumber($order_histories->sum('chi_phi_mua'), 0) }}VND
                                        </th>
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

                    <script>
                        init_datatable(10);
                    </script>

                </div>

            </div>
        </div>
    </section>
@endsection
