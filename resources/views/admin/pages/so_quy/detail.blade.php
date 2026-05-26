@extends('admin.layouts.master')
@section('title')
    Xem Sổ quỹ
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Xem Sổ quỹ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Xem Sổ quỹ</li>
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
                @if($soquy->allow_change)
                    <form method="post" action="{{ route('admin.so.quy.update', $soquy->id) }}">
                        @method('PUT')
                        @csrf
                        @endif
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="ngay">Ngày</label>
                                    <input type="date" class="form-control" id="ngay" name="ngay"
                                           value="{{ \Illuminate\Support\Carbon::parse($soquy->ngay)->format('Y-m-d') }}"
                                           required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="ma_phieu">Mã phiếu</label>
                                    <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                                           name="ma_phieu" value="{{ $soquy->ma_phieu }}" readonly required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label for="loai">Loại phiếu</label>
                                    <select class="form-control" name="loai" id="loai">
                                        <option {{ $soquy->loai == 0 ? 'selected' : '' }} value="0">Phiếu Chi</option>
                                        <option {{ $soquy->loai == 1 ? 'selected' : '' }} value="1">Phiếu Thu</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="loai_quy_id">Tên quỹ</label>
                                    <select class="form-control" name="loai_quy_id" id="loai_quy_id">
                                        @foreach($loai_quies as $loai_quy)
                                            <option {{ $loai_quy->id == $soquy->loai_quy_id ? 'selected' : '' }}
                                                    value="{{ $loai_quy->id }}">
                                                {{ $loai_quy->ten_loai_quy }} - Tổng
                                                tiền: {{ parseNumber($loai_quy->tong_tien_quy, 0) }} VND
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="nhom_quy_id">Nhóm quỹ</label>
                                    <select class="form-control" name="nhom_quy_id" id="nhom_quy_id" required>
                                        @foreach($nhom_quies as $nhom_quy)
                                            <option
                                                value="{{ $nhom_quy->id }}" {{ old('loai_quy_id', $soquy->nhom_quy_id) == $nhom_quy->id ? 'selected' : '' }}>
                                                {{ $nhom_quy->ten_nhom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="so_tien">Số tiền</label>
                                    <input type="text" class="form-control onlyNumber" id="so_tien" name="so_tien"
                                           value="{{ $soquy->so_tien }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="loai_noi_nhan">Loại nơi nhận</label>
                                    <select class="form-control" name="loai_noi_nhan" id="loai_noi_nhan"
                                            onchange="change_loai_nguon_hang();">
                                        <option value="">Lựa chọn</option>
                                        <option {{ $soquy->loai_noi_nhan == 'ncc' ? 'selected' : '' }} value="ncc">Nhà
                                            cung cấp
                                        </option>
                                        <option {{ $soquy->loai_noi_nhan == 'kh' ? 'selected' : '' }} value="kh">Khách
                                            hàng
                                        </option>
                                        <option {{ $soquy->loai_noi_nhan == 'nv' ? 'selected' : '' }} value="nv">Nhân
                                            viên
                                        </option>
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
                                <textarea name="noi_dung" id="noi_dung" class="form-control"
                                          rows="5">{{ $soquy->noi_dung }}</textarea>
                            </div>
                            @if($soquy->allow_change)
                                <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                            @endif
                        </div>
                        @if($soquy->allow_change)
                    </form>
                @endif
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function () {
            change_loai_nguon_hang()
        });

        async function change_loai_nguon_hang() {
            let loai_nguon_hang = $('#loai_noi_nhan').val();

            if (loai_nguon_hang) {
                await nguon_hang(loai_nguon_hang);
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
                    render_nguon_hang(data.data, loai_nguon_hang, `{{ $soquy->noi_nhan }}`);
                },
                error: function (request, status, error) {
                    let data = JSON.parse(request.responseText);
                    alert(data.message);
                }
            });
        }

        function render_nguon_hang(data, loai_nguon_hang, selected = null) {
            let html = '<option value="">Lựa chọn...</option>';
            for (let i = 0; i < data.length; i++) {
                if (loai_nguon_hang == 'ncc') {
                    html += `<option ${selected == data[i].id ? 'selected' : ''} value="${data[i].id}">${data[i].ten}</option>`;
                } else if (loai_nguon_hang == 'kh') {
                    html += `<option ${selected == data[i].id ? 'selected' : ''} value="${data[i].id}">${data[i].ten}</option>`;
                } else {
                    html += `<option ${selected == data[i].id ? 'selected' : ''} value="${data[i].id}">${data[i].full_name}</option>`;
                }
            }

            $('#noi_nhan').empty().append(html);
        }
    </script>
@endsection
