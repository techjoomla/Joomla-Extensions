<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );
require_once ( JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'defines.community.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
CFactory::load('libraries','fields');
$lang =& Jfactory::getLanguage();
$lang->load( 'com_community', JPATH_SITE);
?>

<form action="index.php" method="post" name="adminForm">
<div id="editcell">
					
	<table class="admintable" width="100%" >	
		<tr>
			<td colspan="2"><h2><?php echo JText::_('User Information'); ?></h2></td>
		</tr>
		<tr><td colspan="2"><hr /></td></tr>
		<tr>
			<td><?php echo JText::_('Name'); ?>:</td>
			<td><input name="user[jsname]" id="jsname" size="40" value="" maxlength="50" type="text" /></td>
		</tr>
		<tr>
			<td><?php echo JText::_('Username'); ?>:</td>
			<td><input name="user[jsusername]" id="jsusername" size="40" value="" maxlength="50" type="text" /></td>
		</tr>
		<tr>
			<td><?php echo JText::_('Email'); ?>:</td>
			<td><input name="user[jsemail]" id="jsemail" size="40" value="" maxlength="100" type="text" /></td>
		</tr>			
		<tr>
			<td><?php echo JText::_('Password'); ?>:</td>
			<td><input name="user[jspassword]" id="jspassword" size="40" value="" maxlength="50" type="password" /></td>
		</tr>								
		<tr>
			<td><?php echo JText::_('Verify Password'); ?>:</td>
			<td><input name="user[jspassword2]" id="jspassword2" size="40" value="" maxlength="50" type="password" /></td>
		</tr>			
	</table>
	
	<?php
		$required	= false;
		foreach($this->fields as $group)
		{
			$fieldName	= $group->name == 'ungrouped' ? '' : $group->name;
	?>		
			<div class="ctitle">
				<h2><?php echo JText::_( $fieldName ); ?></h2>
			</div>				
			<table class="formtable" cellspacing="1" cellpadding="0" style="width: 98%;">
			<tbody>	
	<?php	
			foreach($group->fields as $field )
			{
				$field->value = '';
				require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'fields'.DS.$field->type.'.php');
				if( !$required && $field->required == 1 )
					$required	= true;

				$classname = 'CFields' . ucfirst($field->type);
				$class = new $classname;
								
				$html = $class->getFieldHTML($field, $required);	
	?>
				<tr>
					<td class="key" valign="top">
						<label id="lblfield<?php echo $field->id;?>" for="field<?php echo $field->id;?>" class="label"><?php if($field->required == 1) echo '*'; ?><?php echo JText::_($field->name); ?></label>
					</td>
					<td class="value"><?php echo $html; ?></td>					
				</tr>	
	<?php							
			}//inner for
	?>
			</tbody>
			</table>		
	<?php								
		}//outer for
	?>		

		<div>
			<input type="submit" id="btnSubmit" value="<?php echo JText::_('CC REGISTER'); ?>" name="submit" />
		</div>	
	
</div>

		<input type="hidden" name="option" value="com_quickregistration" />
		<input type="hidden" name="view" value="registration" />
		<input type="hidden" id="task" name="task" value="register" />
		<input type="hidden" name="controller" value="registration" />	
</form>
