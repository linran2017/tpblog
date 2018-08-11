<?php

namespace app\common\model;

use houdunwang\arr\Arr;
use think\Model;

class Rule extends Model{
    protected $table='blog_auth_rule';
    protected $pk='id';
    /*
     * 添加规则
     *
     */
    public function store($data){
        if($this->validate(true)->save($data)){
            return ['valid'=>1,'msg'=>'添加成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
    /*
     * 编辑规则
     */
    public function edit($data){
        //halt($data);
        if($this->validate(true)->save($data,[$this->pk=>$data['id']])){
            return ['valid'=>1,'msg'=>'保存成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }

    /*
     *找到子类和自己以外的规则
     */
    public function getRuelData($id){
        //1找到子类
        $data=db('auth_rule')->select();
        $ids=$this->getId($data,$id);
        //halt($ids);
        //2把自己追加进去
        $ids[]=$id;
        //3排除自己和子类
        $data=db('auth_rule')->whereNotIn('id',$ids)->select();
        //halt($data);
        return Arr::tree($data,'title','id','pid');
    }
    private function getId($data,$id){
        static $temp=[];
        foreach ($data as $v){
            if($v['pid']==$id){
                $temp[]=$v['id'];
                $this->getId($data,$v['id']);
            }
        }
        return $temp;
    }

    //删除规则
    public function del($id){
        //如果该规则下有子规则，不能删除
       $result=db('auth_rule')->where('pid',$id)->select();
       if($result){
           return ['valid'=>0,'msg'=>'请先删除子规则'];
       }
        if(self::destroy($id)){
            return ['valid'=>1,'msg'=>'删除成功'];
        }else{
            return ['valid'=>0,'msg'=>'删除失败'];
        }
    }
}
