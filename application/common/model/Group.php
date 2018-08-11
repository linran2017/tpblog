<?php

namespace app\common\model;

use think\Model;

class Group extends Model{
    protected $table='blog_auth_group';
    protected $pk='id';

    /*
     * 用户组添加
     */
    public function store($data){
        //如果选择了规则就把数组拆分成字符串用','隔开，没有就是空字符串
        $data['rules']=isset($data['rules'])?implode(',',$data['rules']):'';
        //halt($data['rules']);
        $result=$this->validate(true)->save($data);
        if($result){
            return ['valid'=>1,'msg'=>'保存成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }

    /*
     * 用户组编辑
     */
    public function edit($data){
       // halt($data);
        //如果选择了规则就把数组拆分成字符串用','隔开，没有就是空字符串
        $data['rules']=isset($data['rules'])?implode(',',$data['rules']):'';
        //halt($data['rules']);
        $result=$this->validate(true)->save($data,[$this->pk=>$data['id']]);
        if($result){
            return ['valid'=>1,'msg'=>'保存成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
}
