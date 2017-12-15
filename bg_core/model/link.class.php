<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------链接模型-------------*/
class MODEL_LINK {

    public $arr_status = array('enable', 'disable');
    public $arr_type   = array('console', 'friend', 'auto');

    function __construct() { //构造函数
        $this->obj_db   = $GLOBALS['obj_db']; //设置数据库对象
        $this->obj_dir  = new CLASS_DIR();
    }



    function mdl_create_table() {
        $_str_status    = implode('\',\'', $this->arr_status);
        $_str_type     = implode('\',\'', $this->arr_type);

        $_arr_linkCreat = array(
            'link_id'       => 'int NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'link_name'     => 'varchar(300) NOT NULL COMMENT \'链接名称\'',
            'link_url'      => 'varchar(900) NOT NULL COMMENT \'链接\'',
            'link_status'   => 'enum(\'' . $_str_status . '\') NOT NULL COMMENT \'状态\'',
            'link_type'     => 'enum(\'' . $_str_type . '\') NOT NULL COMMENT \'类型\'',
            'link_cate_id'  => 'smallint NOT NULL COMMENT \'隶属于栏目\'',
            'link_order'    => 'int NOT NULL COMMENT \'排序\'',
            'link_blank'    => 'tinyint(1) NOT NULL COMMENT \'窗口类型\'',
        );

        $_num_db = $this->obj_db->create_table(BG_DB_TABLE . 'link', $_arr_linkCreat, 'link_id', '链接');

        if ($_num_db > 0) {
            $_str_rcode = 'y240105'; //更新成功
        } else {
            $_str_rcode = 'x240105'; //更新成功
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
        );
    }


    function mdl_column() {
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . 'link');

        $_arr_col = array();

        if (!fn_isEmpty($_arr_colRows)) {
            foreach ($_arr_colRows as $_key=>$_value) {
                $_arr_col[] = $_value['Field'];
            }
        }

        return $_arr_col;
    }


    function mdl_alter_table() {
        $_str_status    = implode('\',\'', $this->arr_status);
        $_str_type     = implode('\',\'', $this->arr_type);

        $_arr_col     = $this->mdl_column();
        $_arr_alter   = array();

        if (in_array('link_status', $_arr_col)) {
            $_arr_alter['link_status'] = array('CHANGE', 'enum(\'' . $_str_status . '\') NOT NULL COMMENT \'状态\'', 'link_status');
        }

        if (in_array('link_type', $_arr_col)) {
            $_arr_alter['link_type'] = array('CHANGE', 'enum(\'' . $_str_type . '\') NOT NULL COMMENT \'类型\'', 'link_type');
        }

        if (in_array('link_is_blank', $_arr_col)) {
            $_arr_alter['link_is_blank'] = array('CHANGE', 'tinyint(1) NOT NULL COMMENT \'是否打开新窗口\'', 'link_blank');
        }

        $_str_rcode = 'y240111';

        if (!fn_isEmpty($_arr_alter)) {
            $_reselt = $this->obj_db->alter_table(BG_DB_TABLE . 'link', $_arr_alter);

            if (!fn_isEmpty($_reselt)) {
                $_str_rcode = 'y240106';
            }
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    function mdl_order($str_orderType = '', $num_doId = 0, $num_targetId = 0) {

        //处理重复排序号
        $_str_sqlDistinct = 'SELECT `link_id` FROM `' . BG_DB_TABLE . 'link` WHERE `link_order` IN (SELECT `link_order` FROM `' . BG_DB_TABLE . 'link` GROUP BY `link_order` HAVING COUNT(`link_order`)>1) ORDER BY `link_id` DESC' ;
        $_obj_reselt      = $this->obj_db->query($_str_sqlDistinct);
        $_arr_row         = $this->obj_db->fetch_assoc($_obj_reselt);

        if (!fn_isEmpty($_arr_row)) {
            $_arr_selectData = array(
                'link_id',
            );

            $_arr_order = array(
                array('link_id', 'DESC'),
            );
            $_arr_lastRows  = $this->obj_db->select(BG_DB_TABLE . 'link', $_arr_selectData, '', '', $_arr_order, 1, 0); //读取倒数第一排序号
            if (isset($_arr_lastRows[0])) {
                $_arr_lastRow   = $_arr_lastRows[0];

                $_arr_updateData = array(
                    'link_order' => $_arr_row['link_id'] + 1,
                );

                $_str_sqlWhere = '`link_id`=' . $_arr_row['link_id'];

                $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_updateData, $_str_sqlWhere, true); //所有小于本条大于目标记录的数据排序号加1
            }
        }
        //end

        //
        $_arr_selectData = array(
            'link_order',
        );

        switch ($str_orderType) {
            case 'order_first':
                $_arr_order = array(
                    array('link_order', 'ASC'),
                );
                $_arr_firstRows = $this->obj_db->select(BG_DB_TABLE . 'link', $_arr_selectData, '', '', $_arr_order, 1, 0); //读取第一排序号
                if (isset($_arr_firstRows[0])) {
                    $_arr_firstRow  = $_arr_firstRows[0];
                }

                $_str_sqlWhere  = '`link_id`=' . $num_doId;
                $_arr_doRows    = $this->obj_db->select(BG_DB_TABLE . 'link', $_arr_selectData, $_str_sqlWhere, '', '', 1, 0); //读取本条排序号
                if (isset($_arr_doRows[0])) {
                    $_arr_doRow     = $_arr_doRows[0];
                } else {
                    return array(
                        'rcode' => 'x240211',
                    );
                }

                $_arr_targetData = array(
                    'link_order' => '`link_order`+1',
                );
                $_str_sqlWhere = '`link_order`<' . $_arr_doRow['link_order'];
                $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_targetData, $_str_sqlWhere, true); //所有小于本条的数据排序号加1

                $_arr_doData = array(
                    'link_order' => $_arr_firstRow['link_order'],
                );
                $_str_sqlWhere = '`link_id`=' . $num_doId;
                $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_doData, $_str_sqlWhere); //更新本条排序号为1
            break;

            case 'order_last':
                $_arr_order = array(
                    array('link_order', 'DESC'),
                );
                $_arr_lastRows  = $this->obj_db->select(BG_DB_TABLE . 'link', $_arr_selectData, '', '', $_arr_order, 1, 0); //读取倒数第一排序号
                if (isset($_arr_lastRows[0])) {
                    $_arr_lastRow   = $_arr_lastRows[0];
                }

                $_str_sqlWhere  = '`link_id`=' . $num_doId;
                $_arr_doRows    = $this->obj_db->select(BG_DB_TABLE . 'link', $_arr_selectData, $_str_sqlWhere, '', '', 1, 0); //读取本条排序号
                if (isset($_arr_doRows[0])) {
                    $_arr_doRow     = $_arr_doRows[0];
                } else {
                    return array(
                        'rcode' => 'x240211',
                    );
                }

                $_arr_targetData = array(
                    'link_order' => '`link_order`-1',
                );
                $_str_sqlWhere = '`link_order`>' . $_arr_doRow['link_order'];
                $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_targetData, $_str_sqlWhere, true); //所有大于本条的数据排序号减1

                $_arr_doData = array(
                    'link_order' => $_arr_lastRow['link_order'],
                );
                $_str_sqlWhere = '`link_id`=' . $num_doId;
                $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_doData, $_str_sqlWhere); //更新本条排序号为最大
            break;

            case 'order_after':
                $_str_sqlWhere = '`link_id`=' . $num_targetId;
                $_arr_targetRows    = $this->obj_db->select(BG_DB_TABLE . 'link', $_arr_selectData, $_str_sqlWhere, '', '', 1, 0); //读取目标记录排序号
                if (isset($_arr_targetRows[0])) {
                    $_arr_targetRow     = $_arr_targetRows[0];
                } else {
                    return array(
                        'rcode' => 'x240212',
                    );
                }

                $_str_sqlWhere      = '`link_id`=' . $num_doId;
                $_arr_doRows    = $this->obj_db->select(BG_DB_TABLE . 'link', $_arr_selectData, $_str_sqlWhere, '', '', 1, 0); //读取本条排序号
                if (isset($_arr_doRows[0])) {
                    $_arr_doRow     = $_arr_doRows[0];
                } else {
                    return array(
                        'rcode' => 'x240211',
                    );
                }

                //print_r($_arr_doRow);

                if ($_arr_targetRow['link_order'] > $_arr_doRow['link_order']) { //往下移
                    $_arr_targetData = array(
                        'link_order' => '`link_order`-1',
                    );
                    $_str_sqlWhere = '`link_order`>' . $_arr_doRow['link_order'] . ' AND `link_order`<=' . $_arr_targetRow['link_order'];
                    $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_targetData, $_str_sqlWhere, true); //所有大于本条小于目标记录的数据排序号减1

                    $_arr_doData = array(
                        'link_order' => $_arr_targetRow['link_order'],
                    );
                } else {
                    $_arr_targetData = array(
                        'link_order' => '`link_order`+1',
                    );
                    $_str_sqlWhere = '`link_order`<' . $_arr_doRow['link_order'] . ' AND `link_order`>' . $_arr_targetRow['link_order'];
                    $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_targetData, $_str_sqlWhere, true); //所有大于本条小于目标记录的数据排序号减1

                    $_arr_doData = array(
                        'link_order' => $_arr_targetRow['link_order'] + 1,
                    );
                }

                $_str_sqlWhere = '`link_id`=' . $num_doId;
                $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_doData, $_str_sqlWhere); //更新本条排序号为目标记录排序号
            break;
        }

        return array(
            'rcode' => 'y240103',
        );
    }


    /**
     * mdl_submit function.
     *
     * @access public
     * @param mixed $num_linkId
     * @param mixed $str_linkName
     * @param mixed $str_linkType
     * @param mixed $str_status
     * @return void
     */
    function mdl_submit() {

        $_arr_linkData = array(
            'link_name'     => $this->linkInput['link_name'],
            'link_url'      => $this->linkInput['link_url'],
            'link_type'     => $this->linkInput['link_type'],
            'link_status'   => $this->linkInput['link_status'],
            'link_cate_id'  => $this->linkInput['link_cate_id'],
            'link_blank'    => $this->linkInput['link_blank'],
        );

        if ($this->linkInput['link_id'] < 1) {

            $_num_linkId = $this->obj_db->insert(BG_DB_TABLE . 'link', $_arr_linkData);

            if ($_num_linkId > 0) { //数据库插入是否成功
                $_str_rcode = 'y240101';
            } else {
                return array(
                    'rcode' => 'x240101',
                );
            }

        } else {
            $_num_linkId = $this->linkInput['link_id'];
            $_num_db  = $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_linkData, '`link_id`=' . $_num_linkId);

            if ($_num_db > 0) {
                $_str_rcode = 'y240103';
            } else {
                return array(
                    'rcode' => 'x240103',
                );
            }
        }

        return array(
            'link_id'   => $_num_linkId,
            'rcode'     => $_str_rcode,
        );
    }


    /**
     * mdl_read function.
     *
     * @access public
     * @param mixed $str_link
     * @param int $num_notThisId (default: 0)
     * @return void
     */
    function mdl_read($num_linkId) {
        $_arr_linkSelect = array(
            'link_id',
            'link_name',
            'link_url',
            'link_type',
            'link_status',
            'link_cate_id',
            'link_blank',
        );

        $_str_sqlWhere = '`link_id`=' . $num_linkId;

        $_arr_linkRows = $this->obj_db->select(BG_DB_TABLE . 'link',  $_arr_linkSelect, $_str_sqlWhere, '', '', 1, 0); //检查本地表是否存在记录

        if (isset($_arr_linkRows[0])) {
            $_arr_linkRow = $_arr_linkRows[0];
        } else {
            return array(
                'rcode' => 'x240102', //不存在记录
            );
        }

        $_arr_linkRow['rcode'] = 'y240102';

        return $_arr_linkRow;
    }


    /**
     * mdl_list function.
     *
     * @access public
     * @param string $str_status (default: '')
     * @param string $str_type (default: '')
     * @return void
     */
    function mdl_list($num_no, $num_except = 0, $arr_search = array()) {
        $_arr_updateData = array(
            'link_order' => '`link_id`',
        );

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_updateData, '`link_order`<1', true); //更新数据

        $_arr_linkSelect = array(
            'link_id',
            'link_name',
            'link_type',
            'link_status',
            'link_url',
            'link_cate_id',
            'link_blank',
        );

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_arr_order = array(
            array('link_order', 'ASC'),
        );

        $_arr_linkRows = $this->obj_db->select(BG_DB_TABLE . 'link',  $_arr_linkSelect, $_str_sqlWhere, '', $_arr_order, $num_no, $num_except);

        return $_arr_linkRows;
    }


    function mdl_count($arr_search = array()) {

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_num_linkCount = $this->obj_db->count(BG_DB_TABLE . 'link', $_str_sqlWhere);

        return $_num_linkCount;
    }


    /**
     * mdl_del function.
     *
     * @access public
     * @param mixed $this->linkIds['link_ids']
     * @return void
     */
    function mdl_del() {
        $_str_linkIds = implode(',', $this->linkIds['link_ids']);

        $_num_db = $this->obj_db->delete(BG_DB_TABLE . 'link',  '`link_id` IN (' . $_str_linkIds . ')'); //删除数据

        //如车影响行数小于0则返回错误
        if ($_num_db > 0) {
            $_str_rcode = 'y240104';
        } else {
            $_str_rcode = 'x240104';
        }

        return array(
            'rcode' => $_str_rcode,
        ); //成功
    }


    function mdl_status($str_status) {
        $_str_linkId = implode(',', $this->linkIds['link_ids']);

        $_arr_linkData = array(
            'link_status' => $str_status,
        );

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'link', $_arr_linkData, '`link_id` IN (' . $_str_linkId . ')'); //更新数据

        //如车影响行数小于0则返回错误
        if ($_num_db > 0) {
            $_str_rcode = 'y240103';
        } else {
            $_str_rcode = 'x240103';
        }

        return array(
            'rcode' => $_str_rcode,
        ); //成功
    }


    function mdl_cache($str_type, $is_reGen = false) {
        if ($is_reGen || !file_exists(BG_PATH_CACHE . 'sys' . DS . 'link_' . $str_type . '.json')) {
            $this->cache_process($str_type);
        }

        if ($is_reGen) {
            foreach ($this->arr_type as $_key=>$_value) {
                $this->cache_process($_value);
            }
        }

        //$_arr_cacheReturn = fn_include(BG_PATH_CACHE . 'sys' . DS . 'link_' . $str_type . '.json');

        $_str_cacheReturn = file_get_contents(BG_PATH_CACHE . 'sys' . DS . 'link_' . $str_type . '.json');

        $_arr_cacheReturn = json_decode($_str_cacheReturn, true);

        return $_arr_cacheReturn;
    }


    function input_submit() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $this->linkInput['link_id'] = fn_getSafe(fn_post('link_id'), 'int', 0);

        if ($this->linkInput['link_id'] > 0) {
            $_arr_linkRow = $this->mdl_read($this->linkInput['link_id']);
            if ($_arr_linkRow['rcode'] != 'y240102') {
                return $_arr_linkRow;
            }
        }

        $_arr_linkName = fn_validate(fn_post('link_name'), 1, 300);
        switch ($_arr_linkName['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x240201',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x240202',
                );
            break;

            case 'ok':
                $this->linkInput['link_name'] = $_arr_linkName['str'];
            break;
        }

        $_arr_linkUrl = fn_validate(fn_post('link_url'), 1, 300);
        switch ($_arr_linkUrl['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x240203',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x240204',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x240205',
                );
            break;

            case 'ok':
                $this->linkInput['link_url'] = $_arr_linkUrl['str'];
            break;
        }

        $_arr_linkType = fn_validate(fn_post('link_type'), 1, 0);
        switch ($_arr_linkType['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x240206',
                );
            break;

            case 'ok':
                $this->linkInput['link_type'] = $_arr_linkType['str'];
            break;
        }

        $_arr_linkStatus = fn_validate(fn_post('link_status'), 1, 0);
        switch ($_arr_linkStatus['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x240207',
                );
            break;

            case 'ok':
                $this->linkInput['link_status'] = $_arr_linkStatus['str'];
            break;
        }

        $_arr_linkCateId = fn_validate(fn_post('link_cate_id'), 1, 0);
        switch ($_arr_linkCateId['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x240208',
                );
            break;

            case 'ok':
                $this->linkInput['link_cate_id'] = $_arr_linkCateId['str'];
            break;
        }

        $this->linkInput['link_blank'] = fn_getSafe(fn_post('link_blank'), 'int', 0);

        $this->linkInput['rcode'] = 'ok';

        return $this->linkInput;
    }


    /**
     * input_ids function.
     *
     * @access public
     * @return void
     */
    function input_ids() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_arr_linkIds = fn_post('link_ids');

        if (fn_isEmpty($_arr_linkIds)) {
            $_str_rcode = 'x030202';
        } else {
            foreach ($_arr_linkIds as $_key=>$_value) {
                $_arr_linkIds[$_key] = fn_getSafe($_value, 'int', 0);
            }
            $_str_rcode = 'ok';
        }

        $this->linkIds = array(
            'rcode'     => $_str_rcode,
            'link_ids'  => array_filter(array_unique($_arr_linkIds)),
        );

        return $this->linkIds;
    }


    private function sql_process($arr_search = array()) {
        $_str_sqlWhere = '1';

        if (isset($arr_search['key']) && !fn_isEmpty($arr_search['key'])) {
            $_str_sqlWhere .= ' AND `link_name` LIKE \'%' . $arr_search['key'] . '%\'';
        }

        if (isset($arr_search['type']) && !fn_isEmpty($arr_search['type'])) {
            $_str_sqlWhere .= ' AND `link_type`=\'' . $arr_search['type'] . '\'';
        }

        if (isset($arr_search['status']) && !fn_isEmpty($arr_search['status'])) {
            $_str_sqlWhere .= ' AND `link_status`=\'' . $arr_search['status'] . '\'';
        }

        if (isset($arr_search['cate_id']) && $arr_search['cate_id'] > 0) {
            $_str_sqlWhere .= ' AND `link_cate_id`=' . $arr_search['cate_id'];
        }

        return $_str_sqlWhere;
    }


    private function cache_process($str_type) {
        $_arr_search = array(
            'type'      => $str_type,
            'status'    => 'enable',
        );
        $_arr_linkRows = $this->mdl_list(1000, 0, $_arr_search);

        $_str_outPut = json_encode($_arr_linkRows);

        $_num_size = $this->obj_dir->put_file(BG_PATH_CACHE . 'sys' . DS . 'link_' . $str_type . '.json', $_str_outPut);
    }
}
