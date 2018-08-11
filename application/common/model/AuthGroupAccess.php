<?php

namespace app\common\model;

use think\Model;

class AuthGroupAccess extends Model{
    protected $table='blog_auth_group_access';

    /*
     * 给后台管理员分配权限
     */
    public function setauth($data){
        if(!isset($data['group_id'])){
            return ['valid'=>0,'msg'=>'请选择用户组'];
        }
        $adminId=db('auth_group_access')->where('uid',$data['admin_id'])->find();
        //如果auth_group_access中admin_id为$data['admin_id']的数据，就删除admin_id为$data['admin_id']的数据
        //也就是删除该用户所在的用户组，然后再重新给该用户添加新的用户组，分配权限
        if($adminId){
            db('auth_group_access')->where('uid',$data['admin_id'])->delete();
        }
        foreach ($data['group_id'] as $v){
            $model=new AuthGroupAccess();
            $model->uid=$data['admin_id'];
            $model->group_id=$v;
            $model->save();
        }
        return ['valid'=>1,'msg'=>'分配权限成功'];
    }
}
