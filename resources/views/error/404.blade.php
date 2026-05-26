@extends('error.master')
@section('title')
    {{ __('error.page_not_found') }}
@endsection
@section('content')
    <div class="container">

        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <h1>404</h1>
            <h2>{{ __('error.page_not_found_description') }}</h2>
            <a class="btn" href="{{ route('home') }}">{{ __('error.back_to_home') }}</a>
            <img src="{{ asset('admin/img/not-found.svg') }}" class="img-fluid py-5" alt="{{ __('error.page_not_found') }}">
            <div class="credits">
                {{ __('error.designed_by') }} <a target="_blank"
                                                 href="{{ $setting ? $setting->author_social : '' }}">{{ $setting ? $setting->author_name : '' }}</a>
            </div>
        </section>

    </div>
@endsection

