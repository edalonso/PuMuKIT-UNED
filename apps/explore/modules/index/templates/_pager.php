<ul class="pager" style="margin: 4px 0px 2px;">
  <li class="previous <?php if($page == 1):?>disabled<?php endif ?>">
    <a href="<?php echo url_for("index/mainpanel?id=" . $id . "&page=". ($page - 1)) ?>">&larr; Older</a>
  </li>
  <li>
    <?php echo $page ?> de <?php echo $total ?>
  </li>
  <li class="next <?php if($page == $total):?>disabled<?php endif ?>">
    <a href="<?php echo url_for("index/mainpanel?id=" . $id . "&page=". ($page + 1)) ?>">Newer &rarr;</a>
  </li>
</ul>
