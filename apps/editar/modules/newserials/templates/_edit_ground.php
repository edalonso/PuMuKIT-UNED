<?php use_helper('Object');?>

<div style="height:41px"></div>
<div id="tv_admin_container" style="padding: 4px 20px 20px">
<fieldset id="tv_fieldset_none" class="" style="position:relative">

<?php include_component('grounds', 'recomendationlist', array('mm' => $mm, 'url' => 'mms/relationgrounds', 'div' => 'groundMmDiv' ,
                                                              'ground_id' => $sf_request->getParameter('ground', 0))); ?>

  <dl style="margin: 0px">

   <?php foreach($groundtypes as $groundtype):?>

  <!-- Para el caso de Itunes Nuevo, vamos a hacerlo de forma diferente al resto -->

     <?php if (($groundtype->getName()=="ItunesPadre")||($groundtype->getName()=="Itunes Hijo")) :?>

     <?php if ($groundtype->getName()=="ItunesPadre") :?>

	 <?php $function = create_function('$a', 'return ($a->getGroundTypeId() == '.$groundtype->getId().');');?>
	 <div class="form-row">
	 <dt><?php echo "Itunes Nuevo"?>:</dt>
     	 <dd>
       	  <div id="ground<?php echo $groundtype->getId()?>_mms">
	  <div style="margin-bottom: 5px; padding-left: 0px">
	     <?php echo __('Filtrar')?>:
             <input type="input" id="input_filter_<?php echo $groundtype->getId()?>" size="40" onkeypress="filtrar<?php echo $groundtype->getId()?>(this.value)" />
	     </div>

	 <?php echo javascript_tag("
	 function filtrar".$groundtype->getId()."(que)
	 {
	  var re = $$('select#grounds".$groundtype->getId()."_select option');
	   for(var i=0; i < re.length; i++){
   	   if (que == ''){
    	    re[i].show();
  	     }else if(re[i].innerHTML.include(que)){
     	     re[i].show();
   	    }else{
     	    re[i].hide();
   	   }
 	  }
	 }
	 ") ?>



	<?php
	  echo select_tag('grounds'.$groundtype->getId().'_select',
                  objects_for_select(array_filter($grounds->getRawValue(), $function), 'getId', 'getName'),
                  array('size' => 20,
                        'style' => 'width:400px; height: 160px',
                        'onclick' => "if(this.value != '') { new Ajax.Updater('groundMmDiv',
                                                         '/editar.php/mms/getGroundChildren/ground/' + this.value + '/id/".$mm->getId()."',
                                                          {asynchronous:true, evalScripts:true});}"

                        )
                  );
	?>
	

	<a href="#" onclick="if ($('grounds<?php echo $groundtype->getId()?>_select').value != '') {new Ajax.Updater('groundMmDiv', '/editar.php/mms/getGroundChildren/ground/' + $('grounds<?php echo $groundtype->getId()?>_select').value +'/id/<?php echo $mm->getId() ?>' , {asynchronous:true, evalScripts:true})}; return false;">&#8594;</a>


     <?php
     echo select_tag('grounds_select_children',
                  objects_for_select($children, 'getId', 'getName'),
                  array('size' => 20,
                               'style' => 'width:400px; height: 160px',
                        'ondblclick' => "if(this.value != ''){ new Ajax.Updater('groundMmDiv',
                                                          '/editar.php/mms/addGround/ground/' + this.value +'/id/".$mm->getId()."',
                                                          {asynchronous:true, evalScripts:true});}"
                        )
                  );	
	?>

<?php else :?>
      <?php $function = create_function('$a', 'return ($a->getGroundTypeId() == '.$groundtype->getId().');');?>


        <a href="#" onclick="if ($('grounds<?php echo $groundtype->getId()?>_sel_select').value != '') {new Ajax.Updater('groundMmDiv', '/editar.php/mms/deleteGround/ground/' + $('grounds<?php echo $groundtype->getId()?>_sel_select').value +'/id/<?php echo $mm->getId() ?>', {asynchronous:true, evalScripts:true})}; return false;">&#8592;</a>
	<a href="#" onclick="if ($('grounds_select_children').value != '') {new Ajax.Updater('groundMmDiv', '/editar.php/mms/addGround/ground/' + $('grounds_select_children').value +'/id/<?php echo $mm->getId() ?>' , {asynchronous:true, evalScripts:true})}; return false;">&#8594;</a>


	<?php
         echo select_tag('grounds'.$groundtype->getId().'_sel_select',
                  objects_for_select(array_filter($grounds_sel->getRawValue(),$function), 'getId', 'getName'),
                  array('size' => 20,
                        'style' => 'width:200px; height: 160px',
                                   'ondblclick' => "if(this.value != '') {new Ajax.Updater('groundMmDiv',
                                                          '/editar.php/mms/deleteGround/ground/' + this.value +'/id/".$mm->getId()."' ,
                                                          {asynchronous:true, evalScripts:true});}"
                        )
                  );
        ?>


   	 </div>
      </dd>
    </div>
     <?php endif ?>
     <?php endif ?>
  <!-- las demás opciones lo hacen como siempre -->
     <?php endforeach ?>

 <?php foreach($groundtypes as $groundtype2):?>
    <?php if (($groundtype2->getName()!="ItunesPadre")&&($groundtype2->getName()!="Itunes Hijo")) :?>   

    <?php $function = create_function('$a', 'return ($a->getGroundTypeId() == '.$groundtype2->getId().');');?>
    <div class="form-row">
      <dt><?php echo $groundtype2->getName()?>:</dt>
      <dd>  
        <div id="ground<?php echo $groundtype2->getId()?>_mms">



<div style="margin-bottom: 5px; padding-left: 0px">
    <?php echo __('Filtrar')?>:
    <input type="input" id="input_filter_<?php echo $groundtype2->getId()?>" size="40" onkeypress="filtrar<?php echo $groundtype2->getId()?>(this.value)" />
</div>

    <?php echo javascript_tag("
function filtrar".$groundtype2->getId()."(que)
{
 var re = $$('select#grounds".$groundtype2->getId()."_select option');
 for(var i=0; i < re.length; i++){
   if (que == ''){
     re[i].show();
   }else if(re[i].innerHTML.include(que)){
     re[i].show();
   }else{
     re[i].hide();
   }
 }
}
") ?>



<!-- classic -->

<?php
  echo select_tag('grounds'.$groundtype2->getId().'_select', 
		  objects_for_select(array_filter($grounds->getRawValue(), $function), 'getId', 'getName'), 
		  array('size' => 20, 
			'style' => 'width:400px; height: 160px',
			'ondblclick' => "if(this.value != '') new Ajax.Updater('groundMmDiv', 
                                                          '/editar.php/mms/addGround/ground/' + this.value +'/id/".$mm->getId()."', 
                                                          {asynchronous:true, evalScripts:true});"
			)
		  );
?>


<a href="#" onclick="if ($('grounds<?php echo $groundtype2->getId()?>_sel_select').value != '') {new Ajax.Updater('groundMmDiv', '/editar.php/mms/deleteGround/ground/' + $('grounds<?php echo $groundtype2->getId()?>_sel_select').value +'/id/<?php echo $mm->getId() ?>', {asynchronous:true, evalScripts:true})}; return false;">&#8592;</a>
<a href="#" onclick="if ($('grounds<?php echo $groundtype2->getId()?>_select').value != '') {new Ajax.Updater('groundMmDiv', '/editar.php/mms/addGround/ground/' + $('grounds<?php echo $groundtype2->getId()?>_select').value +'/id/<?php echo $mm->getId() ?>' , {asynchronous:true, evalScripts:true})}; return false;">&#8594;</a>

<?php
  echo select_tag('grounds'.$groundtype2->getId().'_sel_select',
                  objects_for_select(array_filter($grounds_sel->getRawValue(), $function), 'getId', 'getName'),
                  array('size' => 20,
                        'style' => 'width:200px; height: 160px',
                        'ondblclick' => "if(this.value != '') new Ajax.Updater('groundMmDiv',
                                                          '/editar.php/mms/deleteGround/ground/' + this.value +'/id/".$mm->getId()."' ,
                                                          {asynchronous:true, evalScripts:true});"
                        )
                  );
?>


        
        </div>
      </dd>
    </div>
    <?php endif ?>
   <?php endforeach ?>







  </dl>
</fieldset>

</div>

<?php if (isset($msg_alert)) echo m_msg_alert($msg_alert) ?>