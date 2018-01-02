<?php
return array(
    //修改左界定符
    'TMPL_L_DELIM' => '<{',
    //修改右界定符
    'TMPL_R_DELIM' => '}>',

    // 'DB_CONFIG1' => array(
        //设置数据库类型
        'DB_TYPE' => 'mysql',
        //设置服务器地址
        'DB_HOST' => '127.0.0.1',
        //设置服务器名称
        'DB_NAME' => 'zkycomm',
        //设置服务器角色名称
        'DB_USER' => 'doubibobo',
        //设置服务器角色密码
        'DB_PWD' => '12151618',
        //设置服务器端口
        'DB_PORT' =>'3306',
        //设置表前缀
        'DB_PREFIX' => 'tp_',
        //'DB_DSN' => 'mysql://root:@localhost:3306/zky',
        //开启调试模式
        'SHOW_PAGE_TRACE' => true,
    // ),


    // 第一个数据库的配置
    'DB_CONFIG2'=>array(
        'DB_TYPE'=>'mysql',
        'DB_HOST'=>'127.0.0.1',
        'DB_NAME' => 'zkyuser',
        'DB_USER'=>'doubibobo',
        'DB_PWD'=>'12151618',
        'DB_PORT' =>'3306',
        'DB_PREFIX' => 'tp_',
        'SHOW_PAGE_TRACE' => true,
    ),
);
?>