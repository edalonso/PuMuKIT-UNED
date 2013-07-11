<?php include_partial('serial/cabSerial', array('serial' => $serial) ) ?>

<div id="serial_header">
  <?php echo $sf_data->getRaw('serial')->getHeader() ?>
</div>

<div id="serial_body">
  <?php $lastDate = '--'; $lastPrecinct = 0; $lastSubserialTitle = 0; foreach ($mms as $mm): ?>

  <?php include_partial('serial/bodyMm', 
			array('mm' => $mm, 'roles' => $roles, 
			      'lastDate' => $lastDate, 'lastPrecinct' => $lastPrecinct, 
			      'template' => $serial->getSerialTemplateId(), 'lastSubserialTitle' => $lastSubserialTitle) 
			) 
     ?>
        
  <?php $lastDate = $mm->getRecorddate('dmy'); $lastPrecinct = $mm->getPrecinctId(); 
        $lastSubserialTitle = $mm->getSubserialTitle(); endforeach; ?>
</div>


<div id="serial_footer">
  <?php echo $sf_data->getRaw('serial')->getFooter() ?>
</div>

