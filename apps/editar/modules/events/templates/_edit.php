<?php if( isset($event) ): ?>

  <!-- actualizar vistaPrevia -->
  <ul id="menuTab">
    <li id="editEvent"   class="noSel" >
     <a href="#" onclick="tabMenu.select('editEvent'); return false;" >Informaci&oacute;n</a>
    </li>
    <li id="sessionEvent"   class="noSel" >
      <a href="#" onclick="tabMenu.select('sessionEvent'); return false;" >Sesiones</a>
    </li>
    <li id="serialEvent"   class="noSel" >
      <a href="#" onclick="tabMenu.select('serialEvent'); return false;" >Serie</a>
    </li>

  </ul>

  
  <div class="background_id">
    <?php echo $event->getId() ?>
  </div>


  <div id="editEventDiv"  style="display:none;">
    <?php include_partial('edit_event', array('event' => $event, 'div' => $div)) ?>
  </div>  
    
  
   <div id="sessionEventDiv"  style="display:none;">
    <?php include_partial('edit_sessions', array('event' => $event, 'div' => $div)) ?>
   </div>  


   <div id="serialEventDiv"  style="display:none;">
     <?php include_partial('edit_serial', array('serial' => $event->getSerial(), 'langs' => $langs, 'div' => $div)) ?>
   </div>  
  

  <?php echo javascript_tag("
    var tabMenu = new tabMenuClass(document.location.hash);
  ") ?>

<?php endif ?>