<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

$arr_set = array(
    'base'          => true,
    'ssin'          => true,
    'db'            => true,
);

$obj_runtime->run($arr_set);

$ctrl_article = new CONTROL_CONSOLE_UI_ARTICLE();

switch ($GLOBALS['route']['bg_act']) {
    case 'show_content':
        $ctrl_article->ctrl_show_content();
    break;

    case 'show':
        $ctrl_article->ctrl_show();
    break;

    case 'form':
        $ctrl_article->ctrl_form();
    break;

    default:
        $ctrl_article->ctrl_list();
    break;
}
