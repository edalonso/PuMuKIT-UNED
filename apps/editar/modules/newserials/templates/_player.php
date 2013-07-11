<?php use_javascript('swfobject.js') ?>
<?php use_javascript('AC_OETags.js') ?>

<script language="JavaScript" type="text/javascript">
// Major version of Flash required
var requiredMajorVersion = 10;
// Minor version of Flash required
var requiredMinorVersion = 0;
// Minor version of Flash required
var requiredRevision = 22;
</script>


<div style="background-color: #000; width: 620px; height: 465px; margin: 10px 0px 20px; float: left" id="preview">
    <?php echo __('Necesita instalar o Plugin de Flash')?>
</div>


<script language="JavaScript" type="text/javascript">

// Version check for the Flash Player that has the ability to start Player Product Install (10.0r22)
var hasProductInstall = DetectFlashVer(6, 0, 65);

// Version check based upon the values defined in globals
var hasReqestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);

// Check to see if a player with Flash Product Install is available and the version does not meet the requirements for playback
if ( hasProductInstall && !hasReqestedVersion ) {

  // MMdoctitle is the stored document.title value used by the installation process to close the window that started the process
  // This is necessary in order to close browser windows that are still utilizing the older version of the player after installation has completed
  // DO NOT MODIFY THE FOLLOWING FOUR LINES
  // Location visited after installation is complete if installation is required

  var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
  var MMredirectURL = window.location;
  document.title = document.title.slice(0, 47) + " - Flash Player Installation";
  var MMdoctitle = document.title;

  AC_FL_RunContent(
		   "src", "/swf/playerProductInstall",
		   "FlashVars", "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
		   "width", "550",
		   "height", "300",
		   "align", "middle",
		   "id", "detectionExample",
		   "quality", "high",
		   "bgcolor", "#3A6EA5",
		   "name", "detectionExample",
		   "allowScriptAccess","always",
		   "type", "application/x-shockwave-flash",
		   "pluginspage", "http://www.adobe.com/go/getflashplayer"
		   );
} else if (hasReqestedVersion) {

    var s1 = new SWFObject('/swf/player.swf','player','<?php echo $w ?>','<?php echo $h ?>','9');
    s1.addParam('allowfullscreen','true');
    s1.addParam('allowscriptaccess','always');
    s1.addParam('wmode','opaque');
    s1.addVariable('playlistfile','<?php echo url_for('asx/index?id=' . $file->getId())?>');
    s1.addVariable('repeat','always');
    s1.addVariable('provider', 'http');
    s1.addVariable('autostart','true');
    s1.addVariable('stretching','uniform');
    s1.write('preview');

} else {  // flash is too old or we can't detect the plugin

  var alternateContent = '<Br /><Br /><div style="margin: auto auto; text-align: center;"><h3>Tiene que instalar la ultima version del plugin de flash.<Br />'

    + 'Despues de instalarlo debera reiniciar el navegador. <Br />'

    + '<a href=http://www.adobe.com/go/getflash/>descargar flash</a></h3></div>';
  document.write(alternateContent);  // insert non-flash content

}

// -->
</script>
<noscript>

Provide alternate content for browsers that do not support scripting
or for those that have scripting disabled.
Alternate HTML content should be placed here.
This content requires the Adobe Flash Player and a browser with JavaScript enabled.
<a href="http://www.adobe.com/go/getflash/">Get Flash</a>
</noscript>
