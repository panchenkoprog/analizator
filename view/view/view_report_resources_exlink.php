<div>
<span class="help-block"><b>Ресурсы (внешние ссылки) со страницы <a href="<?php echo self::$detail_report->from_page; ?>"><?php echo self::$detail_report->from_page; ?></a></b></span>
    <table>
        <tr style="width: 1000px">
            <th style="border: 1px solid coral; max-width: 350px;">href</th>
            <th style="border: 1px solid coral; max-width: 150px;">anchor</th>
        </tr>
        <?php
        if(count(self::$detail_report->ar_resources_exlink) > 0)
        {

            foreach(self::$detail_report->ar_resources_exlink as $page)
            {
                ?>
                <tr style="width: 1000px">
                    <td style="border: 1px solid coral; max-width: 350px; font-size: 12px;"><?php echo $page['href']; ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['anchor']; ?></td>
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