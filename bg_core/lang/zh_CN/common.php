<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------------------通用-------------------------*/
return array(
    /*------页面标题------*/
    'page' => array(
        'console'   => '管理后台',
        'gening'    => '正在生成',
        'rcode'     => '提示信息',

        'opt'       => '系统设置', //系统设置
        'optPlugin' => '插件管理',
        'optApp'    => 'API 授权设置',
        'dbconfig'  => '数据库设置',
        'chkver'    => '检查更新',
    ),

    /*------说明文字------*/
    'label' => array(
        'month'         => '月', //月
        'article'       => '文章',
        'spec'          => '专题',
        'cate'          => '栏目',
        'call'          => '调用',
        'errPlugin'     => '经插件过滤的数据错误',
        'errInput'      => '输入错误，请检查！',
        'submitting'    => '正在提交 ...',
    ),

    'pm' => array(
        'in'    => '收件箱',
        'out'   => '已发送',
        'wait'  => '未读',
        'read'  => '已读',
    ),

    'profile' => array(
        'info'      => array(
            'icon'  => 'person',
            'title' => '个人资料',
        ),
        'prefer'    => array(
            'icon'  => 'wrench',
            'title' => '偏好设置',
        ),
        'pass'      => array(
            'icon'  => 'key',
            'title' => '密码',
        ),
        'qa'        => array(
            'icon'  => 'lock-locked',
            'title' => '密保问题',
        ),
        'mailbox'   => array(
            'icon'  => 'inbox',
            'title' => '更换邮箱',
        ),
    ),

    /*------链接------*/
    'href' => array(
        'logout'        => '退出',
        'back'          => '返回',

        'pm'            => '消息',
        'pmNew'         => '新消息',

        'pageFirst'     => '首页', //首页
        'pagePrevList'  => '上十页', //上十页
        'pagePrev'      => '上页', //上一页
        'pageNext'      => '下页', //下一页
        'pageNextList'  => '下十页', //下十页
        'pageLast'      => '末页', //尾页
    ),

    'date' => array('日', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十'),

    /*------按钮------*/
    'btn' => array(
        'ok'                => '确定', //确定
        'close'             => '关闭',
        'gen'               => '生成静态页面',
        'genOverall'        => '全面生成',
        'gen1by1'           => '逐个生成',
        'genEnforce'        => '强制生成（耗时）',
        'genOverall'        => '全面生成',
        'genList'           => '生成列表',
    ),

    'text' => array(
        'x070405'   => '尚未设置允许上传的文件类型，<a href="' . BG_URL_CONSOLE . 'index.php?m=mime&act_get=list" target="_top">立刻设置</a>',
        'x250401'   => '尚未创建栏目，<a href="' . BG_URL_CONSOLE . 'index.php?m=cate&act_get=form" target="_top">立刻创建</a>',

        'x030403' => '<h4>如需重新安装，请执行如下步骤：</h4>
            <ol>
                <li>删除 ./bg_config/installed.php 文件</li>
                <li>重新运行 <a href="' . BG_URL_INSTALL . 'index.php">' . BG_URL_INSTALL . 'index.php</a></li>
            </ol>',

        'x030404' => '<h4>数据库未正确设置：</h4>
            <ol>
                <li><a href="' . BG_URL_INSTALL . 'index.php?m=install&a=dbconfig">返回重新设置</a></li>
            </ol>',

        'x030408' => '<h4>如需重新安装，请执行如下步骤：</h4>
            <ol>
                <li>删除 ' . BG_URL_SSO . 'config/installed.php 文件</li>
                <li>重新运行 <a href="' . BG_URL_INSTALL . 'index.php?m=install&a=ssoAuto">' . BG_URL_INSTALL . 'index.php?m=install&a=ssoAuto</a></li>
            </ol>',

        'x030417' => '<h4>未通过服务器环境检查，安装无法继续：</h4>
            <ol>
                <li>重新检查环境 <a href="' . BG_URL_INSTALL . 'index.php?m=install">' . BG_URL_INSTALL . 'index.php?m=install</a></li>
                <li>根据检查结果，正确安装所必需的 PHP 扩展库。</li>
            </ol>',

        'x030418' => '<h4>未通过服务器环境检查，升级无法继续：</h4>
            <ol>
                <li>重新检查环境 <a href="' . BG_URL_INSTALL . 'index.php?m=upgrade">' . BG_URL_INSTALL . 'index.php?m=upgrade</a></li>
                <li>根据检查结果，正确安装所必需的 PHP 扩展库。</li>
            </ol>',

        'x030420' => '<h4>SSO 未正确上传</h4>
            <ol>
                <li>重新检查 SSO 是否已完整上传至 ' . BG_PATH_SSO . '</li>
                <li>重新运行 <a href="' . BG_URL_INSTALL . 'index.php?m=install&a=ssoAuto">' . BG_URL_INSTALL . 'index.php?m=install&a=ssoAuto</a></li>
            </ol>',

        'x030421' => '<h4>SSO 未正确上传</h4>
            <ol>
                <li>重新检查 SSO 是否已完整上传至 ' . BG_PATH_SSO . '</li>
                <li>重新运行 <a href="' . BG_URL_INSTALL . 'index.php?m=install&a=ssoAdmin">' . BG_URL_INSTALL . 'index.php?m=install&a=ssoAdmin</a></li>
            </ol>',
    ),
);
