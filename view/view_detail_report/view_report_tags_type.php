<div>
    <?php

    $tag_elem_text = " ";
    $tag_elem_ar_words      = explode(' ', $tag_elem_text);
    $t=0;

    ?>
<span class="help-block"><b>Отчёт по анализу тегов</b></span>
    <div>
        <p>Теги <?php echo self::$detail_report->name_current_tag; ?>: <?php echo self::$detail_report->count_fields_tag; ?></p>
        <p>URL : <?php echo self::$detail_report->from_page; ?></p>
    </div>
    <table>
        <tr style="width: 1000px">
            <th style="border: 1px solid coral; width: 50px; max-width: 350px;">Текст</th>
            <th style="border: 1px solid coral; width: 50px; max-width: 150px;">кол. слов</th>
            <th style="border: 1px solid coral; width: 50px; max-width: 150px;">кол.символов</th>
        </tr>
        <?php
        foreach(self::$detail_report->ar_current_tags as $tag)
        { ?>
            <tr style="width: 1000px">
                <td style="border: 1px solid coral; max-width: 350px; font-size: 12px;">
                    <?php echo $tag->info; ?>
                </td>
                <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;">
                    <?php echo $tag->count_words; ?>
                </td>
                <td style="border: 1px solid coral; max-width: 150px; font-size: 12px;">
                    <?php echo $tag->count_symbols; ?>
                </td>
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