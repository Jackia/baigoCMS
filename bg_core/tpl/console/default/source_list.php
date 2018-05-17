<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['article']['main']['title'] . ' &raquo; ' . $this->lang['consoleMod']['article']['sub']['source'],
    'menu_active'    => 'article',
    'sub_active'     => "source",
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . "index.php?m=source&a=list&" . $this->tplData['query'],
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="clearfix mb-3">
        <div class="float-left">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="#source_modal" data-toggle="modal" data-id="0" class="nav-link">
                        <span class="oi oi-plus"></span>
                        <?php echo $this->lang['mod']['href']['add']; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=source" class="nav-link" target="_blank">
                        <span class="badge badge-pill badge-primary">
                            <span class="oi oi-question-mark"></span>
                        </span>
                        <?php echo $this->lang['mod']['href']['help']; ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="float-right">
            <form name="source_search" id="source_search" action="<?php echo BG_URL_CONSOLE; ?>index.php" method="get">
                <input type="hidden" name="m" value="source">
                <input type="hidden" name="a" value="list">
                <div class="input-group">
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

    <form name="source_list" id="source_list">
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
                        <th><?php echo $this->lang['mod']['label']['sourceName']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->tplData['sourceRows'] as $key=>$value) { ?>
                        <tr>
                            <td class="text-nowrap bg-td-xs"><input type="checkbox" name="source_ids[]" value="<?php echo $value['source_id']; ?>" id="source_ids_<?php echo $value['source_id']; ?>" data-validate="source_ids" data-parent="chk_all"></td>
                            <td class="text-nowrap bg-td-xs"><?php echo $value['source_id']; ?></td>
                            <td>
                                <ul class="list-unstyled">
                                    <li>
                                        <?php if (fn_isEmpty($value['source_name'])) {
                                            echo $this->lang['mod']['label']['noname'];
                                        } else {
                                            echo $value['source_name'];
                                        } ?>
                                    </li>
                                    <li>
                                        <a href="#source_modal" data-toggle="modal" data-id="<?php echo $value['source_id']; ?>"><?php echo $this->lang['mod']['href']['edit']; ?></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <small class="form-text" id="msg_source_id"></small>
            <div class="bg-submit-box bg-submit-box-list"></div>
        </div>

        <div class="clearfix mt-3">
            <div class="float-left">
                <input type="hidden" name="a" id="a" value="del">
                <button type="button" class="btn btn-primary bg-submit"><?php echo $this->lang['mod']['btn']['del']; ?></button>
            </div>
            <div class="float-right">
                <?php include($cfg['pathInclude'] . 'page.php'); ?>
            </div>
        </div>
    </form>

    <div class="modal fade" id="source_modal">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_list = {
        source_ids: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='source_ids']", type: "checkbox" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030202']; ?>" }
        }
    };

    var opts_submit_list = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=source&c=request",
        confirm: {
            selector: "#a",
            val: "del",
            msg: "<?php echo $this->lang['mod']['confirm']['del']; ?>"
        },
        box: {
            selector: ".bg-submit-box-list"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        $("#source_modal").on("shown.bs.modal",function(event){
    		var _obj_button   = $(event.relatedTarget);
    		var _id           = _obj_button.data("id");
            $("#source_modal .modal-content").load("<?php echo BG_URL_CONSOLE; ?>index.php?m=source&a=form&source_id=" + _id + "&view=modal");
    	}).on("hidden.bs.modal", function(){
        	$("#source_modal .modal-content").empty();
    	});

        var obj_validate_list = $("#source_list").baigoValidator(opts_validator_list);
        var obj_submit_list   = $("#source_list").baigoSubmit(opts_submit_list);
        $(".bg-submit").click(function(){
            if (obj_validate_list.verify()) {
                obj_submit_list.formSubmit();
            }
        });
        $("#source_list").baigoCheckall();
    });
    </script>

<?php include('include' . DS . 'html_foot.php');