<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿编辑
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

return array(
    'mysqli'      => 'MySQL 增强版扩展 (MySQLi)',
    'gd'          => '图像处理和 GD (GD)',
    'mbstring'    => '多字节字符串 (MBString)',
    'curl'        => 'Client URL 库 (cURL)',
    'ftp'         => 'FTP',
    'dom'         => 'Document Object Model (DOM)',
);
