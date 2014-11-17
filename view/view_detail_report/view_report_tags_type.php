    <?php

    $tag_elem_text = " ";
    $tag_elem_ar_words      = explode(' ', $tag_elem_text);
    $t=0;

    ?>
<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Отчёт по анализу тегов</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-success">
            <div class="panel-heading"><?php echo self::$detail_report->name_current_tag; ?></div>
            <div class="panel-body">
                <p style="padding-left: 10px;">Количество: <?php echo count(self::$detail_report->ar_current_tags); ?></p>
                <pre>URL : <?php echo self::$detail_report->from_page; ?></pre>
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Текст</th>
                                <th>кол. слов</th>
                                <th>кол.символов</th>
                            </tr>
                         <tbody>
                         <?php
                            foreach(self::$detail_report->ar_current_tags as $tag)
                            { ?>
                                <tr>
                                    <td><?php echo $tag->info; ?></td>
                                    <td><?php echo $tag->count_words; ?></td>
                                    <td><?php echo $tag->count_symbols; ?></td>
                                </tr>
                        <?php
                             }
                        ?>
                          </tbody>
                      </table>
                </div>
            </div>


<?php
if(count(self::$ar_a) > 1)//показываем пункты меню, если их больше одного
{
    include_once('view_bottom_menu.php');
}
?>

        </div>
    </div>
</div>