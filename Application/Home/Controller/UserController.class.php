<?php
namespace Home\Controller;
use Home\Model\UserModel;
use Think\Controller;
class UserController extends Controller {
    // 登陆标志
    private $is_hahah = 0;

    public function _initialize() {
        if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
            $this->is_hahah = 1;
        } else {
            $this->is_hahah = 0;
        }
    }

    // 判断是否已经登陆
    public function index() {
        // 判断是否已经登陆
//        var_dump($_SESSION);
        if ($this->is_hahah == 0) {
            echo "<script>alert('请先登录');</script>";
            $this->redirect("Index/main");
        }
        $this->getStudentTeacher();
        $this->show();
    }

    // 展示我的申请页面
    public function application() {
        $this->assign("themeApp", $this->getThemeApp());
        $this->assign("ansApp", $this->getAnsApp());
        $this->assign("commentApp", $this->getCommentApp());
        $this->assign("allResource", $this->getAllResource());
        $this->show();
    }

    // 展示我的消息页面
    public function message() {
        $this->assign("themeApp", $this->getThemeApp());
//        $this->assign("ansApp", $this->getAnsApp());
//        $this->assign("commentApp", $this->getCommentApp());
        $this->show();
    }

    // 学生用户修改个人信息
    public function subStudentMes() {
        $username = I('post.username');
        $email = I('post.email');
        $phone = I('post.phone');
        // 是否点击提交按钮
        isFormSubmit();

        // 用户名是否正确
        if (!isUsernameValid($username)) {
            alertMessage("用户名格式不合法");
        }
        if (!isEmailValid($email)) {
            alertMessage("邮箱格式不合法");
        }
        if (!isPhoneValid($phone)) alertMessage("电话格式不合法");

        // 首先判断是否上传头像
        if ($_FILES['img']['name'] == '') {
            $path = "";
        } else {
            $path = $this->doUpload($this->type, $this->root, $_FILES['img']);
        }

        $data = array(
            "stuser"=>$username,
            "email"=>$email,
            "phonenumber"=>$phone
        );

        if ($path != "") {
            $data['headimg'] = $this->root.substr($path, 1, strlen($path));
        }
        $dataModel = new UserModel();
        $dataModel->subStudentUserOnly($data);
    }

    // 学生、教师、普通用户修改密码
    public function subPws() {
        // 是否点击提交按钮
        isFormSubmit();
        $check = new UserModel();
        // 检查原始密码是否正确
        $check->subAllUserPwd(I('post.expasswortd'));
        $info = array(
            'stpwd' => I('post.newpassword'),
        );

        // 判断密码是否由字母和数字组成、两次输入的密码是否一致
        $pw_is_true = $check->checkRepeatPwd($info['stpwd'], I('post.repassword'));
        $info['stpwd'] = md5($info['stpwd']);
        if ($pw_is_true) {
            // 判断验证码输入是否正确
            $verify = new \Think\Verify();
            if ($verify->check(I('post.code'), '')) {
                // 所有信息填写正确，进行修改
                $result = $check->doPwdChanged($info);
                if ($result) {
                    // 用户退出登陆，
                    $this->redirect("loginout");
                    alertMessage("修改成功！");
                } else {
                    alertMessage("请检查网络！");
                }
            }
        }
    }

    // 用户上传头像文件的执行方法
    private function doUpload($type, $root, $source) {
        $upload = new \Think\Upload();
        $upload->maxSize = 20000000;
        $upload->exts = $type;
        $upload->rootPath = $root;
        $upload->savePath = './'.$_SESSION['username'].'/';
        $info = $upload->uploadOne($source);
        if (!$info) {
            $this->error($upload->getError());
        } else {
            return $info['savepath'].$info['savename'];
        }
    }

    public function appdetail() {
        $this->show();
    }

    public function appdetail1() {
        $this->assign("data", $this->getOneThemeApp());
        $this->display("appdetail");
    }
    public function appdetail2() {
        $this->assign("data", $this->getOneAnsApp());
        $this->display("appdetail");
    }
    public function appdetail3() {
        $this->assign("data", $this->getOneResource());
        $this->display("appdetail");
    }
    // 得到用户的主题申请信息
    private function getThemeApp() {
        $theme_model =  D("Message");
        return $theme_model->where("usertype = \"$_SESSION[usertype]\" and msowner = \"$_SESSION[id]\"")->relation(true)->select();
    }

    // 得到某条主题
    private function getOneThemeApp() {
        $theme_model = M("Message");
        return $theme_model->where("id = ".$this->getId())->find();
    }

    // 得到用户的评论申请信息()
    private function getAnsApp() {
        $theme_model = D("Msas");
        return $theme_model->where("usertype = \"$_SESSION[usertype]\" and asowner = \"$_SESSION[id]\"")->relation(true)->select();
    }
    // 得到某条评论
    private function getOneAnsApp() {
        $theme_model = M("Msas");
        return $theme_model->where("id = ".$this->getId())->find();
    }
    // 得到用户上传的资源
    private function getAllResource() {
        $resourse = M("Resource");
        return $resourse->where("usertype = \"$_SESSION[usertype]\" and rsowner = \"$_SESSION[id]\"")->select();
    }

    // 得到一个资源的信息
    private function getOneResource() {
        $resourse = M("Resource");
        return $resourse->where("id = ".$this->getId())->find();
    }

    // 得到用户的回复申请信息()
    private function getCommentApp() {
        $theme_model = M("Anscomment");
        return $theme_model->where("usertype = \"$_SESSION[usertype]\" and cmmtowner = \"$_SESSION[id]\"")->select();
    }

    // 查询学生用户和普通用户的基本信息
    private function getStudentTeacher() {
        $studentModel = M("Student", "tp_", "DB_CONFIG2");
        if ($_SESSION['usertype'] == 1) {
            $this->assign("student", $studentModel->where("id = $_SESSION[id]")->find());
        }
//        if ($_SESSION['id'] == 1) {
//            $this->assign("student", $studentModel->where("id = $_SESSION[id]")->getField('stname', 'stid'));
//        }
    }


    public function login(){
        // 判断是否是永久登陆
        $this->checkLog();
        // 已经登录跳转至个人中心
        if (isset($_SESSION['username'])) {
           alertMessage("用户已经登陆");
        } else {
            // 判断是否存在cookie
            if (isset($_COOKIE['username'])) {
                session_start();
                $_SESSION['username'] = $_COOKIE['username'];
                $_SESSION['id'] = $_COOKIE['id'];
                $_SESSION['usertype'] = $_COOKIE['usertype'];
//                echo"<script>alert('登陆成功');history.go(-1);</script>";

                echo "<script>alert('登陆成功'); window.location.href=document.referrer; </script>";
                $this->assign('username', $_COOKIE['username']);
            } else {
                // 不存在cookie
                $this->check();
            }
        }
    }

    // 验证登录
    public function check() {
        // 判断用户名和密码
        $user = new UserModel();
        $result = $user->checkName(I('post.username'), I('post.password'));
        if ($result === false) {
            alertMessage("用户名或者密码错误");
        } else {
            // 用户信息存入cookie
            $_SESSION["username"] = $result['username'];
            $_SESSION["id"] = $result['id'];
            $_SESSION["usertype"] = $result['usertype'];
            // 如果用户勾选了记住我，则保持持久登陆
            if (I("post.remember")) {
                $salt = $this->random_str(16);
                // 第二身份标识
                $identifier = md5($salt.md5(I("username")).$salt);
                // 永久登录标识
                $token = md5(uniqid(rand(), true));
                // 设置永久登陆时间（一周）
                $timeout = time() + 3600*24*7;
                // 存入cookie
                setcookie('auth', "$identifier:$token", $timeout);
                $user->saveRemember($result['id'], $identifier, $token, $timeout);
            }

            // 把用户名存入cookie，退出登录之后在表单保存用户名信息

            setcookie('username', I('post.username'), time()+3600*24);
            setcookie('id', $result['id'], time()+3600*24);
            setcookie('usertype', $result['usertype'], time()+3600*24);
//            var_dump($_COOKIE);
            // 跳转到相应的界面
//            echo"<script>alert('登陆成功');history.go(-1);</script>";
            echo "<script>alert('登陆成功'); window.location.href=document.referrer; </script>";
        }
    }

    // 生成随机数：用于生成salt
    public function random_str($length) {
        // 生成一个包含大小写英文字母以及数字的数组
        $arr = array_merge(range(0,9), range('a', 'z'), range('A', 'Z'));
        $str = '';
        $arr_length = count($arr);
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $arr_length-1);
            $str .= $arr[$rand];
        }
        return $str;
    }

    // 退出登录
    public function loginout() {
        session(null);
        setcookie('username', '', time()-3600*8);
        setcookie('id', '', time()-3600*8);
        setcookie('usertype', '', time()-3600*8);
        // 退出登录之后跳转到某一个界面
        $this->redirect("Index/main");
    }

    // 判断是否是永久登陆
    public function checkLog() {
        $check = new UserModel();
        $is_log = $check->checkRemember();
        if ($is_log === false) {

        } else {
            session("username", $is_log['uname']);
            session("id", $is_log['uid']);
        }
    }

    // 验证注册
    public function register() {
        $info = array(
            'stuser' => I('post.username'),
            'stpwd' => I('post.password'),
            );
        $check = new UserModel();
        $is_exits = $check->checkUsername($info['stuser']);
        if ($is_exits === false) {
            alertMessage("用户名已经存在");
        } else {
            // 判断密码是否由字母和数字组成、两次输入的密码是否一致
            $pw_is_true = $check->checkRepeatPwd($info['stpwd'], I('post.repassword'));
            $info['stpwd'] = md5($info['stpwd']);
            if ($pw_is_true) {
                // 判断验证码输入是否正确
                $verify = new \Think\Verify();
                if ($verify->check(I('post.code'), '')) {
                    // 所有信息填写正确，进行注册
                    $check->doRegister($info);
                } else {
                    alertMessage("验证码错误");
                }
            } else {
                alertMessage("两次输入密码不一致");
            }
        }
    }

    /**
     * 获得试用get方式得到的主键值
     * @return string id值
     */
    private function getId() {
        $theID = trim($_GET['id']);
        (empty($theID) || !is_numeric($theID)) ? $this->error("程序内部错误") : $id = $theID;
        return $id;
    }

    // 用户头像存储位置
    protected $root = "./Uploads/User";
    // 用户头像的格式
    protected $type = array("bmp", "jpg", "jpeg", "png");
}
