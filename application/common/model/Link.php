<?php

namespace app\common\model;

use think\Model;

class Link extends Model{
    protected $pk='id';
    protected $table='blog_link';
    /*
     * 添加友链
     */
    public function store($data){
        $result=$this->validate()->save($data);
        if($result){
            return ['valid'=>1,'msg'=>'添加成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }

    /*
     * 编辑友链
     */
    public function edit($data){
        $result=$this->validate(true)->save($data,[$this->pk=>$data['id']]);
        if($result){
            return ['valid'=>1,'msg'=>'编辑成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }

    /*
     * 删除友链
     */
    public function del($id){
        if (self::destroy($id)){
            return ['valid'=>1,'msg'=>'删除成功'];
        }else{
            return ['valid'=>0,'msg'=>'删除失败'];
        }
    }
}
