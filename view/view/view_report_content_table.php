<div>
<span class="help-block"><b>Отчёт по анализу контента</b></span>

    <h3>Количество контента на страницах</h3>
    <div>
        <table>
            <?php
            if(count(self::$detail_report->ar_content_cs) > 0)
            {
                foreach(self::$detail_report->ar_content_cs as $cs_key => $cs_val)
                {
                    ?>
                    <tr style="width: 1000px">
                        <th style="border: 1px solid coral; max-width: 350px; font-size: 12px;">
                            <?php echo $cs_key <= 6 ? ($cs_key-1)."-".$cs_key." тыс. символов" : "больше ".($cs_key-1)." тыс. символов"; ?>
                        </th>
                        <td style="border: 1px solid coral; max-width: 350px; font-size: 12px;"><?php echo $cs_val." стр."; ?></td>
                    </tr>
                <?php
                }
            }
            ?>
        </table>
    </div>
    <br />
    <div>
        <table>
            <tr style="width: 1000px">
                <th style="border: 1px solid coral; max-width: 350px;">Url</th>
                <th style="border: 1px solid coral; max-width: 150px;">кол. слов</th>
                <th style="border: 1px solid coral; max-width: 150px;">кол. символов</th>
            </tr>
            <?php
            if(count(self::$detail_report->ar_content_table) > 0)
            {
                foreach(self::$detail_report->ar_content_table as $page)
                {
                    ?>
                    <tr style="width: 1000px">
                        <td style="border: 1px solid coral; max-width: 350px; font-size: 12px;"><a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['url']; ?></a></td>
                        <td style="border: 1px solid coral; max-width: 350px; font-size: 12px;"><?php echo $page['count_words']; ?></td>
                        <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['count_symbols']; ?></td>
                    </tr>
                <?php
                }
            }
            ?>
        </table>
    </div>
</div>
<?php
if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
{
    include_once('view_bottom_menu.php');
}
?>