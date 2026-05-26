<?php

namespace App\Http\Controllers\admin;

use App\Enums\RoleName;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function list()
    {
        $users = User::where('users.status', '!=', UserStatus::DELETED())
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', '=', 'roles.id')
            ->orderByDesc('users.id')
            ->select('users.*', 'roles.name as role_name')
            ->get();
        return view('admin.pages.users.list', compact('users'));
    }

    public function detail($id)
    {
        $user = User::where('status', '!=', UserStatus::DELETED())
            ->where('id', $id)
            ->first();
        if (!$user) {
            return redirect()->route('error.not.found');
        }
        return view('admin.pages.users.detail', compact('user'));
    }

    public function create()
    {
        return view('admin.pages.users.create');
    }

    public function store(Request $request)
    {   

       $newController = (new MainController());
        try {
            $user = new User();

            $email = $request->input('email');
            $phone = $request->input('phone');
            $password = $request->input('password');
            $password_confirm = $request->input('password_confirm');

            $is_valid = User::checkEmail($email);
            if (!$is_valid) {
                toast('Email đã được sử dụng!', 'error', 'top-right');
                return redirect()->back();
            }

            $is_valid = User::checkPhone($phone);
            if (!$is_valid) {
                toast('Điện thoại đã được sử dụng!', 'error', 'top-right');
                return redirect()->back();
            }

            if ($password != $password_confirm) {
                toast('Mật khẩu không khớp!', 'error', 'top-right');
                return redirect()->back();
            }

            if (strlen($password) < 5) {
                toast('Mật khẩu phải có ít nhất 5 ký tự!', 'error', 'top-right');
                return redirect()->back();
            }

            $user = $this->saveUser($request, $user);
            $user->save();

            $role_name = $request->input('role_name', \App\Enums\RoleName::NHAN_VIEN_SX);
            $newController->saveRoleUser($user->id, $role_name);

            toast('Nhân viên đã được tạo thành công!', 'success', 'top-right');
            return redirect()->route('admin.nhan.vien.list');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $user = User::where('status', '!=', UserStatus::DELETED())
                ->where('id', $id)
                ->first();
            if (!$user) {
                return redirect()->route('error.not.found');
            }

            $email = $request->input('email');
            $phone = $request->input('phone');
            $password = $request->input('password');
            $password_confirm = $request->input('password_confirm');

            if ($user->email != $email) {
                $is_valid = User::checkEmail($email);
                if (!$is_valid) {
                    toast('Email đã được sử dụng!', 'error', 'top-right');
                    return redirect()->back();
                }
            }

            if ($user->phone != $phone) {
                $is_valid = User::checkPhone($phone);
                if (!$is_valid) {
                    toast('Điện thoại đã được sử dụng!', 'error', 'top-right');
                    return redirect()->back();
                }
            }

            if ($password || $password_confirm) {
                if ($password != $password_confirm) {
                    toast('Mật khẩu không khớp!', 'error', 'top-right');
                    return redirect()->back();
                }

                if (strlen($password) < 5) {
                    toast('Mật khẩu phải có ít nhất 5 ký tự!', 'error', 'top-right');
                    return redirect()->back();
                }
            }

            $user = $this->saveUser($request, $user);
            $user->save();

            // Cập nhật role nếu có gửi lên
            if ($request->filled('role_name')) {
                $newController = (new \App\Http\Controllers\MainController());
                $newController->saveRoleUser($user->id, $request->input('role_name'));
            }

            toast('Nhân viên đã cập nhật thành công!', 'success', 'top-right');
            return redirect()->route('admin.nhan.vien.list');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $user = User::where('status', '!=', UserStatus::DELETED())
                ->where('id', $id)
                ->first();
            if (!$user) {
                return redirect()->route('error.not.found');
            }

            $user->status = UserStatus::DELETED();
            $user->save();

            toast('Nhân viên đã bị xóa thành công!', 'success', 'top-right');
            return redirect()->route('admin.nhan.vien.list');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error', 'top-right');
            return redirect()->back();
        }
    }

    private function saveUser(Request $request, User $user)
    {
        $email = $request->input('email');
        $phone = $request->input('phone');
        $password = $request->input('password');
        $full_name = $request->input('full_name');
        $username = $request->input('username') ?? $email;
        $address = $request->input('address');
        $about = $request->input('about');
        $status = $request->input('status');
        $room = $request->input('room');

        $avatar_path = $user->avatar ?? '';

        if ($request->hasFile('avatar')) {
            $item = $request->file('avatar');
            $itemPath = $item->store('users', 'public');
            $avatar = asset('storage/' . $itemPath);
            $avatar_path = $avatar;
        }

        $user->full_name = $full_name;
        $user->username = $username;
        $user->phone = $phone;
        $user->email = $email;

        if ($request->input('password')) {
            $passwordHash = Hash::make($password);
            $user->password = $passwordHash;
        }
        $user->avatar = $avatar_path;
        $user->address = $address;
        $user->about = $about;
        $user->status = $status;
        $user->room = $room;

        return $user;
    }
}
