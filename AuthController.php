<?php

namespace App\Http\Controllers;

use App\Enums\RoleName;
use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function processLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect(route('admin.home'));
        }
        $url_callback = $request->input('url_callback');
        return view('auth.login', compact('url_callback'));
    }

    public function login(Request $request)
    {
        try {
            $loginRequest = $request->input('login_request');
            $password = $request->input('password');
            $url_callback = $request->input('url_callback');

            $credentials = [
                'password' => $password,
            ];

            if (filter_var($loginRequest, FILTER_VALIDATE_EMAIL)) {
                $user = User::where('email', $loginRequest)->first();
                $credentials['email'] = $loginRequest;
            } else {
                $user = User::where('phone', $loginRequest)->first();
                if ($user) {
                    $credentials['phone'] = $loginRequest;
                } else {
                    $user = User::where('username', $loginRequest)->first();
                    $credentials['username'] = $loginRequest;
                }
            }

            if (!$user) {
                toast('User not found!', 'error', 'top-right');
                return redirect()->back();
            } else {
                if ($user->status == UserStatus::INACTIVE) {
                    toast('User is inactive!', 'error', 'top-right');
                    return redirect()->back();
                } else if ($user->status == UserStatus::BLOCKED) {
                    toast('User has been blocked!', 'error', 'top-right');
                    return redirect()->back();
                } else if ($user->status == UserStatus::DELETED) {
                    toast('User is deleted!', 'error', 'top-right');
                    return redirect()->back();
                }
            }

            $roleAdmin = Role::where('name', RoleName::ADMIN)->first();
            if (!$roleAdmin) {
                alert()->error('Role admin not found!');
                return redirect()->back();
            }

            $user_role = RoleUser::where('user_id', $user->id)->where('role_id', $roleAdmin->id)->first();

            if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
                $token = JWTAuth::fromUser($user);

                $user->last_token = $token;
                $user->last_login = now();
                $user->save();

                toast('Login success!', 'success', 'top-right');
            }

            if (Auth::check()) {
                $user = Auth::user();
                $roleUser = RoleUser::where('user_id', $user->id)->first();
                $role = $roleUser ? Role::find($roleUser->role_id) : null;

                $isNhanVienSX = $role && $role->name === RoleName::NHAN_VIEN_SX;

                if ($url_callback && !$isNhanVienSX) {
                    return redirect()->to($url_callback);
                }

                if ($isNhanVienSX) {
                    return redirect()->route('admin.nguyen.lieu.phan.loai.index');
                }

                if ($role && in_array($role->name, [RoleName::ADMIN, RoleName::GIAM_DOC, RoleName::KE_TOAN])) {
                    return redirect()->route('admin.home');
                }

                return redirect()->route('home');
            }
            toast('Login fail! Please check email or password', 'error', 'top-right');
            return redirect()->back();
        } catch (\Exception $exception) {
            toast($exception->getMessage(), 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function processRegister()
    {
        if (Auth::check()) {
            return redirect(route('home'));
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $newController = (new MainController());
        try {
            $name = $request->input('name');
            $email = $request->input('email');
            $username = $request->input('username');
            $phone = $request->input('phone');
            $password = $request->input('password');
            $password_confirm = $request->input('password_confirm');

            if (!$name) {
                toast('Name is required!', 'error', 'top-right');
                return redirect()->back();
            }

            if (!$email) {
                toast('Email is required!', 'error', 'top-right');
                return redirect()->back();
            }

            if (!$phone) {
                toast('Phone is required!', 'error', 'top-right');
                return redirect()->back();
            }

            if (!$username) {
                toast('Username is required!', 'error', 'top-right');
                return redirect()->back();
            }

            if (!$password) {
                toast('Password is required!', 'error', 'top-right');
                return redirect()->back();
            }

            $isEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$isEmail) {
                toast('Email invalid!', 'error', 'top-right');
                return redirect()->back();
            }

            $is_valid = User::checkEmail($email);
            if (!$is_valid) {
                toast('Email already exited!', 'error', 'top-right');
                return redirect()->back();
            }

            $is_valid = User::checkPhone($phone);
            if (!$is_valid) {
                toast('Phone already exited!', 'error', 'top-right');
                return redirect()->back();
            }

            $is_valid = User::checkUsername($username);
            if (!$is_valid) {
                toast('Username already exited!', 'error', 'top-right');
                return redirect()->back();
            }

            if ($password != $password_confirm) {
                toast('Password or Password Confirm incorrect!', 'error', 'top-right');
                return redirect()->back();
            }

            if (strlen($password) < 5) {
                toast('Password invalid!', 'error', 'top-right');
                return redirect()->back();
            }

            $passwordHash = Hash::make($password);

            $user = new User();

            $user->full_name = $name;
            $user->phone = $phone;
            $user->email = $email;
            $user->username = $username;
            $user->password = $passwordHash;
            $user->address = '';
            $user->about = '';
            $user->avatar = '';
            $user->status = UserStatus::ACTIVE;

            $success = $user->save();

            $newController->saveRoleUser($user->id);

            if ($success) {
                toast('Register success!', 'success', 'top-right');
                return redirect(route('home'));
            }
            toast('Register failed!', 'error', 'top-right');
            return redirect()->back();
        } catch (\Exception $exception) {
            toast($exception->getMessage(), 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function logout()
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $user->last_token = null;
                $user->save();
            }
            Auth::logout();
            return redirect(route('home'));
        } catch (\Exception $exception) {
            return redirect(route('home'));
        }
    }
}
