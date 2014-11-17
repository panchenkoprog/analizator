<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Отчёт по анализу контента</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Количество контента на страницах</div>
            <div class="panel-body">
                <div class="table-responsive table-bordered">
                    <table class="table table-bordered table-hover">
                        <tbody>
                    <?php
                        if(count(self::$detail_report->ar_content_cs) > 0)
                        {
                            foreach(self::$detail_report->ar_content_cs as $cs_key => $cs_val)
                            {
                    ?>
                                <tr>
                                    <td>
                                        <?php echo $cs_key <= 6 ? ($cs_key-1)."-".$cs_key." тыс. символов" : "больше ".($cs_key-1)." тыс. символов"; ?>
                                    </td>
                                    <td><?php echo $cs_val." стр."; ?></td>
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
       </div>
   </div>

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Url</th>
                        <th>кол. слов</th>
                        <th>кол. символов</th>
                    </tr>
                </thead>
            <?php
            if(count(self::$detail_report->ar_content_table) > 0)
            {
                foreach(self::$detail_report->ar_content_table as $page)
                {
                    ?>
                    <tr>
                        <td style="min-width: 400; max-width: 600px;"><pre><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['url']; ?></a></pre></td>
                        <td style="min-width: 300px; max-width: 500px;"><?php echo $page['count_words']; ?></td>
                        <td style="max-width: 150px;"><?php echo $page['count_symbols']; ?></td>
                    </tr>
                <?php
                }
            }
            ?>
                    
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