<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\Menu;

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

}
