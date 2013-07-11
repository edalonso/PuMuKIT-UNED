<?php use_javascript('/swf/jwplayer6/jwplayer.js') ?>

<div id="player" style="background: black; width: 100%;">
  <p class="eventPlayer">No se puede cargar el streaming</p>
</div>

<script language="JavaScript" type="text/javascript">
    jwplayer("player").setup({
	 height: "<?php echo $h ?>",
	 width: "<?php echo $w ?>",
	 controlbar: "bottom",
 <?php if($event->getExternal()): ?>
         file: "<?php echo $event->getUrl() ?>"
 <?php else: ?>
	 file: "<?php echo $event->getDirect()->getUrl() ?>"
 <?php endif ?>
    });

</script>