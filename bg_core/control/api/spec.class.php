<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_CLASS . "api.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "app.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "spec.class.php"); //载入后台用户类

/*-------------文章类-------------*/
class API_SPEC {

	private $obj_api;
	private $mdl_app;
	private $mdl_spec;

	function __construct() { //构造函数
		$this->obj_api        = new CLASS_API();
		$this->mdl_app        = new MODEL_APP(); //设置管理组模型
		$this->mdl_spec       = new MODEL_SPEC();

		if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //验证是否已经安装
			include_once(BG_PATH_CONFIG . "is_install.php");
			if (!defined("BG_INSTALL_PUB") || PRD_CMS_PUB > BG_INSTALL_PUB) {
				$_arr_return = array(
					"str_alert" => "x030416"
				);
				$this->obj_api->halt_re($_arr_return);
			}
		} else {
			$_arr_return = array(
				"str_alert" => "x030415"
			);
			$this->obj_api->halt_re($_arr_return);
		}
	}


	/**
	 * ctl_list function.
	 *
	 * @access public
	 * @return void
	 */
	function api_get() {
		$this->app_check("get");

		$_num_specId  = fn_getSafe(fn_get("spec_id"), "int", 0);

		if ($_num_specId == 0) {
			$_arr_return = array(
				"str_alert" => "x180204",
			);
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_specRow = $this->mdl_spec->mdl_read($_num_specId);
		if ($_arr_specRow["str_alert"] != "y180102") {
			$this->obj_api->halt_re($_arr_specRow);
		}

		if ($_arr_specRow["spec_status"] != "show") {
			$_arr_return = array(
				"str_alert" => "x180102",
			);
			$this->obj_api->halt_re($_arr_return);
		}

		unset($_arr_specRow["urlRow"]);

		$this->obj_api->halt_re($_arr_specRow, true);
	}


	/**
	 * ctl_list function.
	 *
	 * @access public
	 * @return void
	 */
	function api_list() {
		$this->app_check("get");

		$_str_key         = fn_getSafe(fn_get("key"), "txt", "");
		$_num_perPage     = fn_getSafe(fn_get("per_page"), "int", BG_SITE_PERPAGE);

		$_num_specCount   = $this->mdl_spec->mdl_count($_str_key, "show");
		$_arr_page        = fn_page($_num_specCount, $_num_perPage); //取得分页数据
		$_arr_specRows    = $this->mdl_spec->mdl_list($_num_perPage, $_arr_page["except"], $_str_key, "show");

		foreach ($_arr_specRows as $_key=>$_value) {
			unset($_arr_specRows[$_key]["urlRow"]);
		}

		$_arr_return = array(
			"pageRow"    => $_arr_page,
			"specRows"   => $_arr_specRows,
		);

		//print_r($_arr_return);

		$this->obj_api->halt_re($_arr_return, true);
	}


	/**
	 * app_check function.
	 *
	 * @access private
	 * @param mixed $num_appId
	 * @param string $str_method (default: "get")
	 * @return void
	 */
	private function app_check($str_method = "get") {
		$this->appGet = $this->obj_api->app_get($str_method);

		if ($this->appGet["str_alert"] != "ok") {
			$this->obj_api->halt_re($this->appGet);
		}

		$_arr_appRow = $this->mdl_app->mdl_read($this->appGet["app_id"]);
		if ($_arr_appRow["str_alert"] != "y190102") {
			$this->obj_api->halt_re($_arr_appRow);
		}
		$this->appAllow = $_arr_appRow["app_allow"];

		$_arr_appChk = $this->obj_api->app_chk($this->appGet, $_arr_appRow);
		if ($_arr_appChk["str_alert"] != "ok") {
			$this->obj_api->halt_re($_arr_appChk);
		}
	}
}
