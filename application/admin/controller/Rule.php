<?php

namespace app\admin\controller;

use houdunwang\arr\Arr;
use think\Controller;
use app\common\model\Rule as RuleModel;
class Rule extends Common {
    /*
     * 规则首页
     *
     */
    public function index(){
        $data=db('auth_rule')->select();
        $data=Arr::tree($data,'title','id','pid');
        return view('',compact('data'));
    }

    /*
     * 增加规则
     */
    public function store(RuleModel $rule){
        if(request()->isPost()){
            $res=$rule->store(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');
            }else{
                $this->error($res['msg']);
            }
        }
        $ruleData=db('auth_rule')->select();
        $ruleData=Arr::tree($ruleData,'title','id','pid');
        return view('',compact('ruleData'));
    }

    /*
     * 编辑规则
     */
    public function edit(RuleModel $rule){
        $id=input('param.id');
        if(request()->isPost()){
            $res=$rule->edit(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');
            }else{
                $this->error($res['msg']);
            }
        }
        //halt($id);
        //把自己和子类排除在外
        $ruleData=$rule->getRuelData($id);
        //获取旧数据
        $oldData=db('auth_rule')->where('id',$id)->find();
        //halt($oldData);
        return view('',compact('ruleData','oldData'));
    }

    /*
     * 删除规则
     */
    public function del(RuleModel $rule){
        $id=input('get.id');
        $res=$rule->del($id);
        if($res['valid']){
            $this->success($res['msg'],'index');
        }else{
            $this->error($res['msg']);
        }
    }
}
