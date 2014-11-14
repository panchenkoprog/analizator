<div class="row">
     <div class="col-lg-12">
         <h2 class="page-header">Отчёт по загрузке</h2>
     </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>URL</th>
                        <th>глубина</th>
                        <th>ответ</th>
                        <th>скорость(кбит/с)</th>
                        <th>размер(кб)</th>
                        <th>редирект</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(count(self::$detail_report->ar_load_table) > 0)
                    {

            foreach(self::$detail_report->ar_load_table as $page)
            {
                ?>
                <tr>
                    <td><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['url']; ?></a></td>
                    <td><?php echo $page['level_links']; ?></td>
                    <td><?php echo $page['http_code']; ?></td>
                    <td><?php echo round( ($page['page_speed'] / 1000) , 1); ?></td>
                    <td><?php echo round( ($page['page_size'] / 1024) , 2); ?></td>
                    <td><?php echo $page['redirect_url']; ?></td>
                </tr>
            <?php
            }
        }
        ?>
                </tbody>
    </table>
    
<?php
if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
{
    include_once('view_bottom_menu.php');
}
?>
         </div>
    </div>
</div>