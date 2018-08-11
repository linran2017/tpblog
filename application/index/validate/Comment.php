<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/11/2
 * Time: 9:52
 */

namespace app\index\validate;


use think\Validate;

class Comment extends Validate {
    //验证规则
    protected $rule=[
        'content'=>'require',
    ];
    //提示消息
    protected $message=[
        'content.require'=>'请填写评论内容',
    ];
}