<?php echo javascript_tag("
  parent.Modalbox.hide();

  new parent.Ajax.Updater('files_mms', '".url_for('files/list?mm='.$mm)."', {asynchronous: true, evalScripts: true});
  //new parent.Ajax.Updater('preview_mm', '".url_for('mms/preview?id='.$mm)."', {asynchronous: true, evalScripts: true});
  parent.update_preview(". $mm .");
  parent.$('files_mms').innerHTML= 'Actualize el video para que se muestren las Archivos.';
"); ?>
