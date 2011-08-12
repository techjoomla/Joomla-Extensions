<?php
//echo "hi";
//print_r($params);
//print_r($data);
?>

<?php
foreach($data as $dat)
{
?>
	<div style="border: 1px solid #FFFFFF;width: 248px;margin-bottom:8px;margin-top:8px;">
	<div style="text-align:center;padding-top:8px;"><a style="color:#333333;" href="<?php echo $dat->link;?>"><?php echo $dat->title;?></a></div>
	<?php if($params->get('show_image')){?>
	<div style="text-align:center;"><a href="<?php echo $dat->link;?>">
	<img src="<?php echo JURI::base().$dat->image;?>"/></a></div>
			<?php } ?>
	<div><hr style="display:block;"/></div>
	<div style="text-align:center;;padding-left:8px;">
	<?php if($params->get('show_deal_value')){?>
		<?php echo str_replace('%d',$dat->dvalue,JText::_('VALUE'));?>
	<?php } ?>
	| <?php echo '<a style=color:#333333; href='.$dat->link.'>'.JText::_('VIEW_LINK').'</a>';?>
	</div>
	</div>
	<!--<tr>
		<td><hr style="display:block;"/></td>
	</tr>-->
<?php	
}
?>

