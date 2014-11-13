<!DOCTYPE html>
<html>

<head>
    <title>Анализатор сайта</title>
    <meta content='text/html; charset=utf-8' http-equiv='Content-type' />
    <link rel='stylesheet' type='text/css' href='css/style.css'/>
    <script type="text/javascript" src="/js/includejQuery_1_8_1.js"></script>
    <script type="text/javascript" src="/js/scripts.js"></script>
</head>

<body>

<div class="all">

    <div class="divleft" id="divleft">&nbsp;</div>

    <div class="divcenter" id="divcenter"><div class="head" id="head">
        </div><div class="div-body">
            <div class="content" id="content">
                <form id="install_form" class="install_form" <?php echo "action='/index.php?install=1'";?> enctype="multipart/form-data" method="POST">
                    <h3 class="title">Форма создания БД и таблиц</h3>
                    <br />
                    <div>Host MySQL Server - по умолчанию - localhost</div>
                    <input type="text" id="input_host" name="host" maxlength="50" placeholder="  localhost… " autocomplete="off" value="localhost"/>

                    <div>Логин к MySQL Server по умолчанию - root</div>
                    <input type="text" id="input_root_login" name="root_login" maxlength="50" placeholder="  root… " autocomplete="off" value="root"/>

                    <div>Пароль к MySQL Server по умолчанию - без пароля</div>
                    <input type="text" id="input_root_password" name="root_password" maxlength="50" placeholder="" autocomplete="off" value=""/>

                    <div>Название БД - по умолчанию - service</div>
                    <input type="text" id="input_db_name" name="db_name" maxlength="50" placeholder="  service… " autocomplete="off" value="service"/>

                    <div>Название таблицы для хранения сообщений пользователя - по умолчанию - service_users</div>
                    <input type="text" id="input_table_users" name="table_users" maxlength="50" placeholder="  service_users… " autocomplete="off" value="service_users"/>

                    <div>Название таблицы для учётной записи админа - по умолчанию - service_site</div>
                    <input type="text" id="input_table_site" name="table_site" maxlength="50" placeholder="  service_site… " autocomplete="off" value="service_site"/>
                    <br /><br />
                    <input type="submit" id="input_button" name="submit" value=" Отправить ">
                </form>
                </div>
        </div>        <div class="footer" id="footer">
            <div class="footer_text">&copy; Александр Панченко :)</div>
        </div>

    </div>

    <div class="divright" id="divright">&nbsp;</div>

</div>

</body>
</html>