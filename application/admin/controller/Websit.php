<?php

namespace app\admin\controller;

use think\Controller;

class Websit extends Common {
    /*
     * 网站设置首页
     */
    public function index(){
        $data=db('websit')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 网站配置编辑
     */
    public function edit(){
        //halt($_POST);
        //如果请求方式是ajax
        if(request()->isAjax()){
            $db=new \app\common\model\Websit();
            $res=$db->edit(input('post.'));
            if($res['valid']){
                //验证成功
                $this->success($res['msg'],'index');
            }else{
                //验证失败
                $this->error($res['msg']);
            }
        }
    }
}
