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
                        <th>изображения</th>
                        <th>внешние ссылки</th>
                    </tr>
                </thead>
                </tbody>
                <?php
                if(count(self::$detail_report->ar_resources_table) > 0)
                {

                    foreach(self::$detail_report->ar_resources_table as $page)
                    {
                        ?>
                        <tr>
                            <td><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['url']; ?></a></td>
                        <?php
                            if($page['c_img'])
                            {
                                $url_resources_img = $this->url.$this->num_menu.$this->separator_arguments
                                    ."report_name=".self::$detail_report->report_name.$this->separator_arguments
                                    ."get_resources_img=".$page['id']. $this->separator_arguments
                                    ."host=" . $_REQUEST['host'];
                        ?>
                            <td><a href="<?php echo $url_resources_img; ?>"><?php echo $page['c_img']; ?></a></td>
                    <?php
                            }
                            else
                            { ?>
                            <td></td>
                    <?php
                            }
                            if($page['c_i_link'])
                            {
                                $url_resources_exlink = $this->url.$this->num_menu.$this->separator_arguments
                                    ."report_name=".self::$detail_report->report_name.$this->separator_arguments
                                    ."get_resources_exlink=".$page['id']. $this->separator_arguments
                                    ."host=" . $_REQUEST['host'];
                     ?>
                            <td><a href="<?php echo $url_resources_exlink;?>"><?php echo $page['c_i_link']; ?></a></td>
                    <?php
                            }
                            else
                            { ?>
                            <td></td>
                    <?php
                            }
                    ?>
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