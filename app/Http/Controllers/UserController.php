<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.pages.profile');
    }

    public function changeInfo(Request $request)
    {
        try {
            $full_name = $request->input('full_name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $address = $request->input('address');
            $about = $request->input('about');
            $room = $request->input('room');

            $avt = Auth::user()->avatar;

            $user = Auth::user();

            if ($request->hasFile('avatar')) {
                $item = $request->file('avatar');
                $itemPath = $item->store('avatars', 'public');
                $avt = asset('storage/' . $itemPath);
            }

            $user->full_name = $full_name;

            if ($user->email != $email) {

                $isEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
                if (!$isEmail) {
                    return redirect()->route('admin.profile.index')->with('error', 'Email không hợp lệ!');
                }

                $isValid = User::checkEmail($email);
                if (!$isValid) {
                    return redirect()->route('admin.profile.index')->with('error', 'Email đã được tài khoản khác sử dụng!');
                }
                $user->email = $email;
            }

            if ($user->phone != $phone) {
                $isValid = User::checkPhone($phone);
                if (!$isValid) {
                    return redirect()->route('admin.profile.index')->with('error', 'Số điện thoại đã được tài khoản khác sử dụng!');
                }
                $user->phone = $phone;
            }

            $user->address = $address;
            $user->about = $about;
            $user->avatar = $avt;
            $user->room = $room;

            $user->save();

            return redirect()->route('admin.profile.index')->with('success', 'Thay đổi thông tin cá nhân thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $password = $request->input('password');
            $password_confirm = $request->input('newpassword');
            $new_password_confirm = $request->input('renewpassword');

            $user = Auth::user();

            if (!Hash::check($password, $user->password)) {
                return redirect()->route('admin.profile.index')->with('error', 'Mật khẩu cũ không khớp!');
            }

            if ($new_password_confirm != $password_confirm) {
                return redirect()->route('admin.profile.index')->with('error', 'Mật khẩu không khớp!');
            }

            if (strlen($password_confirm) < 5) {
                return redirect()->route('admin.profile.index')->with('error', 'Mật khẩu phải có ít nhất 5 ký tự!');
            }

            $passwordHash = Hash::make($password_confirm);
            $user->password = $passwordHash;

            $user->save();
            return redirect()->route('admin.profile.index')->with('success', 'Đổi mật khẩu thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
