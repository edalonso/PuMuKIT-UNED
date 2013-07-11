<?php use_javascript('jwplayer.js') ?>
<video
       id="player1"
       width="620"
       height="465"
       controls="controls" autoplay="autoplay">
<source src="<?php echo 'http://dls2.uvigo.es/vod/cmar/2/665.mp4' ?>" type="video/mp4" />
</video>


<script type="text/javascript">
var nextVideo = "<?php echo $file->getUrl() ?>";
var videoPlayer = document.getElementById('player1');
    videoPlayer.addEventListener('ended', function(e) {
    videoPlayer.src = nextVideo;
});

</script>