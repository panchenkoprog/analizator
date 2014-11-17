<?php

class View extends Service
{
    public $ar_title_not_session = array(
        0 => 'Вход',
        1 => 'Регистрация'
    );

    public $ar_title_session = array(
        0 => 'Анализ',
        1 => 'Проекты',//отчёты проекта, настройки проекта
        2 => 'Цены',
        3 => 'Новости',
        4 => 'Контакты',
        5 => 'Баланс',
        6 => 'Файл'
    );

    public $current_file = 'index.php';// для формирования URL
    public $current_detail_file = 'report.php';// для формирования URL
    public $separator_q  = '?';
    public $separator_v  = '=';
    public $separator_p  = '/';
    public $separator_arguments = '&';
    public $query        = 0;
    public $path         = '';
    public $page         = 'page';


    public $class    = "class='activ' ";
    public $title     = '';
    public $num_menu  = 0;
    public $url       = '';
    public $url_report= '';
    public $session   = 0;
    public $result    = '';
    public $admin     = 0;
    //public $tariff    = 0.05;//при изменении этого параметра, нужно изменить var tariff в файле /js/scripts.js

    function ViewInit( $num_menu, $session = 0, $result = '' )
    {
        if(isset($_SESSION) && $_SESSION['login'] == parent::$login && $_SESSION['password'] == parent::$password)
        {
            $this->admin = 1;
        }
        else
        {
            $tmp_ar = $this->ar_title_session;
            $this->ar_title_session = array();
            for($i=0; $i<(count($tmp_ar)-1); $i++)
            {
                $this->ar_title_session[] = $tmp_ar[$i];
            }
        }

        $this->session = $session;
        $this->result  = $result;
        $this->num_menu = $num_menu;

        //если пункт меню ПРОЕКТЫ -> ОТЧЁТЫ
        if($this->num_menu == 1 && isset($_REQUEST['site_report']) && $_REQUEST['site_report'] != '')
        {
            $this->url_report = $this->separator_p
                . $this->current_detail_file
                . $this->separator_q
                . $this->page
                . $this->separator_v;
        }
        else
        {
            $this->url_report = $this->separator_p
                . $this->current_file
                . $this->separator_q
                . $this->page
                . $this->separator_v;
        }

        $this->url = $this->separator_p
            . $this->current_file
            . $this->separator_q
            . $this->page
            . $this->separator_v;

        if($this->session)
        {
            $this->title = $this->ar_title_session[$num_menu];
        }
        else
        {
            $this->title = $this->ar_title_not_session[$num_menu];
        }

        include_once('view/view_head.php');
        include_once('view/view_menu.php');
        include_once('view/view_content.php');
        include_once('view/view_footer.php');
    }
}
?>