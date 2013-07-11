<div style="margin: 170px;">
   <div style="padding-bottom: 10px;">Para visualizar este video es necesario introducir una contraseña</div>
   <form name="passwdForm" method="post" action="<?php echo url_for('mmobj/index') . '?id=' . $mmobj->getId() ?>">
      <input type="password" size="25" maxlength="256" name="Contraseña">
      <input type="submit" name="Ok" value="Ok">
   </form>
</div>