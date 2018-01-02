<?php
namespace Home\Controller;
use Think\Controller;
class IntroductionController extends Controller {
    /**
     * 方法功能：平台概况的展示界面
     */
    public function introduction() {
        $this->assign("data", $this->getData($this->type_distribute['introduction']));
        $this->show();
    }

    /**
     * 方法功能：通知公告的展示界面
     */
    public function notice() {
        $the_notice_model = M("Infro");
        $count = $the_notice_model->count();
        $Page = new \Think\Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $the_notice_model->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->getField('id, infoname, infotime');
        $this->assign('data',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->show();
    }

    /**
     * 方法功能：联系我们的展示界面
     */
    public function connect() {
        $this->assign("data", $this->getData($this->type_distribute['connect']));
        $this->show();
    }

    /**
     * 方法功能：中转方法，获得相应记录
     * @param $type 查询数据库的类型
     * @return mixed 返回所查询的第一条记录
     */
    private function getData($type) {
        $data_model = M("Intro");
        return $data_model->where("id = $type")->find();
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

    // 不同界面不同类型的匹配
    protected $type_distribute = array(
        "introduction"=> 1,
        "connect"=>2,
    );
}
