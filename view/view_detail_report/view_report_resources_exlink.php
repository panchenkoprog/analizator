<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Ресурсы <i>(внешние ссылки)</i></h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <b>Cо страницы: </b><a href="<?php echo self::$detail_report->from_page; ?>"><?php echo self::$detail_report->from_page; ?></a>
        <br>
    </div>
</div>  

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>href</th>
                        <th>anchor</th>
                    </tr>
                 </thead>
                 <tbody>
                    <?php
                        if(count(self::$detail_report->ar_resources_exlink) > 0)
                            {

                                foreach(self::$detail_report->ar_resources_exlink as $page)
                                {
                     ?>
                                    <tr>
                                        <td style="max-width: 600px;"><pre><?php echo $page['href']; ?><pre></td>
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