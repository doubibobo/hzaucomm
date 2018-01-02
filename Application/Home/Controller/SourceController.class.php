<?php
namespace Home\Controller;
use Think\Controller;
use Think\Upload;

/**
 * Class SourceController
 * @package Home\Controller
 * 类功能：后台资源上传和资源共享模块代码
 */
class SourceController extends Controller {

    public function source() {
        $the_content_model = M("Intro");
        $the_content = $the_content_model->where("id = 3")->find();
        $this->assign("data", $the_content);
        $this->show();
    }

    /**
     * 资源共享模块的图片展示
     */
    public function picture() {
        $this->getData($this->the_type['pic']);
    }

    /**
     * 资源共享模块的文件展示
     */
    public function document() {
        // tranOffice("./Uploads/123.docx");
        $this->getData($this->the_type['file']);
    }

    /**
     * 资源共享模块的视频展示
     */
    public function video() {
        $this->getData($this->the_type['video']);
    }

    /**
     * 资源共享模块的其他展示
     */
    public function zip() {
        $this->getData($this->the_type['other']);
    }

    /**
     * 方法功能：图片资源的详细展示
     */
    public function pictureshow() {
        $where = trim($_GET['id']);
        if (empty($where)) $this->error("查询错误");
        $this->assign("data", $this->findOneRecord($where));
        $this->display();
    }

    /**
     * 文件详情的展示
     */
    public function summary() {
        $where = trim($_GET['id']);
        $type = trim($_GET['cid']);
        if (empty($where)) alertMessage("查询错误");
        $theRecord = $this->findOneRecord($where);
        $pdfWay = $theRecord['rsway'];
        $pdfWay1=explode('.',$pdfWay);//om 负数从结尾开始取
        $theRealWay = $pdfWay1[1].'.pdf';
        // var_dump($pdfWay);
        tranOffice($pdfWay);
        $this->assign("data", $theRecord);
        $this->assign("theRealWay", $theRealWay);
        $this->assign("typehahaha", $type);
        $this->display();
    }

    /**
     * 资源上传模块的图片上传展示
     */
    public function pic_update() {
        $this->show();
    }

    /**
     * 资源上传模块的文件上传展示
     */
    public function doc_update() {
        $this->show();
    }

    /**
     * 资源上传模块的视频上传展示
     */
    public function vid_update() {
        $this->show();
    }

    /**
     * 资源上传模块的其他上传展示
     */
    public function zip_update() {
        $this->show();
    }

    /**
     * 方法功能：查询的展示页面
     */
    public function search() {
        (trim($_GET['type']) == "") ? alertMessage("失败") : ($theType = trim($_GET['type']));
        $thePart = trim($_POST['search']);
        // 判断搜索来源
        ($theType < 0 || $theType > 3) ? $theType = 0 : $theType;

        $this->getSearchData($thePart, $theType);
    }

    /**
     * 方法功能：根据相应的字段查询符合条件的记录
     * @param $theMap 提交的字段
     * @param $theType 文件的类型
     */
    public function getSearchData($theMap, $theType) {
        $theData = M('Resource'); // 实例化User对象
        $condition['rsType'] = $theType;
        $condition['rsName'] = array('like', "%".$theMap."%");
        $count = $theData->where($condition)->count();// 查询满足要求的总记录数
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $theData->where($condition)->order('id desc')->select();
        $this->assign('data',$list);// 赋值数据集
        $this->display($this->the_t[$theType]);
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
        $Page = new \Think\Page($count,18);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $theData->where("rsType = $type")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('data',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display($this->the_t[$type]);
    }

    /**
     * 方法功能：实现图片、文件、视频、其他文件上传的中转方法
     */
    public function doAllUpdate() {
        $theType = trim($_GET['type']);
        if (empty($theType)) alertMessage("上传失败");
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
                        'rsAble' => 0,
                        // 'rsOwner' => $_SESSION['id'],
                        'rsOwner' => 'user',
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
                    $result ? alertMessage('添加成功，请等待管理员审核') : alertMessage("添加失败，请检查网路后重试");
                }

            } else {
                alertMessage("文件大小超过限制，最大上传20M的文件");
            }
        } else {
            alertMessage("请将各项填写完整");
        }
    }

    /**
     * 转化文件的大小，用M进行计算
     * @param $size 以B为单位
     * @return float|int
     */
    private function fileLength($size) {
        return $size/1000000;
    }

    /**
     * 字段是否为空的检查
     * @param $file_name 是否包含文件
     * @param $data 待填入字段是否为空
     * @return bool 返回值
     */
    private function isNullOp($file_name, $data) {
        return (empty($file_name) or empty(trim($data['title'])) or empty(trim($data['descripe']))) ? false : true;
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
