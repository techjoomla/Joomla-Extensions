<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );
global $Itemid ,$mainframe; 
JHTML::_('behavior.modal', 'a.modal');
// Add the CSS and JS
?>
<?php
$document =& JFactory::getDocument();
jimport('joomla.filter.output');
$params = &JComponentHelper::getParams( 'com_showbanners' );
$user =& JFactory::getUser();
$model = $this->getModel();
$data  = $this->user_data;
$cid	= JRequest::getVar( 'cid','' );
?>

<form action="" method="post" name="adminForm" id="adminForm">
<div id="editcell">
	<table class="adminform">
	<tr>
		<td align="left" width = "50%" >
			<label><?php echo JText::_( 'ENT_BANNER_NM' ); ?> :</label>
    		<input type="text" name="search" id="search" value="<?php echo $this->search_r; ?>" class="inputbox" onchange="document.adminForm.submit();" />
    		<button onclick="this.form.submit();">Go</button>
    		
    	</td>
		<td style="text-align:right" width = "50%">
		<?php echo $this->alert_nm; ?>
		<?php echo $this->state; ?>
		</td>
	</tr>
</table>
	<table class="adminlist" >
			<thead>			
				<tr>
					  <th><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo COUNT($this->Data); ?>);" /></th>
					<th><?php echo JText::_( 'BANNERNAME' ); ?></th>
					<th><?php echo JText::_( 'BANNERCODE'); ?></th>
				</tr>
			</thead>							
			<?php 
			$j = 0;
			$ln = 0;
			for ($i=0, $n=count( $this->Data ); $i < $n; $i++)  {
				$row = $this->Data[$i];
				$link=JURI::Base()."index.php?option=com_showbanners&view=addbanner&task=getdata&cid=".$row->banner_id;
				  ?>
				<tr class="<?php echo "row$j"; ?>">	
					<td align="center">
						<?php echo JHTML::_('grid.id',$ln,$row->banner_id ); ?>
					</td>
					<td><a href="<?php echo $link; ?>"><?php echo $row->banner_name; ?></a></td>
					<td><center><?php echo $row->banner_code; ?></center></td>
				</tr>
			<?php $j = 1 - $j;
				  $ln++; 
			} ?>
			<tfoot>
				<tr>
					<td colspan="9"><?php  echo $this->pagination->getListFooter(); ?>
				    </td>
				</tr>
			</tfoot>
	</table>
    <div style="clear:both;"></div>	
 </div>
	<input type="hidden" name="option" value="com_showbanners" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="mybanners" />
	<input type="hidden" name="view" value="mybanners" /> 
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
