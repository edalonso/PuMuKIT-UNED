<ul style="width: 95%;">
  <li class="first_node" style="margin-top: 3px;">
         <a href="#" id="0" 
            class="node <?php if($cat_id==0) echo 'clicked'?>" style="text-decoration: none;" 
            onclick="$$('.clicked').invoke('removeClassName', 'clicked'); this.addClassName('clicked'); new Ajax.Updater('list_mms', '/editar.php/virtualserial/list', { asynchronous:true, evalScripts:true, parameters: {id: this.readAttribute('id')}}); return false;">
            <ins>&nbsp;</ins>Todos [<?php echo $num_all ?>]
         </a>
  </li>
  <li class="first_node">
         <a href="#" id="-1" 
            class="node <?php if($cat_id==-1) echo 'clicked'?>" style="text-decoration: none;" 
            onclick="$$('.clicked').invoke('removeClassName', 'clicked'); this.addClassName('clicked'); new Ajax.Updater('list_mms', '/editar.php/virtualserial/list', { asynchronous:true, evalScripts:true, parameters: {id: this.readAttribute('id')}}); return false;">
            <ins>&nbsp;</ins>Sin categoría [<?php echo $num_none ?>]
         </a>
  </li>
  <li class="first_node">
     <ins>&nbsp;</ins>Ciencias de la salud
  </li>
  <ul>
     <?php foreach($salud as $c): ?>
     <?php if ($c->getNumMm() == 0) continue;?>
       <li class="second_node" draggable="true">
         <a href="#" id="<?php echo $c->getId()?>" draggable="false" class="node <?php if($cat_id==$c->getId()) echo 'clicked'?>" style="text-decoration: none;" onclick="$$('.clicked').invoke('removeClassName', 'clicked'); this.addClassName('clicked'); new Ajax.Updater('list_mms', '/editar.php/virtualserial/list', { asynchronous:true, evalScripts:true, parameters: {id: this.readAttribute('id')}}); return false;"><ins draggable="false">&nbsp;</ins><?php echo $c->getName()?> [<?php echo $c->getNumMm() ?>]</a>
       </li>
     <?php endforeach;?>
  </ul>
  <li class="first_node">
   <ins style="background-image: url(/images/admin/root.png); background-position: 0 0;background-repeat: no-repeat; float:left; width: 20px; height: 15px;">&nbsp;</ins>Tecnologías
  </li>
  <ul>
     <?php foreach($tecnologias as $c): ?>
     <?php if ($c->getNumMm() == 0) continue;?>
       <li class="second_node" draggable="true">
         <a href="#" id="<?php echo $c->getId()?>" draggable="false" class="node <?php if($cat_id==$c->getId()) echo 'clicked'?>" style="text-decoration: none;" onclick="$$('.clicked').invoke('removeClassName', 'clicked'); this.addClassName('clicked'); new Ajax.Updater('list_mms', '/editar.php/virtualserial/list', { asynchronous:true, evalScripts:true, parameters: {id: this.readAttribute('id')}}); return false;"><ins draggable="false">&nbsp;</ins><?php echo $c->getName()?> [<?php echo $c->getNumMm() ?>]</a>
       </li>
     <?php endforeach;?>
  </ul>
  <li class="first_node">
   <ins style="background-image: url(/images/admin/root.png); background-position: 0 0;background-repeat: no-repeat; float:left; width: 20px; height: 15px;">&nbsp;</ins>Ciencias
  </li>
  <ul>
     <?php foreach($ciencias as $c): ?>
     <?php if ($c->getNumMm() == 0) continue;?>
       <li class="second_node" draggable="true">
         <a href="#" id="<?php echo $c->getId()?>" draggable="false" class="node <?php if($cat_id==$c->getId()) echo 'clicked'?>" style="text-decoration: none;" onclick="$$('.clicked').invoke('removeClassName', 'clicked'); this.addClassName('clicked'); new Ajax.Updater('list_mms', '/editar.php/virtualserial/list', { asynchronous:true, evalScripts:true, parameters: {id: this.readAttribute('id')}}); return false;"><ins draggable="false">&nbsp;</ins><?php echo $c->getName()?> [<?php echo $c->getNumMm() ?>]</a>
       </li>
     <?php endforeach;?>
  </ul>
  <li class="first_node">
   <ins style="background-image: url(/images/admin/root.png); background-position: 0 0;background-repeat: no-repeat; float:left; width: 20px; height: 15px;">&nbsp;</ins>
Jurídico-Social</li>
    <ul>
     <?php foreach($juridicas as $c): ?>
     <?php if ($c->getNumMm() == 0) continue;?>
       <li class="second_node" draggable="true">
         <a href="#" id="<?php echo $c->getId()?>" draggable="false" class="node <?php if($cat_id==$c->getId()) echo 'clicked'?>" style="text-decoration: none;" onclick="$$('.clicked').invoke('removeClassName', 'clicked'); this.addClassName('clicked'); new Ajax.Updater('list_mms', '/editar.php/virtualserial/list', { asynchronous:true, evalScripts:true, parameters: {id: this.readAttribute('id')}}); return false;"><ins draggable="false">&nbsp;</ins><?php echo $c->getName()?> [<?php echo $c->getNumMm() ?>]</a>
       </li>
     <?php endforeach;?>
    </ul>
    <li class="first_node"><ins>&nbsp;</ins>Humanidades</li>
    <ul>
     <?php foreach($humanidades as $c): ?>
     <?php if ($c->getNumMm() == 0) continue;?>
       <li class="second_node" draggable="true">
         <a href="#" id="<?php echo $c->getId()?>" draggable="false" class="node <?php if($cat_id==$c->getId()) echo 'clicked'?>" style="text-decoration: none;" onclick="$$('.clicked').invoke('removeClassName', 'clicked'); this.addClassName('clicked'); new Ajax.Updater('list_mms', '/editar.php/virtualserial/list', { asynchronous:true, evalScripts:true, parameters: {id: this.readAttribute('id')}}); return false;"><ins draggable="false">&nbsp;</ins><?php echo $c->getName()?> [<?php echo $c->getNumMm() ?>]</a>
       </li>
     <?php endforeach;?>
     </ul>
</ul>

<?php echo javascript_tag("
function handleDragStart(e) {
  this.style.opacity = '0.4';
  $('list_unesco').style.backgroundColor = '#eef';
  dragSrcEl = this;
}

function handleDragOver(e) {
  if (e.preventDefault) {
    e.preventDefault();
  }
  e.dataTransfer.dropEffect = 'move';
  return false;
}

function handleDragLeave(e) {
  this.classList.remove('over');
}

function handleDrop(e) {
  if (e.stopPropagation) {
    e.stopPropagation();
  }
  return false;
}

function handleDragEnd(e) {
  $('list_unesco').style.backgroundColor = '#FFF';
  this.style.opacity = '1';
  [].forEach.call(nodes, function (node) {
    node.classList.remove('over');
  });
}


var nodes = document.querySelectorAll('.second_node');
[].forEach.call(nodes, function(node) {
  node.addEventListener('dragstart', handleDragStart, false);
  node.addEventListener('dragover', handleDragOver, false);
  node.addEventListener('dragleave', handleDragLeave, false);
  node.addEventListener('drop', handleDrop, false);
  node.addEventListener('dragend', handleDragEnd, false);
});
") ?>
