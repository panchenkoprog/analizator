<div>
<span class="help-block"><b>Отчёт по загрузке</b></span>
    <table>
        <tr style="width: 1000px">
            <th style="border: 1px solid coral; max-width: 350px;">URL</th>
            <th style="border: 1px solid coral; max-width: 150px;">глубина</th>
            <th style="border: 1px solid coral; max-width: 150px;">ответ</th>
            <th style="border: 1px solid coral; max-width: 150px;">скорость(кбит/с)</th>
            <th style="border: 1px solid coral; max-width: 150px;">размер(кб)</th>
            <th style="border: 1px solid coral; max-width: 150px;">редирект</th>
        </tr>
        <?php
        if(count(self::$detail_report->ar_load_table) > 0)
        {

            foreach(self::$detail_report->ar_load_table as $page)
            {
                ?>
                <tr style="width: 1000px">
                    <td style="border: 1px solid coral; max-width: 350px; font-size: 12px;"><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['url']; ?></a></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['level_links']; ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['http_code']; ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo round( ($page['page_speed'] / 1000) , 1); ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo round( ($page['page_size'] / 1024) , 2); ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['redirect_url']; ?></td>
                </tr>
            <?php
            }
        }
        ?>
    </table>
</div>
<?php
if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
{
    include_once('view_bottom_menu.php');
}
?>