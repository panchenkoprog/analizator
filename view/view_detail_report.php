<?php

class ViewDetailReport extends View
{
    public $exit_file = 'index.php';// для формирования URL
    public $exit_url  = '';
    public $current_file = 'report.php';// для формирования URL

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

        $this->url = $this->separator_p
                    . $this->current_file
                    . $this->separator_q
                    . $this->page
                    . $this->separator_v;

        $this->exit_url = $this->separator_p
            . $this->exit_file
            . $this->separator_q
            . $this->page
            . $this->separator_v;

        $this->num_menu = $num_menu;

        if($this->session)
        {
            $this->title = $this->ar_title_session[$num_menu];
        }
        else
        {
            $this->title = $this->ar_title_not_session[$num_menu];
        }

        include_once('view_detail_report/view_head.php');
        include_once('view_detail_report/view_menu.php');
        include_once('view_detail_report/view_content.php');
        include_once('view_detail_report/view_footer.php');
    }
}
?>