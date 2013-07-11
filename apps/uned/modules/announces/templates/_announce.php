<?php setlocale(LC_TIME, $sf_user->getCulture().'_ES.utf-8') ?>

<p class="categories_title"><?php echo strftime('%B-%Y', strtotime($date))?></p>
<input type="hidden" class="categories_title_hidden" value="<?php echo date('m', strtotime($date))?>-<?php echo date('Y', strtotime($date))?>" />
<div style="overflow: hidden;">
 <?php include_partial('global/mmobj', array('mmobjs' => $announces))?>
</div>
<div style="widht:100%"></div> 