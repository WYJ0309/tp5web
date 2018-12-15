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

class ProductController extends AdminBaseController
{

    public function index()
    {

        $proList = Db::name("product")->field("id,product_cate_id,post_status,is_top,recommended,post_hits,create_time,title")->paginate();
        $this->assign("proList",$proList);
        return $this->fetch();
    }


}