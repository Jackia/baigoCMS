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

$ctrl_captcha = new CONTROL_CONSOLE_REQUEST_CAPTCHA();

switch ($GLOBALS['route']['bg_act']) {
    case 'chk':
        $ctrl_captcha->ctrl_check();
    break;
}
