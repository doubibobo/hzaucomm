<?php
/**
 * Created by PhpStorm.
 * User: zhuch
 * Date: 2017/8/5
 * Time: 10:29
 */
namespace Home\Model;
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
        'Message' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Message',
            'foreign_key'   => 'msid',
            'mapping_name'  => 'allans',
            // 定义更多的关联属性
            'mapping_fields' => array(
                'id', 'msname', 'msct','msdate'
            ),
        ),

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