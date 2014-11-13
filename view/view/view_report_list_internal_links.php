<div>
<span class="help-block"><b>Внутренние ссылки проекта</b></span>
<table>
    <tr style="width: 1000px">
        <th style="border: 1px solid coral; max-width: 250px;">откуда</th>
        <th style="border: 1px solid coral; max-width: 350px;">link</th>
        <th style="border: 1px solid coral; max-width: 200px;">anchor</th>
    </tr>
<?php
if(count(self::$detail_report->ar_internal_links) > 0)
{

    foreach(self::$detail_report->ar_internal_links as $page)
    {
        ?>
        <tr style="width: 1000px">
            <td style="border: 1px solid coral; max-width: 250px; font-size: 12px;"><?php echo $page['parent_url']; ?></td>
            <td style="border: 1px solid coral; max-width: 350px; font-size: 12px;"><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['href']; ?></a></td>
            <td style="border: 1px solid coral; max-width: 200px; font-size: 12px;"><?php echo $page['anchor']; ?></td>
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