<?php
namespace Home\Controller;
use Think\Controller;
class CompController extends Controller {
    public function comp() {
        $this->getThemes();
        $this->show();
    }

    public function hahaha() {
        $this->show();
    }
    public function comp1() {
        $this->getThemes(1);
        $this->display('comp');
    }
    public function comp2() {
        $this->getThemes(2);
        $this->display('comp');
    }
    public function comp3() {
        $this->getThemes(3);
        $this->display('comp');
    }

    /**
     * 方法功能：得到最近的4条主题以及热门主题
     * @param 类型
     */
    public function getThemes($type) {
        $data_model = D("Message");
        if ($type == 1) {
            $this->assign("theThemes", $data_model->order("mshaving desc")->limit(9)->select());
            $this->assign("hotThemes", $data_model->order("mshaving desc")->limit(9)->select());
        }
        else if ($type == 2) {
            $this->assign("theThemes", $data_model->order("msdate desc")->limit(9)->select());
            $this->assign("hotThemes", $data_model->order("mshaving desc")->limit(9)->select());
        } else {
            // $data_model1 = D("Msas");
            // if ($_SESSION['id'] != '') {
            //     $sql = "SELECT tp_message.usertype, tp_message.id, msname, msowner, mshaving, mslike, msct, msdate FROM tp_msas, tp_message WHERE msid = tp_message.id  AND asowner =".$_SESSION['id']." AND tp_msas.usertype = ".$_SESSION['usertype'];
            //     $mode = $data_model1->query($sql);

            //     for ($i = 0; $i < count($mode); $i++) {
            //         $mode[$i]['msowner'] = $this->getUserMessage($this->getUserTable($mode[$i]['usertype']));
            //     }

            //     $this->assign("theThemes", $mode);


            //     $this->assign("theThemes", $data_model1->query($sql));
            // }
            // else {

            // }
            // $this->assign("hotThemes", $data_model->order("mshaving desc")->limit(9)->select());
        }
    }

    /**
     * 方法功能；通过判断用户的类型来确定要查询的用户数据表
     */
    public function getUserTable($type) {
        switch ($type) {
            case 0: $database = "user"; break;
            case 1: $database = "student"; break;
            case 2: $database = "teacher"; break;
//            case 3: $database = "Teacher"; break;
//            case 4: $database = "Teacher"; break;
        }
        return $database;
    }

    /**
     * 方法功能；查询登录用户的相关信息
     */
    public function getUserMessage($database) {
        $model = M($database, 'tp_', 'DB_CONFIG2');
        return $model -> where("id = $_SESSION[id]")->getField("username");
    }
}
