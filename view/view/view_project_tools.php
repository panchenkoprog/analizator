<span class="help-block">Настройки проекта</span>
<?php
if(count(self::$project_tools) > 0)
{?>
    <div class="projects">
        <?php
        foreach(self::$project_tools as $elem)
        {?>
            <div id="myform" class="myform">

                <span id="url" class="help-block"><?php echo $elem->site_name; ?></span>

                <br />
                <label class="checkbox inline">
                    <input type="checkbox" name="robots" value="robots" class="robots" <?php if($elem->robots)echo "checked"; ?>><span class="text-success" id="robots_text">robots.txt</span>
                </label>

                <br />
                <br />
                <span class="help-block">E-mail для отчёта</span>
                <input type="text" id="email" name="email" value="<?php echo $elem->site_email_report ? $elem->site_email_report : ''; ?>" maxlength="50" placeholder="Введите e-mail…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/>
                <span id="error_email" class="text-error"><?php echo parent::GetErrors('email');?></span>

                <br />
                <span class="help-block">Анализ</span>
                <select class="analysis_type" name="analysis_type">
                    <!-- <option class="empty">Выберите тип анализа</option>-->

                    <?php if($elem->analysis_type)
                    { ?>
                        <option class="pay" value="1">Платная диагностика</option>
                        <option class="free" value="0">Бесплатная диагностика</option>
                    <?php } else
                    { ?>
                        <option class="free" value="0">Бесплатная диагностика</option>
                        <option class="pay" value="1">Платная диагностика</option>
                    <?php } ?>
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
                    <!--<input type="text" id="limit" name="limit" class="input_calculate" value="<?php /*if($elem->limit_pages)echo $elem->limit_pages; */?>" maxlength="10" placeholder="0.00…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/><span>&nbsp;*&nbsp;</span><span class="tariff"><?php /*echo parent::$tariff;*/?></span><span>&nbsp;grn.&nbsp;=&nbsp;</span><span class="price">0.00</span><span>&nbsp;grn.</span>-->
                    <input type="text" id="limit" name="limit" class="input_calculate" value="<?php if($elem->analysis_type){ if($elem->limit_pages)echo $elem->limit_pages; } ?>" maxlength="10" placeholder="0.00…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/><span>&nbsp;*&nbsp;</span><span class="tariff"><?php echo parent::$tariff;?></span><span>&nbsp;grn.&nbsp;=&nbsp;</span><span class="price">0.00</span><span>&nbsp;grn.</span>
                    <span id="error_limit" class="text-error"><?php echo parent::GetErrors('limit');?></span>
                </div>

                <div class="div_timeout">
                    <span class="help-block">Повторное сканирование</span>
                    <select class="analysis_timeout" name="analysis_timeout">
                        <option class="one" value="1" <?php if($elem->analysis_timeout == 1){echo ' selected';} ?>>Нет</option>
                        <option class="two" value="2" <?php if($elem->analysis_timeout == 2){echo ' selected';} ?>>Раз в 2 неделли</option>
                        <option class="three" value="3" <?php if($elem->analysis_timeout == 3){echo ' selected';} ?>>После окончания сканирования</option>
                    </select>
                    <span class="timeout">
                        <span class="text-success">&nbsp;через&nbsp;</span>
                        <input type="text" id="timeout" name="timeout" class="input_timeout" value="<?php echo $elem->timeout; ?>" maxlength="3" placeholder="0 дней…" data-trigger="manual" data-toggle="tooltip" data-placement="right" data-title="Обязательное поле" autocomplete="off"/><span class="day_timeout"></span>
                        <span id="error_timeout" class="text-error"><?php echo parent::GetErrors('timeout');?></span>
                    </span>
                </div>

                <div class="div_response">
                </div>

                <br />
                <button type="submit" name="submit" value="save_tools" id="save_tools" class="btn btn-info">Сохранить</button>
            </div>
        <?php
        }
        ?>
    </div>
<?php
}
?>