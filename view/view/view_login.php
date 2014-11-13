<div id="panel-login" class="block" style="display: none;">
        <div class="container">
            <form id="form-login" <?php echo "action='".$this->url.$this->num_menu."' enctype='multipart/form-data' method='POST'";?> >
                <span class="title">Вход</span>
                <?php
                    /*if (isset($_REQUEST['submit']))
                    {
                        echo '<br />';
                        echo $this->result;
                    }*/
                ?>
                <div class="box-form">
                    <div class="box">
                        <span class="icon"><i class="fa fa-user" style="line-height: 2;"></i></span>
                        <input type="text" id="inp1" name="login" maxlength="50" placeholder="Введите логин…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off">
                    </div>
                    <div class="box two">
                        <span class="icon"><i class="fa fa-pencil" style="line-height: 2;"></i></span>
                        <input type="password" id="inp2" name="password" maxlength="50" placeholder="Введите пароль…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off">
                    </div>
                    <div class="two">
                        <button type="submit" name="log" class="button-login">Войти</button>
                    </div>
                </div>
                <!--
                <div class="box-form">
                    <label class="checkbox inline" style="margin-left: 325px;">
                        <input type="checkbox" id="chpass"/>
                        <span class="text-success" id="chpass_text">показать пароль</span>
                    </label>
                </div>
                -->
                <!--
                <div class="box2">
                    <label class="checkbox inline">
                        <input type="checkbox" id="chpass"/>
                        <span class="text-success" id="chpass_text">показать пароль</span>
                    </label>
                </div>
                -->
            </form>
        </div>
</div>
