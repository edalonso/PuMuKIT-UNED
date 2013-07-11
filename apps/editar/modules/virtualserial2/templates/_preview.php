<?php if( isset($mm) ):?>


<!------------------------------------->

<!-- DATE (falta I18n)-->
<div style="background-color:#006699; color:#FFFFFF; font-weight:bold; margin-bottom:11px; text-align:center;">
  <?php echo $mm->getRecordDate('%d de %B de %Y')?>
</div>

<!-- SUBSERIAL_TITLE-->
<?php if($mm->getSubserialTitle() !== ''):?>
  <div style="background-color:#006699; color:#FFFFFF; font-weight:bold; margin-bottom:11px; text-align:center;">
    <?php echo $mm->getSubserialTitle(); ?>
  </div>
<!-- PLACE-->
<?php elseif($mm->getPrecinctId() > 1): ?>
  <div style="background-color:#DFDFFF; color:#660000; font-weight:bold; margin-bottom:11px; padding-right:5px; text-align:right;">
    <?php echo $mm->getPlace()->getName()?>  
  </div>
<?php endif?>


<!-- PIC -->
<div id="serial_mm" class="serial_mm_<?php echo ($mm->getStatusId()?1:0)?>" style="background-color:transparent; height: 100%">

 <div style="background-color:transparent; margin: 3%; overflow: hidden;" VALIGN="MIDDLE" ALIGN="CENTER">
    <?php include_partial('player', array('file' => $file, 'w' => 360, 'h' => 280))?>
 </div>
 <div style="padding: 0px 4%;">
          <div class="bs-docs-example-tematicas">
             <?php foreach($mm->getCategorys($cat_raiz_uned) as $uned): ?>
                <div style="font-size: 10px; margin: 1px 5px 1px 1px;" class="label label-info"><?php echo $uned->getName() ?></div>
             <?php endforeach ?>
          </div>
          <div id="list_unesco" draggable="true" class="bs-docs-example">
             <?php foreach($mm->getCategorys($cat_raiz_unesco) as $unesco): ?>
                <div id="cat-<?php echo $unesco->getId()?>" class="label label-success unesco_element">
                  <?php echo $unesco->getName() ?>
                  <a href="#" class="unesco_element_a" onclick="if (window.confirm('¿Seguro?')) {$('cat-<?php echo $unesco->getId()?>').remove(); del_tree_cat(<?php echo $unesco->getId()?>, <?php echo $mm->getId()?>)};; return false;">X</a>
                </div>
             <?php endforeach ?>
          </div>
 </div>

 <div id="old_comments">
   <div style="font-weight: bold; font-size: 15px; margin-bottom: 5px;" VALIGN="MIDDLE" ALIGN="CENTER">Datos de objetos multimedia proporcionados por la UNED</div>
   <table style="border-collapse: collapse; border-spacing: 0; width: 100%;">
      <thead>
         <tr style="border: 2px solid gray;">
            <th style="padding-left: 1%;">Campo</th>
            <th style="padding-left: 1%;">Valor</th>
         </tr>
      </thead>
      <tbody>
         <?php
            $umo = $mm->getUnedMediaOld();
            $columnas = UnedMediaOldPeer::getFieldNames();
            foreach ($columnas as $columna):?>
               <?php $getter = "get" . $columna;?>
                  <tr>
                     <td style="padding: 1%; border: 1px solid #ddd;"><?php echo $columna?></td>
                     <td style="padding: 1%; border: 1px solid #ddd;"><?php echo $umo->$getter()?></td>
                  </tr>
               <?php endforeach?>
      </tbody>
   </table>
</div>
</div>
<!------------------------------------->



<?php else:?>
<p>
  Selecione algun objeto multimedia.
</p>
<?php endif?>


<?php echo javascript_tag("
function handleDragStart(e) {
  if (e.stopPropagation) {
    e.stopPropagation();
  }
  if (e.preventDefault) {
    e.preventDefault();
  }
  return false;
}

function handleDragOver(e) {
  if (e.preventDefault) {
    e.preventDefault();
  }
  this.style.backgroundColor = '#a2bae7';
  e.dataTransfer.dropEffect = 'move';
  return false;
}

function handleDragEnter(e) {
  this.classList.add('over');
}

function handleDragLeave(e) {
  this.style.backgroundColor = '#eef';
}

function handleDrop(e) {
  this.classList.remove('over');
  add_tree_cat(dragSrcEl.children[0].id," . $sf_user->getAttribute('id', null, 'tv_admin/virtualserial')  .");
}

var unescos = document.querySelectorAll('#list_unesco');
[].forEach.call(unescos, function(unesco) {
  unesco.addEventListener('dragstart', handleDragStart, false);
  unesco.addEventListener('dragover', handleDragOver, false);
  unesco.addEventListener('dragenter', handleDragEnter, false);
  unesco.addEventListener('dragleave', handleDragLeave, false);
  unesco.addEventListener('drop', handleDrop, false);
});
") ?>
