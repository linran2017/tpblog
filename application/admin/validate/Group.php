<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/10/10
 * Time: 17:15
 */

namespace app\admin\validate;


use think\Validate;

class Group extends Validate{
    //验证规则
    protected $rule=[
        'title'=>'require',
        'rules'=>'require',
    ];
    //提示消息
    protected $message=[
        'title.require'=>'请输入用户组名称',
        'rules.require'=>'请选择规则',
    ];
}