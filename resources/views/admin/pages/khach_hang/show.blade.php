<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button"
                role="tab" aria-controls="nav-home" aria-selected="true">
            Lịch sử thanh toán
        </button>
        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button"
                role="tab" aria-controls="nav-profile" aria-selected="false">
            Lịch sử mua hàng
        </button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <div class="table-responsive mt-3">
            <table class="table table-hover datatable_wrapper" id="table-payment-history">
                <colgroup>
                    <col width="120px">
                    <col width="x">
                    <col width="25%">
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">Ngày</th>
                    <th scope="col">Tên quỹ</th>
                    <th scope="col">Số tiền</th>
                </tr>
                </thead>
                <tbody>
                @foreach($histories as $payment_)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($payment_->created_at)->format('d-m-Y') }}</td>
                        <td>{{ $payment_->loaiQuy->ten_loai_quy }}</td>
                        <td>{{ parseNumber($payment_->da_thanht_toan, 0) }} VND</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th scope="col" colspan="2">Tổng:</th>
                    <th scope="col" colspan="1">{{ parseNumber($histories->sum('da_thanht_toan'), 0) }} VND</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="table-responsive mt-3">
            <table class="table table-hover datatable_wrapper" id="table-order-history" style="width: 100vw">
                <colgroup>
                    <col width="50px">
                    <col width="100px">
                    <col width="150px">
                    <col width="300px">
                    <col width="200px">
                    <col width="300px">
                    <col width="250px">
                    <col width="250px">
                    <col width="250px">
                    <col width="250px">
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">
                        <input type="checkbox" name="check_all" id="check_all">
                    </th>
                    <th scope="col">Hành động</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col">Khách hàng</th>
                    <th scope="col">Số điện thoại</th>
                    <th scope="col">Địa chỉ</th>
                    <th scope="col">Tổng tiền</th>
                    <th scope="col">Đã thanh toán</th>
                    <th scope="col">Phương thức thanh toán</th>
                    <th scope="col">Công nợ</th>
                </tr>
                </thead>
                <tbody>
                @foreach($histories as $data)
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
                                    <button type="button" class="btn btn-danger btn-sm btnDelete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</td>
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
                        <td>{{ parseNumber($data->da_thanht_toan, 0) }} VND</td>
                        <td>{{ $data->loaiQuy->ten_loai_quy }}</td>
                        <td>{{ parseNumber($data->cong_no, 0) }} VND</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    init_datatable(5);
</script>
