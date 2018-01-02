<?php
/**
 * Created by PhpStorm.
 * User: zhuch
 * Date: 2017/8/7
 * Time: 15:23
 */
namespace Home\Controller;
use Think\Controller;

class HeaderController extends Controller {
    //显示验证码
    public function verifyImg(){
    	ob_clean();
        $verify = new \Think\Verify();
        //$verify->useZh = true;  //使用中文验证码
        $verify->length = 4;
        $verify->entry();
    }

//    // 检测验证码是否正确
//    public function checkVerify($code, $id) {
//
//    }
}