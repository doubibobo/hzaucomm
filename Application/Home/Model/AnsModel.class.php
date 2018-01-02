<?php
/**
 * Created by PhpStorm.
 * User: zhuch
 * Date: 2017/8/5
 * Time: 10:35
 */
namespace Home\Model;
use Think\Model\RelationModel;
class AnsModel extends RelationModel {
    protected $_link = array(
    'Msas' => array(
        'mapping_type'  => self::BELONGS_TO,
        'class_name'    => 'Student',
        'foreign_key'   => 'asowner',
        'mapping_order' => 'asdate desc',
        'mapping_name'  => 'theOwner',
        // 定义更多的关联属性
    ),
    );
}