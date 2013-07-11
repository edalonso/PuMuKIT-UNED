<?php if( isset($mm) ): ?>
  <!-- actualizar vistaPrevia -->
  <ul id="menuTab">
    <?php if($sf_user->getAttribute('user_type_id', 1) == 0): ?>  
    <li id="pubMm" class="noSel">
      <a href="#" onclick="return false">Publicacion</a>
    </li>
    <?php endif?>
    <li id="metaMm"   class="noSel" >
      <a href="#" onclick="return false">Metadatos</a>
    </li>
    <li id="groundMm" class="noSel" >
      <a href="#" onclick="return false">Areas de conocimento</a>
    </li>
    <li id="categoryMm" class="noSel" >
      <a href="#" onclick="return false">Categorias</a>
    </li>
    <li id="personMm" class="noSel" >
      <a href="#" onclick="return false">Personas</a>
    </li>
    <li id="mediaMm"  class="noSel" >
      <a href="#" onclick="return false">Multimedia</a>
    </li>
  </ul>
  <div class="background_id">
    <?php echo $mm->getId() ?>
  </div>
  <?php if($sf_user->getAttribute('user_type_id', 1) == 0): ?>  
    <div id="pubMmDiv" style="display:none;">
      <?php include_partial('edit_pub', array('mm' => $mm, 'langs' => $langs)) ?>
    </div>  
  <?php endif ?>

  <div id="metaMmDiv" class="actual" style="display:none;">
    <?php include_partial('edit_meta', array('mm' => $mm, 'langs' => $langs)) ?>
  </div>
  
  <div id="groundMmDiv" style="display:none;">
    <?php include_partial('edit_ground', array('mm' => $mm, 'langs' => $langs, 'grounds' => $grounds , 'grounds_sel' => $grounds_sel, 'groundtypes' => $groundtypes)) ?>
  </div>

  <div id="categoryMmDiv" style="display:none;">
    <?php include_partial('edit_category', array('mm' => $mm, 'langs' => $langs)) ?>
  </div>
  
  <div id="personMmDiv" style="display:none;">
    <?php include_partial('edit_person', array('mm' => $mm, 'langs' => $langs, 'roles' => $roles)) ?>
  </div>
  
  <div id="mediaMmDiv" style="display:none;">
    <?php include_partial('edit_media', array('mm' => $mm, 'langs' => $langs)) ?>
  </div>
<?php endif?>
<script>
$(document).ready(function(){
   $("#menuTab li:nth-child(2)").addClass("siSel").show();
   var firstco = document.getElementsByClassName("actual");
   $(firstco[0]).show();
   $("#menuTab li a").click(function() {
           // Si ya esta seleccionado salimos sin hacer nada
           if ($(this).hasClass("siSel")) return;
           // Obtengo los id de la pestaña, contenido, tabsheet, pestana anterior
           var psID = $(this).parent().attr("id");
           var coID = psID + "Div";
           // Quito la selección a la pestaña anterior
           $(".siSel").removeClass("siSel");
           // Agrego la selección a la pestaña actual
           $("#" + psID).addClass("siSel");
           // Oculto el contenido anterior
           $(".actual").hide();
           $(".actual").removeClass("actual");
           // Muestro el nuevo contenido
           $("#" + coID).show();
           $("#" + coID).addClass("actual");
   });
});
</script>