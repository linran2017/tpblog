<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/10/25
 * Time: 12:39
 */

namespace app\index\controller;


use think\Controller;
use think\Request;

class Content extends Commen {
    public function index(){
        //获取地址栏中的参数，即文章编号
        $id=input('param.arc_id');
        if(request()->isPost()){
            if(!session('id')){
                $this->error('请先登录账户，再发表评论','index/login/login');
            }
            $comment=new \app\common\model\Comment();
            //input('post.')['article_id']=$id;
            //halt(input('post.'));
            $res=$comment->send(input('post.'),$id);
            if($res['valid']){
                $this->success($res['msg']);
            }else{
                $this->error($res['msg']);
            }
        }
        //文章点击数自增1
        db('article')->where('id',$id)->setInc('click');
        //halt($id);
        //获取文章表中id为$id的这一组数据
        $contentData=db('article')->alias('a')->find($id);
        //给这一条文章数据中添加tags字段，吧标签表和文章标签中间表关联
        $contentData['tags']=db('tag')->alias('t')
            //关联条件是标签表的编号等于中间表的标签编号
            ->join('article_tag at','t.id=at.tag_id')
            //查询标签表中的标签名和标签编号字段
            ->field('t.name,t.id')
            //查询条件为中间表里的文章编号等于这一条文章数据的编号
            ->where('article_id',$contentData['id'])
            ->select();
        //halt($contentData);
        //评论数据
        $commentData=db('comment')->alias('c')
            ->join('user u','c.user_id=u.id')
            ->where('c.article_id',$id)
            ->order('c.sendtime desc')
            ->field('u.name,c.content,c.sendtime')->select();
        //halt($commentData);
        $this->assign('contentData',$contentData);
        //文章页title
        $headConf=['title'=>$contentData['title']];
        $this->assign('headConf',$headConf);
        $this->assign('commentData',$commentData);
        return $this->fetch();
    }
}