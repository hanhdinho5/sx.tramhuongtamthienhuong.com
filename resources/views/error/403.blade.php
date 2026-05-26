@extends('error.master')
@section('title')
    {{ __('error.forbidden') }} 403
@endsection
@section('content')
    <div class="container">

        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <h1>403</h1>
            <h2>{{ __('error.forbidden_description') }}.</h2>
            <a class="btn" href="{{ route('home') }}">{{ __('error.back_to_home') }}</a>
            <img src="{{ asset('admin/img/not-found.svg') }}" class="img-fluid py-5" alt="{{ __('error.forbidden') }}">
            <div class="credits">
                {{ __('error.designed_by') }} <a target="_blank"
                                                 href="{{ $setting ? $setting->author_social : '' }}">{{ $setting ? $setting->author_name : '' }}</a>
            </div>
        </section>

    </div>
@endsection
