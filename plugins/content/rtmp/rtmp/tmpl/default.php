<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top"><div style="padding:0 10px 0 0; height:380px; overflow:auto;"><?php echo $marker_html; ?></div></td>
    <td style="padding-left:5px"><div id="thevideo">The player will be placed here</div></td>
  </tr>
</table>
	<script type="text/javascript">
	
	jwplayer("thevideo").setup({
		flashplayer: "<?php echo JURI::base(); ?>plugins/content/rtmp/player/player.swf",
		file: '<?php echo $file; ?>',
		height: <?php echo $vars['video_height']; ?>,
		width: <?php echo $vars['video_width']; ?>,
		provider: "rtmp",
		streamer: "rtmpe://s2u7kzc90opy5i.cloudfront.net:1935/cfx/st",
		autostart: true,
		controlbar:'over',
		stretching:'fill',
		skin: "<?php echo JURI::base(); ?>plugins/content/rtmp/player/seawave.swf"
	});

</script>
