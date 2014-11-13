<div class="div-body">
    <div class="content color" id="content">
        <div class="container">
        <?php
        switch($this->num_menu)
        {
            case 0:
                {
                    if($this->session)
                    {
                        //загружаем данные анализа сайта
                        include_once('view_analize.php');
                    }
                    else
                    {
                        //загружаем данные войти/выйти
                        //include_once('view_input.php');
                        include_once('view_page.php');
                    }
                }
                break;
            case 1:
                {
                    if($this->session)
                    {
                        if(isset($_REQUEST['site_report']) && $_REQUEST['site_report'] != '')
                        {
                            include_once('view_reports.php');
                        }
                        elseif(isset($_REQUEST['report_name']) && $_REQUEST['report_name'] != '')
                        {
                            if(isset($_REQUEST['list_pages']) && $_REQUEST['list_pages'] != '')
                            {
                                include_once('view_report_list_pages.php');
                            }
                            elseif(isset($_REQUEST['list_links']) && $_REQUEST['list_links'] != '')
                            {
                                include_once('view_report_list_links.php');
                            }
                            elseif(isset($_REQUEST['list_internal_links']) && $_REQUEST['list_internal_links'] != '')
                            {
                                include_once('view_report_list_internal_links.php');
                            }
                            elseif(isset($_REQUEST['list_external_links']) && $_REQUEST['list_external_links'] != '')
                            {
                                include_once('view_report_list_external_links.php');
                            }
                            elseif(isset($_REQUEST['list_unique_links']) && $_REQUEST['list_unique_links'] != '')
                            {
                                include_once('view_report_list_unique_links.php');
                            }
                            elseif(isset($_REQUEST['list_cycle_links']) && $_REQUEST['list_cycle_links'] != '')
                            {
                                include_once('view_report_list_cycle_links.php');
                            }
                            elseif(isset($_REQUEST['p_show']) && $_REQUEST['p_show'] == 'load_table')
                            {
                                include_once('view_report_load_table.php');
                            }
                            elseif(isset($_REQUEST['p_show']) && $_REQUEST['p_show'] == 'resources_table')
                            {
                                include_once('view_report_resources_table.php');
                            }
                            elseif(isset($_REQUEST['get_resources_img']) && intval($_REQUEST['get_resources_img']))
                            {
                                include_once('view_report_resources_img.php');
                            }
                            elseif(isset($_REQUEST['get_resources_exlink']) && intval($_REQUEST['get_resources_exlink']))
                            {
                                include_once('view_report_resources_exlink.php');
                            }
                            elseif(isset($_REQUEST['p_show']) && $_REQUEST['p_show'] == 'content_table')
                            {
                                include_once('view_report_content_table.php');
                            }
                            elseif(isset($_REQUEST['p_show']) && $_REQUEST['p_show'] == 'tags_table')
                            {
                                include_once('view_report_tags_table.php');
                            }
                            elseif((isset($_REQUEST['get_tags']) && intval($_REQUEST['get_tags'])) && (isset($_REQUEST['type']) && $_REQUEST['type'] != ''))
                            {
                                include_once('view_report_tags_type.php');
                            }
                            else
                            {
                                include_once('view_report.php');
                            }
                        }
                        elseif(isset($_REQUEST['project_tools']) && $_REQUEST['project_tools'] != '')
                        {
                            include_once('view_project_tools.php');
                        }
                        else
                        {
                            include_once('view_project.php');
                        }
                    }
                    else
                    {
                        //загружаем данные регистрация
                        include_once('view_register.php');
                    }
                }
                break;
            case 2:
                if($this->session)
                {
                    include_once('view_price.php');
                }
                else
                {
                    include_once('view_error.php');
                }
                break;
            case 3:
                if($this->session)
                {
                    include_once('view_news.php');
                }
                else
                {
                    include_once('view_error.php');
                }
                break;
            case 4:
                if($this->session)
                {
                    include_once('view_contact.php');
                }
                else
                {
                    include_once('view_error.php');
                }
                break;
            case 5:
                if($this->session)
                {
                    include_once('view_balance.php');
                }
                else
                {
                    include_once('view_error.php');
                }
                break;
            case 6:
                if($this->session && $this->admin)
                {
                    include_once('view_file.php');
                }
                else
                {
                    include_once('view_error.php');
                }
                break;
            default:
                include_once('view_error.php');
                break;
        }
        ?>
        </div>
    </div>
</div>