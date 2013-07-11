<table>
   <tr>
     <td rowspan="2">
       <div class="img_category">
         <!--<a href="<?php echo url_for('mmobj/index?id=82')?>">-->
         <a href="<?php echo url_for('educa')?>">
         <img alt="Recursos Educativos" src="/images/uned/recursos_educativos.jpg" style="height: 350px; width: 220px"/>
          <div class="cover titulo"><div style="position: relative; top: 8px;"><span><?php echo __('Recursos educativos')?></span></div></div>
         </a>
       </div>
     </td>
     <td>
       <div class="img_category">
     <!--<?php if (isset($event)&&($event->getPic()!="")): ?>
         <a href="<?php echo url_for('directo/index')?>">
           <img alt="Emisión en Directo" width=252 height=189 src="<?php echo $event->getPic() ?>" />
           <div class="cover"><div><span><?php echo __('EMISIÓN EN DIRECTO')?></span></div></div>
         </a>
     <?php else :?>-->
         <a href="<?php echo url_for('destacados_TV')?>">
           <img alt="Videos por centros" height="200" width="251" src="/images/uned/Destacados_TV.jpg" />
           <div class="cover titulo" style="width: 251px;"><div style="position: relative; top: 8px;"><span><?php echo __('Destacados TV')?></span></div></div>
         </a>
     <!--<?php endif; ?>-->
       </div>
     </td>
     </tr>
     <tr>
     <td>
       <div class="img_category">
         <a href="<?php echo url_for('destacados_RADIO/index')?>">
           <img alt="Clases y polimedias" height="148" width="251" src="/images/uned/Radio-Studio-300x224.jpg" />
           <div class="cover titulo" style="width: 251px;">
             <div style="position: relative; top: 8px;">
               <span><?php echo __('Destacados Radio')?></span>
             </div>
           </div>
         </a>
       </div>
     </td>
   </tr>
</table>
<table class="third_column">
    <tr>
     <td>
       <div class="img_category">
         <a href="<?php echo url_for('teleactos')?>">
           <img alt="Canal empleo"  height="163" width="241" src="/images/uned/teleactos.jpg" />
           <div class="cover_third_column_job titulo" style="width: 241px;">
             <div style="position: relative; top: 8px;">
               <span><?php echo __('Teleactos')?></span>
             </div>
           </div>
         </a>
       </div>
     </td>
    </tr>
    <tr>
     <td>
       <div class="img_category">
         <a href="<?php echo url_for('noticias')?>">
           <img alt="Institucional" height="185" width="241" src="/images/uned/press_room_470x350.jpg" />
           <div class="cover_third_column titulo" style="width: 241px;">
             <div style="position: relative; top: 8px;">
               <span><?php echo __('Noticias')?></span>
             </div>
           </div>
         </a>
       </div>
     </td>
   </tr>
</table>