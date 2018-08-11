<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/10/17
 * Time: 9:12
 */

namespace app\admin\validate;


use think\Validate;

class Link extends Validate{
    //验证规则
    protected $rule=[
        'name'=>'require',
        'url'=>'require',
    ];
    //提示消息
    protected $message=[
        'name.require'=>'请填写网站名称',
         'url.require'=>'请填写网站链接'
    ];
}