<?php

namespace app\admin\controller;


class Tag extends Common{
    //tag模型
    protected $db;
    /*
     * 把tag模型赋值给$db
     * 当需要调用父级有构造函数时，就不能再写__construct，所以要用_initialize方法
     */
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->db=new \app\common\model\Tag();
    }

    /*
     * 标签列表
     */
    public function index(){
        // 查询tag表里的所有数据 并且每页显示5条数据
        $data=db('tag')->paginate(5);
        //halt($data);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 添加标签
     */
    public function store(){
        if(request()->isPost()){
            //halt(input('post.'));
           // halt($this->db);
            $res=$this->db->store(input('post.'));
            if($res['valid']){
                //添加成功，跳转列表页
                $this->success($res['msg'],'index');
            }else{
                //添加失败
                $this->error($res['msg']);
            }
        }
        return $this->fetch();
    }

    /*
     * 编辑标签
     */
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
        $data=db('tag')->where('id',$id)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 删除标签
     */
    public function del(){
        //halt(input('get.id'));
        $res=$this->db->del(input('get.id'));
        if($res['valid']){
            $this->success($res['msg'],'index');
        }else{
            $this->error($res['msg']);
        }
    }
}
