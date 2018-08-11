<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/10/10
 * Time: 17:15
 */

namespace app\admin\validate;


use think\Validate;

class Admin extends Validate{
    //验证规则
    protected $rule=[
        'admin_username'=>'require',
        'admin_password'=>'require'
    ];
    //提示消息
    protected $message=[
        'admin_username.require'=>'请输入用户名',
        'admin_password.require'=>'请输入密码'
    ];
}