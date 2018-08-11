<?php

namespace app\common\model;

use houdunwang\crypt\Crypt;
use think\Loader;
use think\Model;
use think\Validate;

class Admin extends Model{
    protected $pk='id';//主键
    protected $table='blog_admin';//当前模型所对应的表名
    /**
     * 登录
     */
    public function login($data){
       //执行验证
        $validate = Loader::validate('Admin');

        //如果验证不通过
        if(!$validate->check($data)){
            return ['valid'=>0,'msg'=>$validate->getError()];
        }
        //比对用户名和密码是否比配
        $userInfo=$this->where('admin_username',$data['admin_username'])->where('admin_password',Crypt::encrypt($data['admin_password']))->find();
       // halt($userInfo);
        //如果输入的用户名或者密码不和admin的表一样就证明输入错误
        if(!$userInfo){
            return ['valid'=>'0','msg'=>'用户名或密码错误'];
        }
        //把用户信息存入到session里
        session('admin.admin_username',$userInfo['admin_username']);
        session('admin.id',$userInfo['id']);
        //登录成功提示
        return ['valid'=>1,'msg'=>'登录成功'];
    }

    /**
     * 修改密码
     */
    public function pass($data){
        //halt($data);
        //1执行验证
        $validate = new Validate([
            'admin_password'  => 'require',
            'new_password' => 'require',
            'confirm_password' => 'require|confirm:new_password'
        ],  [
                'admin_password.require'  => '请输入原密码',
                'new_password.require'  => '请输入新密码',
                'confirm_password.require'  => '请确认新密码',
                'confirm_password.confirm'  => '确认密码与新密码不一致',
            ]);
        //如果不通过验证
        if (!$validate->check($data)) {

            //返回提示消息
            return ['valid'=>0,'msg'=>$validate->getError()];
        }
        //2验证原密码是否正确
        $userinfo=$this->where('id',session('admin.id'))->where('admin.admin_password',Crypt::encrypt($data['admin_password']))->find();
        //halt($userinfo);
        if(!$userinfo){
            return ['valid'=>0,'msg'=>'原密码输入错误'];
        }
        //3更新密码
        // save方法第二个参数为更新条件
        //找到admin表里主键为session('admin.id')的这一条数据，把原密码更改为新密码
        $result=$this->save([
            'admin_password'  => Crypt::encrypt($data['new_password']),
        ],[$this->pk => session('admin.id')]);
        if($result){
            return ['valid'=>1,'msg'=>'修改成功'];
        }
    }

    /*
     * 添加后台管理员用户
     */
    public function store($data){
        //halt($data);
        //表单验证
        $validate=new Validate([
            'admin_username'=>'require',
            'admin_password'=>'require'
        ],[
            'admin_username.require'=>'请填写用户名',
            'admin_password.require'=>'请填写密码'
        ]);
        //如果没有通过验证，就提示错误信息
        if(!$validate->check($data)){
            return ['valid'=>0,'msg'=>$validate->getError()];
        }
        //如果用户表中有相同的用户名，就提示不能重复添加
        $userinfo=db('admin')->where('admin_username',$data['admin_username'])->select();
        if($userinfo){
            return ['valid'=>0,'msg'=>'该管理员已存在，请勿重复添加'];
        }
        //把添加的用户名和密码赋值给管理员用户模型的相应的字段的键值，
        $this->admin_username=$data['admin_username'];
        //密码加密后再添加到数据表中
        $this->admin_password=Crypt::encrypt($data['admin_password']);
        //把用户提交的数据添加到用户表中
        $this->save();
        return ['valid'=>1,'msg'=>'添加成功'];
    }

    /*
     * 后台有管理员编辑
     */
    public function edit($data){
        //halt($data);
        //表单验证
        $validate=new Validate([
            'admin_username'=>'require',
            'admin_password'=>'require'
        ],[
            'admin_username.require'=>'请填写用户名',
            'admin_password.require'=>'请填写密码'
        ]);
        //没有通过验证就提示错误消息
        if(!$validate->check($data)){
            return ['valid'=>0,'msg'=>$validate->getError()];
        }
        //除了要编辑的在一条数据之外，所填写的用户名都不能与其他的用户名相同，否则就提示不能重复添加
    $userinfo=db('admin')->where("id!={$data['id']}")
            ->where('admin_username',$data['admin_username'])->select();
        if($userinfo){
            return ['valid'=>0,'msg'=>'该管理员已存在，请勿重复添加'];
        }
        //找到编辑的这一条数据
        $admin=$this->find($data['id']);
        //把用户提交的用户名和密码分别赋值给这一条数据的用户名和密码
        $admin->admin_username=$data['admin_username'];
        //添加的密码加密后存储到数据库里
        $admin->admin_password=Crypt::encrypt($data['admin_password']);
        //把这一条数据的用户名和密码更改为用户提交的用户名和密码
        $admin->save();
        //修改成功
        return ['valid'=>1,'msg'=>'修改成功'];
    }

    /*
     * 删除后台管理员用户
     */
    public function del($id){
        if(self::destroy($id)){
            return ['valid'=>1,'msg'=>'删除成功'];
        }else{
            return ['valid'=>1,'msg'=>'删除失败'];
        }
    }
}
