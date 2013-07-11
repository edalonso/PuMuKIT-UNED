<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

  <div style="background-color: #000; width: 620px; height: 465px; margin: 10px 0px 20px;" id="preview">

<?php echo __('Necesita instalar el Plugin de Flash')?>
  </div>

<script type="text/javascript">
var flashvars = { file:"<?php echo ($file->getUrl()) ?>",
                  provider: 'http',
                  type: 'lighttpd',
                  stretching: 'uniform',
                  autostart:'false'
"MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+""
};
var params = { allowfullscreen:'true',
               allowscriptaccess:'always' };
var attributes = { id:'player1',
                   name:'player1'};
swfobject.embedSWF('/swf/player.swf','preview','620','465','9.0.115','false',flashvars, params, attributes);
</script>