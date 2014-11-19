<?php
class Controller
{
    public $model = null; //класс модель
    public $view  = null; //класс вид

    public $current_file = 'index.php';// для формирования URL
    public $separator_q  = '?';
    public $separator_v  = '=';
    public $separator_p  = '/';
    public $separator_arguments = '&';
    public $query        = 0;
    public $path         = '';
    public $result_db    = '';
    public $session      = 0;//

    function __construct( $obj_view = null )
    {
        $this->model = new Model();
        if($obj_view == null)
            $this->view  = new View();
        else
            $this->view  = $obj_view;
    }

    function ControllerSession( $session )
    {
        $this->session = $session;

        if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'exit')
        {
            if(isset($_SESSION['user_session']))//
            {
                unset($_SESSION['user_session']);
                unset($_SESSION['login']);
                unset($_SESSION['password']);
                unset($_SESSION['hash_pass']);
                unset($_SESSION['email']);
                unset($_SESSION['id']);
                $_SESSION = array();
                //если после окончания сессии попадаешь на страницу "регистрация" - нужно закоментить вызов session_destroy()
                //если на страницу "вход" - session_destroy() должен присутствовать
                //session_destroy(); // закоментим, так как на данный момент у нас и Вход и Регистрация на одной странице!
            }
            $this->session = 0;
            $this->view->ViewInit(0, $this->session);
        }
        elseif(isset($_REQUEST['page']))
        {
            $this->model->ModelGetBalanceInSession();

            // для вызова методов модели (Model)
            switch($_REQUEST['page'])
            {
                case 1://Project - выводим все проекты пользователя
                    {
                        if(isset($_REQUEST['site_report']) && $_REQUEST['site_report'] != '')
                        {//отображаем все отчёты по данному проекту (сайту)
                            $this->model->ModelGetReports(intval($_REQUEST['page']));
                        }
                        elseif(isset($_REQUEST['report_name']) && $_REQUEST['report_name'] != '')
                        {//отображаем один отчёт
                            $this->model->ModelGetDetailReport($_REQUEST['report_name']);
                        }
                        elseif(isset($_REQUEST['project_tools']) && $_REQUEST['project_tools'] != '')
                        {//Project_tools - выводим все настройки проекта
                            $this->model->Model_Project_GetProjectTools();
                        }
                        else
                        {//отображаем все проекты (сайты) пользователя
                            $this->model->Model_Project_GetUserProjects(intval($_REQUEST['page']));
                        }
                    }
                    break;
                case 6://
                    {
                        if(isset($_FILES) && count($_FILES))
                        {
                            $this->model->ModelUploadFile();
                        }
                    }
                    break;
                default:
                    break;
            }

            $this->view->ViewInit($_REQUEST['page'], $this->session);
        }
        else
        {
            $this->view->ViewInit(0, $this->session);
        }
    }

    function ControllerNotSession( $session )
    {
        $this->session = $session;

        if(isset($_REQUEST['page']))
        {
            if($_REQUEST['page'] == 0 && isset($_REQUEST['log']))
            {
                $this->model->ModelAutorizationUser($this->session, $this->result_db);
            }
            elseif($_REQUEST['page'] == 0 && isset($_REQUEST['reg']))
            {
                $this->model->ModelAddUser($this->session, $this->result_db);
            }

            $this->view->ViewInit($_REQUEST['page'], $this->session, $this->result_db);
        }
        elseif(isset($_GET['not_send']) && intval($_GET['not_send']) > 0)
        {
            $this->model->ModelNotSendEmail(intval($_GET['not_send']));
        }
        else
        {
            $this->view->ViewInit(0, $this->session);
        }
    }

    function Installation()
    {
        $this->model->ModelInstallService();
    }
}
?>