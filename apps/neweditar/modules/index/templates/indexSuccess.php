<div id="div_login">

<?php echo form_tag('index/login') ?>
  
  <?php echo label_for('login', 'Login:', 'class="required" ') ?>
  <div id="input_login">
    <?php echo input_tag('login')?>
  </div>
  
  <br />
  
  <?php echo label_for('passwd', 'Passwd:', 'class="required" ') ?>
  <div id="input_login">
    <?php echo input_password_tag('passwd')?>
  </div>
  
  <br />
  
  <div id="ok_login">
    <?php if($sf_request->getParameter('error') == 2):?>
      <span id="noSession" class="error" style="float:left; ">Sesi&oacute;n expirada&nbsp;</span>
    <?php endif ?>
    <span id="noEstandar" class="error" style="display:none; float:left; ">No compatible con IE</span>
    <span id="noUser" class="error" style="display:none; float:left; ">ERROR de LOGIN</span>
    <?php echo submit_tag('OK', 'class=ok')?>
  </div>

  <?php if(isset($url)) echo input_hidden_tag('url', substr(strtr($url, ' ', '/'), 1)) ?>  
</form>
</div>

<script type="text/javascript">
  $('#login').focus();
  if (/*@cc_on!@*/false) $('noEstandar').show();
</script>

<div id="js"></div>
