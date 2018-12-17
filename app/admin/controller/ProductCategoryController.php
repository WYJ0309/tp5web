<?php
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;


class ProductCategoryController extends AdminBaseController
{
    //产品分类列表
    public function index()
    {
        $cateListP = Db::name("product_category")->where(array("parent_id"=>0))->field("id,name,list_order,description,status")->select()->toArray();
        $cateList = Db::name("product_category")->where(array("parent_id"=>array("gt",0)))->field("id,parent_id,name,list_order,description,status")->select()->toArray();
        $sonArr = $catArr = array();
        foreach ($cateList as $key => $value) {
            if($value["parent_id"]){
                $sonArr[$value["parent_id"]][] = $value;
            }
        }
        foreach ($cateListP as $kkk => $val) {
            if(empty($sonArr[$val["id"]])){
                $cateListP[$kkk]["sonArr"] = [];
            }else{
                $cateListP[$kkk]["sonArr"] = $sonArr[$val["id"]];
            }
        }
    
        $this->assign('cateListP', $cateListP);

        return $this->fetch();
    }

    //添加产品分类
    public function add()
    {
        $parent_id = input("parent_id");
        $this->assign("parent_id",$parent_id);
        $cateListP = Db::name("product_category")->where(array("parent_id"=>0))->field("id,name,list_order,description,status")->select()->toArray();
        $this->assign('cateListP', $cateListP);
        return $this->fetch();
    }

    //添加产品分类提交
    public function addPost()
    {
        
        $data["parent_id"] = input("parent_id",0);
        $data["name"] = input("name");
        $data["description"] = input("description");
        $data["seo_title"] = input("seo_title");
        $data["seo_keywords"] = input("seo_keywords");
        $data["seo_description"] = input("seo_description");
        
        $result = Db::name("product_category")->insert($data);
        if ($result === false) {
            $this->error('添加失败!');
        }
        $this->success('添加成功!', url('ProductCategory/index'));

    }

    //编辑产品分类
    public function edit()
    {

        $id = $this->request->param('id', 0, 'intval');
        if (empty($id)){
            $this->error('操作错误!');
        }
        $cateListP = Db::name("product_category")->where(array("parent_id"=>0))->field("id,name,list_order,description,status")->select()->toArray();
        $this->assign('cateListP', $cateListP);
        $result = Db::name("product_category")->where(array("id"=>$id))->find();
        $this->assign('result', $result);
        return $this->fetch();
    }

    //编辑产品分类提交
    public function editPost()
    {
        $data["id"] = input("id");
        $data["parent_id"] = input("parent_id",0);
        $data["name"] = input("name");
        $data["description"] = input("description");
        $data["seo_title"] = input("seo_title");
        $data["seo_keywords"] = input("seo_keywords");
        $data["seo_description"] = input("seo_description");
        
        $result = Db::name("product_category")->update($data);
        if ($result === false) {
            $this->error('保存失败!');
        }
        $this->success('保存成功!');
    }

    /**
     * 文章分类排序
     * @adminMenu(
     *     'name'   => '文章分类排序',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章分类排序',
     *     'param'  => ''
     * )
     */
    public function listOrder()
    {
        parent::listOrders(Db::name('product_category'));
        $this->success("排序更新成功！", '');
    }

    //分类显示隐藏
    public function toggle()
    {
        $data                = $this->request->param();

        if (isset($data['ids']) && !empty($data["display"])) {
            $ids = $this->request->param('ids/a');
            Db::name("product_category")->where(['id' => ['in', $ids]])->update(['status' => 1]);
            $this->success("更新成功！");
        }

        if (isset($data['ids']) && !empty($data["hide"])) {
            $ids = $this->request->param('ids/a');
            Db::name("product_category")->where(['id' => ['in', $ids]])->update(['status' => 0]);
            $this->success("更新成功！");
        }

    }

    //删除产品分类
    public function delete()
    {
       $id = input("id");
       if(empty($id)){
            $this->error("id不能为空");
       }
       $check = Db::name("product_category")->where(array("id"=>$id))->count();
       if(empty($check)){
            $this->error("分类不存在");
       }
       $checkP = Db::name("product_category")->where(array("parent_id"=>$id))->count();
       if($checkP){
            $this->error("该分类下有子分类，不可以删除");
       }
       $checkArt  = Db::name("product_category_post")->where(array("category_id"=>$id))->count();
       if($checkArt){
            $this->error("该分类下有已发布的产品，不可以删除");
       }
       Db::name("product_category")->where(array("id"=>$id))->delete();
       $this->success("删除成功");
    }
}
