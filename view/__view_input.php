<form id="myform" class="myform" <?php echo "action='".$this->url.$this->num_menu."' enctype='multipart/form-data' method='POST'";?> >
    <span class="fs">Аутентификация</span>
    <?php
    if(isset($_REQUEST['submit']))
    {
        echo '<br />';
        echo $this->result;
    }
    ?>
    <span class="help-block">Логин</span>
    <input type="text" id="inp1" name="login" maxlength="50" placeholder="Введите логин…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off">
    <span class="help-block">Пароль</span>
    <input type="password" id="inp2" name="password" maxlength="50" placeholder="Введите пароль…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off">
    <br />
    <label class="checkbox inline">
        <input type="checkbox" id="chpass"/>
        <span class="text-success" id="chpass_text">показать пароль</span>
    </label>
    <br />
    <br />
    <button type="submit" name="submit" class="btn btn-info">Войти</button>
</form>