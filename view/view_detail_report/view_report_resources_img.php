<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Ресурсы <i>(изображения)</i></h2>
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
                    <tr style="width: 1000px">
                        <th>src</th>
                        <th>title</th>
                        <th>кол. слов в title</th>
                        <th>alt</th>
                        <th>кол. слов в alt</th>
                        <th>width</th>
                        <th>height</th>
                    </tr>
                 </thead>
                 <tbody>
                    <?php
                        if(count(self::$detail_report->ar_resources_img) > 0)
                        {

                            foreach(self::$detail_report->ar_resources_img as $page)
                            {
                    ?>
                                <tr>
                                    <td style="min-width: 300px; max-width: 500px;"><pre><?php echo $page['src']; ?></pre></td>
                                    <td><?php echo $page['title']?$page['title']:''; ?></td>
                                    <td><?php echo $page['count_word_title']?$page['count_word_title']:''; ?></td>
                                    <td><?php echo $page['alt']?$page['alt']:''; ?></td>
                                    <td><?php echo $page['count_word_alt']?$page['count_word_alt']:''; ?></td>
                                    <td><?php echo $page['width']?$page['width']:''; ?></td>
                                    <td><?php echo $page['height']?$page['height']:''; ?></td>
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