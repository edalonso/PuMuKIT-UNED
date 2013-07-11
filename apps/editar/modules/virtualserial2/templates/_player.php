<?php use_javascript('jwplayer.js') ?>
<video id="player"  controls>
</video>

<script language="JavaScript" type="text/javascript">
    jwplayer("player").setup({
        modes: [
                { type: 'html5' },
                { type: 'flash', src: '/swf/player.swf' }
                ],
                file: '<?php echo $file->getUrl() ?>',
                      'title': 'video',
                      'type': 'http',
                      'http.startparam': 'start',
                controlbar: 'bottom',
                repeat: 'list',
                stretching: 'exacfit',
                autoplay: 'false',
                autostart: 'false',
                width: "320",
                height: "180"
                });
</script>
