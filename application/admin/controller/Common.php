<?php

namespace app\admin\controller;

use think\auth\Auth;
use think\Controller;
use think\Request;

class Common extends Controller{
    /**
     * 公共控制器，如果没有登录后台就不能进入后台
     */
    public function __construct(Request $request = null){
        parent::__construct($request);
        //登录验证
        //如果没有session('admin.admin_id'),证明没有登录
        if(!session('admin.id')){
            //就跳转到后台登录页面
            $this->redirect('admin/login/login');
        }
        //权限验证
        //添加规则 模块 控制器 方法
        $rule=request()->module() . '/' . request()->controller() . '/' . request()->action();
        //halt($rule);
        //执行Auth类中的check方法进行权限验证
        $res=(new Auth())->check($rule,session('admin.id'));
        //如果没有权限就返回提示消息，返回当前页
        if(!$res){
            return $this->error('抱歉，您没有操作权限');
        }
}
}
