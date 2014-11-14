<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Внутренние ссылки проекта</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>откуда</th>
                        <th>link</th>
                        <th>anchor</th>
                    </tr>
                 </thead>
                 <tbody>
                    <?php
                        if(count(self::$detail_report->ar_internal_links) > 0)
                            {

                                foreach(self::$detail_report->ar_internal_links as $page)
                                    {
                     ?>
                                        <tr>
                                            <td><?php echo $page['parent_url']; ?></td>
                                            <td><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['href']; ?></a></td>
                                            <td><?php echo $page['anchor']; ?></td>
                                        </tr>
                    <?php
                                    }
                               }
                    ?>
                    </tbody>
            </table>
        </div>
    </div>
</div>
<?php
if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
{
    include_once('view_bottom_menu.php');
}
?>