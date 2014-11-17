
<?php
if(count(self::$ar_reports) > 0)
{
    ?>
    <h1>Отчёты по сайту: <?php echo self::$ar_reports[0]->site_name;  ?></h1>
    
<table style="margin-top: 20px" class="table table-hover" data-toggle="table" data-url="data1.json" data-cache="false">
    <thead>
        <tr>
            <th>Дата начала анализа</th>
            <th>Дата окончания анализа</th>
            <th>Состояние</th>
            <th>Информация</th>
        </tr>
    </thead>
    <?php
    foreach(self::$ar_reports as $elem)
    {?>
    <tbody>
        <tr>
            <td><?php echo $elem->date_start_report; ?></td>
            <?php
              if($elem->date_start_report && $elem->date_stop_report)
                { ?>   
                <td><?php echo $elem->date_stop_report; ?></td>
                <td>проанализировано</td>
                <td><a href="<?php echo $this->url_report.$this->num_menu.$this->separator_arguments."report_name=".$elem->report_name . $this->separator_arguments . "host=" . $elem->site_name; ?>">подробнее</a></td>
             <?php } 
                elseif($elem->date_start_report && !$elem->date_stop_report)
                        {
                            if($elem->flag_compare == 2)
                            { ?>
                                <td></td>
                                <td>отчёт готовится !</td>
                                <td></td>
                      <?php }
                            elseif($elem->flag_compare == 1)
                            { ?>
                                <td></td>
                                <td><?php echo 'идёт сравнение данных - анализируется ' . $elem->counter_compare_page . " из " . $elem->counter_compare_pages . " найденных ссылок"; ?></td>
                                <td></td>
                      <?php }
                            else
                            {
                                if($elem->flag_parse == 2)
                                { ?>
                                    <td></td>
                                    //echo '2-ой этап - сайт парсится !';
                                    <td><?php echo 'отчёт готовится !'; ?></td>
                                    <td></td>
                          <?php }
                                elseif($elem->flag_parse == 1)
                                { ?>
                                    <td></td>
                                    <td><?php echo '2-ой этап - парсится страниц ' . $elem->counter_parse_page . " из " . $elem->counter_parse_pages;?></td>
                                    <td></td>
                          <?php }
                                else
                                {
                                    if($elem->flag_scan == 2)
                                    { ?>
                                        <td></td>
                                        <td><?php echo '1-ый этап (поиск страниц) - закончен !'; ?></td>
                                        <td></td>
                              <?php }
                                    elseif($elem->flag_scan == 1)
                                    { ?>
                                        <td></td>
                                        <td><?php echo '1-ый этап - анализируется ' . $elem->counter_scan_page . " из " . $elem->counter_scan_pages . " найденых ссылок"; ?></td>
                                        <td></td>
                              <?php }
                                    else
                                    { ?>
                                        <td></td>
                                        <td><?php echo 'сайт анализируется !'; ?></td>
                                        <td></td>
                              <?php }
                                }
                            }
                            
                            //echo 'отчёт готовится !';
                        }
    }
                        ?>
</table>
<?php
    if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
    {
        include_once('view_bottom_menu.php');
    }
} 
?>