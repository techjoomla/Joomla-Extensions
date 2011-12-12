<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );
require_once ( JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'defines.community.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
jimport('joomla.html.pane');

$pane =& JPane::getInstance('Tabs'); 
CFactory::load('libraries','fields');
CFactory::load('helpers','template');

// Load Jomsocial template CSS
$template = new CTemplateHelper();
$config   = CFactory::getConfig();
$assets = $template->getTemplateAsset('style', 'css');
$doc =& JFactory::getDocument();
$doc->addStyleSheet($assets->url);

// Load Jomsocial language file
$lang =& JFactory::getLanguage();
$lang->load( 'com_community', JPATH_SITE);
$css = <<<EOT
/* pane-sliders */
.pane-sliders .title {
margin: 0;
padding: 2px;
color: #666;
cursor: pointer;
}

.pane-sliders .panel { border: 1px solid #ccc; margin-bottom: 3px;}
.pane-sliders .panel h3 { background: #f6f6f6; color: #666}
.pane-sliders .content { background: #f6f6f6; }
.pane-sliders .adminlist { border: 0 none; }
.pane-sliders .adminlist td { border: 0 none; }
.jpane-toggler span { background: transparent url(../images/j_arrow.png) 5px 50% no-repeat; padding-left: 20px;}
.jpane-toggler-down span { background: transparent url(../images/j_arrow_down.png) 5px 50% no-repeat; padding-left: 20px;}
.jpane-toggler-down { border-bottom: 1px solid #ccc; }

/* tabs */

dl.tabs {
float: left;
margin: 10px 0 -1px 0;
z-index: 50;
}

dl.tabs dt {
float: left;
padding: 4px 10px;
border-left: 1px solid #ccc;
border-right: 1px solid #ccc;
border-top: 1px solid #ccc;
margin-left: 3px;
background: #f0f0f0;
color: #666;
}

dl.tabs dt.open {
background: #F9F9F9;
border-bottom: 1px solid #F9F9F9;
z-index: 100;
color: #000;
}

div.current {
clear: both;
border: 1px solid #ccc;
padding: 10px 10px;
}

div.current dd {
padding: 0;
margin: 0;
}
EOT;
$doc->addStyleDeclaration($css);
?>

<form action="index.php" method="post" name="adminForm">
<div id="community-wr">
					
	<table class="ccontentTable paramlist" cellspacing="5" cellpadding="5" style="padding:5px">	
		<tr>
			<td colspan="2"><h2><?php echo JText::_('User Information'); ?></h2></td>
		</tr>
		<tr><td colspan="2"><hr /></td></tr>
		<tr>
			<td><?php echo JText::_('Name'); ?>:</td>
			<td><input name="user[jsname]" id="jsname" size="40" value="" maxlength="50" type="text" class="inputbox" /></td>
		</tr>
		<tr>
			<td><?php echo JText::_('Username'); ?>:</td>
			<td><input name="user[jsusername]" id="jsusername" size="40" value="" maxlength="50" type="text" class="inputbox" /></td>
		</tr>
		<tr>
			<td><?php echo JText::_('Email'); ?>:</td>
			<td><input name="user[jsemail]" id="jsemail" size="40" value="" maxlength="100" type="text" class="inputbox" /></td>
		</tr>			
		<tr>
			<td><?php echo JText::_('Password'); ?>:</td>
			<td><input name="user[jspassword]" id="jspassword" size="40" value="" maxlength="50" type="password" class="inputbox" /></td>
		</tr>								
		<tr>
			<td><?php echo JText::_('Verify Password'); ?>:</td>
			<td><input name="user[jspassword2]" id="jspassword2" size="40" value="" maxlength="50" type="password" class="inputbox" /></td>
		</tr>			
	</table>
	
	<?php
		$required	= false;
		echo $pane->startPane( 'tabs' );
		$pc = 1;
		foreach($this->fields as $group)
		{
			$fieldName	= $group->name == 'ungrouped' ? '' : $group->name;
			echo $pane->startPanel( $fieldName, 'panel'.$pc );
	?>		
			<!--<div class="ctitle">
				<h2><?php echo JText::_( $fieldName  ); ?></h2>
			</div>-->
			<table class="ccontentTable paramlist" cellspacing="1" cellpadding="0">
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
					<td class="key" valign="top" width="30%">
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
			$pc++;
			echo $pane->endPanel();
		}//outer for
		echo $pane->endPane();
	?>		

		<div>
			<input type="submit" id="btnSubmit" value="<?php echo JText::_('CC REGISTER'); ?>" name="submit" class="button" />
		</div>	
	
</div>

		<input type="hidden" name="option" value="com_quickregistration" />
		<input type="hidden" name="view" value="registration" />
		<input type="hidden" id="task" name="task" value="register" />
		<input type="hidden" name="controller" value="registration" />	
</form>
