<?php

namespace app\admin\controller;

use think\Controller;
use app\common\model\Group as GroupModel;
class Group extends Common {
    /*
     * 用户组列表
     */
    public function index(){
        $data=db('auth_group')->paginate(5);
        $this->assign('data',$data);
        return view('');
    }

    /*
     * 用户组添加
     */
    public function store(GroupModel $group){
        if(request()->isPost()){
           $res=$group->store(input('post.'));
           if($res['valid']){
               $this->success($res['msg'],'index');
           }else{
               $this->error($res['msg']);
           }
        }
        //获得规则
        $rules=$this->getRules();
        //halt($rules);
        return view('',compact('rules'));
    }

    /*
     * 获得规则
     */
    private function getRules(){
        $rules=[];
        $data=db('auth_rule')->select();
        foreach ($data as $v){
            //把规则表中规则nav相同的规则合在一起
            $rules[$v['nav']][]=$v;
        }
        return $rules;
    }

    /*
     * 用户组编辑
     */
    public function edit(GroupModel $group){
        $id=input('param.id');
        $oldData=db('auth_group')->where('id',$id)->find();
        //把字符串拆分为数组
        $oldData['rules']=explode(',',$oldData['rules']);
       // halt($oldData);
        if(request()->isPost()){
            $res=$group->edit(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');
            }else{
                $this->error($res['msg']);
            }
        }
        //获得规则
        $rules=$this->getRules();
        //halt($rules);
        return view('',compact('rules','oldData'));
    }

    /*
     * 用户组查看权限
     */
    public function detail(){
        //获得用户组编号
        $id=input('param.id');
        //在用户组表中查找id为$id的这一组用户组
        $oldData=db('auth_group')->where('id',$id)->find();
        //获得这一个用户组的规则编号，并且按','，把他们组合为数组
        $rules=explode(',',$oldData['rules']);
        //halt($rules);
        //先定义一个空数组，用来存储这一个用户组的规则
        $ruleData=[];
        //依次循环用户组的规则编号
        foreach ($rules as $v){
            //在规则表中查询编号为规则数组里的规则编号的数据，
            //并且把找得到这一条数据添加到用户组规则数组里
            $ruleData[]=db('auth_rule')->where('id',$v)->find();
        }
        //halt($ruleData);
        //再定义一个空数组，把规则导航字段相同的规则数据放在一起
        $rulesData=[];
        //依次循环该用户组的规则数据
        foreach ($ruleData as $k=>$v){
            //把规则导航字段相同的规则数据放在一起
            $rulesData[$v['nav']][]=$v;
        }
        //halt($rulesData);
        return view('',compact('oldData','rulesData'));
    }

    /*
     * 删除用户组
     */
    public function del(){
        $id=input('get.id');
        if(GroupModel::destroy($id)){
            $this->success('删除成功','index');
        }
    }
}
