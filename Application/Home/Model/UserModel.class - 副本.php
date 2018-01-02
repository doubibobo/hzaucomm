<?php
/**
 * Created by PhpStorm.
 * User: zhuch
 * Date: 2017/8/7
 * Time: 11:15
 */

namespace Home\Model;
use Think\Model;

class UserModel extends Model {

    //protected $connection='DB_CONFIG1';
    protected $connection= array('DB_CONFIG1', 'DB_CONFIG');

    // 验证登录信息
    public function checkName($name, $pwd) {
        // 学生用户表
        $studentUser = M("Student", "tp_", "DB_CONFIG1");
       // $studentUser = M("Student");

        // 普通用户表
        $ordinaryUser = M("User", "tp_", "DB_CONFIG1");
        //$ordinaryUser = M("User");

        $info = $studentUser->where("stuser = \"$name\"")->find();
        if ($info != null) {
            // 验证密码
            if ($info['stpwd'] == md5($pwd)) {
                $info['username'] = $info['stuser'];
                $info['usertype'] = 1;
                // 学生用户
                session_start();
                $_SESSION["usertype"] = 1;
                return $info;
            } else {
                return false;
            }
        } else {
            $info = $ordinaryUser->where("username = \"$name\"")->find();
            if ($info != null) {
                if ($info['pwd'] == md5($pwd)) {
                    $info['usertype'] = 0;
                    // 普通用户
                    session_start();
                    $_SESSION["usertype"] = 0;
                    return $info;
                } else {
                    return false;
                }
            }
            return false;
        }
    }

    // 当用户勾选记住我
    public function saveRemember($uid, $identifier, $token, $timeout) {
        $stUser = M("Studentlog", "tp_", "DB_CONFIG1");
        //$stUser = M("Studentlog");

        $data['identifier'] = $identifier;
        $data['token'] = $token;
        $data['timeout'] = $timeout;
        $data['stid'] = $uid;
        $where = "stid = ".$uid;
        $result = $stUser->data($data)->where($where)->save();
        return $result;
    }

    // 验证用户是否是永久登录用户
    public function checkRemember() {
        $arr = array();
        $now = time();
        list($identifier, $token) = explode(':', $_COOKIE['auth']);
        if (ctype_alnum($identifier) && ctype_alnum($token)) {
            $arr['identifier'] = $identifier;
            $arr['token'] = $token;
        } else {
            return false;
        }

        $stUser = M("Studentlog", "tp_", "DB_CONFIG1");
        //$stUser = M("Studentlog");

        $info = $stUser->where("identifier = $identifier")->find();
        if ($info != null) {
            if ($arr['token'] != $info['token']) {
                return false;
            } elseif ($now > $info['timeout']) {
                return false;
            } else {
                return $info;
            }
        } else {
            return false;
        }
    }

    // 注册时验证用户名
    public function checkUsername($username) {
        $student = M("Student", "tp_", "DB_CONFIG1");
        // $student = M("Student");

        $where['stuser'] = $username;
        $count = $student->where($where)->count();
        return $count ? false : true;
    }

    // 注册时验证密码是否一致
    public function checkRepeatPwd($pwd, $repwd) {
        if (preg_match("/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,15}$/", $pwd)){
            if ($pwd === $repwd) {
                return true;
            } else {
                alertMessage("两次的密码不一致");
                return false;
            }
        } else {
            alertMessage("密码由数字和大小写字母组成(6-15位)");
            return false;
        }
    }

    // 当普通用户点击注册
    public function doRegister($info) {
        $ordinary = M("Student", "tp_", "DB_CONFIG1");
        // $ordinary = M("Student");

        $result = $ordinary -> add($info);
        if ($result) {
            echo"<script>alert('注册成功，请点击登陆');history.go(-1);</script>";
        } else {
            alertMessage("错误！");
        }
    }

    // 学生用户修改基本信息，默认情况为：姓名和学号从其他系统导入
    public function subStudentUserOnly($info) {
        $student = M("Student", "tp_", "DB_CONFIG1");
        // $student = M("Student");

        $result = $student->where("id = $_SESSION[id]")->data($info)->save();
        if ($result) {
            echo "<script>alert('个人信息修改成功'); window.location.href=document.referrer; </script>";
        } else {
            echo "<script>alert('请检查网络'); window.location.href=document.referrer; </script>";
        }
    }

    // 检查原密码是否正确
    public function subAllUserPwd($oldPwd) {

        // 判断用户的类型
        switch ($_SESSION['usertype']) {
            case 1: $database = "Student"; break;
            case 0: $database = "User"; break;
            case 2: $database = "Teacher"; break;
            default: break;
        }

        $student = M($database, "tp_", "DB_CONFIG1");
        //$student = M($database);

        $result = $student->where("id = $_SESSION[id]")->getField("stpwd");
        if ($result != md5($oldPwd)) {
            alertMessage("重新填写原密码");
            exit();
        } else {
//            return false;
        }
    }

    // 修改密码操作
    public function doPwdChanged($info) {
        $student = M("Student", "tp_", "DB_CONFIG1");
        // $student = M("Student");

        return $student->where("id = $_SESSION[id]")->data($info)->save();
    }
}