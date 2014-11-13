<?php $_SESSION['key'] = md5((string)rand());?>
<div id="panel-reg" class="block" style="display: none;">
    <div class="container">
        <form id="form-register" <?php echo "action='".$this->url.$this->num_menu."' enctype='multipart/form-data' method='POST'";?> >
            <span class="title">Регистрация</span>

            <?php /*echo $this->result;*/ ?>
            <div class="box-form">
                <div class="box">
                    <span class="icon"><i class="fa fa-envelope" style="line-height: 2;"></i></span>
                    <input type="text" id="email" name="email" value="<?php /*echo parent::GetOutText('email');*/ ?>" maxlength="50" placeholder="Введите e-mail…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/>
                    <!--<span id="error_email" class="text-error"><?php /*echo parent::GetErrors('email');*/?></span>-->
                </div>
                
                <div class="box two">
                    <span class="icon"><i class="fa fa-user" style="line-height: 2;"></i></span>
                    <input type="text" id="login" name="login" value="<?php /*echo parent::GetOutText('login');*/ ?>" maxlength="50" placeholder="Введите логин…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/>
                    <!--<span id="error_login" class="text-error"><?php /*echo parent::GetErrors('login');*/?></span>-->
                </div>
                
                <div class="box two">
                    <span class="icon"><i class="fa fa-unlock-alt" style="line-height: 2;"></i></span>
                    <input type="password" id="password" name="password" value="<?php /*echo parent::GetOutText('password');*/ ?>" maxlength="50" placeholder="Введите пароль…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/>
                    <!--<span id="error_password" class="text-error"><?php /*echo parent::GetErrors('password');*/?></span>-->
                </div>
            </div>

            <div class="box-form">
                <div class="box">
                    <img src='captcha/captcha.php<?php echo '?key='.$_SESSION['key'];?>' id='capcha-image'/>
                    <span id="another">Показать другую картинку</span>
                </div>
                
                <div class="box two">   
                    <span class="icon"><i class="fa fa-picture-o" style="line-height: 2;"></i></span>
                    <input type="text" id="capt" name="capt" maxlength="50" placeholder="Введите captcha…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/>
                    <!--<span id="error_capt" class="text-error"><?php /*echo parent::GetErrors('captcha'); */?></span>-->
                </div>
                <div class="two">
                    <button type="submit" name="reg" class="button-login" style="margin-left: 125px;">Зарегестрировать</button>
                </div>
            </div>
        </form>
    </div>
</div>