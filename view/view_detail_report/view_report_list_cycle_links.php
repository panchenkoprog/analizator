<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Циклические ссылки проекта</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr style="width: 1000px">
                        <th>страница</th>
                        <th>ссылается на</th>
                        <th>anchor</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(count(self::$detail_report->ar_cycle_links) > 0)
                    {

                        foreach(self::$detail_report->ar_cycle_links as $page)
                        {
                ?>
                        <tr>
                            <td style="min-width: 300px; max-width: 400px;"><pre><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['href']; ?></a></pre></td>
                            <td style="min-width: 300px; max-width: 400px;"><pre><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['href']; ?></a></pre></td>
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