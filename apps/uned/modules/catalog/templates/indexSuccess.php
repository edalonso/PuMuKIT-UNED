<h1 id="catalog_h1"><?php echo __($title)?></h1>

<div class="lib_menu">
  <div class="lib_menu_sort">
         <?php echo __('Ordenar por') ?>:
         <a href="<?php echo url_for('catalog/date' . $more) ?>">Fecha</a>,
         <a href="<?php echo url_for('catalog/abc' . $more) ?>">Alfab&eacute;ticamente</a>.
  </div>
</div>

<div class="catalog">
  <?php if (count($serials) == 0): ?>
    <div class="noSearch">
      <?php echo __('Su busqueda no produjo ningun resultado') . '&nbsp;&nbsp;&nbsp;' . link_to(__('Cancelar'), 'catalog/' . $sf_request->getParameter('action'))?>.
    </div>
  <?php endif ?>
  <?php foreach ($serials as $o => $ss):  ?>
    <div class="name">
      <?php echo $o ?>
    </div>
      <div class="list">
        <ul>
          <?php foreach ($ss as $serial): $numV = $serial->countMmsPublicPub(); if ($numV == 0) continue;?>
            <li>
              <?php echo link_to($serial->getTitle(), 'serial/index?id=' . $serial->getId() ,'class=azul')?>
              [<?php echo $numV?> V&iacute;deo<?php echo (($numV == 1)?'':'s')?>]
            </li>
           <?php endforeach; ?>
        </ul>
      </div>
  <?php endforeach; ?>
</div>
