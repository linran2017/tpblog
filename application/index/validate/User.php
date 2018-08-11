<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/10/31
 * Time: 10:02
 */

namespace app\index\validate;


use think\Validate;

class User extends Validate{
    //验证规则
    protected $rule=[
        'name'=>'require|email',
        'password'=>'require|min:6',
        'confirm_password'=>'require|confirm:password'
    ];
    //提示消息
    protected $message=[
        'name.require'=>'请填写邮箱',
        'name.email'=>'输入格式不正确',
        'password.require'=>'请填写密码',
        'password.min'=>'密码个数至少是6位',
        'confirm_password.require'=>'请确认密码',
        'confirm_password.confirm'=>'两次输入的密码不一致'
    ];
}