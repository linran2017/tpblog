<?php

namespace app\common\model;

use think\Model;

class Websit extends Model{
    protected $pk='id';
    protected $table='blog_websit';
    /*
     * 网站配置编辑
     */
    public function edit($data){
        //执行验证
        $result=$this->validate([
            'value'=>'require'
        ],[
            'value.require'=>'请填写配置值'
        ])->save($data,[$this->pk=>$data['id']]);
        if ($result){
            return ['valid'=>1,'msg'=>'编辑成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
}
