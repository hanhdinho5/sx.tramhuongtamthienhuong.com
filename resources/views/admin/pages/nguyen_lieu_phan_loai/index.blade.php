@extends('admin.layouts.master')
@section('title')
    Kho nguyên liệu phân loại
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Kho nguyên liệu phân loại</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Kho nguyên liệu phân loại</li>
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
                            <div class="col-md-4 form-group">
                                <input type="text" class="form-control" id="keyword" name="keyword"
                                       onkeypress="handleEnter(event)"
                                       placeholder="Tên nguyên liệu thô, mã đơn hàng" value="{{ $keyword }}">
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
                window.location.href = "{{ route('admin.nguyen.lieu.phan.loai.index') }}?start_date=" + start_date + "&end_date=" + end_date + "&keyword=" + keyword;
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
                        $__ru = RoleUser::where('user_id', auth()->id())->first();
                        $__rl = $__ru ? Role::find($__ru->role_id) : null;
                        $__isSX = $__rl && $__rl->name === RoleName::NHAN_VIEN_SX;
                    @endphp
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới Kho nguyên liệu phân loại</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.nguyen.lieu.phan.loai.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nguyen_lieu_tho_id">Mã đơn hàng</label>
                                <select name="nguyen_lieu_tho_id" id="nguyen_lieu_tho_id"
                                        class="form-control selectCustom">
                                    @foreach($nlthos as $nltho)
                                        <option
                                            value="{{ $nltho->id }}" {{ old('nguyen_lieu_tho_id') == $nltho->id ? 'selected' : '' }}>
                                            {{ $nltho->code }} : {{ $nltho->ten_nguyen_lieu }} :
                                            {{ parseNumber($nltho->khoi_luong - $nltho->khoi_luong_da_phan_loai) }} kg
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ old('ngay', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="nu_cao_cap">NL nụ cao cấp (NCC)</label>
                                <input type="text" class="onlyNumber form-control" id="nu_cao_cap" name="nu_cao_cap"
                                       value="{{ old('nu_cao_cap') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nu_vip">NL nụ VIP (NVIP)</label>
                                <input type="text" class="onlyNumber form-control" id="nu_vip" name="nu_vip"
                                       value="{{ old('nu_vip') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nhang">NL nhang (NLN)</label>
                                <input type="text" class="onlyNumber form-control" id="nhang" name="nhang"
                                       value="{{ old('nhang') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="vong">NL vòng (NLV)</label>
                                <input type="text" class="onlyNumber form-control" id="vong" name="vong"
                                       value="{{ old('vong') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="keo">Keo</label>
                                <input type="text" class="onlyNumber form-control" id="keo" name="keo"
                                       value="{{ old('keo') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nau_dau">Nấu dầu</label>
                                <input type="text" class="onlyNumber form-control" id="nau_dau" name="nau_dau"
                                       value="{{ old('nau_dau') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="tam_dai">NL Tăm dài</label>
                                <input type="text" class="onlyNumber form-control" id="tam_dai" name="tam_dai"
                                       value="{{ old('tam_dai') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tam_ngan">NL Tăm ngắn</label>
                                <input type="text" class="onlyNumber form-control" id="tam_ngan" name="tam_ngan"
                                       value="{{ old('tam_ngan') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nuoc_cat">Nước cất</label>
                                <input type="text" class="onlyNumber form-control" id="nuoc_cat" name="nuoc_cat"
                                       value="{{ old('nuoc_cat') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tam_nhanh_sao">Tăm nhanh sào</label>
                                <input type="text" class="form-control onlyNumber" id="tam_nhanh_sao"
                                       name="tam_nhanh_sao" value="{{ old('tam_nhanh_sao')  }}">
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
            @if(!$__isSX)
            <div class="d-flex mb-4 mt-3 justify-content-end">
                <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('phan_loai')">Xoá tất
                    cả
                </button>
            </div>
            @endif
            <div class="card recent-sales overflow-auto">
                @php
                    $total_nu_cao_cap = $total_nu_vip = $total_nhang = $total_vong = $total_tam_tre = $total_keo = $total_nau_dau = $total_ghi_chu = 0;
                @endphp
                <div class="card-body">
                   <div class="table-responsive pt-3">
                        <table class="table datatable_wrapper table-hover small" style="min-width: 2500px">
                            <colgroup>
                                <col width="50px">
                                <col width="100px">
                                <col width="150px">
                                <col width="150px">
                                <col width="120px">
                                <col width="120px">
                                <col width="120px">
                                <col width="180px">
                                <col width="180px">
                                <col width="180px">
                                <col width="180px">
                                <col width="180px">
                                <col width="180px">
                                <col width="180px">
                                <col width="250px">
                                <col width="200px">
                                <col width="200px">
                                <col width="200px">
                                <col width="200px">
                                <col width="200px">
                                <col width="250px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" name="check_all" id="check_all">
                                </th>
                                <th scope="col">Hành động</th>
                                <th scope="col">MÃ ĐH</th>
                                <th scope="col">Ngày phân loại</th>
                                <th scope="col">NL nụ cao cấp (NCC)</th>
                                <th scope="col">NL nụ VIP (NVIP)</th>
                                <th scope="col">NL nhang (NLN)</th>
                                <th scope="col">NL vòng (NLV)</th>
                                <th scope="col">NL Tăm dài</th>
                                <th scope="col">NL Tăm ngắn</th>
                                <th scope="col">Nước cất</th>
                                <th scope="col">Keo</th>
                                <th scope="col">Nấu dầu</th>
                                <th scope="col">Tăm nhanh sào</th>
                                <th scope="col">Chi phí mua</th>
                                <th scope="col">Tổng khối lượng</th>
                                <th scope="col">Khối lượng ban đầu</th>
                                <th scope="col">Khối lượng hao hụt</th>
                                <th scope="col">Khối lượng đã dùng</th>
                                <th scope="col">Khối lượng tồn</th>
                                <th scope="col">Giá trước phân loại</th>
                                <th scope="col">Giá sau phân loại</th>
                                <th scope="col">Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <th scope="row">
                                        @if($__isSX)
                                            <input type="checkbox" disabled>
                                        @elseif($data->khoi_luong_da_phan_loai > 0)
                                            <input type="checkbox" disabled>
                                        @else
                                            <input type="checkbox" name="check_item[]"
                                                   id="check_item{{ $data->id }}"
                                                   value="{{ $data->id }}">
                                        @endif
                                    </th>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.nguyen.lieu.phan.loai.detail', $data->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(!$__isSX)
                                            @if($data->khoi_luong_da_phan_loai > 0)
                                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <form
                                                    action="{{ route('admin.nguyen.lieu.phan.loai.delete', $data->id) }}"
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
                                    <td>{{ $data->nguyenLieuTho->code }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                                    <td>{{ parseNumber($data->nu_cao_cap, 3) }} kg</td>
                                    <td>{{ parseNumber($data->nu_vip, 3) }} kg</td>
                                    <td>{{ parseNumber($data->nhang, 3) }} kg</td>
                                    <td>{{ parseNumber($data->vong, 3) }} kg</td>
                                    <td>{{ parseNumber($data->tam_dai, 3) }} kg</td>
                                    <td>{{ parseNumber($data->tam_ngan, 3) }} kg</td>
                                    <td>{{ parseNumber($data->nuoc_cat, 3) }} kg</td>
                                    <td>{{ parseNumber($data->keo, 3) }} kg</td>
                                    <td>{{ parseNumber($data->nau_dau, 3) }} kg</td>
                                    <td>{{ parseNumber($data->tam_nhanh_sao, 3) }} kg</td>
                                    <td>{{ parseNumber($data->chi_phi_mua, 3) }} VND</td>
                                    <td>{{ parseNumber($data->tong_khoi_luong, 3) }} kg</td>
                                    <td>{{ parseNumber($data->khoi_luong_ban_dau, 3) }} kg</td>
                                    <td>{{ parseNumber($data->khoi_luong_hao_hut, 3) }} kg</td>
                                    <td>{{ parseNumber($data->khoi_luong_da_phan_loai, 3) }} kg</td>
                                    <td>{{ parseNumber($data->tong_khoi_luong - $data->khoi_luong_da_phan_loai, 3) }}kg
                                    </td>
                                    <td>{{ parseNumber($data->gia_truoc_phan_loai, 0) }} VND</td>
                                    <td>{{ parseNumber($data->gia_sau_phan_loai, 0) }} VND</td>
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
                                <th scope="col">{{ parseNumber($datas->sum('nu_cao_cap'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('nu_vip'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('nhang'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('vong'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('tam_dai'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('tam_ngan'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('nuoc_cat'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('keo'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('nau_dau'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('tam_nhanh_sao'), 3) }} kg</th>
                                <th scope="col"></th>
                                <th scope="col">{{ parseNumber($datas->sum('tong_khoi_luong'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('khoi_luong_ban_dau'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('khoi_luong_hao_hut'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('khoi_luong_da_phan_loai'), 3) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('tong_khoi_luong') - $datas->sum('khoi_luong_da_phan_loai'), 3) }}
                                    kg
                                </th>
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
