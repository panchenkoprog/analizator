<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Уникальные ссылки проекта</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Ссылки</div>
            <div class="panel-body">
            <?php
                if(count(self::$detail_report->ar_unique_links) > 0)
                {
                    foreach(self::$detail_report->ar_unique_links as $page)
                    {
            ?>

                        <span style="min-width: 300px; max-width: 1000px;"><pre><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['url']; ?></a></pre></span>

    <?php
    }
}
?>

<?php
if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
{
    include_once('view_bottom_menu.php');
}
?>
            </div>
        </div> 
    </div>
</div>