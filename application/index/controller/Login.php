<?php

namespace app\index\controller;

use app\common\model\User;
use houdunwang\crypt\Crypt;
use think\Controller;

class Login extends Controller{
    public function login(){
        //获得url.php文件里的内容，获得登录页面中上一个页面的地址栏地址
        $url=file_get_contents('url.php');
        //halt($url);
        if(request()->isPost()){
            $user=new \app\common\model\User();
            $res=$user->login(input('post.'));

            if($res['valid']){
                //登录成功后跳转到上一次的页面
                $this->success($res['msg'],$url);
            }else{
                $this->error($res['msg']);
            }
        }
        return $this->fetch();
    }
    /*
     * 退出登录
     *
     */
    public function logout(){
        //退出登录，清除session
        session(null);
        //跳转到登录页面
        $this->success('退出成功','index/login/login');
    }
}
