<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Adminpage_sidebar;
use Exception;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function getAllMenu(){
        try{
            $menus = Menu::all();
            $data = [];
            foreach($menus as $menu){
                if($menu['parent_id'] == 0){
                    array_push($data, $menu);
                }
                $menu['children'] = [];
            }
            foreach($data as $dt){
                $this->recursion($dt, $menus);
            }
            if(count($data) == 0){
                return response('Chưa có thể loại bài viết nào', 400);
            }
            return response($data, 200);
        }catch(Exception $e){
            return response('Lỗi khi lấy danh sách đề mục', 400);
        }
    }

    private function recursion($data, $menus){
        $arrChild = [];
        foreach ($menus as $menu) {
            if($menu['parent_id'] == $data['id']){
                array_push($arrChild, $menu);
            }
        }
        $data['children'] = $arrChild;
        if(count($arrChild) == 0){
            return;
        }

        foreach($arrChild as $child){
            $this->recursion($child, $menus);
        }
    }

    public function addMenu(Request $request){

    }

    public function getSidebarAdminpage(){
        try{
            $sidebars = Adminpage_sidebar::all();
            $data = [];
            foreach($sidebars as $sidebar){
                if($sidebar['parent_id'] == 0){
                    array_push($data, $sidebar);
                }
                $sidebar['children'] = [];
            }
            foreach($data as $dt){
                $this->recursion($dt, $sidebars);
            }
            return response($data, 200);
        }catch(Exception $e){
            return response($e, 400);
        }
    }

    public function getAllMenuChildren(){
        try{
            $parent = DB::table('menus')->select('parent_id')->distinct()->get();
            $arrParentId = [];
            foreach($parent as $parent_id){
                array_push($arrParentId, $parent_id->parent_id);
            }
            $data = Menu::whereNotIn('id', $arrParentId)->get();
            if(count($data) === 0){
                return response('Không tồn tại thể loại bài viết phụ nào', 400);
            }
            return response($data, 200);
        }catch(Exception $e){
            return response('Lỗi khi lấy thể loại bài viết', 400);
        }

    }

    public function getMenuById(Request $request){
        try{
            $data = Menu::find($request->id);
            if(!$data){
                return response('Không tìm thấy thể loại bài viết', 400);
            }
            return response($data, 200);
        }catch(Exception $e){
            return response('Lỗi khi lấy thể loại bài viết', 400);
        }
    }
}
