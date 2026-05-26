<?php

namespace App\Http\Middleware;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\RoleUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Route names được phép cho NHAN_VIEN_SX (chỉ xem).
     */
    protected array $nhanVienSXAllowedRoutes = [
        'admin.profile.index',
        'admin.profile.change.info',
        'admin.profile.change.password',
        'auth.logout',
        // Kho NL Phân loại
        'admin.nguyen.lieu.phan.loai.index',
        'admin.nguyen.lieu.phan.loai.detail',
        'admin.nguyen.lieu.phan.loai.store',
        // Kho NL Tinh
        'admin.nguyen.lieu.tinh.index',
        'admin.nguyen.lieu.tinh.detail',
        'admin.nguyen.lieu.tinh.store',
        // Phiếu Sản Xuất
        'admin.phieu.san.xuat.index',
        'admin.phieu.san.xuat.detail',
        'admin.phieu.san.xuat.store',
        // Kho Thành phẩm Sản xuất
        'admin.nguyen.lieu.san.xuat.index',
        'admin.nguyen.lieu.san.xuat.detail',
        'admin.nguyen.lieu.san.xuat.store',
        // Kho đã đóng gói
        'admin.nguyen.lieu.thanh.pham.index',
        'admin.nguyen.lieu.thanh.pham.detail',
        'admin.nguyen.lieu.thanh.pham.store',
        // API liên quan (dùng trong các trang của kho)
        'api.chi.tiet.nguyen.lieu',
        'api.thong.tin.san.pham.detail',
        'api.nhan.vien.san.xuat.list',
        'api.phieu.san.xuat.list.chua.dung',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUrl = $request->fullUrl();

        if (Auth::check()) {
            $user = Auth::user();
            $role_user = RoleUser::where('user_id', $user->id)->first();

            if (!$role_user) {
                return redirect(route('error.forbidden') . '?callback=' . urlencode($currentUrl));
            }

            $role = Role::find($role_user->role_id);
            $roleName = $role ? $role->name : null;

            // Full quyền: ADMIN, GIAM_DOC, KE_TOAN
            if (in_array($roleName, [RoleName::ADMIN, RoleName::GIAM_DOC, RoleName::KE_TOAN])) {
                return $next($request);
            }
            // Chỉ được xem 5 kho: NHAN_VIEN_SX
            if ($roleName === RoleName::NHAN_VIEN_SX) {
                $currentRoute = $request->route()?->getName();
                
                // Nếu cố truy cập Dashboard hoặc route không được phép, chuyển hướng về Kho NL Phân loại
                if ($currentRoute === 'admin.home' || !in_array($currentRoute, $this->nhanVienSXAllowedRoutes)) {
                    return redirect(route('admin.nguyen.lieu.phan.loai.index'));
                }

                return $next($request);
            }

            return redirect(route('error.forbidden') . '?callback=' . urlencode($currentUrl));
        }

        return redirect(route('error.unauthorized') . '?callback=' . urlencode($currentUrl));
    }
}
