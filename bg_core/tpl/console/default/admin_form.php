<?php if ($this->tplData['adminRow']['admin_id'] < 1) {
    $title_sub    = $this->lang['mod']['page']['add'];
    $sub_active   = 'form';
} else {
    $title_sub    = $this->lang['mod']['page']['edit'];
    $sub_active   = 'list';
}

$cfg = array(
    'title'          => $this->lang['consoleMod']['admin']['main']['title'] . ' &raquo; ' . $title_sub,
    'menu_active'    => 'admin',
    'sub_active'     => $sub_active,
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=admin',
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=admin&a=list" class="nav-link">
                <span class="oi oi-chevron-left"></span>
                <?php echo $this->lang['common']['href']['back']; ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=admin#form" class="nav-link" target="_blank">
                <span class="badge badge-pill badge-primary">
                    <span class="oi oi-question-mark"></span>
                </span>
                <?php echo $this->lang['mod']['href']['help']; ?>
            </a>
        </li>
    </ul>

    <form name="admin_form" id="admin_form" autocomplete="off">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="a" value="submit">
        <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $this->tplData['adminRow']['admin_id']; ?>">

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <?php if ($this->tplData['adminRow']['admin_id'] > 0) { ?>
                            <div class="form-group">
                                <label><?php echo $this->lang['mod']['label']['username']; ?></label>
                                <input type="text" name="admin_name" id="admin_name" class="form-control" readonly value="<?php echo $this->tplData['adminRow']['ssoRow']['user_name']; ?>">
                            </div>

                            <div class="form-group">
                                <label><?php echo $this->lang['mod']['label']['password']; ?></label>
                                <input type="text" name="admin_pass" id="admin_pass" class="form-control" placeholder="<?php echo $this->lang['mod']['label']['onlyModi']; ?>">
                            </div>
                        <?php } else { ?>
                            <div class="form-group">
                                <label><?php echo $this->lang['mod']['label']['username']; ?> <span class="text-danger">*</span></label>
                                <input type="text" name="admin_name" id="admin_name" data-validate class="form-control">
                                <small class="form-text" id="msg_admin_name"></small>
                            </div>

                            <div class="form-group">
                                <label><?php echo $this->lang['mod']['label']['password']; ?> <span class="text-danger">*</span></label>
                                <input type="text" name="admin_pass" id="admin_pass" data-validate class="form-control">
                                <small class="form-text" id="msg_admin_pass"></small>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['mail']; ?></label>
                            <input type="text" name="admin_mail" id="admin_mail" value="<?php echo $this->tplData['adminRow']['ssoRow']['user_mail']; ?>" data-validate class="form-control">
                            <small class="form-text" id="msg_admin_mail"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['nick']; ?></label>
                            <input type="text" name="admin_nick" id="admin_nick" value="<?php if (fn_isEmpty($this->tplData['adminRow']['admin_nick'])) { echo $this->tplData['adminRow']['ssoRow']['user_nick']; } else { echo $this->tplData['adminRow']['admin_nick']; } ?>" data-validate class="form-control">
                            <small class="form-text" id="msg_admin_nick"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['cateAllow']; ?></label>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <label for="chk_all" class="form-check-label">
                                                    <input type="checkbox" id="chk_all" data-parent="first" class="form-check-input">
                                                    <?php echo $this->lang['mod']['label']['all']; ?>
                                                </label>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php cate_list_allow($this->tplData['cateRows'], $this->tplData['cateAllow'], $this->lang['mod'], $this->tplData['adminRow']['admin_allow_cate'], array(), $this->tplData['adminRow']['admin_type']); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['note']; ?></label>
                            <input type="text" name="admin_note" id="admin_note" value="<?php echo $this->tplData['adminRow']['admin_note']; ?>" data-validate class="form-control">
                            <small class="form-text" id="msg_admin_note"></small>
                        </div>

                        <div class="bg-submit-box"></div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary bg-submit"><?php echo $this->lang['mod']['btn']['save']; ?></button>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <?php if ($this->tplData['adminRow']['admin_id'] > 0) { ?>
                            <div class="form-group">
                                <label><?php echo $this->lang['mod']['label']['id']; ?></label>
                                <div class="form-text"><?php echo $this->tplData['adminRow']['admin_id']; ?></div>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['type']; ?> <span class="text-danger">*</span></label>
                            <?php foreach ($this->tplData['type'] as $key=>$value) { ?>
                                <div class="form-check">
                                    <label for="admin_type_<?php echo $value; ?>" class="form-check-label">
                                        <input type="radio" name="admin_type" id="admin_type_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($this->tplData['adminRow']['admin_type'] == $value) { ?>checked<?php } ?> data-validate="admin_type" class="form-check-input">
                                        <?php if (isset($this->lang['mod']['type'][$value])) {
                                            echo $this->lang['mod']['type'][$value];
                                        } else {
                                            echo $value;
                                        } ?>
                                    </label>
                                </div>
                            <?php } ?>
                            <small class="form-text" id="msg_admin_type"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['status']; ?> <span class="text-danger">*</span></label>
                            <?php foreach ($this->tplData['status'] as $key=>$value) { ?>
                                <div class="form-check">
                                    <label for="admin_status_<?php echo $value; ?>" class="form-check-label">
                                        <input type="radio" name="admin_status" id="admin_status_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($this->tplData['adminRow']['admin_status'] == $value) { ?>checked<?php } ?> data-validate="admin_status" class="form-check-input">
                                        <?php if (isset($this->lang['mod']['status'][$value])) {
                                            echo $this->lang['mod']['status'][$value];
                                        } else {
                                            echo $value;
                                        } ?>
                                    </label>
                                </div>
                            <?php } ?>
                            <small class="form-text" id="msg_admin_status"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['profileAllow']; ?></label>
                            <?php foreach ($this->profile as $_key=>$_value) { ?>
                                <div class="form-check">
                                    <label for="admin_allow_profile_<?php echo $_key; ?>" class="form-check-label">
                                        <input type="checkbox" name="admin_allow_profile[<?php echo $_key; ?>]" id="admin_allow_profile_<?php echo $_key; ?>" value="1" <?php if (isset($this->tplData['adminRow']['admin_allow_profile'][$_key]) && $this->tplData['adminRow']['admin_allow_profile'][$_key] == 1) { ?>checked<?php } ?> class="form-check-input">
                                        <?php echo $this->lang['mod']['label']['forbidModi'];
                                        if (isset($this->lang['common']['profile'][$_key]['title'])) {
                                            echo $this->lang['common']['profile'][$_key]['title'];
                                        } else {
                                            echo $_value['title'];
                                        } ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        admin_name: {
            len: { min: 1, max: 0 },
            validate: { type: "ajax", format: "strDigit" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x010201']; ?>", too_long: "<?php echo $this->lang['rcode']['x010202']; ?>", format_err: "<?php echo $this->lang['rcode']['x010203']; ?>", ajaxIng: "<?php echo $this->lang['rcode']['x030401']; ?>", ajax_err: "<?php echo $this->lang['rcode']['x030402']; ?>" },
            ajax: { url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=admin&c=request&a=chkname", key: 'admin_name', type: "str" }
        },
        admin_pass: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x010212']; ?>" }
        },
        admin_mail: {
            len: { min: 0, max: 0 },
            validate: { type: "ajax", format: "email" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x010206']; ?>", too_long: "<?php echo $this->lang['rcode']['x010207']; ?>", format_err: "<?php echo $this->lang['rcode']['x010208']; ?>", ajaxIng: "<?php echo $this->lang['rcode']['x030401']; ?>", ajax_err: "<?php echo $this->lang['rcode']['x030402']; ?>" },
            ajax: { url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=admin&c=request&a=chkmail", key: "admin_mail", type: "str", attach_selectors: ["#admin_id"], attach_keys: ["admin_id"] }
        },
        admin_nick: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text" },
            msg: { too_long: "<?php echo $this->lang['rcode']['x020216']; ?>" }
        },
        admin_note: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text" },
            msg: { too_long: "<?php echo $this->lang['rcode']['x020212']; ?>" }
        },
        admin_type: {
            len: { min: 1, max: 0 },
            validate: { selector: "input[name='admin_type']", type: "radio" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x020219']; ?>" }
        },
        admin_status: {
            len: { min: 1, max: 0 },
            validate: { selector: "input[name='admin_status']", type: "radio" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x020213']; ?>" }
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=admin&c=request",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validate_form = $("#admin_form").baigoValidator(opts_validator_form);
        var obj_submit_form   = $("#admin_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#admin_form").baigoCheckall();
    });
    </script>

<?php include('include' . DS . 'html_foot.php');