    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body">
                <div class="form-group">
                    <label><?php echo $this->lang['mod']['label']['id']; ?></label>
                    <div class="form-text"><?php echo $this->tplData['adminLogged']['admin_id']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $this->lang['mod']['label']['status']; ?></label>
                    <div class="form-text">
                        <?php admin_status_process($this->tplData['adminLogged']['admin_status'], $this->lang['mod']['status']); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $this->lang['mod']['label']['type']; ?></label>
                    <div class="form-text">
                        <?php if (isset($this->lang['mod']['type'][$this->tplData['adminLogged']['admin_type']])) {
                            echo $this->lang['mod']['type'][$this->tplData['adminLogged']['admin_type']];
                        } ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php foreach ($this->profile as $_key=>$_value) {
                        if (isset($this->tplData['adminLogged']['admin_allow_profile'][$_key]) && $this->tplData['adminLogged']['admin_allow_profile'][$_key] == 1) { ?>
                            <div>
                                <span class="badge badge-danger">
                                    <?php echo $this->lang['mod']['label']['forbidModi'];
                                    if (isset($this->lang['common']['profile'][$_key]['title'])) {
                                        echo $this->lang['common']['profile'][$_key]['title'];
                                    } else {
                                        echo $_value['title'];
                                    } ?>
                                </span>
                            </div>
                        <?php }
                    } ?>
                </div>

                <div class="form-group">
                    <label><?php echo $this->lang['mod']['label']['adminGroup']; ?></label>
                    <div class="form-text">
                        <?php if (isset($this->tplData['adminLogged']['groupRow']['group_name'])) { ?>
                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=group&a=show&group_id=<?php echo $this->tplData['adminLogged']['admin_group_id']; ?>">
                                <?php echo $this->tplData['adminLogged']['groupRow']['group_name']; ?>
                            </a>
                        <?php } else {
                            echo $this->lang['mod']['label']['none'];
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>