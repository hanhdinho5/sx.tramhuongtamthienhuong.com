@php @endphp
<div class="col-12">
    <div class="card recent-sales overflow-auto">

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">Thêm mới phiếu thu/chi</h5>
                <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
            </div>
            <form method="post" action="{{ route('admin.so.quy.store') }}" class="d-none">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="ngay">Ngày</label>
                        <input type="date" class="form-control" id="ngay" name="ngay"
                               value="{{ old('ngay', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="ma_phieu">Mã phiếu</label>
                        <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                               name="ma_phieu" readonly
                               value="{{ old('ma_phieu', $ma_phieu) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="loai">Loại phiếu</label>
                        <select class="form-control" name="loai" id="loai" required
                                onchange="show_nha_cung_cap()">
                            <option value="0" {{ old('loai') === '0' ? 'selected' : '' }}>Phiếu Chi</option>
                            <option value="1" {{ old('loai') === '1' ? 'selected' : '' }}>Phiếu Thu</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="loai_quy_id">Tên quỹ</label>
                        <select class="form-control" name="loai_quy_id" id="loai_quy_id" required>
                            @foreach($loai_quies as $loai_quy)
                                <option
                                    value="{{ $loai_quy->id }}" {{ old('loai_quy_id') == $loai_quy->id ? 'selected' : '' }}>
                                    {{ $loai_quy->ten_loai_quy }} - Tổng
                                    tiền: {{ parseNumber($loai_quy->tong_tien_quy, 0) }} VND
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="nhom_quy_id">Nhóm quỹ</label>
                        <select class="form-control" name="nhom_quy_id" id="nhom_quy_id">
                            @foreach($nhom_quies as $nhom_quy)
                                <option
                                    value="{{ $nhom_quy->id }}" {{ old('loai_quy_id') == $nhom_quy->id ? 'selected' : '' }}>
                                    {{ $nhom_quy->ten_nhom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="so_tien">Số tiền</label>
                        <input type="text" class="form-control onlyNumber" id="so_tien" name="so_tien"
                               value="{{ old('so_tien') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="loai_noi_nhan">Loại nơi nhận</label>
                        <select class="form-control" name="loai_noi_nhan" id="loai_noi_nhan"
                                onchange="change_loai_nguon_hang();">
                            <option value="">Lựa chọn</option>
                            <option value="ncc">Nhà cung cấp</option>
                            <option value="kh">Khách hàng</option>
                            <option value="nv">Nhân viên</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="noi_nhan">Nơi nhận</label>
                        <select class="form-control selectCustom" name="noi_nhan" id="noi_nhan">

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="noi_dung">Nội dung</label>
                    <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5"
                              required>{{ old('noi_dung') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
            </form>

        </div>

    </div>
</div>

<div class="col-12">
    <div class="card recent-sales overflow-auto">

        <div class="card-body">
            <div class="mt-4 mb-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="item_ " style="width: 20%">
                        <h5>Quỹ đầu kỳ</h5>
                        <div class="p-3 border rounded-3">
                            <span class="text-danger">{{ parseNumber($ton_dau, 0) }} VND</span>
                        </div>
                    </div>

                    <div class="item_ " style="width: 20%">
                        <h5>Tổng thu</h5>
                        <div class="p-3 border rounded-3">
                            <span class="text-danger">{{ parseNumber($thu, 0) }} VND</span>
                        </div>
                    </div>

                    <div class="item_ " style="width: 20%">
                        <h5>Tổng chi</h5>
                        <div class="p-3 border rounded-3">
                            <span class="text-danger">{{ parseNumber($chi, 0) }} VND</span>
                        </div>
                    </div>

                    <div class="item_ " style="width: 20%">
                        <h5>Quỹ cuối kỳ</h5>
                        <div class="p-3 border rounded-3">
                            <span class="text-danger">{{ parseNumber($ton_cuoi, 0) }} VND</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive pt-3">
                <table class="table datatable_wrapper table-sm table-hover">
                    <colgroup>
                        <col width="120px">
                        <col width="120px">
                        <col width="120px">
                        <col width="120px">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="x">
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">Hành động</th>
                        <th scope="col">Ngày</th>
                        <th scope="col">Mã phiếu</th>
                        <th scope="col">Thu/chi</th>
                        <th scope="col">Nhóm thu/chi</th>
                        <th scope="col">Số tiền</th>
                        <th scope="col">Tên quỹ</th>
                        <th scope="col">Nơi nhận</th>
                        <th scope="col">Đối tượng</th>
                        <th scope="col">Nội dung</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($datas as $data)
                        <tr>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    @if($data->allow_change)
                                        <a href="{{ route('admin.so.quy.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.so.quy.delete', $data->id) }}"
                                              method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                            <td>{{ $data->ma_phieu }}</td>
                            <td>@if($data->loai == 0)
                                    Phiếu Chi
                                @else
                                    Phiếu Thu
                                @endif
                            </td>
                            <td>{{ $data->nhomQuy?->ten_nhom }}</td>
                            <td>{{ parseNumber($data->so_tien, 0) }} VND</td>
                            <td>{{ $data->loaiQuy->ten_loai_quy }}</td>
                            <td>
                                @php
                                    switch ($data->loai_noi_nhan){
                                        case 'ncc':
                                            $loai_noi_nhan = 'Nhà cung cấp';
                                            break;
                                        case 'kh':
                                            $loai_noi_nhan = 'Khách hàng';
                                            break;
                                        default:
                                            $loai_noi_nhan = 'Nhân viên';
                                            break;
                                    }
                                @endphp

                                {{ $loai_noi_nhan }}
                            </td>
                            <td>
                                @php
                                    switch ($data->loai_noi_nhan){
                                        case 'ncc':
                                            $noi_nhan = \App\Models\NhaCungCaps::where('id', $data->noi_nhan)->first()?->ten;
                                            break;
                                        case 'kh':
                                            $noi_nhan = \App\Models\KhachHang::where('id', $data->noi_nhan)->first()?->ten;
                                            break;
                                        default:
                                            $noi_nhan = \App\Models\User::where('id', $data->noi_nhan)->first()?->full_name;
                                            break;
                                    }
                                @endphp
                                {{ $noi_nhan }}
                            </td>
                            <td>{{ $data->noi_dung }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th scope="col" colspan="5">Tổng:</th>
                        <th scope="col" colspan="5">{{ parseNumber($datas->sum('so_tien'), 0) }} VND</th>
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>

    </div>
</div>
<script>
    async function change_loai_nguon_hang() {
        let loai_noi_nhan = $('#loai_noi_nhan').val();

        if (loai_noi_nhan) {
            await nguon_hang(loai_noi_nhan);
        } else {
            $('#noi_nhan').empty().append('<option value="">Lựa chọn...</option>');
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

        $('#noi_nhan').empty().append(html);
    }
</script>
