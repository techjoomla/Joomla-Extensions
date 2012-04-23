<?php

if(empty($bannercodes))
return ;
?>
<table width="100%"  style="border:none;">
<?php
	$i=0;
	foreach($bannercodes AS $bc) { ?>
	<tr>
		<td>
		<?php
			echo $bc->banner_code; 
			$i++;
			if($i==1){ break; }
		?>
		</td>
	 </tr> 
	 <?php }
	?>
</table>
	

