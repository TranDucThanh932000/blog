<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\User;

class BlogController extends Controller
{
    public function getBlog(Request $request){
        try{
            $blog = Blog::find($request->id);
            if($blog === null){
                return response('Không tồn tại bài viết', 400); 
            }
            $blog['author'] = User::find($blog->author_id);
            $blog['censor'] = User::find($blog->censor_id);
            $blog['totalRated'] = 0;
            return response($blog, 200);
        }catch(Exception $e){
            return response('Lỗi khi lấy thông tin bài viết', 500);
        } 
    }

    public function saveBlog(Request $request){
        
    }
}
