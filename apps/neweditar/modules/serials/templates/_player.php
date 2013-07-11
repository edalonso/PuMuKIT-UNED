<video id="player"  controls>
</video>

<script language="JavaScript" type="text/javascript">
    jwplayer("player").setup({
        modes: [
                { type: 'html5' },
                { type: 'flash', src: '/swf/player.swf' }
                ],
                playlist: [
                           {'file': '<?php echo ($file->getPerfil()->getExtension() == "mp4")?"http://dls2.uvigo.es/vod//630/65694.mp4":"http://videodownload.uvigo.es/new/VoDiPod/453/11635.flv"?>',
                                   'title': 'Intro'
                                   },{
                           'file': '<?php echo $file->getUrl() ?>',
                                   'title': 'video',
                                   'type': 'http',
                                   'http.startparam': 'start'
                                   }
                           ],
                controlbar: 'bottom',
                repeat: 'list',
                stretching: 'exacfit',
                autoplay: 'true',
                autostart: 'true',
                width: "320",
                height: "180"
                });
</script>
