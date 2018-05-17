<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['attach']['main']['title'],
    'menu_active'    => 'attach',
    'sub_active'     => "list",
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    "baigoClear"     => 'true',
    'upload'         => 'true',
    "tooltip"        => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . "index.php?m=attach&a=list&" . $this->tplData['query'],
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="clearfix mb-3">
        <div class="float-left">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=attach" class="nav-link<?php if ($this->tplData['search']['box'] == 'normal') { ?> active<?php } ?>">
                        <?php echo $this->lang['mod']['href']['all']; ?>
                        <span class="badge badge-pill badge-<?php if ($this->tplData['search']['box'] == 'normal') { ?>light<?php } else { ?>primary<?php } ?>"><?php echo $this->tplData['attachCount']['all']; ?></span>
                    </a>
                </li>
                <?php if ($this->tplData['attachCount']['recycle'] > 0) { ?>
                    <li class="nav-item">
                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=attach&box=recycle" class="nav-link<?php if ($this->tplData['search']['box'] == 'recycle') { ?>  active<?php } ?>">
                            <?php echo $this->lang['mod']['href']['recycle']; ?>
                            <span class="badge badge-pill badge-<?php if ($this->tplData['search']['box'] == 'recycle') { ?>light<?php } else { ?>primary<?php } ?>"><?php echo $this->tplData['attachCount']['recycle']; ?></span>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=attach" class="nav-link" target="_blank">
                        <span class="badge badge-pill badge-primary">
                            <span class="oi oi-question-mark"></span>
                        </span>
                        <?php echo $this->lang['mod']['href']['help']; ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="float-right">
            <form name="attach_search" id="attach_search" action="<?php echo BG_URL_CONSOLE; ?>index.php" method="get">
                <input type="hidden" name="m" value="attach">
                <input type="hidden" name="a" value="list">
                <div class="input-group">
                    <select name="ext" class="custom-select">
                        <option value=""><?php echo $this->lang['mod']['option']['allExt']; ?></option>
                        <?php foreach ($this->tplData['extRows'] as $key=>$value) { ?>
                            <option <?php if ($this->tplData['search']['ext'] == $value['attach_ext']) { ?>selected<?php } ?> value="<?php echo $value['attach_ext']; ?>"><?php echo $value['attach_ext']; ?></option>
                        <?php } ?>
                    </select>
                    <select name="year" class="custom-select d-none d-md-block">
                        <option value=""><?php echo $this->lang['mod']['option']['allYear']; ?></option>
                        <?php foreach ($this->tplData['yearRows'] as $key=>$value) { ?>
                            <option <?php if ($this->tplData['search']['year'] == $value['attach_year']) { ?>selected<?php } ?> value="<?php echo $value['attach_year']; ?>"><?php echo $value['attach_year']; ?></option>
                        <?php } ?>
                    </select>
                    <select name="month" class="custom-select d-none d-md-block">
                        <option value=""><?php echo $this->lang['mod']['option']['allMonth']; ?></option>
                        <?php for ($iii = 1 ; $iii <= 12; $iii++) {
                            if ($iii < 10) {
                                $str_month = "0" . $iii;
                            } else {
                                $str_month = $iii;
                            } ?>
                            <option <?php if ($this->tplData['search']['month'] == $str_month) { ?>selected<?php } ?> value="<?php echo $str_month; ?>"><?php echo $str_month; ?></option>
                        <?php } ?>
                    </select>
                    <input type="text" name="key" class="form-control" value="<?php echo $this->tplData['search']['key']; ?>" placeholder="<?php echo $this->lang['mod']['label']['key']; ?>">
                    <span class="input-group-append">
                        <button class="btn btn-secondary" type="submit">
                            <span class="oi oi-magnifying-glass"></span>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?php if ($this->tplData['search']['box'] != "recycle") {
                include($cfg['pathInclude'] . 'upload.php');
            } ?>

            <div class="card bg-light">
                <div class="card-body">
                    <?php if ($this->tplData['search']['box'] == "recycle") { ?>
                        <form name="attach_empty" id="attach_empty">
                            <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
                            <input type="hidden" name="a" id="act_empty" value="empty">
                            <div class="form-group">
                                <button type="button" class="btn btn-warning" id="go_empty">
                                    <span class="oi oi-trash"></span>
                                    <?php echo $this->lang['mod']['btn']['empty']; ?>
                                </button>
                            </div>
                            <div class="form-group">
                                <div class="baigoClear progress">
                                    <div class="progress-bar progress-bar-info progress-bar-striped active"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="baigoClearMsg">

                                </div>
                            </div>
                        </form>
                    <?php } else { ?>
                        <form name="attach_clear" id="attach_clear">
                            <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
                            <input type="hidden" name="a" id="act_clear" value="clear">
                            <div class="form-group">
                                <button type="button" class="btn btn-warning" id="go_clear">
                                    <span class="oi oi-trash"></span>
                                    <?php echo $this->lang['mod']['btn']['attachClear']; ?>
                                </button>
                            </div>
                            <div class="form-group">
                                <div class="baigoClear progress">
                                    <div class="progress-bar progress-bar-info progress-bar-striped active"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="baigoClearMsg">

                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <form name="attach_list" id="attach_list">
                <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">

                <div class="table-responsive">
                    <table class="table table-striped table-hover border">
                        <thead>
                            <tr>
                                <th class="text-nowrap bg-td-xs">
                                    <div class="form-check">
                                        <label for="chk_all" class="form-check-label">
                                            <input type="checkbox" name="chk_all" id="chk_all" data-parent="first" class="form-check-input">
                                            <?php echo $this->lang['mod']['label']['all']; ?>
                                        </label>
                                    </div>
                                </th>
                                <th class="text-nowrap bg-td-xs"><?php echo $this->lang['mod']['label']['id']; ?></th>
                                <th class="text-nowrap bg-td-sm"><?php echo $this->lang['mod']['label']['thumb']; ?></th>
                                <th><?php echo $this->lang['mod']['label']['detail']; ?></th>
                                <th class="text-nowrap bg-td-md"><?php echo $this->lang['mod']['label']['status']; ?> / <?php echo $this->lang['mod']['label']['admin']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->tplData['attachRows'] as $key=>$value) {
                                if ($value['attach_box'] == 'normal') {
                                    $css_status = 'success';
                                } else {
                                    $css_status = 'secondary';
                                } ?>
                                <tr>
                                    <td class="text-nowrap bg-td-xs"><input type="checkbox" name="attach_ids[]" value="<?php echo $value['attach_id']; ?>" id="attach_id_<?php echo $value['attach_id']; ?>" data-validate="attach_id" data-parent="chk_all"></td>
                                    <td class="text-nowrap bg-td-xs"><?php echo $value['attach_id']; ?></td>
                                    <td class="text-nowrap bg-td-sm">
                                        <?php if ($value['attach_type'] == 'image') { ?>
                                            <a href="<?php echo $value['attach_url']; ?>" target="_blank"><img src="<?php echo $value['attach_thumb'][0]['thumb_url']; ?>" alt="<?php echo $value['attach_name']; ?>" width="100"></a>
                                        <?php } else { ?>
                                            <a href="<?php echo $value['attach_url']; ?>" target="_blank"><img src="<?php echo BG_URL_STATIC; ?>image/file_<?php echo $value['attach_ext']; ?>.png" alt="<?php echo $value['attach_name']; ?>" width="50"></a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li><a href="<?php echo $value['attach_url']; ?>" target="_blank"><?php echo $value['attach_name']; ?></a></li>
                                            <li>
                                                <abbr data-toggle="tooltip" data-placement="bottom" title="<?php echo date(BG_SITE_DATE . ' ' . BG_SITE_TIME, $value['attach_time']); ?>"><?php echo date(BG_SITE_DATE, $value['attach_time']); ?></abbr>
                                            </li>
                                            <?php
                                            $arr_size = attach_size_process($value['attach_size']);
                                            $num_attachSize = $arr_size['size'];
                                            $str_attachUnit = $arr_size['unit'];
                                            ?>
                                            <li>
                                                <?php echo fn_numFormat($num_attachSize, 2), ' ', $str_attachUnit; ?>
                                            </li>
                                            <li>
                                                <?php if ($value['attach_type'] == 'image') { ?>
                                                    <div class="dropdown dropright">
                                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="attach_<?php echo $value['attach_id']; ?>" data-toggle="dropdown">
                                                            <?php echo $this->lang['mod']['btn']['thumb']; ?>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <?php foreach ($value['attach_thumb'] as $key_thumb=>$value_thumb) { ?>
                                                                <a href="<?php echo $value_thumb['thumb_url']; ?>" target="_blank" class="dropdown-item">
                                                                    <?php echo $value_thumb['thumb_width']; ?>
                                                                    x
                                                                    <?php echo $value_thumb['thumb_height'];
                                                                    if (isset($this->lang['mod']['type'][$value_thumb['thumb_type']])) {
                                                                        echo $this->lang['mod']['type'][$value_thumb['thumb_type']];
                                                                    } ?>
                                                                </a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="text-nowrap bg-td-md">
                                        <ul class="list-unstyled">
                                            <li>
                                                <span class="badge badge-<?php echo $css_status; ?>"><?php echo $this->lang['mod']['box'][$value['attach_box']]; ?></span>
                                            </li>
                                            <li>
                                                <?php if (isset($value['adminRow']['admin_name'])) { ?>
                                                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=attach&admin_id=<?php echo $value['attach_admin_id']; ?>"><?php echo $value['adminRow']['admin_name']; ?></a>
                                                <?php } else {
                                                    echo $this->lang['mod']['label']['unknown'];
                                                } ?>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <small class="form-text" id="msg_attach_id"></small>
                    <div class="bg-submit-box bg-submit-box-list"></div>
                </div>

                <div class="clearfix mt-3">
                    <div class="float-left">
                        <div class="input-group">
                            <select name="a" id="a" data-validate class="custom-select">
                                <option value=""><?php echo $this->lang['mod']['option']['batch']; ?></option>
                                <?php if ($this->tplData['search']['box'] == "recycle") { ?>
                                    <option value="normal"><?php echo $this->lang['mod']['option']['revert']; ?></option>
                                    <option value="del"><?php echo $this->lang['mod']['option']['del']; ?></option>
                                <?php } else { ?>
                                    <option value="recycle"><?php echo $this->lang['mod']['option']['recycle']; ?></option>
                                <?php } ?>
                            </select>
                            <span class="input-group-append">
                                <button type="button" class="btn btn-primary bg-submit"><?php echo $this->lang['mod']['btn']['submit']; ?></button>
                            </span>
                        </div>
                        <small class="form-text" id="msg_a"></small>
                    </div>
                    <div class="float-right">
                        <?php include($cfg['pathInclude'] . 'page.php'); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_list = {
        attach_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='attach_id']", type: "checkbox" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030202']; ?>" }
        },
        a: {
            len: { min: 1, "max": 0 },
            validate: { type: "select" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030203']; ?>" }
        }
    };

    var opts_submit_list = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=attach&c=request",
        confirm: {
            selector: "#a",
            val: "del",
            msg: "<?php echo $this->lang['mod']['confirm']['del']; ?>"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    var opts_empty = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=attach&c=request",
        confirm: {
            selector: "#act_empty",
            val: "empty",
            msg: "<?php echo $this->lang['mod']['confirm']['empty']; ?>"
        },
        msg: {
            loading: "<?php echo $this->lang['rcode']['x070408']; ?>",
            complete: "<?php echo $this->lang['rcode']['y070408']; ?>"
        }
    };

    var opts_clear = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=attach&c=request",
        confirm: {
            selector: "#act_clear",
            val: "clear",
            msg: "<?php echo $this->lang['mod']['confirm']['clear']; ?>"
        },
        msg: {
            loading: "<?php echo $this->lang['rcode']['x070407']; ?>",
            complete: "<?php echo $this->lang['rcode']['y070407']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validate_list = $("#attach_list").baigoValidator(opts_validator_list);
        var obj_submit_list   = $("#attach_list").baigoSubmit(opts_submit_list);
        $(".bg-submit").click(function(){
            if (obj_validate_list.verify()) {
                obj_submit_list.formSubmit();
            }
        });
        var obj_empty = $("#attach_empty").baigoClear(opts_empty);
        $("#go_empty").click(function(){
            obj_empty.clearSubmit();
        });
        var obj_clear  = $("#attach_clear").baigoClear(opts_clear);
        $("#go_clear").click(function(){
            obj_clear.clearSubmit();
        });
        $("#attach_list").baigoCheckall();
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');