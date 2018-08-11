<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/10/12
 * Time: 10:36
 */

namespace app\admin\validate;


use think\Validate;

class Category extends Validate {
    //验证规则
    protected $rule=[
        'name'=>'require',
        'sort'=>'require|number|between:0,999'
    ];
    //提示消息
    protected $message=[
        'name.require'=>'请输入栏目名称',
        'sort.require'=>'请输入栏目排序',
        'sort.number'=>'栏目排序必须是数字',
        'sort.between'=>'栏目排序必须在0到999之间'
    ];
}