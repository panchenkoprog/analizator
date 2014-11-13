<div>
<span class="help-block"><b>Страницы проекта</b></span>
<?php
if(count(self::$detail_report->ar_pages) > 0)
{
    foreach(self::$detail_report->ar_pages as $page)
    {
        ?>

        <p><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['url']; ?></a></p>

        <?php
    }
}
?>
</div>
<?php
if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
{
    include_once('view_bottom_menu.php');
}
?>