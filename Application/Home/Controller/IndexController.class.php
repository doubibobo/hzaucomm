<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    /**
     * 方法功能：首页的展示
     */
    public function main() {
    	$result = $this->getInfroData();
        $this->assign("latestInfro", strip_tags($result['infoct']));
        $this->getResourceData();
        $this->getThemes();
        $this->getPictures();
        $this->show();
    }

    public function index() {
        $result = $this->getInfroData();
        $this->assign("latestInfro", strip_tags($result['infoct']));
        $this->getResourceData();
        $this->getThemes();
        $this->getPictures();
        $this->show();
    }

    private function getPictures() {
        $mode = M("Imagics");
        $this->assign("imagics", $mode->select());
    }

    /**
     * 重定向跳转到其他的页面
     */
    public function showitem() {
        $this->redirect("Home/Course/showitem/id/".$this->getId(), '', 0,'');
    }

    /**
     * 方法功能：得到最近的4条主题以及热门主题
     * @param 类型
     */
    private function getThemes() {
        $data_model = D("Message");
        $this->assign("hotThemes", $data_model->order("mshaving desc")->limit(4)->select());
        $this->assign("latestThemes", $data_model->order("msdate desc")->limit(4)->select());
    }

    /**
     * 方法功能：查询最新通知公告的内容
     * @return mixed 最新通知公告的内容
     */
    private function getInfroData() {
        $infro_data_model = M("Infro", 'tp_', 'DB_CONFIG1');
        return $infro_data_model->order("id desc")->find();
    }

    /**
     * 方法功能：得到最新共享相关数据
     * @return mixed 最新共享相关数据
     */
    private function getResourceData() {
        $resource_data_model = M("Resource");
        $this->assign("latestResource", $resource_data_model->order("id desc")->limit(4)->select());
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


    public function test() {
        $resource_data_model = M("Resource");
        $result = $resource_data_model -> query("SELECT * FROM tp_resource WHERE rsdesp = '%第%'");
        var_dump($result);
        exit();
    }

    // 获取的主题类型
    protected $type = array(
        "hot", "latest",
    );
}
