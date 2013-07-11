<?php use_javascript('/swf/jwplayer6/jwplayer.js') ?>

<video id="player1" controls>
</video>

<script language="JavaScript" type="text/javascript">
    jwplayer("player1").setup({
        modes: [
           { type: 'html5', src: '/swf/jwplayer6/jwplayer.html5.js' },
           { type: 'flash', src: '/swf/jwplayer6/jwplayer.flash.js' }
        ],
        file: '<?php echo $file->getInternalUrl(true) ?>',
        title: '<?php echo $file->getMm()->getTitle() ?>',
          <?php $captions = $mmobj->getCaptions(); 
          if ($captions != null && false):?>
        tracks: [{ 
            file: "<?php echo $captions->getUrl()?>", 
            label: "Subtitulos",
            kind: "captions",
            "default": "true"
        }],
        captions:{
          <?php // ejemplo de opciones para estilo: color: 'cc0000', fontsize: 20, back: false // para eliminar fondo negro?>
          back: true
        },
          <?php endif?>
        provider: 'http',
	startparam: 'start',
      <?php if ($file->getAudio()) :?>
	image: '/images/uned/thumbnail_audio.png',
      <?php endif;?>
        logo: {
	  file: "/images/uned/iconos/mosca.png",
	  link: "http://www.uned.es"
        },
        controlbar: 'bottom',
        repeat: 'false',
        stretching: 'exacfit',
        autoplay: 'true',
        autostart: 'true',
        width: '<?php echo $w?>',
        height: '<?php echo $h?>'
    });
</script>
<br />