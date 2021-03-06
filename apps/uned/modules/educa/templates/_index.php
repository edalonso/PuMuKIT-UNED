<div>

<div class="grid_4_educa" style="margin-left: 35px;">
  <div  class="img_perfis">
     <img width="135px" height="205" alt="<?php echo __("Ciencias de la Salud")?>" src="/images/uned/educa/REC_EDUC_ciencias_de_la_Salud.jpg" />
     <div class="pe_foto_perfis_educa titulo"><div style="position: relative; top: 8px;"><?php echo __("Ciencias de la Salud")?></div></div>
  </div>
  <ul>
     <?php foreach($salud as $c): ?>
       <?php $numMm = $c->countPublicMms(); if ($numMm == 0) continue;?>
       <li>
         <a href="<?php echo url_for('educa/allMmsByDate?id=' . $c->getId()) ?>"><?php echo $c->getName()?> [<?php echo $numMm ?>]</a>
       </li>
     <?php endforeach;?>
  </ul>
</div>

<div class="grid_4_educa">
  <div  class="img_perfis">
    <img width="135px" alt="<?php echo __("Tecnologías")?>" src="/images/uned/educa/REC_EDUC_Tecnologias.jpg" />
    <div class="pe_foto_perfis_educa titulo" style="width: 127px;"><div style="position: relative; top: 8px; padding-left: 20px;"><?php echo __("Tecnologías")?></div></div>
  </div>
  <ul>
     <?php //foreach($tecnologias->getChildren() as $c): ?>
     <?php foreach($tecnologias as $c): ?>
       <?php $numMm = $c->countPublicMms(); if ($numMm == 0) continue;?>
       <li>
         <a href="<?php echo url_for('educa/allMmsByDate?id=' . $c->getId()) ?>"><?php echo $c->getName()?> [<?php echo $numMm ?>]</a>
       </li>
     <?php endforeach;?>
  </ul>
</div>

<div class="grid_4_educa">
  <div class="img_perfis">
    <img width="135px" alt="<?php echo __("Ciencias")?>" src="/images/uned/educa/REC_EDUC_ciencias.jpg" />
    <div class="pe_foto_perfis_educa titulo" style="width: 127px;"><div style="position: relative; top: 8px; padding-left: 30px;"><?php echo __("Ciencias")?></div></div>
  </div>
  <ul>
     <?php //foreach($ciencias->getChildren() as $c): ?>
     <?php foreach($ciencias as $c): ?>
       <?php $numMm = $c->countPublicMms(); if ($numMm == 0) continue;?>
       <li>
         <a href="<?php echo url_for('educa/allMmsByDate?id=' . $c->getId()) ?>"><?php echo $c->getName()?> [<?php echo $numMm ?>]</a>
       </li>
     <?php endforeach;?>
  </ul>
</div>

<div class="grid_4_educa">
  <div  class="img_perfis">
    <img width="135px" alt="<?php echo __("Jurídico-Social")?>" src="/images/uned/educa/REC_EDUC_juidico_social.jpg" />
    <div class="pe_foto_perfis_educa titulo"><div style="position: relative; top: 8px; padding-left: 10px;"><?php echo __("Jurídico-Social")?></div></div>
  </div>
  <ul>
     <?php //foreach($juridicas->getChildren() as $c): ?>
     <?php foreach($juridicas as $c): ?>
       <?php $numMm = $c->countPublicMms(); if ($numMm == 0) continue;?>
       <li><a href="<?php echo url_for('educa/allMmsByDate?id=' . $c->getId()) ?>"><?php echo $c->getName()?> [<?php echo $numMm ?>]</a>
       </li>
     <?php endforeach;?>
  </ul>
</div>

<div class="grid_4_educa omega">
  <div  class="img_perfis">
    <img width="135px" height="205px" alt="<?php echo __("Humanidades")?>" src="/images/uned/educa/REC_EDUC_humanidades.jpg" />
    <div class="pe_foto_perfis_educa titulo"><div style="position: relative; top: 8px; padding-left: 10px;"><?php echo __("Humanidades")?></div></div>
  </div>
  <ul>
     <?php //foreach($humanidades->getChildren() as $c): ?>
     <?php foreach($humanidades as $c): ?>
       <?php $numMm = $c->countPublicMms(); if ($numMm == 0) continue;?>
       <li>
         <a href="<?php echo url_for('educa/allMmsByDate?id=' . $c->getId()) ?>"><?php echo $c->getName()?> [<?php echo $numMm ?>]</a>
       </li>
     <?php endforeach;?>
  </ul>
</div>
</div>

<div style="clear: both"></div>
