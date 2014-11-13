<div>
<span class="help-block"><b>Отчёт по ресурсам</b></span>
    <table>
        <tr style="width: 1000px">
            <th style="border: 1px solid coral; max-width: 350px;">URL</th>
            <th style="border: 1px solid coral; max-width: 150px;">изображения</th>
            <th style="border: 1px solid coral; max-width: 150px;">внешние ссылки</th>
        </tr>
        <?php
        if(count(self::$detail_report->ar_resources_table) > 0)
        {

            foreach(self::$detail_report->ar_resources_table as $page)
            {
                ?>
                <tr style="width: 1000px">
                    <td style="border: 1px solid coral; max-width: 350px; font-size: 12px;"><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['url']; ?></a></td>
                    <?php
                    if($page['c_img'])
                    {
                        $url_resources_img = $this->url.$this->num_menu.$this->separator_arguments
                            ."report_name=".self::$detail_report->report_name.$this->separator_arguments
                            ."get_resources_img=".$page['id']. $this->separator_arguments
                            ."host=" . $_REQUEST['host'];
                        ?>
                        <td style="border: 1px solid #ff8050; max-width: 150px; font-size: 12px;"><a href="<?php echo $url_resources_img; ?>"><?php echo $page['c_img']; ?></a></td>
                    <?php
                    }
                    else
                    { ?>
                        <td style="border: 1px solid #ff8050; max-width: 150px; font-size: 12px;"></td>
                    <?php
                    }
                    if($page['c_i_link'])
                    {
                        $url_resources_exlink = $this->url.$this->num_menu.$this->separator_arguments
                            ."report_name=".self::$detail_report->report_name.$this->separator_arguments
                            ."get_resources_exlink=".$page['id']. $this->separator_arguments
                            ."host=" . $_REQUEST['host'];
                        ?>
                        <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><a href="<?php echo $url_resources_exlink;?>"><?php echo $page['c_i_link']; ?></a></td>
                    <?php
                    }
                    else
                    { ?>
                        <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"></td>
                    <?php
                    }
                    ?>
                </tr>
            <?php
            }
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