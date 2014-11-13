<!--<form id="myform" class="myform" <?php /*echo "action='".$this->url.$this->num_menu."' enctype='multipart/form-data' method='POST'";*/?> >-->
    <h1>Анализ сайта</h1>
<div id="myform" class="myform">
     
    <span class="help-block">Сайт</span>
    <input type="text" id="url" name="url" value="<?php echo parent::GetOutText('url');?>" maxlength="50" placeholder="http://…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="on"/>
    <span id="error_url" class="text-error"><?php echo parent::GetErrors('url');?></span>

    <br />
    <label class="checkbox inline">
        <input type="checkbox" name="robots" value="robots" class="robots"><span class="text-success" id="robots_text">robots.txt</span>
    </label>

    <br />
    <br />
    <span class="help-block">E-mail для отчёта</span>
    <input type="text" id="email" name="email" value="<?php echo parent::GetOutText('email') ? parent::GetOutText('email') : $_SESSION['email'];?>" maxlength="50" placeholder="Введите e-mail…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="on"/>
    <span id="error_email" class="text-error"><?php echo parent::GetErrors('email');?></span>

    <br />
    <span class="help-block">Анализ</span>
    <select class="analysis_type" name="analysis_type">
        <!--<option class="empty">Выберите тип анализа</option>-->
        <option class="pay" value="1">Платная диагностика</option>
        <option class="free" value="0">Бесплатная диагностика</option>
    </select>

    <div class="div_pay">
        <p>Наш робот под видом поисковой системы скачает ваш сайт и проведет тестирование, изучив несколько сотен параметров, влияющих на поисковое продвижение.</p>
        <p>
            <b>Введите лимит страниц для анализа.</b>
            <br />
            Максимальное количество страниц доступное вам для анализа: <span class="pages"><?php if($this->session){ echo ($_SESSION['balance']/parent::$tariff);}else{ echo "0";}?></span>. <?php echo "<a href='".$this->url.(count($this->ar_title_session)-(1+$this->admin))."'>Пополнить баланс?</a>";?>
            <br />
            Если робот при анализе сайта достигнет лимита то остановится.
            <br />
            Если же робот не достигнет лимита то оплата будет взята исходя из обработанного количества страниц. Остаток средств будет возвращён на баланс после звершения анализа.
        </p>
    </div>

    <div class="div_free">
        <p>Бесплатная диагностика - Позволяет получить полный комплекс тестирования по всем праметрам, но только для 50 страниц вашего сайта.В результате анализа вы получите исчерпывающую информацию о техническом состоянии вашего сайта и его внутренней оптимизации по итогам анализа нескольких сотен факторов, применимо только к 50 страницам.</p>
    </div>

    <div class="div_calculate">
        <span class="help-block">Количество страниц</span>
        <input type="text" id="limit" name="limit" class="input_calculate" value="<?php echo parent::GetOutText('limit');?>" maxlength="10" placeholder="0.00…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/><span>&nbsp;*&nbsp;</span><span class="tariff"><?php echo parent::$tariff;?></span><span>&nbsp;grn.&nbsp;=&nbsp;</span><span class="price">0.00</span><span>&nbsp;grn.</span>
        <span id="error_limit" class="text-error"><?php echo parent::GetErrors('limit');?></span>
    </div>

    <!--<br class="br_error"/>
    <br class="br_error"/>-->

    <div class="div_timeout">
        <span class="help-block">Повторное сканирование</span>
        <select class="analysis_timeout" name="analysis_timeout">
            <option class="one" value="1">Нет</option>
            <option class="two" value="2">Раз в 2 неделли</option>
            <option class="three" value="3">После окончания сканирования</option>
        </select>
        <span class="timeout">
            <span class="text-success">&nbsp;через&nbsp;</span>
            <input type="text" id="timeout" name="timeout" class="input_timeout" value="<?php echo parent::GetOutText('timeout');?>" maxlength="3" placeholder="0 дней…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/><span class="day_timeout"></span>
            <span id="error_timeout" class="text-error"><?php echo parent::GetErrors('timeout');?></span>
        </span>
    </div>

    <div class="div_response">
        <?php
/*        if($this->result)
        {
            echo $this->result;
        }
        else
        {*/?>
        <?php /*}*/ ?>
    </div>

    <br />
    <button type="submit" name="submit" value="analysis" id="analysis" class="btn btn-info">Анализ</button>
    <!--<input type="button" name="analysis" value="analysis" class="analysis" class="btn btn-info" />-->
    <!--<input type="hidden" name="free_pay" value=""/>-->
</div>
<!--</form>-->
