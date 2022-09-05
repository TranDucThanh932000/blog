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
                'username' => 'required|string|unique:users|min:6|max:50',
                'email' => 'required|email|min:9|max:50',
                'password' => 'required|min:6|max:20'
            ]);
            if($validator->fails()){
                return response('Thông tin chưa đúng định dạng', 400);
            }else{
                DB::beginTransaction();
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
                'username' => 'required|string|min:6|max:50',
                'password' => 'required|min:6|max:20'
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

    public function logout(Request $request){
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
}
