<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class DialogController extends AdminBaseController
{
    public function _initialize()
    {

    }

    public function map()
    {
        $location = $this->request->param('location');
        $location = explode(',', $location);
        $lng      = empty($location[0]) ? 116.424966 : $location[0];
        $lat      = empty($location[1]) ? 39.907851 : $location[1];

        $this->assign(['lng' => $lng, 'lat' => $lat]);
        return $this->fetch();
    }
    public function index(){

        $data= Db::name("message")->order('create_time DESC')->paginate(20,false,["query"=>$this->request->param()]);
        $this->assign('msgList', $data->items());
        $this->assign('page', $data->render());
        return $this->fetch();
    } 
    public function edit(){

        $id = input("id");
        $result = Db::name("message")->where(array("id"=>$id))->find();
        $this->assign("result",$result);
        return $this->fetch();
    }
    public function editPost(){
        $data["username"] = input("username");
        $data["email"] = input("email");
        $data["title"] = input("title");
        $data["content"] = input("content");
        $data["id"] = input("id");
        Db::name("message")->update($data);
        $this->success("更新成功");
    }  

    public function delete(){
        $id = input("id");
        Db::name("message")->where(array("id"=>$id))->delete();
        $this->success("删除成功");
    }

}