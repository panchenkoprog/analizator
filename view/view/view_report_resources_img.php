<div>
<span class="help-block"><b>Ресурсы (изображения) со страницы <a href="<?php echo self::$detail_report->from_page; ?>"><?php echo self::$detail_report->from_page; ?></a></b></span>
    <table>
        <tr style="width: 1000px">
            <th style="border: 1px solid coral; max-width: 350px;">src</th>
            <th style="border: 1px solid coral; max-width: 150px;">title</th>
            <th style="border: 1px solid coral; max-width: 150px;">кол. слов в title</th>
            <th style="border: 1px solid coral; max-width: 150px;">alt</th>
            <th style="border: 1px solid coral; max-width: 150px;">кол. слов в alt</th>
            <th style="border: 1px solid coral; max-width: 150px;">width</th>
            <th style="border: 1px solid coral; max-width: 150px;">height</th>
        </tr>
        <?php
        if(count(self::$detail_report->ar_resources_img) > 0)
        {

            foreach(self::$detail_report->ar_resources_img as $page)
            {
                ?>
                <tr style="width: 1000px">
                    <td style="border: 1px solid coral; max-width: 350px; font-size: 12px;"><?php echo $page['src']; ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['title']?$page['title']:''; ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['count_word_title']?$page['count_word_title']:''; ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['alt']?$page['alt']:''; ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['count_word_alt']?$page['count_word_alt']:''; ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['width']?$page['width']:''; ?></td>
                    <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;"><?php echo $page['height']?$page['height']:''; ?></td>
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