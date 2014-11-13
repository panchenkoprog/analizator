<?php
class ServiceReport
{
    public $report_id             = 0;
    public $user_id               = 0;
    public $report_name           = '';
    public $date_start_report     = '';
    public $date_stop_report      = '';
    public $site_name             = '';
    public $flag_scan             = 0;
    public $flag_parse            = 0;
    public $flag_compare          = 0;
    public $counter_scan_page     = 0;
    public $counter_scan_pages    = 0;
    public $counter_parse_page    = 0;
    public $counter_parse_pages   = 0;
    public $counter_compare_page  = 0;
    public $counter_compare_pages = 0;
}

class ServiceDetailReport
{
    public $ip_address                      = '';//IP-адрес сайта
    public $domain                          = '';//domain сайта
    public $report_name                     = '';//$_REQUEST['report_name'] - название БД, используется для работы с БД и формирования URL
    public $date_start_scan                 = '';//начало текущего анализа
    public $date_stop_scan                  = '';//конец текущего анализа
    public $date_start_report               = '';//начало отчёта
    public $date_stop_report                = '';//конец отчёта
    public $limit_pages                     = 0;
    public $level_links                     = 0;//максимальный уровень вложенности ссылок
    public $from_page                       = '';//страница ресурса - для отображения заголовка (РЕСУРСЫ со страницы ...$resource_page...)

    public $list_pages                      = 0;//список страниц проекта
    public $list_unique_links               = 0;//список уникальных ссылок проекта
    public $list_links                      = 0;//список ссылок проекта
    public $list_internal_links             = 0;//список внутренних ссылок проекта
    public $list_external_links             = 0;//список внешних ссылок проекта
    public $list_cycle_links                = 0;//список циклических ссылок проекта

    public $average_time_load_page          = 0;//среднее время загрузки страницы
    public $average_time_connect_server     = 0;//среднее время соединения с сервером
    public $average_speed                   = 0;//Средняя скорость ~ ... kbps (кбит/сек.) http://www.artlebedev.ru/kovodstvo/sections/84/
    public $kb_volume_pages_project         = 0;//Объем страниц проекта kb(кбайт)
    public $mb_volume_pages_project         = 0;//Объем страниц проекта mb(мбайт)
    public $kb_average_volume_pages_project = 0;//Средний Объем страниц проекта kb(кбайт)

    public $ar_pages                        = array();//массив страниц
    public $ar_links                        = array();//массив ссылок
    public $ar_internal_links               = array();//массив внутренних ссылок
    public $ar_external_links               = array();//массив внешних ссылок
    public $ar_unique_links                 = array();//массив уникальных ссылок
    public $ar_cycle_links                  = array();//массив циклических ссылок
    public $ar_load_table                   = array();//таблица - данные по загрузке страницы
    public $ar_resources_table              = array();//таблица - данные по ресурсам страницы (img, внешние ссылки)
    public $ar_resources_img                = array();
    public $ar_resources_exlink             = array();
    public $ar_content_table                = array();//таблица - данные по контенту страницы
    public $ar_content_cs                   = array();//массив count_symbols в контенте

    public $ar_tags                         = array();//массив тегов
    public $ar_tags_table                   = array();//таблица тегов
    public $ar_current_tags                 = array();//массив тегов на текущей (указанной) странице
    public $name_current_tag                = '';//название тегущего тега
    public $fields_tag                      = array();
    public $count_fields_tag                = 0;

    public $ar_emails                       = array();//массив email'ов на которые будет осуществляться рассылка отчёта

    public $ar_dates                        = array();//массив дат - вытаскиваем из таблицы counter (date_start_scan & date_stop_scan)
    public $ar_errors404                    = array();//массив содержит страницы из таблицы pages с http_code == 22 (ошибки >= 404)
    public $ar_errors500                    = array();//массив содержит страницы из таблицы pages с http_code == 28 (ошибки сервера при окончании таймаута)
    public $ar_pages_internal_links         = array();//массив внешних ссылок
    public $ar_pages_not_title              = array();//массив страниц у которых нет || пастой title
    public $ar_pages_not_keywords           = array();//массив страниц у которых нет || пастой keywords
    public $ar_pages_not_description        = array();//массив страниц у которых нет || пастой description
}

class ServiceDetailReportTag
{
    private $ar_property = array();

    public function __set($name, $value)
    {
        $this->ar_property[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->ar_property))
        {
            return $this->ar_property[$name];
        }
        else
        {
            return null;
        }
    }

    public function __isset($name)
    {
        return isset($this->ar_property[$name]);
    }

    public function __unset($name)
    {
        if (array_key_exists($name, $this->ar_property))
        {
            unset($this->ar_property[$name]);
        }
    }

    function __destruct()
    {
        if(count($this->ar_property))
        {
            foreach($this->ar_property as $key=>$val)
            {
                unset($this->ar_property[$key]);
            }
            unset($this->ar_property);
        }
    }
}
?>