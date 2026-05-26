@extends('admin.layouts.master')
@section('title')
    Cài đặt website
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Cài đặt website</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Cài đặt website</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="post" action="{{ route('admin.app.setting.store') }}" enctype="multipart/form-data">
            @csrf
            <table class="table table-bordered">
                <colgroup>
                    <col width="15%">
                    <col width="35%">
                    <col width="15%">
                    <col width="35%">
                </colgroup>
                <tbody>
                <tr>
                    <td colspan="4">
                        Thông tin SEO
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="logo">logo</label>
                    </th>
                    <td colspan="">
                        <div class="d-flex justify-content-between flex-wrap">
                            <input type="file" name="logo" id="logo" class="form-control w-75">
                            <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleLogo">Xem Logo
                            </button>
                        </div>
                    </td>
                    <th class="align-middle">
                        <label for="favicon">favicon</label>
                    </th>
                    <td colspan="">
                        <div class="d-flex justify-content-between flex-wrap">
                            <input type="file" name="favicon" id="favicon" class="form-control w-75">
                            <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleFavicon">Xem Favicon
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="og_title">og_title</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="og_title" id="og_title" class="form-control w-100"
                               value="{{ $setting ? $setting->og_title : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="og_des">og_des</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="og_des" id="og_des" class="form-control w-100"
                               value="{{ $setting ? $setting->og_des : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="og_url">og_url</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="og_url" id="og_url" class="form-control w-100"
                               value="{{ $setting ? $setting->og_url : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="og_site">og_site</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="og_site" id="og_site" class="form-control w-100"
                               value="{{ $setting ? $setting->og_site : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="og_img">og_img</label>
                    </th>
                    <td colspan="">
                        <div class="d-flex justify-content-between flex-wrap">
                            <input type="file" name="og_img" id="og_img" class="form-control w-75">
                            <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleog_img">Xem og_img
                            </button>
                        </div>
                    </td>
                    <th class="align-middle">
                        <label for="thumbnail">thumbnail</label>
                    </th>
                    <td colspan="">
                        <div class="d-flex justify-content-between flex-wrap">
                            <input type="file" multiple name="thumbnail[]" id="thumbnail" class="form-control w-75">
                            <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#examplethumbnail">Xem thumbnail
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="meta_tag">meta_tag</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="meta_tag" id="meta_tag" class="form-control w-100"
                               value="{{ $setting ? $setting->meta_tag : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="meta_keyword">meta_keyword</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="meta_keyword" id="meta_keyword" class="form-control w-100"
                               value="{{ $setting ? $setting->meta_keyword : ''}}">
                    </td>
                </tr>

                <tr>
                    <th class="align-middle">
                        <label for="google_site_verification">google_site_verification</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="google_site_verification" id="google_site_verification"
                               class="form-control w-100"
                               value="{{ $setting ? $setting->google_site_verification : ''}}">
                    </td>
                </tr>

                <tr>
                    <td colspan="4">
                        App Information
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="company_name">company_name</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="company_name" id="company_name" class="form-control w-100"
                               value="{{ $setting ? $setting->company_name : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="home_name">home_name</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="home_name" id="home_name" class="form-control w-100"
                               value="{{ $setting ? $setting->home_name : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="brand_name">brand_name</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="brand_name" id="brand_name" class="form-control w-100"
                               value="{{ $setting ? $setting->brand_name : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="browser_title">browser_title</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="browser_title" id="browser_title" class="form-control w-100"
                               value="{{ $setting ? $setting->browser_title : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="domain_url">domain_url</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="domain_url" id="domain_url" class="form-control w-100"
                               value="{{ $setting ? $setting->domain_url : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="email">email</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="email" id="email" class="form-control w-100"
                               value="{{ $setting ? $setting->email : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="phone">phone</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="phone" id="phone" class="form-control w-100"
                               value="{{ $setting ? $setting->phone : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="address">address</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="address" id="address" class="form-control w-100"
                               value="{{ $setting ? $setting->address : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="address_detail">address_detail</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="address_detail" id="address_detail" class="form-control w-100"
                               value="{{ $setting ? $setting->address_detail : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="zip">zip</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="zip" id="zip" class="form-control w-100"
                               value="{{ $setting ? $setting->zip : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="fax">fax</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="fax" id="fax" class="form-control w-100"
                               value="{{ $setting ? $setting->fax : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="qna_email">qna_email</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="qna_email" id="qna_email" class="form-control w-100"
                               value="{{ $setting ? $setting->qna_email : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="business_number">business_number</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="business_number" id="business_number" class="form-control w-100"
                               value="{{ $setting ? $setting->business_number : ''}}">
                    </td>
                </tr>

                <tr>
                    <td colspan="4">
                        Owner Information
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="owner_name">owner_name</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="owner_name" id="owner_name" class="form-control w-100"
                               value="{{ $setting ? $setting->owner_name : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="owner_phone">owner_phone</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="owner_phone" id="owner_phone" class="form-control w-100"
                               value="{{ $setting ? $setting->owner_phone : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="owner_email">owner_email</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="owner_email" id="owner_email" class="form-control w-100"
                               value="{{ $setting ? $setting->owner_email : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="owner_social_01">owner_social_01</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="owner_social_01" id="owner_social_01" class="form-control w-100"
                               value="{{ $setting ? $setting->owner_social_01 : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="owner_social_02">owner_social_02</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="owner_social_02" id="owner_social_02" class="form-control w-100"
                               value="{{ $setting ? $setting->owner_social_02 : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="owner_social_03">owner_social_03</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="owner_social_03" id="owner_social_03" class="form-control w-100"
                               value="{{ $setting ? $setting->owner_social_03 : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="copyright">copyright</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="copyright" id="copyright" class="form-control w-100"
                               value="{{ $setting ? $setting->copyright : ''}}">
                    </td>
                </tr>

                <tr>
                    <td colspan="4">
                        App Author
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="author_name">author_name</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="author_name" id="author_name" class="form-control w-100"
                               value="{{ $setting ? $setting->author_name : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="author_social">author_social</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="author_social" id="author_social" class="form-control w-100"
                               value="{{ $setting ? $setting->author_social : ''}}">
                    </td>
                </tr>

                <tr>
                    <td colspan="4">
                        Bank Information 01
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="bank_name">bank_name</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="bank_name" id="bank_name" class="form-control w-100"
                               value="{{ $setting ? $setting->bank_name : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="bank_number">bank_number</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="bank_number" id="bank_number" class="form-control w-100"
                               value="{{ $setting ? $setting->bank_number : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="bank_holder">bank_holder</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="bank_holder" id="bank_holder" class="form-control w-100"
                               value="{{ $setting ? $setting->bank_holder : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="qr_code">qr_code</label>
                    </th>
                    <td colspan="">
                        <div class="d-flex justify-content-between flex-wrap">
                            <input type="file" name="qr_code" id="qr_code" class="form-control w-75">
                            <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleqrcode">Xem qr_code
                            </button>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="4">
                        Bank Information 02
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="bank_name_02">bank_name_02</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="bank_name_02" id="bank_name_02" class="form-control w-100"
                               value="{{ $setting ? $setting->bank_name_02 : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="bank_number_02">bank_number_02</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="bank_number_02" id="bank_number_02" class="form-control w-100"
                               value="{{ $setting ? $setting->bank_number_02 : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="bank_holder_02">bank_holder_02</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="bank_holder_02" id="bank_holder_02" class="form-control w-100"
                               value="{{ $setting ? $setting->bank_holder_02 : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="qr_code_02">qr_code_02</label>
                    </th>
                    <td colspan="">
                        <div class="d-flex justify-content-between flex-wrap">
                            <input type="file" name="qr_code_02" id="qr_code_02" class="form-control w-75">
                            <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleqrcode02">Xem qr_code_02
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleFavicon" tabindex="-1" aria-labelledby="exampleFaviconLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleFaviconLabel">Xem Favicon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ $setting ? $setting->favicon : '' }}" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleLogo" tabindex="-1" aria-labelledby="exampleLogoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleLogoLabel">Xem Logo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ $setting ? $setting->logo : '' }}" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleog_img" tabindex="-1" aria-labelledby="exampleog_imgLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleog_imgLabel">Modal og_img</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ $setting ? $setting->og_img : '' }}" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="examplethumbnail" tabindex="-1" aria-labelledby="examplethumbnailLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="examplethumbnailLabel">Xem thumbnail</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleqrcode" tabindex="-1" aria-labelledby="exampleqrcodeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleqrcodeLabel">Xem qrcode</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ $setting ? $setting->qr_code : '' }}" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleqrcode02" tabindex="-1" aria-labelledby="exampleqrcode02Label"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleqrcode02Label">Xem qrcode02</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ $setting ? $setting->qr_code_02 : '' }}" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
