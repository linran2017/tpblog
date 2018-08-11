<?php

namespace app\admin\controller;

use app\common\model\Admin;
use houdunwang\crypt\Crypt;
use think\captcha\Captcha;
use think\Controller;

class Login extends Controller{
    /**
     * @return array
     */
    public function login(){
        //echo Crypt::encrypt('admin888');//加密 h3vPU8JGuF3VS/uxIpjRSw==
        //echo Crypt::decrypt('h3vPU8JGuF3VS/uxIpjRSw==');//测试解密 结果：admin888
        //测试数据库链接
        //$data=db('admin')->find(1);
        //dump($data);
        //判断是否是post请求方式
        if(request()->isPost()){
            //input('post.')表示提交过来的数据
            //获得验证的结果
            $res=(new Admin())->login(input('post.'));

            //如果验证通过
            if($res['valid']){
                //说明登录成功,跳转到后台首页
                $this->success($res['msg'],'admin/entry/index');
            }else{
                //否则验证失败
                $this->error($res['msg']);
            }
        }
        /*$captcha=new Captcha();
        $captcha->fontSize = 30;
        $captcha->length   = 3;
        $captcha->useNoise = false;
        return $captcha->entry();*/
        //加载后台登录模板
        return $this->fetch();
    }

    /*
     * 退出登录
     */
    public function logout(){
        session(null);
        $this->success('退出成功','admin/entry/index');
    }
}
