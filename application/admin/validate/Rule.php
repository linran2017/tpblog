<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/10/10
 * Time: 17:15
 */

namespace app\admin\validate;


use think\Validate;

class Rule extends Validate{
    //验证规则
    protected $rule=[
        'title'=>'require',
        'name'=>'require',
        'nav'=>'require'
    ];
    //提示消息
    protected $message=[
        'title.require'=>'请输入规则中文标识',
        'name.require'=>'请输入规则名称',
        'nav.require'=>'请输入导航',
    ];
}