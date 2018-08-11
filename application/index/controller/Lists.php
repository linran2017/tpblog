<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/10/25
 * Time: 12:03
 */

namespace app\index\controller;


use app\common\model\Category;
use think\Controller;

class Lists extends Commen {
    public function index(){

        //halt(input('param.'));
        //如果地址栏参数有cate_id就是去栏目页
        if(input('param.cate_id')){
            //进入的是栏目页就显示栏目
            $head='栏目';
            //获得当前的栏目编号
            $cate_id=input('param.cate_id');
            //获得该栏目下的子栏目的id
            $category=new Category();
            $cateId=$category->getSonId(db('category')->select(),$cate_id);
            //把自己追加进去，获得自己和自己下的子栏目
            $cateId[]=$cate_id;
            //halt($cateId);
            //获得文章数据,文章表与栏目表关联
            $data=db('article')->alias('a')
                ->join('category c','c.id=a.category_id')
                //查询这些字段
                ->field('a.id,a.title,a.abstract,a.author,a.preview
                ,a.sendtime,a.category_id,c.name,a.category_id')
                //查询条件为文章表的栏目编号在栏目编号的数组中
                ->whereIn('a.category_id',$cateId)
                ->where('isrecycle',0)
                ->order('a.sort desc,a.sendtime desc')
                ->select();
            //halt($data);
            //获得栏目表中栏目编号为地址栏参数的栏目名称
            $ct=db('category')->field('name')->find($cate_id);
            //halt($ct);
            //halt($headData);
        }else{
            //进入的标签页，就显示标签
            $head='标签';
            //获得标签编号
            $tag_id=input('param.tag_id');
            //将文章表，中间表，栏目表关联
            $data=db('article')->alias('a')
                ->join('article_tag at','a.id=at.article_id')
                ->join('category c','c.id=a.category_id')
                //查询这些字段
                ->field('a.id,a.title,a.author,a.sendtime,a.category_id,a.abstract,
                a.preview,at.tag_id,c.name,a.category_id')
                //查询条件为中间表的tag_id为地址栏的参数即标签id
                ->where('at.tag_id',$tag_id)
                ->where('isrecycle',0)
                ->order('a.sort desc,a.sendtime desc')
                ->select();
            //获得标签表中id为地址栏参数的标签编号的标签名称
            $ct=db('tag')->field('name')->find($tag_id);
            //halt($ct);

        }
        //循环上面查询的文章数组
        foreach ($data as $k=>$v){
            //在每一组文章表数据里添加一个tags字段
            //中间表与标签表关联，关联条件是中间表的tag_id等于标签表的id
            $data[$k]['tags']=db('article_tag')->alias('at')
                ->join('tag t','at.tag_id=t.id')
                //查询条件为中间表的article_id等于这一条文章数据的id
                ->where('at.article_id',$v['id'])
                //查询标签表里的标签id，标签名称字段
                ->field('t.id,t.name')
                ->select();
        }
        //创建一个存储页面里需要的其他数组
        $headData=[
            //进入栏目页就是栏目，进入标签页就是标签
            'title'=>$head,
            //栏目名称或者标签名称
            'tc'=>$ct['name'],
            //统计文章数量
            'total'=>count($data)
        ];
        //页面title标签的内容
        $headConf=['title'=>'技术博客--'.$head.'页'];
        //halt($data);
        $this->assign('data',$data);

        //halt($headConf);
        $this->assign('headData',$headData);
        $this->assign('headConf',$headConf);
        return $this->fetch();
    }
}