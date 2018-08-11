<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/10/14
 * Time: 10:31
 */

namespace app\admin\validate;


use think\Validate;

class Tag extends Validate {
    //验证规则
    protected $rule=[
        'name'=>'require',
    ];
    //提示消息
    protected $message=[
        'name.require'=>'请填写标签名称'
    ];
}