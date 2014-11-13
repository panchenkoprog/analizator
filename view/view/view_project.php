
<h1>Мои проекты</h1>

<table class="table table-striped">
    <thead>
    <tr>
        <th>№</th>
        <th>Сайт</th>
        <th>Отчеты</th>
        <th>Настройки</th>
        <th>Состояние</th>
        <th>Повторный анализ</th>
    </tr>
    </thead>
    <?php
    if(count(self::$ar_projects) > 0)
    {?>
        <tbody>
        <?php
        //$counter = 0;
        foreach(self::$ar_projects as $k => $elem)
        {?>
            <tr>
                <td></td>
                <td><?php echo $elem->site_name; ?></td>
                <td><a href="<?php echo $this->url.$this->num_menu.'&site_report='.$elem->site_name ?>"><span class="table-icon"><i class="fa fa-file-text-o" style="line-height: 2;"></i></span></a></td>
                <td><a href="<?php echo $this->url.$this->num_menu."&project_tools=tools&site_db_name=".$elem->site_db_name;?>"><span class="table-icon"><i class="fa fa-cogs" style="line-height: 2;"></i></span></a></td>
                <td> <?php
                    if($elem->analyze)
                    {
                        echo 'проанализирован';
                    }
                    else
                    {
                        echo "анализируется";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if($elem->analyze)
                    {
                        echo '<a class="restart_analysis" href="model/restart_analyze.php?site_name='.$elem->site_name.'&error='.$k.'"><button class="table-but">Повторный анализ</button></a>'.'<span id="error_'.$k.'" class="text-error"></span>';
                    }
                    ?>
                </td>
            </tr>
            </tbody>
        <?php
        }}
    ?>
</table>
<?php
if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
{
    include_once('view_bottom_menu.php');
}

?>
