<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function createUser(Request $request){
        try{
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50',
                'username' => 'required|string|min:5|max:20',
                'email' => 'required|email|min:9|max:50',
                'password' => 'required|min:5|max:20'
            ]);
            if($validator->fails()){
                return response('Thông tin chưa đúng định dạng', 400);
            }else{
                DB::beginTransaction();
                $checkExist = User::where(['username' => $request->username])->first();
                if($checkExist){
                    return response('Tên tài khoản đã tồn tại', 400);
                }
                $user = User::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password) 
                ]);
                DB::commit();

                if (!Auth::attempt([
                    'username' => $request->username,
                    'password' => $request->password
                ])) {
                    return response(['message' => 'Sai tài khoản hoặc mật khẩu'], 403);
                }

                $accessToken = Auth::user()->createToken('authToken')->accessToken;
                return response([
                    'user' => $user,
                    'access_token' => $accessToken
                ], 200);
            }
        }catch(Exception $e){
            DB::rollBack();
            return response($e, 400);
        }
    }

    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|min:5|max:20',
                'password' => 'required|min:5|max:20'
            ]);
            if($validator->fails()){
                return response('Thông tin chưa đúng định dạng', 400);
            }else{
                if (!Auth::attempt([
                    'username' => $request->username,
                    'password' => $request->password
                ])) {
                    return response(['message' => 'Sai tài khoản hoặc mật khẩu'], 403);
                }

                $accessToken = Auth::user()->createToken('authToken')->accessToken;
                return response([
                    'user' => auth()->user(),
                    'access_token' => $accessToken
                ], 200);
            }
        }catch(Exception $e){
            return response('Lỗi khi đăng nhập vào hệ thống', 500);
        }
    }

    public function logout(){
        //xóa token hiện tại
        $user = Auth::user()->token();
        $user->revoke();

        //xóa tất cả token của user
        //cái này mục đích đăng xuất hết ở các thiết bị, giống kiểu khi đổi mật khẩu của facebook
        // Auth::user()->tokens->each(function($token, $key) {
        //     $token->delete();
        // });

        return response('Đăng xuất thành công', 200);
    }

    public function checkAbility(Request $request){
        try{
            return response(Auth::user()->checkPermissionAccess($request->ability), 200);
        }catch(Exception $e){
            return response('Lỗi khi kiểm tra quyền thao tác', 500);
        }
    }

    public function getCurrentUser(Request $request){
        try{
            $user = Auth::user();
            $roles = $user->roles()->get();
            $permissions = [];
            foreach($roles as $role){
                foreach($role->rolepermissions()->get() as $permiss){
                    array_push($permissions, $permiss->key_code);
                }
            }
            $user['roles'] = $permissions;
            return response(Auth::user(), 200);
        }catch(Exception $e){
            return response('Lỗi khi lấy thông tin người dùng hiện tại', 500);
        }
    }
}
