<?php

namespace app\admin\controller;

use think\Controller;

class Article extends Common {
    protected $db;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->db=new \app\common\model\Article();
    }

    /*
     * 文章列表页
     */
    public function index(){
        //获取文章首页数据
        $data=$this->db->getAll(0);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 添加文章
     */
    public function store(){
        if(request()->isPost()){
            $res=$this->db->store(input('post.'));
            if($res['valid']){
                //添加成功
                $this->success($res['msg'],'index');
            }else{
                //添加失败
                $this->error($res['msg']);
            }
        }
        //获取所有栏目的树状结构
        $categoryData=(new \app\common\model\Category())->getAll();
        $this->assign('categoryData',$categoryData);
        //获取所有标签
        $tagData=db('tag')->select();
        $this->assign('tagData',$tagData);
        return $this->fetch();
    }

    //文章编辑
    public function edit(){
        if(request()->isPost()){
            $res=$this->db->edit(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');
            }else{
                $this->error($res['msg']);
            }
        }
        $id=input('param.id');
        //获取文章数据
        $articleData=db('article')->where('id',$id)->find();
        $this->assign('articleData',$articleData);
        //获取标签数据
        $categoryData=(new \app\common\model\Category())->getAll();
        $this->assign('categoryData',$categoryData);
        //获取栏目数据
        $tagData=db('tag')->select();
        $this->assign('tagData',$tagData);
        //获得文章标签中间表里的article_id为$id的这几组数据
        $arcicleTag=db('article_tag')->where('article_id',$id)->select();
        //halt($arcicleTag);
        //创建一个存储被选中标签的tag_id的数组
        $tagId=[];
        //把$arcicleTag数组里的tag_id字段（这篇文章所对应的标签）提取出来
        foreach ($arcicleTag as $v){
            //依次把被选中的标签tag_id追加到tag_id数组里
            //吧编辑页面，如果标签的id如果在tag_id数组里，就显示被选中
            $tagId[]=$v['tag_id'];
        }
        //halt($tagId);
        $this->assign('tagId',$tagId);
        return $this->fetch();
    }

    /*
     * 修改排序
     */
    public function changeSort(){
        if(request()->isAjax()){
            $res=$this->db->changeSort(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');
            }else{
                $this->error($res['msg']);
            }
        }
    }

    /*
     * 回收站列表
     */
    public function recycle(){
        //获取文章回收站的数据
        $data=$this->db->getAll(1);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 把文章放入回收站
     */
    public function toRecyce(){
        $id=input('get.id');
        $res=$this->db->toRecyce($id);
        if($res['valid']){
            $this->success($res['msg'],'index');
        }else{
            $this->error($res['msg']);
        }
    }

    /*
     * 恢复文章
     */
    public function back(){
        $id=input('param.id');
        $res=$this->db->back($id);
        if($res['valid']){
            $this->success($res['msg'],'index');
        }else{
            $this->error($res['msg']);
        }
    }

    /*
     * 文章正真的删除
     */
    public function del(){
        $id=input('get.id');
        $res=$this->db->del($id);
        if($res['valid']){
            $this->success($res['msg'],'index');
        }else{
            $this->error($res['msg']);
        }
    }
}