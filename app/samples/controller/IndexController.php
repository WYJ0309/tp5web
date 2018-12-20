<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\samples\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\Request;

class IndexController extends HomeBaseController
{
    public function index()
    {

        $slide = Db::name("slide_item")->where(array("slide_id"=>1,"status"=>1))->select()->toArray();
        $this->assign("slideArr",$slide);
        $this->assign('site_info', cmf_get_option('site_info'));
        $article = Db::name("portal_post")->where(array("post_type"=>1,"post_status"=>1,"recommended"=>1))->field("post_title,post_keywords,post_excerpt,thumbnail,create_time,comment_count,post_hits,post_excerpt")->select()->toArray();
        $this->assign("artRec",$article);

        $product = Db::name("product_post")->where(array("post_type"=>1,"post_status"=>1,"recommended"=>1))->field("post_title,post_keywords,post_excerpt,thumbnail,create_time,comment_count,post_hits")->select()->toArray();
        $this->assign("productList",$product);
        $cateListP = Db::name("product_category")->where(array("parent_id"=>0))->field("id,name,list_order,description,status")->select()->toArray();
        $this->assign('cateListP', $cateListP);


        return $this->fetch(':index');
    }
    public function about()
    {

        $result = Db::name("portal_post")->where(array("id"=>4))->find();
        $this->assign("result",$result);
        return $this->fetch(':about');
    }
    public function news()
    {
        return $this->fetch(':blog');
    }
    public function contact()
    {
        if(Request::instance()->isPost()){
            $data["username"] = input("username");
            $data["email"] = input("email");
            $data["title"] = input("title");
            $data["content"] = input("content");
            $data["create_time"] = time();
            Db::name("message")->insert($data);
            return array("status"=>true,"msg"=>"留言成功");die;
        }
        $result = Db::name("portal_post")->where(array("id"=>6))->find();
        $result["post_content"] = htmlspecialchars_decode($result["post_content"]);
        $this->assign("result",$result);

        $this->assign('site_info', cmf_get_option('site_info'));
        return $this->fetch(':contact');
    }
    public function product()
    {
        return $this->fetch(':courses');
    }
    public function sigle_courses()
    {
        return $this->fetch(':sigle_courses');
    }
    public function sigle_post()
    {
        return $this->fetch(':sigle_post');
    }




}
