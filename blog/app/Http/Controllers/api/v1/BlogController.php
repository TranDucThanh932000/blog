<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function getBlog(Request $request){
        try{
            $blog = Blog::find($request->id);
            if($blog === null || $blog->censor_id === null){
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
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'title' => 'required|min:5|max:100',
                'content' => 'required|min:1',
            ]);
            if($validator->fails()){
                return response('Hãy điền đầy đủ thông tin', 422);
            }else{
                $blog = Blog::create([
                    'title' => $request->title,
                    'content' => $request->content,
                    'date_publish' => Carbon::now(),
                    'author_id' => Auth::user()->id
                ]);
                DB::commit();
                return response($blog, 200);
            }
        }catch(Exception $e){
            DB::rollBack();
            return response('Lỗi khi thêm bài viết', 500);
        }
    }

    public function getBlogAcceptedByPaginate(Request $request){
        try{
            $blog = Blog::select([
                'id',
                'title',
                'author_id',
                'censor_id',
                'created_at'
            ])->whereNotNull('censor_id')->offset($request->pageNumber * 10 - 10)->limit(10)->orderBy('created_at', 'DESC')->get();
            $data = [];
            foreach($blog as $b){
                $b['author'] = User::find($b->author_id);
                $b['censor'] = User::find($b->censor_id);
            }
            $data['blogs'] = $blog;
            $count = Blog::whereNotNull('censor_id')->count();
            $data['pageCount'] = $count % 10  === 0 ? ($count / 10) : floor($count / 10) + 1;
            return response($data, 200);
        }catch(Exception $e){
            return response('Lấy danh sách blog thất bại', 500);
        }
    }

    public function getBlogUnacceptByPaginate(Request $request){
        try{
            $blog = Blog::select([
                'id',
                'title',
                'author_id',
                'created_at'
            ])->whereNull('censor_id')->offset($request->pageNumber * 10 - 10)->limit(10)->orderBy('created_at', 'DESC')->get();
            $data = [];
            foreach($blog as $b){
                $b['author'] = User::find($b->author_id);
            }
            $data['blogs'] = $blog;
            $count = Blog::whereNull('censor_id')->count();
            $data['pageCount'] = $count % 10  === 0 ? ($count / 10) : floor($count / 10) + 1;
            return response($data, 200);
        }catch(Exception $e){
            return response('Lấy danh sách blog thất bại', 500);
        }
    }

    public function getBlogPreview(Request $request){
        try{
            $blog = Blog::find($request->id);
            if($blog === null){
                return response('Không tồn tại bài viết', 400); 
            }
            $blog['author'] = User::find($blog->author_id);
            $blog['censor'] = User::find($blog->censor_id);
            return response($blog, 200);
        }catch(Exception $e){
            return response('Lỗi khi lấy thông tin bài viết', 500);
        } 
    }

    public function acceptBlog(Request $request){
        try{
            DB::beginTransaction();
            $blog = Blog::find($request->id);
            $blog->update([
                'censor_id' => Auth::user()->id
            ]);
            DB::commit();
            return response($blog, 200);
        }catch(Exception $e){
            DB::rollBack();
            return response('Lỗi khi phê duyệt bài viết', 400);
        }
    }

    public function cancelBlog(Request $request){
        try{
            DB::beginTransaction();
            $blog = Blog::find($request->id);
            if($blog === null){
                return response('Không tồn tại bài viết này', 400);
            }else{
                $blog->delete();
            }
            DB::commit();
            return response('Hủy bài viết thành công', 200);
        }catch(Exception $e){
            DB::rollBack();
            return response('Lỗi khi hủy bài viết', 400);
        }
    }
}
