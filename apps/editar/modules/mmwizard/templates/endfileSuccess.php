<?php

$new_mm_id = (count($new_mms) == 0)?'':'&mm_id=' . $new_mms[0];

echo javascript_tag("
  parent.Modalbox.hide();

  new parent.Ajax.Updater('list_mms', '".url_for($mod . '/list?page=last' . $new_mm_id)."', {asynchronous: true, evalScripts: true});
");
