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
    <?php if($file): ?>
       <?php include_partial('player', array('file' => $file, 'w' => 360, 'h' => 280))?>
    <?php else: ?>
       <div class="SimPlayer">
          <img WIDTH="30" HEIGHT="30" src="/images/uned/play.png" style="margin-top: 40px;"></img>
          <p class="SimPlayerText" style="background-color: black">El objeto multimedia seleccionado no posee un video reproducible</p>
       </div>
    <?php endif?>
 </div>

 <div style="padding: 0px 3% 8px">
  Pertenece a: <a href="<?php echo url_for("mms/index?serial=" . $mm->getSerialId())?>"><?php echo $mm->getSerial()->getTitle() ?></a>
 </div>

 <?php if($mm->getStatusId() == MmPeer::STATUS_NORMAL):?>
 <!-- TODOUNED Ver si esta publicado..-->
   <div style="padding: 0px 3% 8px">
    <a target="_black" href="/mmobj/index/id/<?php echo $mm->getId()?>">Ver en CANALUNED</a>
   </div>
 <?php endif ?>

 <div style="padding: 0px 3%;">
          <div class="bs-docs-example-tematicas" id="bs-docs-example-tematicas" >
             <?php foreach($mm->getCategorys($cat_raiz_uned) as $uned): ?>
                <div id="cat-<?php echo $uned->getId()?>" style="font-size: 10px; margin: 1px 5px 1px 1px;" class="label label-success"><?php echo $uned->getName()?></div>
             <?php endforeach ?>
          </div>
          <div id="list_unesco" draggable="true" class="bs-docs-example">
             <?php foreach($mm->getCategorys($cat_raiz_unesco) as $unesco): ?>
                <div id="cat-<?php echo $unesco->getId()?>" class="label label-info unesco_element">
                  <?php echo $unesco->getName() ?>
                  <a href="#" class="unesco_element_a" onclick="if (window.confirm('¿Seguro?')) {$('cat-<?php echo $unesco->getId()?>').remove(); del_tree_cat(<?php echo $unesco->getId()?>, <?php echo $mm->getId()?>)};; return false;">X</a>
                </div>
             <?php endforeach ?>
          </div>
 </div>

 <?php $umo = $mm->getUnedMediaOld(); if($umo): ?>
 <div id="old_comments" style="margin-bottom: 35px;">
   <div style="font-weight: bold; font-size: 15px; margin-bottom: 5px; text-aling:center" >Datos de objetos multimedia proporcionados por la UNED</div>
      <table style="border-collapse: collapse; border-spacing: 0; width: 100%; table-layout: fixed;">
      <thead>
         <tr style="border: 2px solid gray;">
            <th style="padding-left: 1%;">Campo</th>
            <th style="padding-left: 1%;">Valor</th>
         </tr>
      </thead>
      <tbody>
         <?php if($umo): ?>
         <?php 
            $columnas = UnedMediaOldPeer::getFieldNames();
            foreach ($columnas as $columna): if(in_array($columna, array('Id', 'MmId'))) continue;?>
               <?php $getter = "get" . $columna;?>
                  <tr>
                     <td style="padding: 1%; border: 1px solid #ddd; word-wrap: break-word;"><?php echo $columna?></td>
                     <td style="padding: 1%; border: 1px solid #ddd; word-wrap: break-word;"><?php echo $umo->$getter()?></td>
                  </tr>
               <?php endforeach?>
         <?php else: ?>
		 <tr><td colspan="2">Si datos</td></tr>
         <?php endif ?>
      </tbody>
   </table>
  </div>
  <?php endif?>
</div>
<!------------------------------------->



<?php else:?>
<p>
  Selecione algun objeto multimedia.
</p>
<?php endif?>


<?php echo javascript_tag("
function previewHandleDragOver(e) {
  e.preventDefault();
  //console.log('PREVIEW dragover');
  if (dragElement == 'tree') {
    this.classList.add('over');
  }
}

function previewHandleDragEnter(e) {
  //console.log('PREVIEW dragenter');
  if (dragElement == 'tree') {
    this.classList.add('over');
  }
}

function previewHandleDragLeave(e) {
  //console.log('PREVIEW dragleave');
  this.classList.remove('over');
}

function previewHandleDrop(e) {
  //console.log('PREVIEW drop');
  this.classList.remove('over');
  if (dragElement == 'tree') {
    console.log('#########-> Drop tree con id: ', e.dataTransfer.getData('id'));
    add_tree_several_cat(e.dataTransfer.getData('id')," . $sf_user->getAttribute('id', null, 'tv_admin/virtualserial')  .", " . $cat_raiz_unesco->getId() . ");
    //add_tree_cat(dragSrcEl.children[0].id," . $sf_user->getAttribute('id', null, 'tv_admin/virtualserial')  .", " . $cat_raiz_unesco->getId() . ");
  }

}

var unescos = document.querySelectorAll('#list_unesco');
[].forEach.call(unescos, function(unesco) {
  unesco.addEventListener('dragstart', function(e) {e.preventDefault();}, false);
  unesco.addEventListener('dragover', previewHandleDragOver, false);
  unesco.addEventListener('dragenter', previewHandleDragEnter, false);
  unesco.addEventListener('dragleave', previewHandleDragLeave, false);
  unesco.addEventListener('drop', previewHandleDrop, false);
});
") ?>
