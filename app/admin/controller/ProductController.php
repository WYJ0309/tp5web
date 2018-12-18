<?php
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\ThemeModel;

class ProductController extends AdminBaseController
{
    //产品列表
    public function index()
    {

        $keyword = input("keyword");
        $where = array();
        if($keyword){
            $where["post_title"] = array("like","%".$keyword."%");
        }
        $parent_id = input("parent_id");
        if($parent_id){

        }
        $data= Db::name("product_post")->field("id,cate_id,post_hits,id,published_time,post_title,post_keywords,thumbnail,recommended,post_status,is_top")->order('create_time DESC')->paginate(20);
        $this->assign('keyword', isset($param['keyword']) ? $param['keyword'] : '');
        $this->assign('parent_id', isset($param['parent_id']) ? $param['parent_id'] : '');
        $this->assign('products', $data->items());
        $this->assign('page', $data->render());



        $cateListP = Db::name("product_category")->where(array("parent_id"=>0))->field("id,name,list_order,description,status")->select()->toArray();
        $this->assign('cateListP', $cateListP);
        $cateArr = array();
        foreach ($cateListP as $key => $value) {
            $cateArr[$value["id"]] = $value["name"];
        }
        $this->assign("cateArr",$cateArr);
        return $this->fetch();
    }

    //添加产品
    public function add()
    {
        $cateListP = Db::name("product_category")->where(array("parent_id"=>0))->field("id,name,list_order,description,status")->select()->toArray();
        $this->assign('cateListP', $cateListP);

        return $this->fetch();
    }

    //保存产品添加
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $data["post"]["post_content"] = htmlspecialchars_decode($data["post"]["post_content"]);
            //状态只能设置默认值。未发布、未置顶、未推荐
            $data['post']['post_status'] = 0;
            $data['post']['is_top']      = 0;
            $data['post']['recommended'] = 0;

            
            $id = Db::name("product_post")->insertGetId($data["post"]);
            $this->success('添加成功!', url('Product/edit', ['id' => $id]));
        }

    }

    //编辑产品
    public function edit()
    {
        
        $id = $this->request->param('id', 0, 'intval');
        $cateListP = Db::name("product_category")->where(array("parent_id"=>0))->field("id,name,list_order,description,status")->select()->toArray();
        $this->assign('cateListP', $cateListP);
        $result = Db::name("product_post")->where(array("id"=>$id))->find();
        $result["post_content"] = htmlspecialchars_decode($result["post_content"]);
        $this->assign('result', $result);

        return $this->fetch();
    }

    //编辑保存产品
    public function editPost()
    {

        if ($this->request->isPost()) {
            $data = $this->request->param();
            $post   = $data['post'];
            
            Db::name("product_post")->update($post);
            $this->success('保存成功!');
        }
    }

    //产品删除
    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        Db::name("product_post")->where(array("id"=>$id))->delete();
        $this->success("删除成功");
    }

    //产品发布
    public function publish()
    {
        $param           = $this->request->param();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            Db::name("product_post")->where(['id' => ['in', implode(",",$ids)]])->update(['post_status' => 1, 'published_time' => time()]);

            $this->success("发布成功！", '');
        }

        if (isset($param['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            Db::name("product_post")->where(['id' => ['in', implode(",",$ids)]])->update(['post_status' => 0]);

            $this->success("取消发布成功！", '');
        }

    }

    //产品置顶
    public function top()
    {
        $param           = $this->request->param();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            Db::name("product_post")->where(['id' => ['in', implode(",",$ids)]])->update(['is_top' => 1]);

            $this->success("置顶成功！", '');

        }

        if (isset($_POST['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            Db::name("product_post")->where(['id' => ['in', implode(",",$ids)]])->update(['is_top' => 0]);

            $this->success("取消置顶成功！", '');
        }
    }

    //产品推荐
    public function recommend()
    {
        $param           = $this->request->param();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            Db::name("product_post")->where(['id' => ['in', implode(",",$ids)]])->update(['recommended' => 1]);

            $this->success("推荐成功！", '');

        }
        if (isset($param['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            Db::name("product_post")->where(['id' => ['in', implode(",",$ids)]])->update(['recommended' => 0]);

            $this->success("取消推荐成功！", '');

        }
    }

}
