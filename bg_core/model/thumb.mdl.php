<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------缩略图模型-------------*/
class MODEL_THUMB {

    public $arr_type = array('ratio', 'cut');

    function __construct() { //构造函数
        $this->obj_db    = $GLOBALS['obj_db']; //设置数据库对象
        $this->obj_file  = new CLASS_FILE();
    }

    function mdl_create_table() {
        $_str_type = implode('\',\'', $this->arr_type);

        $_arr_thumbCreat = array(
            'thumb_id'       => 'smallint NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'thumb_width'    => 'smallint NOT NULL COMMENT \'宽度\'',
            'thumb_height'   => 'smallint NOT NULL COMMENT \'高度\'',
            'thumb_type'     => 'enum(\'' . $_str_type . '\') NOT NULL COMMENT \'类型\'',
        );

        $_num_db = $this->obj_db->create_table(BG_DB_TABLE . 'thumb', $_arr_thumbCreat, 'thumb_id', '缩略图');

        if ($_num_db > 0) {
            $_str_rcode = 'y090105'; //更新成功
        } else {
            $_str_rcode = 'x090105'; //更新成功
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
        );
    }


    function mdl_column() {
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . 'thumb');

        $_arr_col = array();

        if (!fn_isEmpty($_arr_colRows)) {
            foreach ($_arr_colRows as $_key=>$_value) {
                $_arr_col[] = $_value['Field'];
            }
        }

        return $_arr_col;
    }


    function mdl_alter_table() {
        $_str_type = implode('\',\'', $this->arr_type);

        $_arr_col     = $this->mdl_column();
        $_arr_alter   = array();

        if (in_array('thumb_id', $_arr_col)) {
            $_arr_alter['thumb_id'] = array('CHANGE', 'smallint NOT NULL AUTO_INCREMENT COMMENT \'ID\'', 'thumb_id');
        }

        if (in_array('thumb_width', $_arr_col)) {
            $_arr_alter['thumb_width'] = array('CHANGE', 'smallint NOT NULL COMMENT \'宽度\'', 'thumb_width');
        }

        if (in_array('thumb_height', $_arr_col)) {
            $_arr_alter['thumb_height'] = array('CHANGE', 'smallint NOT NULL COMMENT \'高度\'', 'thumb_height');
        }

        if (in_array('thumb_type', $_arr_col)) {
            $_arr_alter['thumb_type'] = array('CHANGE', 'enum(\'' . $_str_type . '\') NOT NULL COMMENT \'类型\'', 'thumb_type');
        }

        $_str_rcode = 'y090111';

        if (!fn_isEmpty($_arr_alter)) {
            $_reselt = $this->obj_db->alter_table(BG_DB_TABLE . 'thumb', $_arr_alter);

            if (!fn_isEmpty($_reselt)) {
                $_str_rcode = 'y090106';
                $_arr_thumbData = array(
                    'thumb_type' => $this->arr_type[0],
                );
                $this->obj_db->update(BG_DB_TABLE . 'thumb', $_arr_thumbData, 'LENGTH(`thumb_type`)<1'); //更新数据
            }
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /*============提交缩略图============
    @num_thumbWidth 宽度
    @num_thumbHeight 高度
    @str_thumbType 缩略图类型

    返回多维数组
        num_thumbId ID
        str_rcode 提示
    */
    function mdl_submit() {
        $_arr_thumbData = array(
            'thumb_width'    => $this->thumbInput['thumb_width'],
            'thumb_height'   => $this->thumbInput['thumb_height'],
            'thumb_type'     => $this->thumbInput['thumb_type'],
        );

        if ($this->thumbInput['thumb_id'] < 1) {
            $_num_thumbId = $this->obj_db->insert(BG_DB_TABLE . 'thumb', $_arr_thumbData);

            if ($_num_thumbId > 0) { //数据库插入是否成功
                $_str_rcode = 'y090101';
            } else {
                return array(
                    'rcode' => 'x090101',
                );
            }
        } else {
            $_num_thumbId    = $this->thumbInput['thumb_id'];
            $_num_db      = $this->obj_db->update(BG_DB_TABLE . 'thumb', $_arr_thumbData, '`thumb_id`=' . $_num_thumbId);

            if ($_num_db > 0) { //数据库插入是否成功
                $_str_rcode = 'y090103';
            } else {
                return array(
                    'rcode' => 'x090103',
                );
            }
        }

        return array(
            'thumb_id'   => $_num_thumbId,
            'rcode'  => $_str_rcode,
        );
    }

    function mdl_read($num_thumbId) {

        $_arr_thumbSelect = array(
            'thumb_id',
            'thumb_width',
            'thumb_height',
            'thumb_type',
        );

        $_str_sqlWhere    = '`thumb_id`=' . $num_thumbId;

        $_arr_thumbRows   = $this->obj_db->select(BG_DB_TABLE . 'thumb',  $_arr_thumbSelect, $_str_sqlWhere, '', '', 1, 0); //查询数据

        if (isset($_arr_thumbRows[0])) { //用户名不存在则返回错误
            $_arr_thumbRow    = $_arr_thumbRows[0];
        } else {
            return array(
                'rcode' => 'x090102', //不存在记录
            );
        }

        $_arr_thumbRow['rcode'] = 'y090102';

        return $_arr_thumbRow;
    }


    function mdl_check($num_thumbWidth = 0, $num_thumbHeight = 0, $str_thumbType = '', $num_notId = 0) {
        if ($num_thumbWidth == 100 && $num_thumbHeight == 100 && $str_thumbType == 'cut') {
            return array(
                'thumb_width'   => 100,
                'thumb_height'  => 100,
                'thumb_type'    => 'cut',
                'rcode'         => 'y090102', //存在记录
            );
        }

        $_arr_thumbSelect = array(
            'thumb_id',
            'thumb_width',
            'thumb_height',
            'thumb_type',
        );

        $_str_sqlWhere = '1';

        if ($num_thumbWidth > 0) {
            $_str_sqlWhere .= ' AND `thumb_width`=' . $num_thumbWidth;
        }

        if ($num_thumbHeight > 0) {
            $_str_sqlWhere .= ' AND `thumb_height`=' . $num_thumbHeight;
        }

        if (!fn_isEmpty($str_thumbType)) {
            $_str_sqlWhere .= ' AND `thumb_type`=\'' . $str_thumbType . '\'';
        }

        if ($num_notId > 0) {
            $_str_sqlWhere .= ' AND `thumb_id`<>' . $num_notId;
        }

        $_arr_thumbRows = $this->obj_db->select(BG_DB_TABLE . 'thumb',  $_arr_thumbSelect, $_str_sqlWhere, '', '', 1, 0); //查询数据

        if (isset($_arr_thumbRows[0])) { //用户名不存在则返回错误
            $_arr_thumbRow = $_arr_thumbRows[0];
        } else {
            return array(
                'rcode' => 'x090102', //不存在记录
            );
        }

        $_arr_thumbRow['rcode'] = 'y090102';

        return $_arr_thumbRow;
    }


    /*============列出缩略图============
    返回多维数组
        thumb_id 缩略图 ID
        thumb_width 缩略图宽度
        thumb_height 缩略图高度
    */
    function mdl_list($num_no, $num_except = 0) {
        $_arr_thumbSelect = array(
            'thumb_id',
            'thumb_width',
            'thumb_height',
            'thumb_type',
        );

        $_str_sqlWhere    = '1';

        $_arr_order = array(
            array('thumb_id', 'DESC'),
        );

        $_arr_thumb       = $this->obj_db->select(BG_DB_TABLE . 'thumb',  $_arr_thumbSelect, $_str_sqlWhere, '', $_arr_order, $num_no, $num_except); //查询数据
        $_arr_thumbRow[] = array(
            'thumb_id'       => 0,
            'thumb_width'    => 100,
            'thumb_height'   => 100,
            'thumb_type'     => 'cut',
        );
        $_arr_thumbRows = array_merge($_arr_thumbRow, $_arr_thumb);

        return $_arr_thumbRows;
    }


    function mdl_count() {
        $_str_sqlWhere = '1';

        $_num_count = $this->obj_db->count(BG_DB_TABLE . 'thumb', $_str_sqlWhere); //查询数据

        return $_num_count;
    }


    /**
     * mdl_del function.
     *
     * @access public
     * @param mixed $this->thumbIds['thumb_ids']
     * @return void
     */
    function mdl_del() {
        $_str_thumbId = implode(',', $this->thumbIds['thumb_ids']);

        $_num_db = $this->obj_db->delete(BG_DB_TABLE . 'thumb', '`thumb_id` IN (' . $_str_thumbId . ')'); //删除数据

        //如车影响行数小于0则返回错误
        if ($_num_db > 0) {
            $_str_rcode = 'y090104';
        } else {
            $_str_rcode = 'x090104';
        }

        return array(
            'rcode' => $_str_rcode
        );
    }


    function mdl_cache($is_reGen = false) {
        if ($is_reGen || !file_exists(BG_PATH_CACHE . 'sys' . DS . 'thumb_list.json')) {
            $_arr_thumbRows = $this->mdl_list(100);

            $_str_outPut = json_encode($_arr_thumbRows);

            $_num_size = $this->obj_file->file_put(BG_PATH_CACHE . 'sys' . DS . 'thumb_list.json', $_str_outPut);
        }

        $_str_cacheReturn = $this->obj_file->file_read(BG_PATH_CACHE . 'sys' . DS . 'thumb_list.json');

        $_arr_cacheReturn = json_decode($_str_cacheReturn, true);

        return $_arr_cacheReturn;
    }


    function input_submit() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $this->thumbInput['thumb_id'] = fn_getSafe(fn_post('thumb_id'), 'int', 0);

        if ($this->thumbInput['thumb_id'] > 0) {
            $_arr_thumbRow = $this->mdl_read($this->thumbInput['thumb_id']);
            if ($_arr_thumbRow['rcode'] != 'y090102') {
                return $_arr_thumbRow;
            }
        }

        $_arr_thumbWidth = fn_validate(fn_post('thumb_width'), 1, 0);
        switch ($_arr_thumbWidth['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x090201',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x090202',
                );
            break;

            case 'ok':
                $this->thumbInput['thumb_width'] = $_arr_thumbWidth['str'];
            break;

        }

        $_arr_thumbHeight = fn_validate(fn_post('thumb_height'), 1, 0);
        switch ($_arr_thumbHeight['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x090203',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x090204',
                );
            break;

            case 'ok':
                $this->thumbInput['thumb_height'] = $_arr_thumbHeight['str'];
            break;

        }

        $_arr_thumbType = fn_validate(fn_post('thumb_type'), 1, 0);
        switch ($_arr_thumbType['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x090205',
                );
            break;

            case 'ok':
                $this->thumbInput['thumb_type'] = $_arr_thumbType['str'];
            break;

        }

        $_arr_thumbRow = $this->mdl_check($this->thumbInput['thumb_width'], $this->thumbInput['thumb_height'], $this->thumbInput['thumb_type'], $this->thumbInput['thumb_id']);
        if ($_arr_thumbRow['rcode'] == 'y090102') {
            return array(
                'rcode' => 'x090206',
            );
        }

        $this->thumbInput['rcode'] = 'ok';
        return $this->thumbInput;
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

        $_arr_thumbIds = fn_post('thumb_ids');

        if (fn_isEmpty($_arr_thumbIds)) {
            $_str_rcode = 'x030202';
        } else {
            foreach ($_arr_thumbIds as $_key=>$_value) {
                $_arr_thumbIds[$_key] = fn_getSafe($_value, 'int', 0);
            }
            $_str_rcode = 'ok';
        }

        $this->thumbIds = array(
            'rcode'     => $_str_rcode,
            'thumb_ids' => array_filter(array_unique($_arr_thumbIds)),
        );

        return $this->thumbIds;
    }
}
