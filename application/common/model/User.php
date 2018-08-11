<?php

namespace app\common\model;

use houdunwang\crypt\Crypt;
use think\Model;
use think\Validate;

class User extends Model{
    protected $table='blog_user';
    /*
     * 用户注册
     */
    public function reg($data){
        //如果在用户表里查到有相同的邮箱，证明该用户已存在
        $email=db('user')->where('name',$data['name'])->find();
        if($email){
            return ['valid'=>0,'msg'=>'该用户已存在'];
        }
        //如果没有输入验证码，就提示输入
        if(!$data['captcha']){
            return ['valid'=>0,'msg'=>'请输入验证码'];
        }
        //验证码比配
        if(session('captcha')!==$data['captcha']){

            return ['valid'=>0,'msg'=>'验证码输入错误'];
        }
        //输入的邮箱要与接收的邮箱一致
        if(session('email')!==$data['name']){
            return ['valid'=>0,'msg'=>'邮箱输入错误'];
        }
        //5分钟后超时
        if(session('savetime')+300<time()){
            return ['valid'=>0,'msg'=>'已超时'];
        }
        //密码加密后存入数据库里
        $data['password']=Crypt::encrypt($data['password']);
        //因为要比对确认密码，所以确认密码也有加密
        $data['confirm_password']=Crypt::encrypt($data['confirm_password']);
        //halt($data['password']);
        $result=$this->validate(true)->allowField(true)->save($data);
        //halt(db('user')->where('email',$data['email'])->select());
        if(!$result){
            return ['valid'=>0,'msg'=>$this->getError()];
        }

        return ['valid'=>1,'msg'=>'注册成功'];
    }

    /*
     * 用户登录
     */
    public function login($data){
        $validate=new Validate([
            'name'=>'require|email',
            'password'=>'require'
        ],[
            'name.require'=>'请填写用户邮箱',
            'name.email'=>'邮箱格式不正确',
            'password.require'=>'请填写用户密码'
        ]);
        //验证不通过，提示验证消息
        if(!$validate->check($data)){
            return ['valid'=>0,'msg'=>$validate->getError()];
        }
        //如果在用户表中没有查找到用户输入的用户名，提示用户不存在
        $email=db('user')->where('name',$data['name'])->select();
        if(!$email){
            return ['valid'=>0,'msg'=>'该用户不存在'];
        }
        //如果用户输入的密码加密后和用户表中这一个用户名相同的这一条数据
        //密码不相等，就提示密码错误
        $userinfo=$this->where('name',$data['name'])->where('password',Crypt::encrypt($data['password']))->select();
        if(!$userinfo){
            return ['valid'=>0,'msg'=>'密码输入错误'];
        }
        //halt($email[0]);
        //把用户的用户名和用户id传入到session里
        session('name',$data['name']);
        session('id',$email[0]['id']);
        //登录成功
        return ['valid'=>1,'msg'=>'登录成功'];
    }

}
