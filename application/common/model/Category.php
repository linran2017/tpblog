<?php

namespace app\common\model;

use houdunwang\arr\Arr;
use think\Model;

class Category extends Model{
    //主键
    protected $pk='id';
    //表名
    protected $table='blog_category';
    /*
     * 获得分类数据（树状结构）
     */
    public function getAll(){
        return Arr::tree(db('category')->order('sort desc,id')->select(), 'name', $fieldPri = 'id', $fieldPid = 'pid');
    }

    /*
     * 数据添加
     */
    public function store($data){
        //1执行验证
        // 调用当前模型对应的User验证器类进行数据验证
        $result = $this->validate(true)->save($data);
        if(false === $result){
            return ['valid'=>0,'msg'=>$this->getError()];
            // 验证失败 输出错误信息
            dump($this->getError());
        }else{
            return ['valid'=>1,'msg'=>'添加成功'];
        }
        //2添加数据
    }

    /*
     * 处理所属分类栏目
     */
    public function getCateData($id){
        //halt(db('category')->select());
        //调用该类下的getSon方法，
        //获得该栏目下面的子栏目的id，传递栏目表里的所有数据和当前栏目的id
        $cateId=$this->getSonId(db('category')->select(),$id);
        //halt($cateId);
        //把该栏目的id添加到子栏目的集合里
        $cateId[]=$id;
        //halt($cateId);
        //获得除了子栏目和自己之外的所有栏目
        $cateData=db('category')->whereNotIn('id',$cateId)->select();
        //halt($cateData);
        //获得树状结构
        return Arr::tree($cateData,'name','id','pid');
    }

    /*
     * 获得子类栏目的id
     */
    public function getSonId($data,$id){
        //调用一个静态变量，用来存储所有子集栏目id的数组
        static $temp=[];
        //循环栏目表里所有的数据
        foreach ($data as $v){
            //如果循环的这一条栏目数据的pid等于传递的id，那么这一组栏目就是传递该id所属栏目的子栏目
            if($id==$v['pid']){
                //就把子集栏目的id添加到用来存储所有子集栏目id的数组
                $temp[]=$v['id'];
                //采用递归函数，依次找出下一层栏目的子栏目
                $this->getSonId($data,$v['id']);
            }
        }
        //最后返回该栏目下的子集栏目所有id组成的数组
        return $temp;
    }

    /*
     * 编辑栏目
     */
    public function edit($data){
        //halt($data);
        //1执行验证
        // 调用当前模型对应的User验证器类进行数据验证
        //更新id为$id的这一条数据
        $result = $this->validate(true)->save($data,[$this->pk=>$data['id']]);
        if ($result){
            //执行成功
            return ['valid'=>1,'msg'=>'编辑成功'];
        }else{
            //执行失败,返回失败提示
            //halt($this->getError());
            return ['vaild'=>0,'msg'=>$this->validate($this->getError())];
        }
    }

    /*
     * 删除栏目
     */
    public function del($id){
        //获得要删除这一条数据的pid
        $pid=db('category')->where('id',$id)->value('pid');
        //halt($pid);
        //把该栏目的下一级的子栏目的pid改为该栏目的pid
        $this->where('pid',$id)->update(['pid'=>$pid]);
        //删除id为$id的这一个栏目的数据
        if(self::destroy($id)){
            return ['valid'=>1,'msg'=>'删除成功'];
        }else{
            return ['valid'=>0,'msg'=>'删除失败'];
        }
    }
}
