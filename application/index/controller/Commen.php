<?php

namespace app\index\controller;

use think\Controller;
use think\Cookie;
use think\Request;

class Commen extends Controller{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        //获取配置项数据
        $websit=$this->websit();
        $this->assign('_websit',$websit);
        //获取顶级栏目数据
        $cateData=$this->cateData();
        $this->assign('_cateData',$cateData);
        //获取全部栏目
        $category=$this->category();
        $this->assign('_category',$category);
        //获取标签
        $tag=$this->tag();
        $this->assign('_tag',$tag);
        //获取友情链接
        $link=$this->link();
        $this->assign('_link',$link);
        //最新文章
        $newArc=$this->newArc();
        //halt($newArc);
        $this->assign('_newArc',$newArc);
        //用户名
        $this->assign('name',session('name'));
        //用js把当前页面 地址栏的网址存储到cookie里
$str=<<<str
<script>
document.cookie='url='+window.location.href;
</script>
str;
//在页面执行一次上面的js,为了存储地址栏的网址
echo $str;
        //再在php里获取cookie存储的网址
        $url=Cookie::get('url');
        //halt($url);
        //把当前的网址写入url.php文件里，为了登录时调用跳转
        file_put_contents('url.php',$url);
        //Cookie::delete('url');
        //halt(Cookie::get('name'));
    }


    /*
     * 获取网站配置项
     */
    private function websit(){
        return db('websit')->column('value','name');
    }

    /*
     * 获取栏目数据
     *
     */
    private function cateData(){
        return db('category')->where('pid',0)->select();
    }

    /*
     * 获取全部栏目
     */
    private function category(){
        return db('category')->select();
    }

    /*
     * 获取标签
     */
    private function tag(){
        return db('tag')->select();
    }

    /*
     * 友情链接
     */
    private function link(){
        return db('link')->select();
    }

    /*
     * 最新文章
     */
    private function newArc(){
        return db('article')
            ->where('isrecycle',0)
            ->order('sendtime desc')
            ->limit(2)->select();
    }
}
