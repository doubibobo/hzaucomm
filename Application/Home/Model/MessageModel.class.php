<?php
/**
 * Created by PhpStorm.
 * User: zhuch
 * Date: 2017/8/5
 * Time: 10:35
 */
namespace Home\Model;
use Think\Model\RelationModel;
class MessageModel extends RelationModel {
    protected $_link = array(
        'Msas' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'Msas',
            'foreign_key'   => 'msid',
            'mapping_order' => 'asdate desc',
            'mapping_name'  => 'allans',
            'mapping_fields' => array(
                'id',
                'asct',
                'asdate',
                'asowner',
                'aslike',
                'ashaving',
            ),
            // 定义更多的关联属性
        ),

       'theThing' => array(
           'mapping_type'  => self::BELONGS_TO,
           'class_name'    => 'Student',
           'foreign_key'   => 'msowner',
           'mapping_name'  => 'theOwner',
           // 定义更多的关联属性
       ),

//        'Message' => array(
//            'mapping_type'  => self::HAS_MANY,
//            'class_name'    => 'Msas',
//            'foreign_key'   => 'msid',
//            'mapping_name'  => 'articles',
//            'mapping_order' => 'msdate desc',
//            // 定义更多的关联属性
//        ),

    );
}