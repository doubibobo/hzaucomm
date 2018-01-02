<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index() {
        $this->assign("allImagics", $this->getAllImgs());
        $this->getResourceData();
        $this->getThemes();
        $this->show();
    }

    /**
     * 方法功能：左上角图片添加
     */
    public function addImagic() {
        //设置上传文件类型
        $Realname = $_FILES['gallery_file']['name'];
        $upload = new \Think\Upload();//实例化上传类
        $upload -> maxSize = 3145728;//设置上传文件的大小
        $upload->rootPath  =   './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =   './File/'; // 设置附件上传（子）目录
        $upload->saveName = 'time';

        //上传文件
        $info = $upload -> upload();
        if(!$info){
            //上传错误提示信息
            $this -> error($upload->getError());
        }

        $n = M('Imagics');
        $n -> imgname = $_POST['gallery_title'];
        $n -> imgintro = $_POST['gallery_textarea'];
        $n -> imgadd =$info['gallery_file']['savepath'].$info['gallery_file']['savename'];
        $last = $n -> add();
        if($last){
            $this -> success("添加成功");
        }else{
            $this -> error("上传失败！");
        }
    }

    /**
     * 左上角图片编辑功能
     */
    public function imgEdit() {

    }

    /**
     * 左上角图片删除功能
     */
    public function imgDelete() {
        $id = $_GET['id'];
        $n = M("Imagics");
        $result = $n -> where("id = $id") -> delete();
        if ($result) {
            $this -> redirect("index");
        } else {
            $this -> error("删除失败");
        }
    }

    public function themeshow() {
        $the_course_control = A("Course");
        $the_course_control->course6($this->getId());
        $this->display('Course:themeshow');
    }

    public function deleteMs() {
        $the_course_control = A("Course");
        $the_course_control->deleteMs();
    }

    /**
     * 查看一条评论及其回复
     */
    public function commentshow() {
        $the_course_control = A("Course");
        $the_course_control->course7($this->getId());
        $this->display('Course:commentshow');
    }

    /**
     * 查看评论回复
     */
    public function commentread() {
        $the_course_control = A("Course");
        $the_course_control->course8($this->getId());
        $this->display('Course:commentread');

    }


    /**
     * 得到所有首页的图片
     * @return mixed 所有首页图片记录
     */
    private function getAllImgs() {
        $dataModel = M("Imagics");
        return $dataModel->select();
    }

    /**
     * 方法功能：得到最近的4条热门主题
     * @param 类型
     */
    private function getThemes() {
        $data_model = D("Message");
        $this->assign("hotThemes", $data_model->order("mshaving desc")->limit(4)->select());
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
        (empty(trim($_GET['id'])) || !is_numeric(trim($_GET['id']))) ? $this->error("程序内部错误") : $id = trim($_GET['id']);
        return $id;
    }


    /**
     * 同意一条主题申请
     */
    public function msAgree() {
        $the_course_control = A("Course");
        $the_course_control->msAgree();
    }

    /**
     * 拒绝一条主题申请
     */
    public function msReject() {
        $the_course_control = A("Course");
        $the_course_control->msReject();
    }

    /**
     * 添加一条回复
     */
    public function addAns() {
        $the_course_control = A("Course");
        $the_course_control->addAns();
    }

    /**
     * 同意一条回复
     */
    public function ansAgree() {
        $the_course_control = A("Course");
        $the_course_control->ansAgree();
    }

    /**
     * 拒绝一条回复
     */
    public function ansReject() {
        $the_course_control = A("Course");
        $the_course_control->ansReject();
    }

    /**
     * 删除一条回复
     */
    public function deleteAns() {
        $the_course_control = A("Course");
        $the_course_control->deleteAns();
    }

    /**
     * 添加一条评论回复
     */
    public function addAnsComment() {
        $the_course_control = A("Course");
        $the_course_control->addAnsComment();
    }

    /**
     * 同意一条评论回复
     */
    public function CommentAgree() {
        $the_course_control = A("Course");
        $the_course_control->CommentAgree();
    }

    /**
     * 拒绝一条评论回复
     */
    public function CommentReject() {
        $the_course_control = A("Course");
        $the_course_control->CommentReject();
    }

    /**
     * 删除一条回复
     */
    public function deleteComment() {
        $the_course_control = A("Course");
        $the_course_control->deleteComment();
    }

    public function test2($length) {
        if ($length < 1024) return $length."B";
        if ($length < 1024*1024) return ($length/1024)."K";
        if ($length < 1024*1024*1024) return ($length/1024*1024)."M";
        if ($length < 1024*1024*1024*1024) return ($length/1024*1024*1024)."G";
    }

    public function test() {
        $theOther = M("Resource", "tp_", "DB_CONFIG1")->query("select * from tp_resource");
        $about = M("about")->db(0)->query("select * from tp_about");
        var_dump(M("about")->db(0)->getTableName());
        var_dump($about);
        echo "___________分隔符__________";
        var_dump($theOther);
    }
    public function test1() {
        $about = D("about")->select();
        $theOther = D("resource")->query("select * from tp_resource");
        var_dump($about);
        echo "___________分隔符__________";
        var_dump($theOther);
    }
}
