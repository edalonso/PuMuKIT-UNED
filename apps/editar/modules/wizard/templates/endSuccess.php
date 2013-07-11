<?php echo javascript_tag("
  parent.Modalbox.hide();

  new parent.Ajax.Updater('list_" . $div . "', '".url_for($div . '/list')."', {asynchronous: true, evalScripts: true});
"); ?>
