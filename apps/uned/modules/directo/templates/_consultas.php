<?php use_helper('Validation'); ?>


 <h3 style="font-weight: bold"><?php echo __('Realizar una consulta') ?></h3>

  <?php if($sf_flash->get('enviado') == 'enviado'): ?>
   <div class="form_ok"><?php echo __('Su consulta ha sido enviada') ?> </div>
  <?php endif ?>

 <form action="<?php echo url_for('directo/mail') ?>" enctype="multipart/form-data" method="post" id="tv_send_query_form" class="send_query" name="send_query"
       onsubmit="if (validator()) {new Ajax.Updater('consulta', '<?php echo url_for('directo/mail') ?>', {asynchronous:true, evalScripts:true, onLoading: function(){ s = document.getElementById('spinning'); s.style.display = 'block'}, parameters:Form.serialize(this)}); return false} else {return false}">

   <input type="hidden" name="event" value="<?php echo $event->getId() ?>" > 

  <table>
   <tbody>   
    <tr>
      <td align="right">
       <div id="error_for_name" class="form_error" style="display: none;"> ↓ <?php echo __("Debe introducir un nombre") ?> ↓</div>
        <?php echo form_error('name') ?>
       <label for="name"><?php echo __('Su nombre: ') ?></label> 
      </td>
       <td align="left"><span style="margin-left: 5px"><?php echo input_tag('name', (isset($name))? html_entity_decode($name): '', array('class' => 'texto')) ?></span></td>
    </tr>

   
    <tr>
      <td align="right">
       <div id="error_for_mail" class="form_error" style="display: none;"> ↓ <?php echo __("Debe introducir una direccion de email valida") ?> ↓</div>
       <?php echo form_error('mail') ?>
       <label for="mail"><?php echo __("Correo de respuesta: ") ?></label>
       </td>
       <td align="left"><span style="margin-left: 5px"><?php echo input_tag("mail", (isset($mail))? $mail: '', array('class' => 'texto')) ?></span></td>
    </tr>

    <tr>
      <td align="right">
       <div id="error_for_content" class="form_error" style="display: none;"> ↓ <?php echo __("Debe introducir una consulta") ?> ↓</div>
       <?php echo form_error('content') ?>
       <label for="content"><?php echo __("Texto de consulta: ") ?></label>
      </td>
       <td align="left"><span style="margin-left: 5px"><?php echo textarea_tag("content", (isset($content))?  html_entity_decode($content) : "", "size=59x10") ?></span>
      <img id="spinning" src="/images/admin/load/spinner.gif" style="position: absolute; bottom: 130px;right: 240px; display: none;">
      </td>
    </tr>

    <tr>
     <td align="right">
       <div id="error_for_captcha" class="form_error" style="display: none;"> ↓ <?php echo __("Debe introducir el número que ve en la imagen") ?> ↓</div>
         <?php echo form_error('captcha') ?>
         <label for="captcha"><?php echo __("Número de la imagen: ") ?></label>
     </td>

      <td align="left">
       <span style="margin-left: 7px">
        <img src="<?php echo url_for('sfCaptcha/index') ?>" style="vertical-align: middle;">
        <?php echo input_tag("captcha", "", array('class' => 'numero')) ?>
       </span>
     </td>
    </tr>

    <tr>  
       <td colspan="2" align="right"><input type="submit" value="Enviar consulta" class="queryButton jr"></td>
    </tr>

   </tbody>
  </table>
 </form>


<script>

function validator (){

   //valida el nombre                                                                                                                                                                                 
   if (document.send_query.name.value.length == 0) {
     document.getElementById("error_for_name").show();
     document.send_query.name.focus();
     return false;
   } 
   else {
     document.getElementById("error_for_name").hide();
   }


   //valida el email                                                                                                                                                           
   if ((document.send_query.mail.value.length == 0) || !(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.send_query.mail.value))){
     document.getElementById("error_for_mail").show();
     document.send_query.mail.focus();
     return false;
   }
   else {
     document.getElementById("error_for_mail").hide();
   }

   //tinyMCE.triggerSave();
   //valida el fichero de video o el comentario de texto 
          
   if (document.send_query.content.value.length == 0) {
     document.getElementById("error_for_content").show();
     document.send_query.captcha.focus();
     return false;
   }
   else {
     document.getElementById("error_for_content").hide();
   }
                                                                                                                                                                      
   if (document.send_query.captcha.value.length == 0) {
     document.getElementById("error_for_captcha").show();
     document.send_query.captcha.focus();
     return false;
   }

   return true;

 }

</script>
