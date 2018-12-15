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

class IndexController extends HomeBaseController
{
    public function index()
    {

        $slide = Db::name("slide_item")->where(array("slide_id"=>1,"status"=>1))->select()->toArray();
        $this->assign("slideArr",$slide);
        $this->assign('site_info', cmf_get_option('site_info'));
        return $this->fetch(':index');
    }
    public function about()
    {
        return $this->fetch(':about');
    }
    public function blog()
    {
        return $this->fetch(':blog');
    }
    public function contact()
    {
        return $this->fetch(':contact');
    }
    public function courses()
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
