<?php
if(self::$detail_report !== null)
{
    $url_list_pages = $this->url.$this->num_menu.$this->separator_arguments
        ."report_name=".self::$detail_report->report_name.$this->separator_arguments
        ."list_pages=".self::$detail_report->list_pages. $this->separator_arguments
        ."host=" . $_REQUEST['host'];

    $url_list_unique_pages = $this->url.$this->num_menu.$this->separator_arguments
        ."report_name=".self::$detail_report->report_name.$this->separator_arguments
        ."list_unique_links=".self::$detail_report->list_unique_links. $this->separator_arguments
        ."host=" . $_REQUEST['host'];

    $url_list_links = $this->url.$this->num_menu.$this->separator_arguments
        ."report_name=".self::$detail_report->report_name.$this->separator_arguments
        ."list_links=".self::$detail_report->list_links. $this->separator_arguments
        ."host=" . $_REQUEST['host'];

    $url_list_internal_links = $this->url.$this->num_menu.$this->separator_arguments
        ."report_name=".self::$detail_report->report_name.$this->separator_arguments
        ."list_internal_links=".self::$detail_report->list_internal_links. $this->separator_arguments
        ."host=" . $_REQUEST['host'];

    $url_list_external_links = $this->url.$this->num_menu.$this->separator_arguments
        ."report_name=".self::$detail_report->report_name.$this->separator_arguments
        ."list_external_links=".self::$detail_report->list_external_links. $this->separator_arguments
        ."host=" . $_REQUEST['host'];

    $url_list_cycle_links = $this->url.$this->num_menu.$this->separator_arguments
        ."report_name=".self::$detail_report->report_name.$this->separator_arguments
        ."list_cycle_links=".self::$detail_report->list_cycle_links. $this->separator_arguments
        ."host=" . $_REQUEST['host'];
?>
    <span class="help-block"><b>Информация о домене</b></span>
    <div>
        <p>Домен: <?php echo self::$detail_report->domain;?></p>
        <p>IP-адрес: <?php echo self::$detail_report->ip_address;?></p>
    </div>
    <br />
    <span class="help-block"><b>Общий технический анализ</b></span>
    <div>
        <p>Количество уникальных ссылок: <a href="<?php echo $url_list_unique_pages; ?>"><?php echo self::$detail_report->list_unique_links; ?></a></p>
        <p>Из них страниц: <a href="<?php echo $url_list_pages; ?>"><?php echo self::$detail_report->list_pages; ?></a> при лимите в <?php echo self::$detail_report->limit_pages; ?></p>
        <p>Количество ссылок: <a href="<?php echo $url_list_links; ?>"><?php echo self::$detail_report->list_links; ?></a>. Углубление до: <?php echo self::$detail_report->level_links; ?> уровня доступности от стартовой</p>
        <p>Среднее время соединения с сервером: <?php echo self::$detail_report->average_time_connect_server; ?> сек.</p>
        <p>Время закачки страницы ~ <?php echo self::$detail_report->average_time_load_page; ?> сек. Средняя скорость ~ <?php echo self::$detail_report->average_speed; ?> kbps.</p>
        <p>Объем страниц проекта: <?php echo self::$detail_report->kb_volume_pages_project; ?> кб. (<?php echo self::$detail_report->mb_volume_pages_project; ?> мб.) Средний <?php echo self::$detail_report->kb_average_volume_pages_project; ?> кб.</p>
        <!--<p>Файл Robots.txt: Не обработано ни одной инструкции из файла robots.txt</p>
        <p>Доступность robots.txt (инструкции для роботов):	Нет	</p>
        <p>Доступность Sitemap.xml (карта сайта):	Нет	</p>
        <p>Корректность 404 ответа (несуществующие страницы):	Да	размер: 3772 симв.</p>-->
    </div>


    <br />
    <span class="help-block"><b>Типы ссылок</b></span>
    <div>
        <?php
        //-----------------------------
        if(self::$detail_report->list_internal_links)
        { ?>
            <p>Количество внутренних ссылок: <a href="<?php echo $url_list_internal_links; ?>"><?php echo self::$detail_report->list_internal_links; ?></a></p>
        <?php
        }else{ ?>
            <p>Количество внутренних ссылок: <?php echo self::$detail_report->list_internal_links; ?></p>
        <?php
        }
        //-----------------------------
        if(self::$detail_report->list_external_links)
        { ?>
            <p>Количество внешних ссылок: <a href="<?php echo $url_list_external_links; ?>"><?php echo self::$detail_report->list_external_links; ?></a></p>
        <?php
        }else{ ?>
            <p>Количество внешних ссылок: <?php echo self::$detail_report->list_external_links; ?></p>
        <?php
        }
        //-----------------------------
        if(self::$detail_report->list_cycle_links)
        { ?>
            <p>Количество циклических ссылок: <a href="<?php echo $url_list_cycle_links; ?>"><?php echo self::$detail_report->list_cycle_links; ?></a></p>
        <?php
        }else{ ?>
            <p>Количество циклических ссылок: <?php echo self::$detail_report->list_cycle_links; ?></p>
        <?php
        }?>
    </div>
    <br />
    <?php
        $url_load = $this->url.$this->num_menu.$this->separator_arguments
            ."report_name=".self::$detail_report->report_name.$this->separator_arguments
            ."p_show=load_table". $this->separator_arguments
            ."host=" . $_REQUEST['host'];
    ?>
    <span class="help-block"><a href="<?php echo $url_load;?>"><b>Загрузка</b></a></span>
    <br />
    <?php
        $url_resources = $this->url.$this->num_menu.$this->separator_arguments
            ."report_name=".self::$detail_report->report_name.$this->separator_arguments
            ."p_show=resources_table". $this->separator_arguments
            ."host=" . $_REQUEST['host'];
    ?>
    <span class="help-block"><a href="<?php echo $url_resources;?>"><b>Ресурсы</b></a></span>
    <br />
    <?php
        $url_content = $this->url.$this->num_menu.$this->separator_arguments
            ."report_name=".self::$detail_report->report_name.$this->separator_arguments
            ."p_show=content_table". $this->separator_arguments
            ."host=" . $_REQUEST['host'];
    ?>
    <span class="help-block"><a href="<?php echo $url_content;?>"><b>Контент</b></a></span>
    <br />
    <?php
    $url_tags = $this->url.$this->num_menu.$this->separator_arguments
        ."report_name=".self::$detail_report->report_name.$this->separator_arguments
        ."p_show=tags_table". $this->separator_arguments
        ."host=" . $_REQUEST['host'];
    ?>
    <span class="help-block"><a href="<?php echo $url_tags;?>"><b>Теги</b></a></span>
<?php
}
?>