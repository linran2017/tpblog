<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/10/19
 * Time: 8:38
 */

namespace app\admin\validate;


use think\Validate;

class Article extends Validate {
    protected $rule=[
        'title'=>'require',
        'author'=>'require',
        'sort'=>'require|between:1,9999',
        'category_id'=>'notIn:0',
        //'tag_id'=>'require',
        'preview'=>'require',
        'abstract'=>'require',
        'content'=>'require',
    ];
    //提示消息
    protected $message=[
        'title.require'=>'请填写文章标题',
        'author.require'=>'请填写文章作者',
        'sort.require'=>'请填写文章排序',
        'sort.between'=>'文章排序必须在1到9999之间',
        'category_id.notIn'=>'请选择文章所属栏目',
       // 'tag_id.require'=>'请选择文章标签',
        'preview.require'=>'请上传图片',
        'abstract.require'=>'请填写文章摘要',
        'content.require'=>'请填写文章内容'
    ];
}