<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------专题归属模型-------------*/
class MODEL_SPEC_BELONG {

    function __construct() { //构造函数
        $this->obj_db = $GLOBALS['obj_db']; //设置数据库对象
    }


    function mdl_create_table() {
        $_arr_belongCreat = array(
            'belong_id'         => 'int NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'belong_spec_id'    => 'int NOT NULL COMMENT \'专题ID\'',
            'belong_article_id' => 'int NOT NULL COMMENT \'文章ID\'',
        );

        $_num_db = $this->obj_db->create_table(BG_DB_TABLE . 'spec_belong', $_arr_belongCreat, 'belong_id', '专题从属');

        if ($_num_db > 0) {
            $_str_rcode = 'y230105'; //更新成功

            $_str_sql       = 'INSERT INTO `' . BG_DB_TABLE . 'spec_belong`(`belong_spec_id`,`belong_article_id`) SELECT `article_spec_id`,`article_id` FROM `' . BG_DB_TABLE . 'article` WHERE `article_spec_id`>0';
            $_obj_reselt    = $this->obj_db->query($_str_sql);
            //$_arr_row       = $this->obj_db->fetch_assoc($_obj_reselt);
        } else {
            $_str_rcode = 'x230105'; //更新成功
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
        );
    }


    function mdl_create_index() {
        $_str_rcode = 'y230109'; //更新成功
        $_arr_indexRow    = $this->obj_db->show_indexs(BG_DB_TABLE . 'spec_belong');

        $drop_overlap        = false;
        foreach ($_arr_indexRow as $_key=>$_value) {
            if (in_array('search_article', $_value)) {
                $drop_overlap = true;
                break;
            }
        }

        $_arr_specBelongIndex = array(
            'belong_article_id',
        );

        $_num_db = $this->obj_db->create_index('search_article', BG_DB_TABLE . 'spec_belong', $_arr_specBelongIndex, 'BTREE', $drop_overlap);

        if ($_num_db < 1) {
            $_str_rcode = 'x230109'; //更新成功
        }

        $drop_overlap        = false;
        foreach ($_arr_indexRow as $_key=>$_value) {
            if (in_array('search_spec', $_value)) {
                $drop_overlap = true;
                break;
            }
        }

        $_arr_specBelongIndex = array(
            'belong_spec_id',
        );

        $_num_db = $this->obj_db->create_index('search_spec', BG_DB_TABLE . 'spec_belong', $_arr_specBelongIndex, 'BTREE', $drop_overlap);

        if ($_num_db < 1) {
            $_str_rcode = 'x230109'; //更新成功
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
        );
    }


    function mdl_create_view() {
        $_arr_viewCreat = array(
            array('spec_id',             BG_DB_TABLE . 'spec'),
            array('spec_name',           BG_DB_TABLE . 'spec'),
            array('spec_status',         BG_DB_TABLE . 'spec'),
            array('spec_attach_id',      BG_DB_TABLE . 'spec'),
            array('belong_article_id',   BG_DB_TABLE . 'spec_belong'),
        );

        $_str_sqlJoin = 'LEFT JOIN `' . BG_DB_TABLE . 'spec_belong` ON (`' . BG_DB_TABLE . 'spec`.`spec_id`=`' . BG_DB_TABLE . 'spec_belong`.`belong_spec_id`)';

        $_num_db = $this->obj_db->create_view(BG_DB_TABLE . 'spec_view', $_arr_viewCreat, BG_DB_TABLE . 'spec', $_str_sqlJoin);

        if ($_num_db > 0) {
            $_str_rcode = 'y230109'; //更新成功
        } else {
            $_str_rcode = 'x230109'; //更新成功
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
        );
    }


    function mdl_column() {
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . 'spec_belong');

        $_arr_col = array();

        if (!fn_isEmpty($_arr_colRows)) {
            foreach ($_arr_colRows as $_key=>$_value) {
                $_arr_col[] = $_value['Field'];
            }
        }

        return $_arr_col;
    }


    /**
     * mdl_submit function.
     *
     * @access public
     * @param mixed $num_belongId
     * @param mixed $num_specId
     * @param mixed $num_belongId
     * @return void
     */
    function mdl_submit($num_articleId, $num_specId) {
        $_str_rcode = 'x230101';

        if ($num_articleId > 0 && $num_specId > 0) { //插入
            $_arr_belongRow = $this->mdl_read($num_articleId, $num_specId);

            if ($_arr_belongRow['rcode'] == 'x230102') { //插入
                if ($_arr_belongRow['rcode'] == 'x230102') { //插入
                    $_arr_belongData = array(
                        'belong_article_id'  => $num_articleId,
                        'belong_spec_id'     => $num_specId,
                    );

                    $_arr_belongRow = $this->mdl_read($num_articleId, 0);

                    if ($_arr_belongRow['rcode'] == 'y230102') {
                        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'spec_belong', $_arr_belongData, '`belong_id`=' . $_arr_belongRow['belong_id']); //删除数据
                        if ($_num_db > 0) {
                            $_str_rcode = 'y230103';
                        } else {
                            $_str_rcode = 'x230103';
                        }
                    } else {
                        $_num_belongId = $this->obj_db->insert(BG_DB_TABLE . 'spec_belong', $_arr_belongData);

                        if ($_num_belongId > 0) { //数据库插入是否成功
                            $_str_rcode = 'y230101';
                        } else {
                            $_str_rcode = 'x230101';
                        }
                    }
                } else {
                    $_arr_belongData = array(
                        'belong_spec_id'  => 0,
                    );

                    $_num_db = $this->obj_db->update(BG_DB_TABLE . 'spec_belong', $_arr_belongData, '`belong_id`=' . $_arr_belongRow['belong_id']); //删除数据

                    if ($_num_db > 0) {
                        $_str_rcode = 'y230103';
                    } else {
                        $_str_rcode = 'x230103';
                    }
                }
            }
        }

        return array(
            'rcode'  => $_str_rcode,
        );
    }



    /**
     * mdl_read function.
     *
     * @access public
     * @param mixed $str_belong
     * @param string $str_readBy (default: 'belong_id')
     * @param int $num_parentId (default: 0)
     * @return void
     */
    function mdl_read($num_articleId = 0, $num_specId = 0) {
        $_arr_belongSelect = array(
            'belong_article_id',
            'belong_spec_id',
        );

        $_str_sqlWhere = '`belong_spec_id`=' . $num_specId;

        if ($num_articleId > 0) {
            $_str_sqlWhere .= ' AND `belong_article_id`=' . $num_articleId;
        }

        $_arr_belongRows  = $this->obj_db->select(BG_DB_TABLE . 'spec_belong',  $_arr_belongSelect, $_str_sqlWhere, '', '', 1, 0); //检查本地表是否存在记录

        if (isset($_arr_belongRows[0])) {
            $_arr_belongRow   = $_arr_belongRows[0];
        } else {
            return array(
                'rcode' => 'x230102', //不存在记录
            );
        }

        $_arr_belongRow['rcode'] = 'y230102';

        return $_arr_belongRow;
    }


    function mdl_ids($num_articleId) {
        $_arr_belongSelect = array(
            'belong_spec_id',
        );

        $_str_sqlWhere = '`belong_article_id`=' . $num_articleId;

        $_arr_belongRows  = $this->obj_db->select(BG_DB_TABLE . 'spec_belong',  $_arr_belongSelect, $_str_sqlWhere, '', '', 1000, 0); //检查本地表是否存在记录

        $_arr_specIds = array();

        foreach ($_arr_belongRows as $_key=>$_value) {
            $_arr_specIds[]   = $_value['belong_spec_id'];
        }

        return $_arr_specIds;
    }


    function mdl_count($num_specId = 0) {
        $_str_sqlWhere = '1';

        if ($num_specId > 0) {
            $_str_sqlWhere .= ' AND `belong_spec_id`=' . $num_specId;
        }

        $_num_specCount  = $this->obj_db->count(BG_DB_TABLE . 'spec_belong', $_str_sqlWhere);

        return $_num_specCount;
    }


    /**
     * mdl_del function.
     *
     * @access public
     * @param int $num_specId (default: 0)
     * @param int $num_articleId (default: 0)
     * @return void
     */
    function mdl_del($num_specId = 0, $num_articleId = 0, $arr_specIds = false, $arr_articleIds = false, $arr_notSpecIds = false, $arr_notArticleIds = false) {

        $_str_sqlWhere = '1';

        if ($num_specId > 0) {
            $_str_sqlWhere     .= ' AND `belong_spec_id`=' . $num_specId;
        }

        if ($num_articleId > 0) {
            $_str_sqlWhere     .= ' AND `belong_article_id`=' . $num_articleId;
        }

        if (!fn_isEmpty($arr_specIds)) {
            $_str_specIds       = implode(',', $arr_specIds);
            $_str_sqlWhere     .= ' AND `belong_spec_id` IN (' . $_str_specIds . ')';
        }

        if (!fn_isEmpty($arr_articleIds)) {
            $_str_articleIds    = implode(',', $arr_articleIds);
            $_str_sqlWhere     .= ' AND `belong_article_id` IN (' . $_str_articleIds . ')';
        }

        if (!fn_isEmpty($arr_notSpecIds)) {
            $_str_notSpecIds    = implode(',', $arr_notSpecIds);
            $_str_sqlWhere     .= ' AND `belong_spec_id` NOT IN (' . $_str_notSpecIds . ')';
        }

        if (!fn_isEmpty($arr_notArticleIds)) {
            $_str_notArticleIds = implode(',', $arr_notArticleIds);
            $_str_sqlWhere     .= ' AND `belong_article_id` NOT IN (' . $_str_notArticleIds . ')';
        }

        $_arr_belongData = array(
            'belong_spec_id'  => 0,
        );

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'spec_belong', $_arr_belongData, $_str_sqlWhere); //删除数据

        if ($_num_db > 0) {
            $_str_rcode = 'y230104';
        } else {
            $_str_rcode = 'x230104';
        }

        return array(
            'rcode' => $_str_rcode,
        ); //成功
    }
}
