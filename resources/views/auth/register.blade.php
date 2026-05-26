@extends('error.master')
@section('title')
    {{ __('auth.page_register') }}
@endsection
@section('content')
    <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="#" class="logo d-flex align-items-center w-auto">
                                <img src="{{ $setting ? $setting->logo : '' }}" alt="">
                                <span class="d-none d-lg-block">{{ $setting ? $setting->home_name : '' }}</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">{{ __('auth.page_register_title') }}</h5>
                                    <p class="text-center small">{{ __('auth.page_register_description') }}</p>
                                </div>

                                <form class="row g-3 needs-validation" novalidate method="post"
                                      action="{{ route('auth.register') }}">
                                    @csrf
                                    <div class="col-12">
                                        <label for="name" class="form-label">{{ __('input.Your_Name') }}</label>
                                        <input type="text" name="name" class="form-control" id="name" required>
                                        <div class="invalid-feedback">{{ __('input.name_message') }}</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="email" class="form-label">{{ __('input.Your_Email') }}</label>
                                        <input type="email" name="email" class="form-control" id="email" required>
                                        <div class="invalid-feedback">{{ __('input.email_message') }}</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="username" class="form-label">{{ __('input.Username') }}</label>
                                        <input type="text" name="username" class="form-control" id="username" required>
                                        <div class="invalid-feedback">{{ __('input.username_message') }}</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">{{ __('input.Password') }}</label>
                                        <input type="password" name="password" class="form-control" id="password"
                                               required>
                                        <div class="invalid-feedback">{{ __('input.password_message') }}</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="password_confirm"
                                               class="form-label">{{ __('input.Password_Confirm') }}</label>
                                        <input type="password" name="password_confirm" class="form-control"
                                               id="password_confirm" required>
                                        <div class="invalid-feedback">{{ __('input.password_confirm_message') }}</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="terms" type="checkbox" value=""
                                                   id="acceptTerms" required>
                                            <label class="form-check-label" for="acceptTerms">{{ __('auth.agreement') }}
                                                <a
                                                    href="#">{{ __('auth.terms_and_conditions') }}</a></label>
                                            <div class="invalid-feedback">{{ __('input.message_must_agree') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100"
                                                type="submit">{{ __('auth.create_account') }}</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">{{ __('auth.already_have_account') }} <a
                                                href="{{ route('auth.processLogin') }}">{{ __('auth.login') }}</a></p>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="credits">
                            {{ __('error.designed_by') }} <a target="_blank"
                                href="{{ $setting ? $setting->author_social : '' }}">{{ $setting ? $setting->author_name : '' }}</a>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>
@endsection

