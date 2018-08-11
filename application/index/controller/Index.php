<?php
namespace app\index\controller;

use think\Controller;

class Index extends Commen
{
    public function index(){
        //halt(session('email'));
        //首页title
        $headConf=['title'=>'技术博客--首页'];
        $this->assign('headConf',$headConf);
        //获取文章表数据，与栏目表关联,并且不在回收站里
        $articleData=db('article')->alias('a')
            ->join('category c','a.category_id=c.id')
            ->where('isrecycle',0)
            ->field('a.id,a.title,a.author,a.category_id,a.preview
            ,a.abstract,a.sendtime,c.name')
            ->order('a.sort desc','a.sendtime desc')
            ->limit('3')
            ->select();

        foreach ($articleData as $k=>$v){
            //在每一组文章表数据里添加一个tags字段
            $articleData[$k]['tags']=db('article_tag')->alias('at')
                ->join('tag t','at.tag_id=t.id')
                ->where('at.article_id',$v['id'])
                ->field('t.id,t.name')
                ->select();
        }
        //halt($articleData);
        $this->assign('articleData',$articleData);

        return $this->fetch();
    }
}
