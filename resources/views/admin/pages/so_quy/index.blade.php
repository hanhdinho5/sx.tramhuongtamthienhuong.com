@php use App\Enums\TrangThaiNguyenLieuTho;use App\Models\NguyenLieuTho; @endphp
@extends('admin.layouts.master')
@section('title')
    Sổ quỹ
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Sổ quỹ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Sổ quỹ</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm</label></h5>
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
                            <div class="col-md-4 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="loai_quy_search">Loại quỹ: </label>
                                    <select name="loai_quy_search" id="loai_quy_search" class="form-control">
                                        <option value="">Tất cả</option>
                                        @foreach($loai_quies as $loai_quy)
                                            <option {{ $loai_quy->id == $loai_quy_search ? 'selected' : '' }}
                                                    value="{{ $loai_quy->id }}">{{ $loai_quy->ten_loai_quy }}</option>
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
                const loai_quy_search = $('#loai_quy_search').val();
                window.location.href = "{{ route('admin.so.quy.index') }}?start_date=" + start_date + "&end_date=" + end_date + "&loai_quy_search=" + loai_quy_search;
            }
        </script>

        @include('admin.pages.inc.soquy')
    </section>
@endsection
