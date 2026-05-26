<aside id="sidebar" class="sidebar">
@php
    use App\Enums\RoleName;
    use App\Models\RoleUser;
    use App\Models\Role;
    $__currentUser = auth()->user();
    $__roleUser = RoleUser::where('user_id', $__currentUser->id)->first();
    $__role = $__roleUser ? Role::find($__roleUser->role_id) : null;
    $__isNhanVienSX = $__role && $__role->name === RoleName::NHAN_VIEN_SX;
@endphp

    <ul class="sidebar-nav" id="sidebar-nav">

        @if(!$__isNhanVienSX)
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.home') ? 'active' : 'collapsed' }}"
               href="{{ route('admin.home') }}">
                <i class="bi bi-grid"></i>
                <span>Trang quản trị</span>
            </a>
        </li><!-- End Dashboard Nav -->
        @endif

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.nguyen.lieu.*') || Request::routeIs('admin.phieu.*') ? '' : 'collapsed' }}"
               data-bs-target="#categories-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-card-list"></i><span>Quản lý kho</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="categories-nav"
                class="nav-content collapse {{ Request::routeIs('admin.nguyen.lieu.*') || Request::routeIs('admin.phieu.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                @if(!$__isNhanVienSX)
                <li>
                    <a class="{{ Request::routeIs('admin.nguyen.lieu.tho.index') || Request::routeIs('admin.nguyen.lieu.tho.detail') ? 'active' : '' }}"
                       href="{{ route('admin.nguyen.lieu.tho.index') }}">
                        <i class="bi bi-circle"></i><span>Kho NL Thô</span>
                    </a>
                </li>
                @endif
                <li>
                    <a class="{{ Request::routeIs('admin.nguyen.lieu.phan.loai.index') || Request::routeIs('admin.nguyen.lieu.phan.loai.detail') ? 'active' : '' }}"
                       href="{{ route('admin.nguyen.lieu.phan.loai.index') }}">
                        <i class="bi bi-circle"></i><span>Kho NL Phân loại</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.nguyen.lieu.tinh.index') || Request::routeIs('admin.nguyen.lieu.tinh.detail') ? 'active' : '' }}"
                       href="{{ route('admin.nguyen.lieu.tinh.index') }}">
                        <i class="bi bi-circle"></i><span>Kho NL Tinh</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.phieu.san.xuat.index') || Request::routeIs('admin.phieu.san.xuat.detail') ? 'active' : '' }}"
                       href="{{ route('admin.phieu.san.xuat.index') }}">
                        <i class="bi bi-circle"></i><span>Phiếu Sản xuất</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.nguyen.lieu.san.xuat.index') || Request::routeIs('admin.nguyen.lieu.san.xuat.detail') ? 'active' : '' }}"
                       href="{{ route('admin.nguyen.lieu.san.xuat.index') }}">
                        <i class="bi bi-circle"></i><span>Kho Thành phẩm Sản xuất</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.nguyen.lieu.thanh.pham.index') || Request::routeIs('admin.nguyen.lieu.thanh.pham.detail') ? 'active' : '' }}"
                       href="{{ route('admin.nguyen.lieu.thanh.pham.index') }}">
                        <i class="bi bi-circle"></i><span>Kho đã đóng gói</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Categories Nav -->

        @if(!$__isNhanVienSX)
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.san.pham.*') ? '' : 'collapsed' }}"
               data-bs-target="#attributes-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Quản lý sản phẩm</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="attributes-nav"
                class="nav-content collapse {{ Request::routeIs('admin.san.pham.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.san.pham.index') || Request::routeIs('admin.san.pham.detail') ? 'active' : '' }}"
                       href="{{ route('admin.san.pham.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Attributes Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.nha.cung.cap.*') ? '' : 'collapsed' }}"
               data-bs-target="#properties-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Quản lí nhà cung cấp</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="properties-nav"
                class="nav-content collapse {{ Request::routeIs('admin.nha.cung.cap.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.nha.cung.cap.index') || Request::routeIs('admin.nha.cung.cap.detail') ? 'active' : '' }}"
                       href="{{ route('admin.nha.cung.cap.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.nha.cung.cap.payment') ? 'active' : '' }}"
                       href="{{ route('admin.nha.cung.cap.payment') }}">
                        <i class="bi bi-circle"></i><span>Thanh toán NCC</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Properties Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.ban.hang*') ? '' : 'collapsed' }}"
               data-bs-target="#products-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Bán hàng</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="products-nav" class="nav-content collapse {{ Request::routeIs('admin.ban.hang.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.ban.hang.create') ? 'active' : '' }}"
                       href="{{ route('admin.ban.hang.create') }}">
                        <i class="bi bi-circle"></i><span>Thêm mới</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.ban.hang.index') || Request::routeIs('admin.ban.hang.detail') ? 'active' : '' }}"
                       href="{{ route('admin.ban.hang.index') }}">
                        <i class="bi bi-circle"></i><span>Lịch sử bán hàng</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Products Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.khach.hang.*') || Request::routeIs('admin.nhom.khach.hang.*') ? '' : 'collapsed' }}"
               data-bs-target="#orders-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-vcard"></i><span>Quản lý khách hàng</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="orders-nav"
                class="nav-content collapse {{ Request::routeIs('admin.khach.hang.*') || Request::routeIs('admin.nhom.khach.hang.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.khach.hang.index') || Request::routeIs('admin.khach.hang.detail') ? 'active' : '' }}"
                       href="{{ route('admin.khach.hang.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách khách hàng</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.nhom.khach.hang.index') || Request::routeIs('admin.nhom.khach.hang.detail') ? 'active' : '' }}"
                       href="{{ route('admin.nhom.khach.hang.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách nhóm khách hàng</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Orders Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.so.quy.*') || Request::routeIs('admin.loai.quy.*') || Request::routeIs('admin.nhom.quy.*') ? '' : 'collapsed' }}"
               data-bs-target="#news-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-piggy-bank"></i><span>Sổ quỹ</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="news-nav"
                class="nav-content collapse {{ Request::routeIs('admin.so.quy.*') || Request::routeIs('admin.loai.quy.*') || Request::routeIs('admin.nhom.quy.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.loai.quy.index') || Request::routeIs('admin.loai.quy.detail') ? 'active' : '' }}"
                       href="{{ route('admin.loai.quy.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách quỹ</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.nhom.quy.*') ? 'active' : '' }}"
                       href="{{ route('admin.nhom.quy.index') }}">
                        <i class="bi bi-circle"></i><span>Quản lý nhóm quỹ</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.so.quy.index') || Request::routeIs('admin.so.quy.detail') ? 'active' : '' }}"
                       href="{{ route('admin.so.quy.index') }}">
                        <i class="bi bi-circle"></i><span>Chi tiết sổ quỹ</span>
                    </a>
                </li>
            </ul>
        </li><!-- End News Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.nhan.vien.*') ? '' : 'collapsed' }}"
               data-bs-target="#purchases-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Quản lý nhân sự</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="purchases-nav"
                class="nav-content collapse {{ Request::routeIs('admin.nhan.vien.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.nhan.vien.list') || Request::routeIs('admin.nhan.vien.detail') ? 'active' : '' }}"
                       href="{{ route('admin.nhan.vien.list') }}">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.nhan.vien.create') ? 'active' : '' }}"
                       href="{{ route('admin.nhan.vien.create') }}">
                        <i class="bi bi-circle"></i><span>Thêm mới</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Purchases Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.thong.tin.*') ? '' : 'collapsed' }}"
               data-bs-target="#consultants-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-question-circle"></i><span>Lương + OKR</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="consultants-nav"
                class="nav-content collapse {{ Request::routeIs('admin.thong.tin.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.thong.tin.index') || Request::routeIs('admin.thong.tin.detail') ? 'active' : '' }}"
                       href="{{ route('admin.thong.tin.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Consultants Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.app.setting.index') ? '' : 'collapsed' }}"
               href="{{ route('admin.app.setting.index') }}">
                <i class="bi bi-gear"></i>
                <span>Cài đặt website</span>
            </a>
        </li><!-- End Setting Page Nav -->
        @endif

        <li class="nav-heading">Trang</li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.profile.index') ? '' : 'collapsed' }}"
               href="{{ route('admin.profile.index') }}">
                <i class="bi bi-person"></i>
                <span>Trang cá nhân</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('auth.logout') ? '' : 'collapsed' }}"
               href="{{ route('auth.logout') }}">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Đăng xuất</span>
            </a>
        </li><!-- End Logout Page Nav -->

    </ul>

</aside>
