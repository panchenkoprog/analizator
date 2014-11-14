<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Отчёт по анализу тегов</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>URL</th>
                    <?php
                        foreach(self::$detail_report->ar_tags as $tag)
                        { ?>
                        <th><?php echo $tag; ?></th>
                        <?php
                        } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach(self::$detail_report->ar_tags_table as $page)
                        {
                        ?>
                        <tr>
                            <td style="max-width: 600px"><pre><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['url']; ?></a></pre></td>
                        <?php
                            foreach(self::$detail_report->ar_tags as $tag)
                            {
                                if($page[$tag])
                                {
                                    $url_tag = $this->url.$this->num_menu.$this->separator_arguments
                                        ."report_name=".self::$detail_report->report_name.$this->separator_arguments
                                        ."get_tags=".$page['id']. $this->separator_arguments
                                        ."type=".$tag."". $this->separator_arguments
                                        ."host=" . $_REQUEST['host'];
                         ?>
                                <td><a href="<?php echo $url_tag; ?>"><?php echo $page[$tag]; ?></a></td>
                        <?php
                                }
                            else
                                { ?>
                                <td></td>
                        <?php
                                }
                            }
                        ?>
                        </tr>
        <?php
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