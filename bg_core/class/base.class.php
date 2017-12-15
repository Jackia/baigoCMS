<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------基类-------------*/
class CLASS_BASE {

    public $config; //配置
    public $key_pub; //公钥

    function __construct() { //构造函数
        //$this->getUi(); //获取界面类型
        $this->getKeyPub(); //获取公钥
        $this->getLang(); //获取当前语言
        $this->setTimezone(); //设置时区
    }


    function getKeyPub() {
        if (!file_exists(BG_PATH_CACHE . 'sys' . DS . 'crypt_key_pub.php')) {
            $_str_rand  = fn_rand();
            $_str_key   = '<?php return \'' . $_str_rand . '\';';
            $this->obj_dir->put_file(BG_PATH_CACHE . 'sys' . DS . 'crypt_key_pub.php', $_str_key);
        }

        $this->key_pub = fn_include(BG_PATH_CACHE . 'sys' . DS . 'crypt_key_pub.php');
    }


    /**
     * getLang function.
     *
     * @access public
     * @return void
     */
    function getLang() {
        if (defined('BG_SWITCH_LANG') && BG_SWITCH_LANG > 0) { //语言开关为开
            $str_lang = fn_getSafe(fn_get('lang'), 'txt', '');

            if (fn_isEmpty($str_lang)) { //查询串指定
                /*if (fn_cookie('cookie_lang')) { //cookie 指定
                    $_str_return = fn_cookie('cookie_lang');
                } else { //系统识别*/
                    if (fn_isEmpty(fn_server('HTTP_ACCEPT_LANGUAGE'))) {
                        $_str_return = BG_DEFAULT_LANG; //客户端是中文
                    } else {
                        $_str_agentUser = fn_server('HTTP_ACCEPT_LANGUAGE');
                        if (stristr($_str_agentUser, 'zh')) {
                            $_str_return = BG_DEFAULT_LANG; //客户端是中文
                        } else {
                            $_str_return = 'en'; //客户端是英文
                        }
                    }

                //}
            } else {
                $_str_return = $str_lang;
            }
        } else { //语言开关为关
            $_str_return = BG_DEFAULT_LANG; //默认语言
        }

        $this->config['lang'] = $_str_return;
    }


    /**
     * getUi function.
     *
     * @access public
     * @return void
     */
    function getUi() {
        if (BG_SWITCH_UI == 1) { //界面开关为开
            $str_ui = fn_getSafe(fn_get('ui'), 'txt', '');

            if (fn_isEmpty($str_ui)) { //查询串指定
                /*if (fn_cookie('cookie_ui')) { //cookie 指定
                    $_str_return = fn_cookie('cookie_ui');
                } else { //系统识别*/
                    $_str_return = BG_DEFAULT_UI; //客户端是 pc
                //}
            } else {
                $_str_return = $str_ui;
            }
        } else { //界面开关为关
            $_str_return = BG_DEFAULT_UI; //默认界面
        }
        $this->config['ui'] = $_str_return;
    }


    /**
     * setTimezone function.
     *
     * @access public
     * @return void
     */
    function setTimezone() {
        date_default_timezone_set(BG_SITE_TIMEZONE);
    }
}