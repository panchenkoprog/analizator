<div>
<span class="help-block"><b>Отчёт по анализу тегов</b></span>
    <table>
        <tr style="width: 1000px">
            <th style="border: 1px solid coral; max-width: 350px;">URL</th>
            <?php
            foreach(self::$detail_report->ar_tags as $tag)
            { ?>
                <th style="border: 1px solid coral; width: 50px; max-width: 150px;"><?php echo $tag; ?></th>
            <?php
            } ?>
        </tr>
        <?php
        foreach(self::$detail_report->ar_tags_table as $page)
        {
            ?>
            <tr style="width: 1000px">
                <td style="border: 1px solid coral; max-width: 350px; font-size: 12px;">
                    <a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['url']; ?></a>
                </td>
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
                        <td style="border: 1px solid #ff8050; max-width: 150px; font-size: 12px;">
                            <a href="<?php echo $url_tag; ?>"><?php echo $page[$tag]; ?></a>
                        </td>
                    <?php
                    }
                    else
                    { ?>
                        <td style="border: 1px solid #ff8050; max-width: 150px; font-size: 12px;"></td>
                    <?php
                    }
                }
                ?>
            </tr>
        <?php
        }
        ?>
    </table>
</div>
<?php
if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
{
    include_once('view_bottom_menu.php');
}
?>