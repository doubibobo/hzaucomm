<?php
namespace Admin\Controller;
use Think\Controller;
class CourseController extends Controller {
    /**
     * 后台首界面
     */
    public function course() {
        // 实践学习类课程
        $this->getTheViewModel($this->type['shijianxuexi']);
        $this->show();
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

    public function course5() {
        // 综合讨论区
        $this->getTheViewModel($this->type['zonghe']);
//        $this->display('course');
    }

    public function course6($id) {
        $this->getOneViewModelofMessage($id);
    }

    public function course7($id) {
        $this->getViewModle($id);
    }

    public function course8($id) {
        $this->getCommentRead($id);
    }

    /**
     * 展示某条申请的详细信息
     */
    public function themeshow() {
        $this->getOneViewModelofMessage($this->getId());
        $this->show();
    }

    /**
     * 方法功能：得到所有未处理的数组
     * @param $type 主题的类型
     * @param $msdo 是否通过申请
     * @return mixed 未处理的申请数组
     */
    private function getMessageUnchecked($msdo, $type) {
        $message_unchecked_model = M("Message");
        return $message_unchecked_model -> where("msdo = $msdo and mstype = $type") -> order('id desc') -> select();
    }

    /**
     * 模板和后台数据的结合
     * @param $type 主题的类型
     */
    private function getTheViewModel($type) {
        // 获得当前的所属类别
        $this->assign("theType", $type);
        // 获得处于等待状态的留言主题
        $this->assign("uncheckedMs", $this->getMessageUnchecked(0, $type));
        // 获得申请通过状态的主题
        $this->assign("checkedMs", $this->getMessageUnchecked(2, $type));
        // 获得处于等待状态的评论回复
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
     * 添加一项主题
     */
    public function addMs() {
        $the_add_model = M('Message');
        $data['mstype'] = trim($_POST['mstype']);
        $data['msname'] = trim($_POST['msname']);
        $data['msct'] = htmlspecialchars_decode(trim($_POST['msct']));
        $data['msdate'] = date("Y-m-d h:i:s");
//        $data['msowner'] = $_SESSION['userid'];
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
     * 删除一条主题
     */
    public function deleteMs() {
        $this->doDeleteMs($this->getId());
    }

    /**
     * 删除一条主题的执行方法
     * @param $id 主键
     */
    private function doDeleteMs($id) {
        $delete_model_data = D("Message");
        $delete_model_data->where("id = $id")->relation(true)->delete() ? $this->success("删除成功") : $this->error("删除失败");
    }

    /**
     * 同意一条主题申请
     */
    public function msAgree() {
        $this->doAR($this->getId(), $this->msdo['agree']);
    }

    /**
     * 拒绝一条主题申请
     */
    public function msReject() {
        $this->doAR($this->getId(), $this->msdo['reject']);
    }

    /**
     * 申请或者拒绝主题的执行方法
     */
    private function doAR($id, $msdo) {
        $updateDo = M("Message");
        $updateDo -> msdo = $msdo;
        $updateDo -> where("id = $id") -> save() ? $this->success('主题同意成功', '', 0) : $this->error("程序内部错误");
    }

    /**
     * 修改评论数、回复数方法
     * @param $id   主键
     * @param $database 数据库名称
     */
    private function submithaving($id, $database, $do) {
        $the_ms_model = M($database);
        $the_ms_model -> mshaving = ($do == 0) ? ($the_ms_model->where("id = $id")->getField('mshaving') - 1) : ($the_ms_model->where("id = $id")->getField('mshaving') + 1);
        $the_ms_model -> where("id = $id") -> save();
    }

    /**
     * 添加一条回复
     */
    public function addAns() {
        $the_add_model = M('Msas');
        $data['msid'] = trim($_POST['msid']);
        $data['asct'] = html_entity_decode(trim($_POST['asct']));
        $data['asdate'] = date("Y-m-d h:i:s");
//        $data['msowner'] = $_SESSION['userid'];
        $data['asowner'] = "admin";
        $data['ashaving'] = 1;

        $this->submithaving(trim($_POST['msid']), "Message", 1);

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
     * 同意一条回复
     */
    public function ansAgree() {
        $id = $this->getId();
        $data_model = M("Msas");
        $this->submithaving($data_model->where("id = $id")->getField("msid"), "Message", 1);
        $this->doAsAr($id, $this->ashaving['agree']);
    }

    /**
     * 拒绝一条回复
     */
    public function ansReject() {
        $this->doAsAr($this->getId(), $this->ashaving['reject']);
    }

    /**
     * 同意或者拒绝回复的执行方法
     * @param $id
     * @param $ashaving
     */
    private function doAsAr($id, $ashaving) {
        $updateDo = M("Msas");
        $updateDo -> ashaving = $ashaving;
        $updateDo -> where("id = $id") -> save() ? $this->success('主题同意成功', '', 0) : $this->error("程序内部错误");
    }

    /**
     * 删除一条回复
     */
    public function deleteAns() {
        $id = $this->getId();
        $data_model = M("Msas");
        $this->submithaving($data_model->where("id = $id")->getField("msid"), "Message", 0);
        $this->doDeleteAns($this->getId());
    }

    /**
     * 删除一条回复的执行方法
     * @param $id
     */
    private function doDeleteAns($id) {
        $delete_model_data = D("Msas");
        $delete_model_data->where("id = $id")->relation(true)->delete() ? $this->success("删除成功") : $this->error("删除失败");
    }

    /**
     * 查看一条评论及其回复
     */
    public function commentshow() {
        $this->getViewModle($this->getId());
        $this->show();
    }

    /**
     * 得到某条评论及相应的评论回复
     */
    private function getViewModle($id) {
        $one_view_modle = D("Msas");
        // 获取已申请通过的评论
        $this->assign("data", $one_view_modle->where("id = $id")->relation(true)->find());
    }

    /**
     * 添加一条评论回复
     */
    public function addAnsComment() {
        $the_add_model = M('Anscomment');
        $data['ansid'] = trim($_POST['ansid']);
        $data['cmmtct'] = html_entity_decode(trim($_POST['asct']));
        $data['cmmtdate'] = date("Y-m-d h:i:s");
//        $data['msowner'] = $_SESSION['userid'];
        $data['cmmtowner'] = "admin";
        $data['cmmthaving'] = 2;
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
     * 同意一条评论回复
     */
    public function CommentAgree() {
        $this->doCommentAr($this->getId(), $this->msdo['agree']);
    }

    /**
     * 拒绝一条评论回复
     */
    public function CommentReject() {
        $this->doCommentAr($this->getId(), $this->msdo['reject']);
    }

    /**
     * 同意、拒绝一条评论回复的执行方法
     * @param $id 评论的主键
     */
    private function doCommentAr($id, $cmmthaving) {
        $updateDo = M("Anscomment");
        $updateDo -> cmmthaving = $cmmthaving;
        $updateDo -> where("id = $id") -> save() ? $this->success('主题同意成功', '', 0) : $this->error("程序内部错误");
    }

    /**
     * 删除一条回复
     */
    public function deleteComment() {
        $this->doDeleteComment($this->getId());
    }

    /**
     * 删除一条回复的执行方法
     * @param $id
     */
    private function doDeleteComment($id) {
        $delete_model_data = D("Msas");
        $delete_model_data->where("id = $id")->relation(true)->delete() ? $this->success("删除成功") : $this->error("删除失败");
    }

    /**
     * 查看评论回复
     */
    public function commentread() {
        $this->getCommentRead($this->getId());
        $this->show();
    }

    /**
     * 获得评论内容
     * @param $id 主键名称
     */
    private function getCommentRead($id) {
        $one_view_modle = M("Anscomment");
        $this->assign("data", $one_view_modle->where("id = $id")->find());
    }

    /**
     * 获得试用get方式得到的主键值
     * @return string id值
     */
    private function getId() {
        (empty(trim($_GET['id'])) || !is_numeric(trim($_GET['id']))) ? $this->error("程序内部错误") : $id = trim($_GET['id']);
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

    // 主题相应的处理手段
    protected $msdo = array(
        'agree' => 2,
        'reject' => 1,
        'waiting' => 0,
    );

    // 回复相应的处理手段
    protected $ashaving = array(
        'waiting' => 0,
        'agree' => 1,
        'reject' => 2,
    );
}
