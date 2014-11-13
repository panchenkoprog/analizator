<?php //view_menu
?>
<div class="head" id="head">
    <div class="divmenu">
        <ul class="menu">
            <?php
            if($this->session)
            {
                for( $i=0; $i < count($this->ar_title_session); $i++)
                { ?>

                    <li>
                        <a <?php if($i == $this->num_menu) echo $this->class; ?>href="<?php echo $this->url . $i; ?>"><?php echo $this->ar_title_session[$i]; ?></a>
                    </li>

                <?php }
            }
            else
            {
                for( $i=0; $i < count($this->ar_title_not_session); $i++)
                { ?>

                    <li>
                        <a <?php if($i == $this->num_menu) echo $this->class; ?>href="<?php echo $this->url . $i; ?>"><?php echo $this->ar_title_not_session[$i]; ?></a>
                    </li>

                <?php }
            }?>
        </ul>
    </div>
    <?php /*if($this->session)
    { */?><!--
        <div class="session"><a href="<?php /*echo $this->url . 'exit'; */?>">выход</a></div>
    --><?php /*
    } */?>
</div>



<!-- view_register-->
<?php $_SESSION['key'] = md5((string)rand());?>
<div id="panel-reg" class="block" style="display: none;">
<form id="myform" class="myform" <?php echo "action='".$this->url.$this->num_menu."' enctype='multipart/form-data' method='POST'";?> >
    <span class="fs">Регистрация</span>

    <?php echo $this->result; ?>

    <span class="help-block">E-mail</span>
    <input type="text" id="email" name="email" value="<?php echo parent::GetOutText('email');?>" maxlength="50" placeholder="Введите e-mail…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/>
    <span id="error_email" class="text-error"><?php echo parent::GetErrors('email');?></span>

    <span class="help-block">Логин</span>
    <input type="text" id="login" name="login" value="<?php echo parent::GetOutText('login');?>" maxlength="50" placeholder="Введите логин…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/>
    <span id="error_login" class="text-error"><?php echo parent::GetErrors('login');?></span>

    <span class="help-block">Пароль</span>
    <input type="password" id="password" name="password" value="<?php echo parent::GetOutText('password');?>" maxlength="50" placeholder="Введите пароль…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/>
    <span id="error_password" class="text-error"><?php echo parent::GetErrors('password');?></span>

    <br />
    <label class="checkbox inline">
        <input type="checkbox" id="chpass"/>
        <span class="text-success" id="chpass_text">показать пароль</span>
    </label>

    <br />
    <br />
    <img src='captcha/captcha.php<?php echo '?key='.$_SESSION['key'];?>' id='capcha-image'/>
    <span id="another">Показать другую картинку</span>

    <br />
    <br />
    <input type="text" id="capt" name="capt" maxlength="50" placeholder="Введите captcha…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/>
    <span id="error_capt" class="text-error"><?php /*echo parent::GetErrors('captcha');*/ ?></span>

    <br />
    <br />
    <button type="submit" name="submit" class="btn btn-info">Зарегестрировать</button>
</form>
</div>