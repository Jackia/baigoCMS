<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}


/*-------------控制中心通用类-------------*/
class GENERAL_CONSOLE {

    public $config;
    public $dspType = '';

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->obj_file  = new CLASS_FILE();
        $this->obj_tpl  = new CLASS_TPL(BG_PATH_TPLSYS . 'console' . DS . BG_DEFAULT_UI); //初始化视图对象

        $this->obj_tpl->opt         = $GLOBALS['obj_config']->arr_opt; //系统设置配置文件
        $this->obj_tpl->consoleMod  = fn_include(BG_PATH_INC . 'consoleMod.inc.php'); //菜单配置文件
        $this->obj_tpl->profile     = fn_include(BG_PATH_INC . 'profile.inc.php'); //个人设置配置文件

        //语言文件
        $this->obj_tpl->lang = array(
            'common'        => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'common.php'), //通用
            'opt'           => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'opt.php'), //系统设置
            'rcode'         => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'rcode.php'), //返回代码
            'consoleMod'    => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'consoleMod.php'), //菜单
        );

        if (file_exists(BG_PATH_LANG . $this->config['lang'] . DS . 'console' . DS . $GLOBALS['route']['bg_mod'] . '.php')) {
            $this->obj_tpl->lang['mod'] = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'console' . DS . $GLOBALS['route']['bg_mod'] . '.php');
        }

        $_mdl_link                  = new MODEL_LINK();
        $this->obj_tpl->linkRows    = $_mdl_link->mdl_cache('console');

        $_mdl_pm                    = new MODEL_PM();

        $this->obj_tpl->pm = array(
            'status'    => $_mdl_pm->arr_status,
            'type'      => $_mdl_pm->arr_type,
        );

        $this->obj_tpl->setModule();

        $this->mdl_admin  = new MODEL_ADMIN(); //设置管理员对象
        $this->mdl_group  = new MODEL_GROUP(); //设置组对象

        $GLOBALS['obj_plugin']->trigger('action_console_init'); //管理后台初始化时触发
    }


    /*============验证 session, 并获取用户信息============
    返回数组
        admin_id ID
        admin_open_label OPEN ID
        admin_open_site OPEN 站点
        admin_note 备注
        group_allow 权限
        str_rcode 提示信息
    */
    function ssin_begin() {
        $_num_ssinTimeDiff      = fn_session('admin_ssin_time') + BG_DEFAULT_SESSION; //session有效期
        $_num_cookieTimeDiff    = fn_cookie('admin_ssin_time') + BG_DEFAULT_SESSION; //session有效期

        if (fn_isEmpty(fn_session('admin_id')) || fn_isEmpty(fn_session('admin_ssin_time')) || fn_isEmpty(fn_session('admin_hash')) || $_num_ssinTimeDiff < time() || fn_isEmpty(fn_cookie('admin_id')) || fn_isEmpty(fn_cookie('admin_ssin_time')) || fn_isEmpty(fn_cookie('admin_hash')) || $_num_cookieTimeDiff < time()) {
            if (fn_isEmpty(fn_cookie('remenber_admin_id')) || fn_isEmpty(fn_cookie('remenber_admin_hash')) || fn_isEmpty(fn_cookie('remenber_hash_time'))) {
                $this->ssin_end();
                return array(
                    'rcode' => 'x020402',
                );
            } else {
                $_num_cookieRemenberDiff = fn_cookie('remenber_hash_time') + BG_DEFAULT_REMENBER; //记住密码有效期
                if ($_num_cookieRemenberDiff < time()) {
                    $this->ssin_end();
                    return array(
                        'rcode' => 'x020402',
                    );
                }

                $_arr_adminRow = $this->mdl_admin->mdl_read(fn_cookie('remenber_admin_id'));
                if ($_arr_adminRow['rcode'] != 'y020102') {
                    $this->ssin_end();
                    return $_arr_adminRow;
                }

                if ($_arr_adminRow['admin_status'] == 'disable') {
                    $this->ssin_end();
                    return array(
                        'rcode' => 'x020401',
                    );
                }

                if ($this->hash_process($_arr_adminRow) != fn_cookie('remenber_admin_hash')){
                    $this->ssin_end();
                    return array(
                        'rcode' => 'x020403',
                    );
                }

                $this->session_process($_arr_adminRow, 'auto');
            }
        }

        $_arr_adminRow = $this->mdl_admin->mdl_read(fn_session('admin_id'));
        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->ssin_end();
            return $_arr_adminRow;
        }

        if ($_arr_adminRow['admin_status'] == 'disable') {
            $this->ssin_end();
            return array(
                'rcode' => 'x020401',
            );
        }

        if ($this->hash_process($_arr_adminRow) != fn_session('admin_hash') || $this->hash_process($_arr_adminRow) != fn_cookie('admin_hash')){
            $this->ssin_end();
            return array(
                'rcode' => 'x020403',
            );
        }

        if ($_arr_adminRow['admin_group_id'] > 0) {
            $_arr_groupRow = $this->mdl_group->mdl_read($_arr_adminRow['admin_group_id']);

            if (isset($_arr_groupRow['group_status']) && $_arr_groupRow['group_status'] == 'disable') {
                $this->ssin_end();
                return array(
                    'rcode' => 'x040401',
                );
            }

            $_arr_adminRow['groupRow'] = $_arr_groupRow;
        }

        fn_session('admin_ssin_time', 'mk', time());
        fn_cookie('admin_id', 'mk', fn_session('admin_id'), BG_DEFAULT_SESSION, BG_URL_CONSOLE);
        fn_cookie('admin_ssin_time', 'mk', time(), BG_DEFAULT_SESSION, BG_URL_CONSOLE);
        fn_cookie('admin_hash', 'mk', fn_session('admin_hash'), BG_DEFAULT_SESSION, BG_URL_CONSOLE);

        return $_arr_adminRow;
    }


    function ssin_login($num_adminId, $str_accessToken, $tm_accessExpire, $str_refreshToken, $tm_refreshExpire, $str_remenber = '', $str_loginType = 'form') {
        $_arr_adminRow = $this->mdl_admin->mdl_read($num_adminId); //本地数据库处理

        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->ssin_end();
            return $_arr_adminRow;
        }

        if ($_arr_adminRow['admin_status'] == 'disable') {
            $this->ssin_end();
            return array(
                'rcode' => 'x020401',
            );
        }

        $_arr_loginRow = $this->mdl_admin->mdl_login($num_adminId, $str_accessToken, $tm_accessExpire, $str_refreshToken, $tm_refreshExpire);

        $_arr_loginRow['admin_name'] = $_arr_adminRow['admin_name']; //用于 hash

        if ($str_remenber == 'remenber') {
            fn_cookie('remenber_admin_id', 'mk', $num_adminId, BG_DEFAULT_REMENBER, BG_URL_CONSOLE);
            fn_cookie('remenber_admin_hash', 'mk', $this->hash_process($_arr_loginRow), BG_DEFAULT_REMENBER, BG_URL_CONSOLE);
            fn_cookie('remenber_hash_time', 'mk', time(), BG_DEFAULT_REMENBER, BG_URL_CONSOLE);
        }

        $this->session_process($_arr_loginRow, $str_loginType);

        return array(
            'rcode' => 'ok',
        );
    }

    /** 结束登录 session
     * $this->ssin_end function.
     *
     * @access public
     * @return void
     */
    function ssin_end() {
        fn_session('admin_id', 'unset');
        fn_session('admin_ssin_time', 'unset');
        fn_session('admin_hash', 'unset');
        fn_cookie('admin_id', 'unset', '', '', BG_URL_CONSOLE);
        fn_cookie('admin_ssin_time', 'unset', '', '', BG_URL_CONSOLE);
        fn_cookie('admin_hash', 'unset', '', '', BG_URL_CONSOLE);
        fn_cookie('remenber_admin_id', 'unset', '', '', BG_URL_CONSOLE);
        fn_cookie('remenber_admin_hash', 'unset', '', '', BG_URL_CONSOLE);
        fn_cookie('remenber_hash_time', 'unset', '', '', BG_URL_CONSOLE);
    }


    function chk_install() {
        $_str_rcode = '';
        $_str_jump  = '';

        if (file_exists(BG_PATH_CONFIG . 'installed.php')) { //如果新文件存在
            fn_include(BG_PATH_CONFIG . 'installed.php', 'require_once'); //载入
        } else if (file_exists(BG_PATH_CONFIG . 'is_install.php')) { //如果旧文件存在
            $this->obj_file->file_copy(BG_PATH_CONFIG . 'is_install.php', BG_PATH_CONFIG . 'installed.php'); //拷贝
            fn_include(BG_PATH_CONFIG . 'installed.php', 'require_once'); //载入
        } else { //如已安装文件不存在
            $_str_rcode = 'x030415';
            $_str_jump  = BG_URL_INSTALL;
        }

        if (defined('BG_INSTALL_PUB') && PRD_CMS_PUB > BG_INSTALL_PUB) { //如果小于当前版本
            $_str_rcode = 'x030416';
            $_str_jump  = BG_URL_INSTALL . 'index.php?m=upgrade';
        }

        if (!fn_isEmpty($_str_rcode)) {
            switch ($this->dspType) {
                case 'result':
                    $_arr_tplData = array(
                        'rcode' => $_str_rcode,
                    );
                    $this->obj_tpl->tplDisplay('result', $_arr_tplData);
                break;

                default:
                    header('Location: ' . $_str_jump);
                    exit;
                break;
            }
        }
    }


    function is_admin($arr_adminRow) {
        $_str_rcode = '';
        $_str_jump  = '';

        if ($arr_adminRow['rcode'] != 'y020102') {
            $this->ssin_end();
            $_str_rcode = $arr_adminRow['rcode'];

            if ($GLOBALS['view'] != 'iframe' && $GLOBALS['view'] != 'modal') {
                $_str_forwart   = fn_forward(fn_server('REQUEST_URI'));
                $_str_jump      = BG_URL_CONSOLE . 'index.php?m=login&forward=' . $_str_forwart;
            }
        }

        if (!fn_isEmpty($_str_rcode)) {
            switch ($this->dspType) {
                case 'result':
                    $_arr_tplData = array(
                        'rcode' => $_str_rcode,
                    );
                    $this->obj_tpl->tplDisplay('result', $_arr_tplData);
                break;

                default:
                    if (!fn_isEmpty($_str_jump)) {
                        header('Location: ' . $_str_jump);
                        exit;
                    }

                    $_arr_tplData = array(
                        'rcode' => $_str_rcode,
                    );
                    $this->obj_tpl->tplDisplay('error', $_arr_tplData);
                break;
            }
        }
    }


    private function hash_process($arr_adminRow) {
        return fn_baigoCrypt($arr_adminRow['admin_id'] . $arr_adminRow['admin_name'] . $arr_adminRow['admin_time_login'], $arr_adminRow['admin_ip']);
    }


    private function session_process($arr_adminRow, $str_loginType = 'form') {
        fn_session('admin_id', 'mk', $arr_adminRow['admin_id']);
        fn_session('admin_ssin_time', 'mk', time());
        fn_session('admin_hash', 'mk', $this->hash_process($arr_adminRow));
        fn_session('admin_login_type', 'mk', $str_loginType);
        fn_cookie('admin_id', 'mk', $arr_adminRow['admin_id'], BG_DEFAULT_SESSION, BG_URL_CONSOLE);
        fn_cookie('admin_ssin_time', 'mk', time(), BG_DEFAULT_SESSION, BG_URL_CONSOLE);
        fn_cookie('admin_hash', 'mk', $this->hash_process($arr_adminRow), BG_DEFAULT_SESSION, BG_URL_CONSOLE);
        fn_cookie('admin_login_type', 'mk', $str_loginType, BG_DEFAULT_SESSION, BG_URL_CONSOLE);
    }
}
