<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * Class SourceController
 * @package Admin\Controller
 * 类功能：后台资源上传和资源共享模块代码
 */
class SourceController extends Controller {

    public function notice() {
        $the_content_model = M("Intro");
        $the_content = $the_content_model->where("id = 3")->find();
        $this->assign("data", $the_content);
        $this->show();
    }

    public function aboutDoUpdate() {
        $Introduction = A("Introduction");
        $Introduction -> aboutDoUpdate();
    }

    /**
     * 资源共享模块的图片展示
     */
    public function picture() {
        $this->getData($this->the_type['pic']);
    }

    // 获得文件资源
    private function getFileSource($type) {
        $theData = M('Resource'); // 实例化User对象
        $list = $theData->where("rsType = $type")->order('id desc')->select();
        $this->assign('data',$list);// 赋值数据集
        $this->display($this->the_t[$type]);
    }

    /**
     * 资源共享模块的文件展示
     */
    public function document() {
        $this->getData($this->the_type['file']);
    }

    /**
     * 资源共享模块的视频展示
     */
    public function video() {
        $this->show();
    }

    /**
     * 资源共享模块的其他展示
     */
    public function zip() {
        $this->getData($this->the_type['other']);
    }

    /**
     * 资源上传模块的图片上传
     */
    public function pic_update() {
        $this->getAllType($this->the_type['pic']);
        $this->show();
    }

    /**
     * 资源上传模块的文件上传
     */
    public function doc_update() {
        $this->getAllType($this->the_type['file']);
        $this->show();
    }

    /**
     * 资源上传模块的视频上传
     */
    public function vid_update() {
        $this->getAllType($this->the_type['vedio']);
        $this->show();
    }

    /**
     * 资源上传模块的其他上传
     */
    public function zip_update() {
        $this->getAllType($this->the_type['other']);
        $this->show();
    }

    // 同意上传文件
    public function resourceAgree() {
        $id = $_GET['id'];
        if ($this -> doResource(1, $id)) {
            alertMessage("已同意该申请");
        } else {
            alertMessage("同意该申请失败");
        }
    }

    // 拒绝上传文件
    public function resourceReject() {
        $id = $_GET['id'];
        if ($this -> doResource(2, $id)) {
            alertMessage("已拒绝该申请");
        } else {
            alertMessage("拒绝该申请失败");
        }
    }

    private function doResource($do, $id) {
        $mode = M("Resource");
        $mode -> rsAble = $do;
        if ($mode -> where("id = $id") -> save()) {
            return true;
        } else {
            return false;
        }
    }


    // 查看所有待批准的各类文件
    private function getAllType($type) {
        $mode = M("Resource");
        $this -> assign("data", $mode -> where("rstype = $type and rsable = 0") -> select());
    }

    // 判断各项是否填写完整
    private function isNullOp($file_name, $data) {
        return (empty($file_name) or empty(trim($data['title'])) or empty(trim($data['descripe']))) ? false : true;
    }

    /**
     * 转化文件的大小，用M进行计算
     * @param $size 以B为单位
     * @return float|int
     */
    private function fileLength($size) {
        return $size/1000000;
    }

    // 删除一个共享资源
    public function documentDelete() {
        $where = $_GET['id'];
        $mode = M("Resource");
        if ($mode -> where("$where = id") -> delete()) {
            alertMessage("删除成功");
        } else {
            alertMessage("请检查网络之后重试");
        }
    }

    // 上传文件
    public function doAllUpdate() {
        $theType = trim($_POST['type']);
        if ($theType == "") alertMessage("上传失败");
        // 判断各个字段是否填写完整
        if ($this->isNullOp($_FILES['photo']['name'], $_POST)) {
            // 判断文件大小是否合法
            if ($this->isLegal($this->fileLength($_FILES['photo']['size']))) {
                // 判断上传文件的来源
                switch ($theType) {
                    case 1: $str = "pic"; $data['rsType'] = 1; break;
                    case 2: $str = "file"; $data['rsType'] = 2; break;
                    case 3: $str = "video"; $data['rsType'] = 3; break;
                    case 4: $str = "other"; $data['rsType'] = 0; break;
                    default: $str = "other"; $data['rsType'] = 0;
                }
                // 上传文件并且判断是否成功
                $path = $this->doUpload($this->fileType[$str], $this->root[$str], $_FILES['photo']);
                if (!empty($path)) {
                    $data = array(
                        'rsName' => trim($_POST['title']),
                        'rsDesp' => trim($_POST['descripe']),
                        'rsWay' => $this->root['pic'].substr($path, 1, strlen($path)),
                        'rsLength' => $_FILES['photo']['size'],
                        'rsAble' => 1,
                        // 'rsOwner' => $_SESSION['id'],
                        'rsOwner' => 'admin',
                        'rsDate' => date("Y-m-d"),
                        'usertype' => $_SESSION['usertype'],
                    );
                    // 定义数据库中的类型
                    switch ($theType) {
                        case 1: $data['rsType'] = 1; $data['rsWay'] = $this->root['pic'].substr($path, 1, strlen($path)); break;
                        case 2: $data['rsType'] = 2; $data['rsWay'] = $this->root['file'].substr($path, 1, strlen($path));break;
                        case 3: $data['rsType'] = 3; $data['rsWay'] = $this->root['video'].substr($path, 1, strlen($path));break;
                        case 4: $data['rsType'] = 0; $data['rsWay'] = $this->root['other'].substr($path, 1, strlen($path));break;
                        default: $data['rsType'] = 0;
                    }
                    $theData = M("Resource");
                    $result = $theData->add($data);
                    $result ? alertMessage('添加成功') : alertMessage("添加失败");
                }

            } else {
                alertMessage("文件大小超过限制，最大上传20M的文件");
            }
        } else {
            alertMessage("请将各项填写完整");
        }
    }

    /**
     * 判断要上传的文件大小是否合法
     * @param $size 文件大小，以M为单位，不超过20M
     * @return bool 返回值
     */
    private function isLegal($size) {
        return $size > 20 ? false : true;
    }

    /**
     * 上传文件的执行方法
     * @param $type 文件的类型（图片、文件、视频、其他）
     * @param $root 文件在服务器上保存的根目录
     * @param $source 文件上传时的操作
     * @return 返回文件在服务器上的存储相对路径
     */
    private function doUpload($type, $root, $source) {
        $upload = new \Think\Upload();
        $upload->maxSize = 20000000;
        $upload->exts = $type;
        $upload->rootPath = $root;
        $upload->savePath = './';
        $info = $upload->uploadOne($source);
        if (!$info) {
            $this->error($upload->getError());
        } else {
            return $info['savepath'].$info['savename'];
        }
    }

    // 文件功能的详细展示
    public function documentshow() {
        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            alertMessage("查询错误！");
        }
        $record = $this->findOneRecord($id);
        $pdfWay = $record['rsway'];
        $pdfWay1=explode('.',$pdfWay);//om 负数从结尾开始取
        $theRealWay = $pdfWay1[1].'.pdf';
        tranOffice($pdfWay);
        $this->assign("data", $record);
        $this->assign("theRealWay", $theRealWay);
        $this->display();
    }

    // 压缩包资源的详细展示
    public function zipshow() {
        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            alertMessage("查询错误！");
        }
        $this->assign("data", $this->findOneRecord($id));
        $this->display();
    }

    /**
     * 方法功能：图片资源的详细展示
     */
    public function pictureshow() {
        $where = trim($_GET['id']);
        if (empty($where)) alertMessage("查询错误");
        // var_dump($where);
        // exit();
        $this->assign("data", $this->findOneRecord($where));
        $this->display();
    }


    // 方法功能：删除一张图pain
    public function imgDelete() {
        $where = trim($_GET['id']);
        $mode = M("Resource");
        if ($mode -> where("id = $where") -> delete()) {
            alertMessage("删除成功！");
        } else {
            alertMessage("删除失败！");
        }
    }

    /**
     * 方法功能：从数据表中查询一条数据
     * @param $id 数据表中的字段值
     * @return mixed 数据表中的一条记录
     */
    private function findOneRecord($id) {
        $record = M("Resource");
        return $record->where("id = $id")->find();
    }

    /**
     * 方法功能：从数据库中查询数据
     * @param $type 文件的类型
     * @return mixed 数据库查询结果
     */
    private function getData($type) {
        $theData = M('Resource'); // 实例化User对象
        $count = $theData->where("rsType = $type")->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $theData->where("rsType = $type and rsAble = 1")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('data',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display($this->the_t[$type]);
    }
    // 定义各种文件对应的模板
    protected  $the_t = array(
        "zip", "picture", "document", "video",
    );
    // 定义数据库中文件类型
    protected $the_type = array(
        "pic" => 1,
        "file" => 2,
        "video" => 3,
        "other" => 0,
    );
    // 定义上传文件的格式
    protected $fileType = array(
        "pic" => array("bmp", "jpg", "jpeg", "png", "gif"),
        "file" => array("txt", "doc", "docx", "ppt", "pptx", "xls", "xlsx", "wps", "pdf"),
        "video" => array("avi", "rm", "mpg", "mov", "swf"),
        "other" => array("rar", "zip"),
    );
    // 定义上传文件的根路径
    protected $root = array(
        "pic" => "./Uploads/Pic",
        "file" => "./Uploads/File",
        "video" => "./Uploads/Video",
        "other" => "./Uploads/Other",
    );
}
