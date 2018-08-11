<?php

namespace app\common\model;

use think\Model;

class Tag extends Model{
    //主键
    protected $pk='id';
    //表名
    protected $table='blog_tag';
    /*
     * 添加标签
     */
   public function store($data){
        //halt($data);
        //执行验证
        $result=$this->validate(true)->save($data);
        if($result){
            //验证成功
            return ['valid'=>1,'msg'=>'添加成功'];
        }else{
            //验证失败
            //halt($this->getError());
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }

    /*
     * 编辑标签
     */
    public function edit($data){
        //halt($data);
        $result=$this->validate(true)->save($data,[$this->pk=>$data['id']]);
        if($result){
            return ['valid'=>1,'msg'=>'编辑成功'];
        }else{
            //halt($this->getError());
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }

    /*
     * 删除标签
     */
    public function del($id){
        if(self::destroy($id)){
            return ['valid'=>1,'msg'=>'删除成功'];
        }else{
            return ['valid'=>0,'msg'=>'删除失败'];
        }
    }
}