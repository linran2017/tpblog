<?php

namespace app\system\controller;

use think\Controller;
use think\Db;

class Component extends Controller{
    //上传文件
    public function uploader ()
    {
        //halt($_FILES);
        //不转义
        //echo json_encode(HD_ROOT,JSON_UNESCAPED_SLASHES);

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        //halt(ROOT_PATH);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move( ROOT_PATH . 'public' . DS . 'uploads' );
        //halt($info->getSaveName());
        if ( $info ) {
        $data = [
            'name'       => input( 'post.name' ) ,
            'filename'   => $info->getFilename() ,
            'path'       => 'uploads/' . $info->getSaveName() ,
            'extension'  => $info->getExtension() ,
            'createtime' => time() ,
            'size'       => $info->getSize() ,
        ];
        //halt($data);
        Db::name( 'attachment' )->insert( $data );
        $json=json_encode( [ 'valid' => 1 , 'message' => HD_ROOT.'uploads/'.$info->getSaveName()]);
        //return [ 'valid' => 1 , 'message' => HD_ROOT.'uploads/'.$info->getSaveName()];
    }
    else {
        // 上传失败获取错误信息
        $json=json_encode( [ 'valid' => 0 , 'message' => $file->getError() ] );
    }
        exit($json);
    }

    //获取文件列表
   public function filesLists ()
    {
        $db   = Db::name( 'attachment' )->whereIn( 'extension' , explode( ',' , strtolower( input( "post.extensions" ) ) ) )->order( 'id desc' );
        $Res  = $db->paginate( 32 );
        $data = [];
        if ( $Res->toArray() ) {
            //dump($Res->toArray());die;
            foreach ( $Res as $k => $v ) {
                $data[ $k ][ 'createtime' ] = date( 'Y/m/d' , $v[ 'createtime' ] );
                $data[ $k ][ 'size' ]       = $v[ 'size' ];
                $data[ $k ][ 'url' ]        = HD_ROOT . $v[ 'path' ];
                $data[ $k ][ 'path' ]       = HD_ROOT . $v[ 'path' ];
                $data[ $k ][ 'name' ]       = $v[ 'name' ];
            }
        }
        echo json_encode( [ 'data' => $data , 'page' => $Res->render() ? : '' ] );
    }


}
?>