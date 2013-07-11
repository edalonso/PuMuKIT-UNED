<?php echo javascript_tag("
  parent.Modalbox.hide();

  new parent.Ajax.Updater('list_mms', '".url_for('virtualserial/list')."', {asynchronous: true, evalScripts: true});
  new parent.Ajax.Updater('jstree', '" . url_for('virtualserial/tree') . "', {asynchronous:true, evalScripts:true});//TODO cambiar esta función por update_tree si es necesario
"); ?>