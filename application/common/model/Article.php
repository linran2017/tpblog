<?php

namespace app\common\model;

use think\Model;

class Article extends Model{
    protected $pk='id';
    protected $table='blog_article';
    //自动添加管理员id
    protected $auto=['admin_id'];
    //自动添加文章发表时间
    protected $insert=['sendtime'];
    //自动添加文章更新数据
    protected $update=['updatetime'];
    protected function setAdminIdAttr(){
        return session('admin.id');
    }
    protected function setSendTimeAttr(){
        return time();
    }
    protected function setUpdateTimeAttr(){
        return time();
    }

    /*
     * 获取文章首页数据
     */
    public function getAll($num){
        //给文章表起别名
        return db('article')->alias('a')
            //与栏目关联，起别名，关联条件为a.category_id=c.id
            ->join('category c','a.category_id=c.id')
            //查询不在回收站的文章
            ->where('a.isrecycle',$num)
            //查询字段为文章表的标题，作者，发布时间，排序以及栏目表的栏目名称
            ->field('a.id,a.title,a.author,a.sendtime,a.sort,c.name')
            //依次按排序，发布时间，文章标题降序排序
            ->order('a.sort desc,a.sendtime desc,a.id desc')
            //每页显示5条数据
            ->paginate(5);
        //halt($res);
    }

    /*
   *文章添加
   */
    public function store($data){
        //halt($data);
        if(!isset($data['tag_id'])){
            return ['valid'=>0,'msg'=>'请选择文章标签'];
        }
        //过滤掉添加的标签字段，把提交的其他数据添加到文章表里
        //allowField(true) 过滤post数组中的非数据表字段数据
        $result=$this->validate(true)->allowField(true)->save($data);
        if($result){
            //添加标签id到article_tag中间表
            foreach ($data['tag_id'] as $v){
                $articleTagData=[
                    'article_id'=>$this->id,
                    'tag_id'=>$v
                ];
                $articleTag=new ArticleTag();
                $articleTag->save($articleTagData);
            }
            return ['valid'=>1,'msg'=>'添加成功'];
        }else{
            //halt(time());
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }

    /*
     * 改变排序
     */
    public function changeSort($data){
        //halt($data);
        $result=$this->validate([
            'sort'=>'between:1,9999'
        ],[
            'sort.between'=>'文章排序必须在1到9999之间'
        ])->save($data,[$this->pk=>$data['id']]);
        if($result){
            return ['valid'=>1,'msg'=>'排序成功'];
        }else{
            //halt($this->getError());
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }

    /*
     * 文章编辑
     */
    public function edit($data){
        //halt($data['id']);
        //halt($data['tag_id']);
        if(!isset($data['tag_id'])){
            return ['valid'=>0,'msg'=>'请选择文章标签'];
        }
        $result=$this->validate(true)->allowField(true)->save($data,[$this->pk=>$data['id']]);
        if($result){
            //实例化中间表模型
            $articleTag=new ArticleTag();
            //删除中间表中article_id为要删除这篇文章编号的数据
            $articleTag->where('article_id',$data['id'])->delete();
            //循环提交过来的标签tag_id数组里的每一个键值，即所选中的标签
            foreach ($data['tag_id'] as $v){
                //创建一个存储tag_id,article_id的数组
                $articleTagData=[
                    'tag_id'=>$v,
                    'article_id'=>$data['id']
                ];
                //依次把tag_id,article_id添加到中间表里
                (new ArticleTag())->save($articleTagData);
            }
            return ['valid'=>1,'msg'=>'编辑成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }

    /*
     * 把文章放入回收站
     */
    public function toRecyce($id){
        //把id为$id的这一篇文章的isrecycle的值改为1，放入到回收站
        if($this->where('id',$id)->update(['isrecycle'=>1])){
            return ['valid'=>1,'msg'=>'放进回收站成功'];
        }else{
            return ['valid'=>0,'msg'=>'放进回收站失败'];
        }
    }

    /*
   * 把文章放入回收站
   */
    public function back($id){
        //把id为$id的这一篇文章的isrecycle的值改为0，放入到列表页
        if($this->where('id',$id)->update(['isrecycle'=>0])){
            return ['valid'=>1,'msg'=>'恢复文章成功'];
        }else{
            return ['valid'=>0,'msg'=>'恢复文章失败'];
        }
    }

    /*
     * 文章正真的删除
     */
    public function del($id){
        $articleTag=new ArticleTag();
        //删除中间表里article_id为$id的标签关联数据和文章表里id为$id的数据
        if($articleTag->where('article_id',$id)->delete() && self::destroy($id)){
            return ['valid'=>1,'msg'=>'删除成功'];
        }else{
            return ['valid'=>0,'msg'=>'删除失败'];
        }
    }
}


