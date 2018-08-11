<?php

namespace app\admin\controller;

use app\common\model\AuthGroupAccess;
use houdunwang\crypt\Crypt;
use think\Controller;
use app\common\model\Admin as AdminModel;
class Admin extends Common {
    /*
     * 后台管理员首页
     */
    public function index(){
        $data=db('admin')->select();
        foreach ($data as $k=>$v){
           $group=db('auth_group_access')->alias('ga')
                ->join('auth_group g','ga.group_id=g.id')
                ->where('ga.uid',$v['id'])->column('title');
           $data[$k]['group']=implode('，',$group);
        }
        //halt($data);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 后台用户管理员添加
     */
    public function store(AdminModel $admin){
        if(request()->isPost()){
            $res=$admin->store(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');
            }else{
                $this->error($res['msg']);
            }
        }
        return $this->fetch();
    }

    /*
     * 后台有管理员编辑
     */
    public function edit(AdminModel $admin){
        $id=input('param.id');
        if(request()->isPost()){
            $res=$admin->edit(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');
            }else{
                $this->error($res['msg']);
            }
        }
        //找到要编辑的这一条数据
        $data=AdminModel::find($id);
        //将数据库中的密码解密后显示在页面中
        $data['admin_password']=Crypt::decrypt($data['admin_password']);
        //显示模板，添加数据
        return view('',compact('data'));
    }

    /*
     * 删除后台管理员
     */
    public function del(AdminModel $admin){
        $id=input('get.id');
        $res=$admin->del($id);
        if($res['valid']){
            $this->success($res['msg'],'index');
        }else{
            $this->error($res['msg']);
        }
    }

    /*
     * 给用户分配权限
     */
    public function setauth(AuthGroupAccess $authGroupAccess){
        $id=input('param.id');
        if(request()->isPost()){
            $res=$authGroupAccess->setauth(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');
            }else{
                $this->error($res['msg']);
            }
        }
        //获得要分配权限的管理员信息
        $adminInfo=db('admin')->where('id',$id)->find();
        //获得所有用户组
        $groupData=db('auth_group')->select();
        //获得该用户所在的用户组的group_id
        $groupIds=db('auth_group_access')->where('uid',$id)->column('group_id');
        //halt($groupIds);
        return view('',compact('groupData','adminInfo','groupIds'));
    }
}