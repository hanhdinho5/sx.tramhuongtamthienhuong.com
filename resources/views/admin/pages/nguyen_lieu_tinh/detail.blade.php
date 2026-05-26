@php use App\Enums\TrangThaiNguyenLieuTinh;use Carbon\Carbon; @endphp
@php @endphp
@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho nguyên liệu tinh
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho nguyên liệu tinh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho nguyên liệu tinh</li>
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
                    <h5 class="card-title">Chi tiết Kho nguyên liệu tinh</h5>
                    @php
                        use App\Enums\RoleName; use App\Models\RoleUser; use App\Models\Role;
                        $__ruD2 = RoleUser::where('user_id', auth()->id())->first();
                        $__rlD2 = $__ruD2 ? Role::find($__ruD2->role_id) : null;
                        $__isSX = $__rlD2 && $__rlD2->name === RoleName::NHAN_VIEN_SX;
                    @endphp
                    @if($nguyen_lieu_tinh->so_luong_da_dung <= 0 && !$__isSX)
                        <form method="post" action="{{ route('admin.nguyen.lieu.tinh.update', $nguyen_lieu_tinh) }}">
                            @method('PUT')
                            @csrf
                            @endif
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="ma_phieu">Mã phiếu</label>
                                    <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                                           name="ma_phieu" readonly value="{{ old('ma_phieu', $ma_phieu) }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                                    <input type="text" class="form-control" id="ten_nguyen_lieu"
                                           name="ten_nguyen_lieu"
                                           value="{{ old('ten_nguyen_lieu', $nguyen_lieu_tinh->ten_nguyen_lieu) }}"
                                           required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="code">Mã lô hàng</label>
                                    <input type="text" class="form-control bg-secondary bg-opacity-10" id="code"
                                           name="code" value="{{ old('code', $code) }}" readonly required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="ngay">Ngày</label>
                                    <input type="date" class="form-control" id="ngay" name="ngay"
                                           value="{{ Carbon::parse($nguyen_lieu_tinh->ngay)->format('Y-m-d') }}"
                                           required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="trang_thai">Trạng thái</label>
                                    <select id="trang_thai" name="trang_thai" class="form-control">
                                        <option
                                            {{ $nguyen_lieu_tinh->trang_thai == TrangThaiNguyenLieuTinh::ACTIVE() ? 'selected' : '' }}
                                            value="{{ TrangThaiNguyenLieuTinh::ACTIVE() }}">{{ TrangThaiNguyenLieuTinh::ACTIVE() }}</option>
                                        <option
                                            {{ $nguyen_lieu_tinh->trang_thai == TrangThaiNguyenLieuTinh::INACTIVE() ? 'selected' : '' }}
                                            value="{{ TrangThaiNguyenLieuTinh::INACTIVE() }}">{{ TrangThaiNguyenLieuTinh::INACTIVE() }}</option>
                                    </select>
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
                                    @foreach($dsNLTChiTiet as $nltct)
                                        <tr>
                                            <td>
                                                <select class="form-control selectCustom nguyen_lieu_phan_loai_ids"
                                                        name="nguyen_lieu_phan_loai_ids[]"
                                                        onchange="selectNLPhanLoai(this)">
                                                    @foreach($nlphanloais as $nlphanloai)
                                                        <option
                                                            {{ $nlphanloai->id == $nltct->nguyen_lieu_phan_loai_id ? 'selected' : '' }}
                                                            value="{{ $nlphanloai->id }}">
                                                            {{ $nlphanloai->nguyenLieuTho->code }}
                                                            - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="ten_nguyen_lieus[]">
                                                    <option
                                                        {{ trim($nltct->ten_nguyen_lieu) == 'Nguyên liệu nụ cao cấp (NCC)' ? 'selected' : '' }}
                                                        value="Nguyên liệu nụ cao cấp (NCC)">
                                                        Nguyên liệu nụ cao cấp (NCC)
                                                    </option>
                                                    <option
                                                        {{ trim($nltct->ten_nguyen_lieu) == 'Nguyên liệu nụ VIP (NVIP)' ? 'selected' : '' }}
                                                        value="Nguyên liệu nụ VIP (NVIP)">
                                                        Nguyên liệu nụ VIP (NVIP)
                                                    </option>
                                                    <option
                                                        {{ trim($nltct->ten_nguyen_lieu) == 'Nguyên liệu nhang (NLN)' ? 'selected' : '' }}
                                                        value="Nguyên liệu nhang (NLN)">
                                                        Nguyên liệu nhang (NLN)
                                                    </option>
                                                    <option
                                                        {{  trim($nltct->ten_nguyen_lieu) == 'Nguyên liệu vòng (NLV)' ? 'selected' : '' }}
                                                        value="Nguyên liệu vòng (NLV)">
                                                        Nguyên liệu vòng (NLV)
                                                    </option>
                                                    <option
                                                        {{ trim($nltct->ten_nguyen_lieu) == 'Tăm dài' ? 'selected' : '' }}
                                                        value="Tăm dài">
                                                        Tăm dài
                                                    </option>
                                                    <option
                                                        {{ trim($nltct->ten_nguyen_lieu) == 'Tăm ngắn' ? 'selected' : '' }}
                                                        value="Tăm ngắn">
                                                        Tăm ngắn
                                                    </option>
                                                    <option
                                                        {{ trim($nltct->ten_nguyen_lieu) == 'Nước cất' ? 'selected' : '' }}
                                                        value="Nước cất">
                                                        Nước cất
                                                    </option>
                                                    <option
                                                        {{ trim($nltct->ten_nguyen_lieu) == 'Keo' ? 'selected' : '' }}
                                                        value="Keo">
                                                        Keo
                                                    </option>
                                                    <option
                                                        {{ trim($nltct->ten_nguyen_lieu) == 'Nấu dầu' ? 'selected' : '' }}
                                                        value="Nấu dầu">
                                                        Nấu dầu
                                                    </option>

                                                    <option
                                                        {{ trim($nltct->ten_nguyen_lieu) == 'Tăm nhanh sào' ? 'selected' : '' }}
                                                        value="Tăm nhanh sào">Tăm nhanh sào
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="khoi_luongs[]" class="form-control onlyNumber"
                                                       value="{{ $nltct->khoi_luong }}" required>
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
                            @if($nguyen_lieu_tinh->so_luong_da_dung <= 0 && !$__isSX)
                                <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                        </form>
                    @endif

                </div>

            </div>
        </div>

        <script>
            const baseHtml = `<tr>
                                    <td>
                                        <select class="form-control selectCustom" name="nguyen_lieu_phan_loai_ids[]" onchange="selectNLPhanLoai(this)">
                                            @foreach($nlphanloais as $nlphanloai)
            <option  value="{{ $nlphanloai->id }}">
            {{ $nlphanloai->nguyenLieuTho->code }} - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
            </option>
@endforeach
            </select>
        </td>
        <td>
            <select class="form-control" name="ten_nguyen_lieus[]" >

                                        </select>
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

            $(document).ready(function () {

            })

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

            function selectNLPhanLoai(elm) {
                const id = $(elm).val();
                const url = `{{ route('api.chi.tiet.nguyen.lieu') }}?id=${id}&type={{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI() }}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        renderChiTietSanPham(data.data, elm);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            const nlPhanLoai = $('.nguyen_lieu_phan_loai_ids');
            nlPhanLoai.each(function (index, elm) {
                selectNLPhanLoai(elm);
            })

            function renderChiTietSanPham(data, elm) {
                const currentValue = $(elm).parent().next().find('select').val();
                console.log(currentValue);

                const list = {
                    "Nguyên liệu nụ cao cấp (NCC)": data.nu_cao_cap,
                    "Nguyên liệu nụ VIP (NVIP)": data.nu_vip,
                    "Nguyên liệu nhang (NLN)": data.nhang,
                    "Nguyên liệu vòng (NLV)": data.vong,
                    "Tăm dài": data.tam_dai,
                    "Tăm ngắn": data.tam_ngan,
                    "Nước cất": data.nuoc_cat,
                    "Keo": data.keo,
                    "Nấu dầu": data.nau_dau,
                    "Tăm nhanh sào": data.tam_nhanh_sao
                };

                let html = '';
                for (const [key, value] of Object.entries(list)) {
                    const selected = key === currentValue ? 'selected' : '';
                    html += `<option value="${key}" ${selected}>${key} - ${value} kg</option>`;
                }

                $(elm).parent().next().find('select').html(html);
            }
        </script>
    </section>
@endsection
