<?php

namespace app\common\model;

use think\Model;

class Comment extends Model{
    protected $table='blog_comment';
    protected $pk='comment_id';
    //自动添加用户id
    protected $auto=['user_id'];
    //自动添加发表时间
    protected $insert=['sendtime'];
    protected function setUserIdAttr(){
        return session('id');
    }
    protected function setSendTimeAttr(){
        return time();
    }

    /*
     * 添加评论
     */
    public function send($data,$id){
        //给提交的数组添加article_id字段
        $data['article_id']=$id;
        //halt($data);
        $result=$this->validate(true)->save($data);
        if($result){
            return ['valid'=>1,'msg'=>'发表评论成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
}
