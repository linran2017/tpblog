<?php

namespace app\admin\controller;


use app\common\model\Admin;

class Entry extends Common {
    /*
     * 后台首页
     */
    public function index(){
        //加载后台首页
        return $this->fetch();
    }

    /**
     * 修改密码
     */
    public function pass(){
        //判断是否是post请求
        if(request()->isPost()){
            //input('post.')表示提交过来的数据
            //获得验证的结果
            $res=(new Admin())->pass(input('post.'));
            if($res['valid']){
                //修改成功，删除session中的管理员信息
                session(null);
                //跳转到后台首页，但是session信息被删除所以跳转到登录页面
                $this->success($res['msg'],'admin/entry/index');
            }else{
               //halt($_POST);
                $this->error($res['msg']);
            }
        }
        //加载修改密码页面
        return $this->fetch();
    }
}
