
<h1>Мои проекты</h1>
<?php
if(count(self::$ar_projects) > 0)
{?>
    <div class="projects">
        <?php
        //$counter = 0;
        foreach(self::$ar_projects as $k => $elem)
        {?>
            <div class="project">
                <!--<div><?php /*echo ++$counter;*/?></div>-->
                <div class="div-site">
                    <p class="p-site">Сайт</p>
                    <p class="site"><?php echo $elem->site_name; ?></p>
                </div>
                <div class="div-state">
                    <p class="p-state">Состояние</p>
                    <p class="state">
                        <?php
                        if($elem->analyze)
                        {
                            echo 'проанализирован&nbsp;&nbsp;&nbsp;<a class="restart_analysis" href="model/restart_analyze.php?site_name='.$elem->site_name.'&error='.$k.'"><button class="btn btn-info">повторный анализ</button></a>'.'<span id="error_'.$k.'" class="text-error"></span>';
                        }
                        else
                        {
                            echo "анализируется";
                        }
                        ?>
                    </p>
                </div>
                <?php
/*                if($elem->report)
                {*/?><!--
                    <div class="div-report">
                        <p class="p-report">Отчеты</p>
                        <p class="report"><?php /*echo '<a class="project_report" href="'.$this->url.$this->num_menu.'&site_report='.$elem->site_name.'">отчёты</a>'; */?></p>
                    </div>
                <?php
/*                }
                elseif($elem->analyze && !$elem->report)
                {*/?>
                <div class="div-report">
                    <p class="p-report">Отчет</p>
                    <p class="report"><?php /*echo 'отчёт готовится !'; */?></p>
                </div>
                --><?php
/*                }*/?>
                <div class="div-report">
                    <p class="p-report">Отчеты</p>
                    <p class="report"><?php echo '<a class="project_report" href="'.$this->url.$this->num_menu.'&site_report='.$elem->site_name.'">отчёты</a>'; ?></p>
                </div>
                <div class="div-tools">
                    <p class="p-tools">Настройки</p>
                    <p class="tools"><a href="<?php echo $this->url.$this->num_menu."&project_tools=tools&site_db_name=".$elem->site_db_name;?>">настройки</a></p>
                </div>
                <!--<p id="error_<?php /*echo $k; */?>" class="text-error"></p>-->
            </div>
        <?php
        }
        ?>
    </div>
<?php
    if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
    {
        include_once('view_bottom_menu.php');
    }
}
?>
