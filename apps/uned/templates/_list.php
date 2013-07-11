<?php if(!is_null($title)): ?>
  <h4><span class="azul">[</span><?php echo $title ?><span class="azul">]</span></h4>
<?php endif ?>

<?php foreach($list as $item): ?>
  <?php include_partial('global/announce', array('announce' => $item)) ?>
<?php endforeach ?>