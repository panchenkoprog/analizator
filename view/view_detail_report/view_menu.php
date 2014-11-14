<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">MAS analizator</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?php echo $this->exit_url . '1'; ?>"><i class="fa fa-user fa-fw"></i> Мои проекты</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $this->exit_url . 'exit'; ?>"><i class="fa fa-sign-out fa-fw"></i> Выйти</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <h3>Название сайта</h3>
                        </li>
                        <li>
                            <?php
                            $url_total = $this->url.$this->num_menu.$this->separator_arguments
                                ."report_name=".self::$detail_report->report_name . $this->separator_arguments
                                ."host=" . $_REQUEST['host'];
                            ?>
                            <a class="active" href="<?php echo $url_total;?>"><i class="fa fa-bar-chart-o fa-fw"></i> Общая инфориация</a> 
                        </li>
                        <li>
                            <?php
                                $url_load = $this->url.$this->num_menu.$this->separator_arguments
                                ."report_name=".self::$detail_report->report_name.$this->separator_arguments
                                ."p_show=load_table". $this->separator_arguments
                                ."host=" . $_REQUEST['host'];
                            ?>
                            <a href="<?php echo $url_load;?>"><i class="fa fa-dashboard fa-fw"></i> Загрузка</a>
                        </li>
                        <li>
                            <?php
                            $url_resources = $this->url.$this->num_menu.$this->separator_arguments
                            ."report_name=".self::$detail_report->report_name.$this->separator_arguments
                            ."p_show=resources_table". $this->separator_arguments
                            ."host=" . $_REQUEST['host'];
                            ?>
                            <a href="<?php echo $url_resources;?>"><i class="fa fa-folder-open fa-fw"></i> Ресурсы</a>
                        </li>
                        <li>
                            <?php
                            $url_content = $this->url.$this->num_menu.$this->separator_arguments
                            ."report_name=".self::$detail_report->report_name.$this->separator_arguments
                            ."p_show=content_table". $this->separator_arguments
                            ."host=" . $_REQUEST['host'];
                            ?>
                            <a href="<?php echo $url_content;?>"><i class="fa fa-file-text-o fa-fw"></i> Контент</a>
                        </li>
                        <li>
                            <?php
                            $url_tags = $this->url.$this->num_menu.$this->separator_arguments
                            ."report_name=".self::$detail_report->report_name.$this->separator_arguments
                            ."p_show=tags_table". $this->separator_arguments
                            ."host=" . $_REQUEST['host'];
                            ?>
                            <a href="<?php echo $url_tags;?>"><i class="fa fa-code fa-fw"></i> Теги</a>
                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>


