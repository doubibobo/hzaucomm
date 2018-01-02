<?php
namespace Admin\Controller;
use Think\Controller;
class CompController extends Controller {
    public function comp() {
        $the_course_control = A("Course");
        $the_course_control->course5();
        $this->display('Course:course');
    }

    /**
     * 展示某条申请的详细信息
     */
    public function themeshow() {
        $the_course_control = A("Course");
        $the_course_control->course6($this->getId());
        $this->display('Course:themeshow');
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
     * 添加一项主题
     */
    public function addMs() {
        $the_course_control = A("Course");
        $the_course_control->addMs();
    }

    /**
     * 方法功能：删除一条主题
     */
    public function deleteMs() {
        $the_course_control = A("Course");
        $the_course_control->deleteMs();
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

    /**
     * 获得试用get方式得到的主键值
     * @return string id值
     */
    private function getId() {
        (empty(trim($_GET['id'])) || !is_numeric(trim($_GET['id']))) ? $this->error("程序内部错误") : $id = trim($_GET['id']);
        return $id;
    }
}
