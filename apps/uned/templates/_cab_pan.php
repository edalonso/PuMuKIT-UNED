<div class="cab_pan">
 <ul id="tvunedes_pan">
  <li>
    <a class="breadcrumb" href="http://www.uned.es/">
      <?php echo __("INICIO")?>
    </a>
  </li>

  <li>
    &nbsp;»&nbsp; 
    <a class="breadcrumb" href="<?php echo url_for('index/index')?>" 
       <?php echo (sfConfig::has("pan_nivel_1")) ? 'class="select"' : ''?> >
      UNED TV
    </a>
  </li>

  <?php if($sf_user->getAttribute('nivel2_name') !== null):?>
  <li>
     &nbsp;»&nbsp; 
     <a class="breadcrumb" href="<?php echo url_for($sf_user->getAttribute('nivel2_url'))?>" 
       <?php echo (sfConfig::has("pan_nivel_2")) ? 'class="select"' : ''?> >
       <?php echo __($sf_user->getAttribute('nivel2_name'))?>
     </a>
  </li>
  <?php endif;?>

  <?php if($sf_user->getAttribute('nivel25_name') !== null):?>
  <li>
     &nbsp;»&nbsp;
     <a class="breadcrumb" id="tvunedes_pan_category_a" href="<?php echo url_for($sf_user->getAttribute('nivel25_url'))?>"
       <?php echo (sfConfig::has("pan_nivel_2_5")) ? 'class="select"' : ''?> >
       <?php echo __($sf_user->getAttribute('nivel25_name'))?>
     </a>
  </li>
  <?php endif;?>

  <?php if($sf_user->getAttribute('nivel3_name') !== null):?>
  <li>
     &nbsp;»&nbsp; 
     <a class="breadcrumb" id="tvunedes_pan_serial_a" href="<?php echo url_for($sf_user->getAttribute('nivel3_url'))?>" 
        <?php echo (sfConfig::has("pan_nivel_3")) ? 'class="select"' : ''?> >
       <?php echo __($sf_user->getAttribute('nivel3_name'))?>
     </a>
  </li>
  <?php endif;?>

  <?php if ($sf_user->getAttribute('nivel4_name') !== null):?>
  <li id="tvunedes_pan_mmobj">
     &nbsp;»&nbsp; 
     <a class="breadcrumb" id="tvunedes_pan_mmobj_a" href="<?php echo url_for($sf_user->getAttribute('nivel4_url'))?>" 
        <?php echo (sfConfig::has("pan_nivel_4")) ? 'class="select"' : ''?> >
       <?php echo __($sf_user->getAttribute('nivel4_name'))?>
     </a>
  </li>
  <?php endif;?>


<?php if (($sf_user->getAttribute('nivel3_name') !== null) || ($sf_user->getAttribute('nivel4_name') !== null)) :?>
<script type="text/javascript" language="javascript">
 //<![CDATA[

Event.observe(document, 'dom:loaded', function() { 
  var pan_mmobja = $('tvunedes_pan_mmobj_a');
  var tvunedes_pan = $('tvunedes_pan');
  var lista = $$('.breadcrumb');

  while(tvunedes_pan.offsetWidth > 790) {
     var k = lista[0];
     for(var i=0; i<lista.length; i++) if(lista[i].innerHTML.strip().length > k.innerHTML.strip().length) k = lista[i];
     k.update(k.innerHTML.truncate(k.innerHTML.strip().length -1 ));
  }
});
 //]]>
</script>

  
  <?php endif;?>

 </ul>


</div>







