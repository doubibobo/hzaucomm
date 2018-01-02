<?php
namespace Admin\Controller;
use Think\Controller;
class IntroductionController extends Controller {
    /**
     * 平台概况的展示
     */
    public function introduction() {
        $the_content_model = M("Intro");
        $the_content = $the_content_model->where("id = 1")->find();
        $this->assign("data", $the_content);
        $this->show();
    }

    /**
     * 通知的展示
     */
    public function notice() {
        $this->assign("data", $this->getAllInfor());
        $this->show();
    }

    /**
     * 联系我们的展示
     */
    public function connect() {
        $the_content_model = M("Intro");
        $the_content = $the_content_model->where("id = 2")->find();
        $this->assign("data", $the_content);
        $this->show();
    }

    /**
     * 方法功能：添加一条通知
     */
    public function addInfor() {
        $info_new_model = M("Infro");
        if ($info_new_model->create()) {
            // TODO 发表用户暂时默认为admin
            $info_new_model->infoowner = "admin";
            $info_new_model->infotime = date("Y-m-d");
            $info_new_model->infoct = htmlspecialchars_decode($_POST['infoct']);
            $info_new_model->add() ? $data = "添加成功" : $data = "添加失败";
        } else {
            $data = "添加失败";
        }
        echo json_encode($data);
    }

    /**
     * 方法功能：展示某个具体的通知
     */
    public function summary() {
        $where = trim($_GET['id']);
        if (empty($where) && is_numeric($where)) $this->error("通知公告显示错误！");
        $this->assign("data", $this->getOneInfor($where));
        $this->show();
    }

    /**
     * 方法功能：修改一条具体的通知
     */
    public function infoUpdate() {
        $where = $_POST['id'];
        if (empty(trim($_POST['title']))) {
            $data = "通告标题不能为空";
        } elseif (empty(trim($_POST['content']))) {
            $data = "通告标题不能为空";
        } else {
            $the_info_model = M("Infro");
            $the_info_model->infoname = trim($_POST['title']);
            $the_info_model->infoct = htmlspecialchars_decode($_POST['content']);
            $result = $the_info_model->where("id = $where")->save();
            if ($result) {
                $data = array(
                    'code' => 1,
                    'where' => $where,
                );
            }
        }
        echo json_encode($data);
    }

    /**
     * 方法功能：删除一条通知
     */
    public function infoDelete() {
        $where = trim($_GET['id']);
        (empty($where) || !is_numeric($where)) ? $this->error("传入字段错误") : 1;
        $info_data_model = M("Infro");
        $info_data_model->where("id = $where")->delete() ? $this->success("删除成功！") : $this->error("删除失败");
    }

    /**
     * 方法功能：对基本情况进行修改
     */
    public function aboutDoUpdate() {
        $which = $_POST['id'];
        $the_intro_model = M("Intro");
        $the_intro_model->content = htmlspecialchars_decode($_POST['content']);
        $result = $the_intro_model->where("id = $which")->save();
        if ($result) {
            $data = array(
                'code'=>1,
                'where'=>$which,
            );
            echo json_encode($data);
        } else {
            alert("错误");
        }
    }

    /**
     * 方法功能：得到通知数据表中的主键列、通知名称、通知时间，是一个中转方法
     * @return mixed 返回相应的数据
     */
    private function getAllInfor() {
        $data_model = M("Infro");
        return $data_model->order("id desc")->getField('id, infoname, infotime');
    }

    /**
     * 方法功能：获取某一条具体的通知
     * @param $id 查询时候的主键
     * @return mixed 查询所得的一条记录
     */
    private function getOneInfor($id) {
        $data_model = M("Infro");
        return $data_model->where("id = $id")->find();
    }
}
