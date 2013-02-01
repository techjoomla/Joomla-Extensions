<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top"><div style="padding:0 10px 0 0; height:380px; overflow:auto;"><?php echo $marker_html; ?></div></td>
    <td style="padding-left:5px"><div id="thevideo">Javascript and Adobe Flash Player are needed to access the resource. Please enable the javascript from your browser or(and) Download the Flash Player <a href="http://www.adobe.com/products/flashplayer/" target="_blank">here</a></div></td>
  </tr>
</table>
<script language=JavaScript>
<!--

//Disable right mouse click Script
//By Maximus (maximus@nsimail.com) w/ mods by DynamicDrive
//For full source code, visit http://www.dynamicdrive.com

var message="Function Disabled!";

///////////////////////////////////
function clickIE4(){
if (event.button==2){
alert(message);
return false;
}
}

function clickNS4(e){
if (document.layers||document.getElementById&&!document.all){
if (e.which==2||e.which==3){
alert(message);
return false;
}
}
}

if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
document.onmousedown=clickIE4;
}

document.oncontextmenu=new Function("return false;")

// --> 
</script>
<script type="text/javascript">
	jwplayer("thevideo").setup({
		flashplayer: "<?php echo JURI::base(); ?>plugins/content/rtmp/player/player.swf",
		file: '<?php echo $crypt;?>',
		height: <?php echo $vars['video_height']; ?>,
		width: <?php echo $vars['video_width']; ?>,
		provider: "rtmp",
		streamer: "rtmpe://s2u7kzc90opy5i.cloudfront.net:1935/cfx/st",
		autostart: true,
		controlbar:'over',
		stretching:'exactfit',
		skin: "<?php echo JURI::base(); ?>plugins/content/rtmp/player/seawave.swf"
	});	
</script>
