
<div class="row">
    <div class="col-lg-offset-3  col-lg-6">
        <div class="dataTables_paginate paging_simple_numbers">
            <ul class="pagination">
                <?php foreach(self::$ar_a as $li){?>
                    <li class="paginate_button previous"><?php echo $li; ?></li>
                <?php }?>
            </ul>
        </div>
    </div>
</div>