<?php
namespace Home\Controller;
use Think\Controller;
class CourseController extends Controller {

    public function course() {
        // 实践学习类课程
        $this->getTheViewModel($this->type['shijianxuexi']);
        $this->show();
    }

    /**
     * 方法功能：得到最近的4条主题以及热门主题
     * @param 类型
     */
    public function getThemes() {
        $data_model = D("Message");
        $this->assign("hotThemes", $data_model->order("mshaving desc")->limit(9)->select());
        $this->assign("latestThemes", $data_model->order("msdate desc")->limit(4)->select());
    }

    public function course1() {
        // 专业基础类课程
        $this->getTheViewModel($this->type['zhuanyejichu']);
        $this->display('course');
    }

    public function course2() {
        // 作物生产类课程
        $this->getTheViewModel($this->type['zuowushengchan']);
        $this->display('course');
    }

    public function course3() {
        // 植物保护类课程
        $this->getTheViewModel($this->type['zhiwubaohu']);
        $this->display('course');
    }

    public function course4() {
        // 创新实践课程
        $this->getTheViewModel($this->type['chuangxinshiyankecheng']);
        $this->display('course');
    }

    /**
     * 展示某条申请的详细信息
     */
    public function showitem() {
        $where = $this -> getId();
        $this->getAllAnsofAtheme($where);
        $this->getOneViewModelofMessage($where);
        $this->show();
    }

    // 方法功能：得到某个主题下面的所有的评论
    public function getAllAnsofAtheme($id) {
        $mode = D("Msas");
        $this->assign("theAns", $mode->where("msid = $id")->relation(true)->select());
    }

    /**
     * 展示某条申请的详细信息
     */
    public function showitem1($id) {
        $this->getOneViewModelofMessage($id);
    }

    /**
     * 方法功能：跳转到发布新主题的页面
     */
    public function themeluanch() {
        // 判断用户是否登录，如果登录，继续，如果没有登录，跳转到登录界面
//        isset($_SESSION['userid']) ? $this->show() : $this->redirect();
        $this->show();
    }

    /**
     * 方法功能：添加新主题的执行方法
     */
    public function doThemeLaunch() {
        $the_add_model = M('Message');
        $data['mstype'] = trim($_POST['mstype']);
        $data['msname'] = trim($_POST['msname']);
        $data['msct'] = html_entity_decode(trim($_POST['msct']));
        $data['msdate'] = date("Y-m-d h:i:s");
        $data['msowner'] = $_SESSION['id'];
        $data['usertype'] = $_SESSION['usertype'];
        if ($the_add_model -> add($data)) {
            $data = array(
                'code' => 'success',
                'where' => 1,
            );
            echo json_encode($data);
        } else {
            $data = array(
                'code' => 'false',
                'where' => 0,
            );
            echo json_encode($data);
        }
    }

    /**
     * 模板和后台数据的结合
     * @param $type 主题的类型
     */
    private function getTheViewModel($type) {
        // 获得当前的所属类别
        $this->assign("theType", $type);
        // 获得处于等待状态的留言主题
//        $this->assign("uncheckedMs", $this->getMessageUnchecked(0, $type));
        // 获得申请通过状态的主题
        $this->assign("checkedMs", $this->getMessageUnchecked(2, $type));
//        var_dump($this->getMessageUnchecked(2, $type));
        // 获得处于等待状态的评论回复

        $this->getThemes();
    }

    /**
     * 添加一条回复
     */
    public function addAns() {
        $the_add_model = M('Msas');
        $data['msid'] = trim($_POST['msid']);
        $data['asct'] = html_entity_decode(trim($_POST['asct']));
        $data['asdate'] = date("Y-m-d h:i:s");
        $data['asowner'] = $_SESSION['id'];
        $data['usertype'] = $_SESSION['usertype'];
        $data['ashaving'] = 0;

        // 修改评论数
//        $this->submithaving(trim($_POST['msid']), "Message", 1);

        $the_add_model -> add($data) ? alertMessage("回复成功，请等待审核"): alertMessage("请检查网络");
    }

    public function addComment() {
        $the_add_model = M('Anscomment');
        $data['ansid'] = trim($_POST['asid']);
        $data['cmmtct'] = html_entity_decode(trim($_POST['title']));
        $data['cmmtdate'] = date("Y-m-d h:i:s");
        $data['cmmtowner'] = $_SESSION['id'];
        $data['usertype'] = $_SESSION['usertype'];
        $data['cmmthaving'] = 0;
        // 修改评论数
//        $this->submithaving(trim($_POST['msid']), "Message", 1);
        $the_add_model -> add($data) ? alertMessage("回复成功，请等待审核"): alertMessage("请检查网络");
    }

    /**
     * 方法功能：得到所有未处理的数组
     * @param $type 主题的类型
     * @param $msdo 是否通过申请
     * @return mixed 未处理的申请数组
     */
    private function getMessageUnchecked($msdo, $type) {
        $message_unchecked_model = D("Message");
        return $message_unchecked_model -> where("msdo = $msdo and mstype = $type") -> relation(true) -> order('id desc') -> select();
    }

    /**
     * 得到最新的一条回复
     * @param $id 主题的主键
     */
    private function getLastestAnswer($id) {
        $data_model = M("Msas");
        return $data_model->where("id = $id")->order("msdate desc")->find();
    }

    /**
     * 查询一条主题的内容，评论等
     * @param $id 要获取记录的主键
     */
    private function getOneViewModelofMessage($id) {
        $one_view_modle = D("Message");
        // 获取已申请通过的评论
        $this->assign("data", $one_view_modle->where("id = $id")->relation(true)->find());
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


    // 主题的类型
    protected $type = array(
        'shijianxuexi' => 0,
        'zhuanyejichu' => 1,
        'zuowushengchan' => 2,
        'zhiwubaohu' => 3,
        'chuangxinshiyankecheng' => 4,
        'zonghe' => 5,
    );
}
