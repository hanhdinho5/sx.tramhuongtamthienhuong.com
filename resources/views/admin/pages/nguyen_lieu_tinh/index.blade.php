@php use App\Enums\TrangThaiNguyenLieuTinh;use Carbon\Carbon; @endphp
@php @endphp
@extends('admin.layouts.master')
@section('title')
    Kho nguyên liệu tinh
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Kho nguyên liệu tinh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Kho nguyên liệu tinh</li>
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
                        <div class="d-flex justify-content-start align-items-center gap-4 w-100">
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
                                    <input type="text" class="form-control" id="code_search" name="code"
                                           onkeypress="handleEnter(event)"
                                           placeholder="Tìm kiếm theo mã lô hàng" value="{{ $code_search }}">
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                <select name="nguyen_lieu_phan_loai" id="nguyen_lieu_phan_loai"
                                        class="form-select selectCustom">
                                    <option value="">-- Chọn --</option>
                                    @foreach($nlphanloais as $nlphanloai)
                                        <option {{ $nguyen_lieu_phan_loai == $nlphanloai->id ? 'selected' : '' }}
                                                value="{{ $nlphanloai->id }}">
                                            {{ $nlphanloai->nguyenLieuTho->code }}
                                            - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
                                        </option>
                                    @endforeach
                                </select>
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
                const code_search = $('#code_search').val();
                const nguyen_lieu_phan_loai = $('#nguyen_lieu_phan_loai').val();

                window.location.href = "{{ route('admin.nguyen.lieu.tinh.index') }}?start_date=" + start_date + "&end_date=" + end_date + "&code=" + code_search + "&nguyen_lieu_phan_loai=" + nguyen_lieu_phan_loai;
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
                    @php
                        use App\Enums\RoleName; use App\Models\RoleUser; use App\Models\Role;
                        $__ru2 = RoleUser::where('user_id', auth()->id())->first();
                        $__rl2 = $__ru2 ? Role::find($__ru2->role_id) : null;
                        $__isSX = $__rl2 && $__rl2->name === RoleName::NHAN_VIEN_SX;
                    @endphp
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới Kho nguyên liệu tinh</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.nguyen.lieu.tinh.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="ma_phieu">Mã phiếu</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                                       name="ma_phieu" value="{{ old('ma_phieu', $ma_phieu) }}" readonly required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                                <input type="text" class="form-control" id="ten_nguyen_lieu"
                                       name="ten_nguyen_lieu" value="{{ old('ten_nguyen_lieu') }}" required>
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
                                       value="{{ old('ngay', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option value="{{ TrangThaiNguyenLieuTinh::ACTIVE() }}"
                                        {{ old('trang_thai') ==TrangThaiNguyenLieuTinh::ACTIVE() ? 'selected' : '' }}>
                                        {{TrangThaiNguyenLieuTinh::ACTIVE() }}
                                    </option>
                                    <option value="{{TrangThaiNguyenLieuTinh::INACTIVE() }}"
                                        {{ old('trang_thai') ==TrangThaiNguyenLieuTinh::INACTIVE() ? 'selected' : '' }}>
                                        {{ TrangThaiNguyenLieuTinh::INACTIVE() }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-2">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Danh sách nguyên liệu</h4>

                                <button type="button" class="btn btn-success btn-sm" onclick="plusItem()">
                                    <i class="bi bi-plus"></i>
                                </button>
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
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody id="tbodyListNL" class="text-center">
                                <tr>
                                    <td>
                                        <select class="form-control nguyen_lieu_phan_loai_ids selectCustom"
                                                name="nguyen_lieu_phan_loai_ids[]"
                                                onchange="selectNLPhanLoai(this)">
                                            @foreach($nlphanloais as $nlphanloai)
                                                <option value="{{ $nlphanloai->id }}">
                                                    {{ $nlphanloai->nguyenLieuTho->code }}
                                                    - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" name="ten_nguyen_lieus[]">

                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="khoi_luongs[]" class="form-control onlyNumber"
                                               required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm disabled">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <script>
            const baseHtml = `<tr>
    <td>
        <select class="form-select selectCustom" name="nguyen_lieu_phan_loai_ids[]" onchange="selectNLPhanLoai(this)">
            @foreach($nlphanloais as $nlphanloai)
            <option value="{{ $nlphanloai->id }}">
                    {{ $nlphanloai->nguyenLieuTho->code }} - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
            </option>
@endforeach
            </select>
        </td>
        <td>
            <select class="form-select" name="ten_nguyen_lieus[]"></select>
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
            selectNLPhanLoai(nlPhanLoai);

            function renderChiTietSanPham(data, elm) {
                const html = `
                <option value="Nguyên liệu nụ cao cấp (NCC)">Nguyên liệu nụ cao cấp (NCC) - ${data.nu_cao_cap} kg</option>
                                            <option value="Nguyên liệu nụ VIP (NVIP)">Nguyên liệu nụ VIP (NVIP) - ${data.nu_vip} kg</option>
                                            <option value="Nguyên liệu nhang (NLN)">Nguyên liệu nhang (NLN) - ${data.nhang} kg</option>
                                            <option value="Nguyên liệu vòng (NLV)">Nguyên liệu vòng (NLV) - ${data.vong} kg</option>
                                            <option value="Tăm dài">Tăm dài - ${data.tam_dai} kg</option>
                                            <option value="Tăm ngắn">Tăm ngắn - ${data.tam_ngan} kg</option>
                                            <option value="Nước cất">Nước cất - ${data.nuoc_cat} kg</option>
                                            <option value="Keo">Keo - ${data.keo} kg</option>
                                            <option value="Nấu dầu">Nấu dầu - ${data.nau_dau} kg</option>
                                            <option value="Tăm nhanh sào">Tăm nhanh sào - ${data.tam_nhanh_sao} kg</option>
                `;

                $(elm).parent().next().find('select').html(html);
            }
        </script>

        <div class="col-12">
            @if(!$__isSX)
            <div class="d-flex mb-4 mt-3 justify-content-end">
                <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('tinh')">Xoá tất cả
                </button>
            </div>
            @endif
            <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <div class="table-responsive pt-3">
                        <table class="table datatable_wrapper table-hover w-100">
                            <colgroup>
                                <col width="50px">
                                <col width="100px">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="x">
                                <col width="10%">
                                <col width="10%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" name="check_all" id="check_all">
                                </th>
                                <th scope="col">Hành động</th>
                                <th scope="col">Ngày</th>
                                <th scope="col">Mã phiếu</th>
                                <th scope="col">Tên nguyên liệu</th>
                                <th scope="col">Mã lô hàng</th>
                                <th scope="col">Nguồn gốc</th>
                                <th scope="col">Tổng khối lượng</th>
                                <th scope="col">Khối lượng đã dùng</th>
                                <th scope="col">Khối lượng tồn</th>
                                <th scope="col">Đơn giá</th>
                                <th scope="col">Tổng tiền</th>
                                <th scope="col">Giá trị tồn kho</th>
                                <th scope="col">Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <th scope="row">
                                        @if($__isSX)
                                            <input type="checkbox" disabled>
                                        @elseif($data->so_luong_da_dung > 0)
                                            <input type="checkbox" disabled>
                                        @else
                                            <input type="checkbox" name="check_item[]"
                                                   id="check_item{{ $data->id }}"
                                                   value="{{ $data->id }}">
                                        @endif
                                    </th>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.nguyen.lieu.tinh.detail', $data->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(!$__isSX)
                                            @if($data->so_luong_da_dung > 0)
                                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <form action="{{ route('admin.nguyen.lieu.tinh.delete', $data->id) }}"
                                                      method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                                    <td>{{ $data->ma_phieu }}</td>
                                    <td>{{ $data->ten_nguyen_lieu }}</td>
                                    <td>{{ $data->code }}</td>
                                    <td> {!! $data->get_list_child($data) !!}</td>
                                    <td>{{ parseNumber($data->tong_khoi_luong, 3) }} kg</td>
                                    <td>{{ parseNumber($data->so_luong_da_dung, 3) }} kg</td>
                                    <td>{{ parseNumber($data->tong_khoi_luong - $data->so_luong_da_dung, 3) }} kg</td>
                                    <td>{{ parseNumber($data->gia_tien, 0) }} VND</td>
                                    <td>{{ parseNumber($data->tong_tien, 0) }} VND</td>
                                    <td>{{ parseNumber(($data->tong_khoi_luong - $data->so_luong_da_dung) * $data->gia_tien, 0) }}
                                        VND
                                    </td>
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
                                <th scope="col">{{ parseNumber($datas->sum('tong_khoi_luong'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('so_luong_da_dung'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('tong_khoi_luong') - $datas->sum('so_luong_da_dung'), 3) }}
                                    kg
                                </th>
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
