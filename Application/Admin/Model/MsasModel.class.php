<?php
/**
 * Created by PhpStorm.
 * User: zhuch
 * Date: 2017/8/5
 * Time: 10:29
 */
namespace Admin\Model;
use Think\Model\RelationModel;
class MsasModel extends RelationModel {
    protected $_link = array(
        'anscomment' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'Anscomment',
            'foreign_key'   => 'ansid',
            'mapping_order' => 'cmmtdate desc',
            'mapping_name'  => 'allcomment',
            'mapping_fields' => array(
                'id', 'cmmtct', 'cmmtdate', 'cmmtowner', 'cmmthaving',
            ),
            // 定义更多的关联属性
        ),
    );
}