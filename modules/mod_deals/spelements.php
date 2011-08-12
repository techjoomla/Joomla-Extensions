<?php
/**
 * @version: $Id: spelements.php 1269 2011-04-22 07:51:24Z Sigrid Suski $
 * @package: SobiPro Entries Module Application 
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2011 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/gpl.html GNU/GPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU General Public License version 3
 * ===================================================
 * $Date: 2011-04-22 09:51:24 +0200 (Fr, 22 Apr 2011) $
 * $Revision: 1269 $
 * $Author: Sigrid Suski $
 */

defined('_JEXEC') or die();
defined( 'DS' ) || define( 'DS', DIRECTORY_SEPARATOR );
$ver = new JVersion();
$ver = str_replace( '.', null, $ver->RELEASE );
define( 'SOBI_CMS', 'joomla'. $ver );
define( 'SOBIPRO', true );
define( 'SOBIPRO_ADM', true );
define( 'SOBI_TASK', 'task' );
define( 'SOBI_DEFLANG', JFactory::getLanguage()->getDefault() );
define( 'SOBI_ACL', 'adm' );
define( 'SOBI_ROOT', JPATH_ROOT );
define( 'SOBI_PATH', SOBI_ROOT.DS.'components'.DS.'com_sobipro' );
define( 'SOBI_ADM_PATH', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sobipro' );
$adm = str_replace( JPATH_ROOT, null, JPATH_ADMINISTRATOR );
define( 'SOBI_ADM_FOLDER', $adm  );
define( 'SOBI_ADM_LIVE_PATH', $adm . '/components/com_sobipro' );
define( 'SOBI_LIVE_PATH', JURI::base().'components/com_sobipro/' );

class JElementSPElements extends JElement
{
	public static function getInstance()
	{
		static $instance = null;
		if( !( $instance instanceof self ) ) {
			$instance = new self();
		}
		return $instance;
	}

	public function __construct()
	{
		static $loaded = false;
		if( $loaded ) {
			return true;
		}
		require_once ( SOBI_PATH.DS.'lib'.DS.'base'.DS.'fs'.DS.'loader.php' );
		/* load all needed classes */
		SPLoader::loadController( 'interface' );
		SPLoader::loadClass( 'base.filter' );
		SPLoader::loadClass( 'base.request' );
		SPLoader::loadClass( 'base.const' );
		SPLoader::loadClass( 'base.factory' );
		SPLoader::loadClass( 'base.object' );
		SPLoader::loadClass( 'base.filter' );
		SPLoader::loadClass( 'base.request' );
		SPLoader::loadClass( 'sobi' );
		SPLoader::loadClass( 'base.config' );
		SPLoader::loadClass( 'base.exception' );
		SPLoader::loadClass( 'cms.base.lang' );
		SPLoader::loadClass( 'mlo.input' );
		SPFactory::config()->set( 'live_site', JURI::root() );
		$head =& SPFactory::header();
		$ccurl = Sobi::Url( array( 'task' => 'category.chooser', 'out' => 'html', 'treetpl' => 'rchooser' ), true );
		$ccmsg = Sobi::Txt( 'JS_SELECT_CATEGORY' );
		$cemsg = Sobi::Txt( 'JS_PLEASE_SELECT_SECTION_FIRST' );
		JHTML::_( 'behavior.modal' );
		/* load admin html files */
		SPFactory::header()->addJsFile( 'sobipro' );
		SPFactory::header()->addJsFile( 'adm.sobipro' );

		$head->addJsCode( '
			window.addEvent( "domready", function() {
					var semaphor = 0;
					var spApply = $$( "#toolbar-apply a" )[ 0 ];
					var spSave = $$( "#toolbar-save a" )[ 0 ];
					spApplyFn = spApply.onclick;
					spApply.onclick = null;
					spSaveFn = spSave.onclick;
					spSave.onclick = null;
					try {
						var spSaveNew = $$( "#toolbar-save-new a" )[ 0 ];
						spSaveNewFn = spSaveNew.onclick;
						spSaveNew.onclick = null;
						spSaveNew.addEvent( "click", function() {
							if( SPValidate() ) {
								spSaveNewFn();
							} 
						} );
					} catch( e ) {}
					
					function SPValidate()
					{
						if( $( "sid" ).value == 0 || $( "sid" ).value == "" ) {
							alert( "'.Sobi::Txt( 'JS_YOU_HAVE_TO_AT_LEAST_SELECT_A_SECTION' ).'" );
							return false;
						}
						else {
							return true;
						}	
					}
					spApply.addEvent( "click", function() { 
						if( SPValidate() ) {
							spApplyFn();
						} 
					} );
					spSave.addEvent( "click", function() {
						if( SPValidate() ) {
							spSaveFn();
						} 
					} );
					$( "spsection" ).addEvent( "change", function( event ) {
						sid = $( "spsection" ).options[ $( "spsection" ).selectedIndex ].value;
						$( "sid" ).value = sid;
						semaphor = 0;
					} );
					if( $( "sp_category" ) != null ) {
						$( "sp_category" ).addEvent( "click", function( ev ) {
							if( semaphor == 1 ) {
								return false;
							}
							semaphor = 1;
							new Event( ev ).stop();
							if( $( "sid" ).value == 0 ) {
								alert( "' . $cemsg . '" );
								semaphor = 0;
								return false;
							}
							else {
							url = "' . $ccurl . '&sid=" + $( "sid" ).value
				try {
					SqueezeBox.open( $( "sp_category" ), { handler: "iframe", size: { x: 700, y: 500 }, url: url });
				}
				catch( x ) {
					SqueezeBox.fromElement( $( "sp_category" ), { url: url, handler: "iframe", size: { x: 700, y: 500 } } );
				}
							}
						} );
					}
				} );
				function SP_close()
				{
					$( "sbox-btn-close" ).fireEvent( "click" );
					semaphor = 0;
				}
		' );
		$head->send();
		parent::__construct();
		$loaded = true;
	}
	
	private function ordering( $selected = null )
	{
		$fData = array(
			null => Sobi::Txt( 'FD.SEARCH_SELECT_LABEL' ),
//			'position.asc' => Sobi::Txt( 'SEC.CFG.ENTRY_ORDER_BY_POSITION_ASCENDING' ),
//			'position.desc' => Sobi::Txt( 'SEC.CFG.ENTRY_ORDER_BY_POSITION_DESCENDING' ),
			'counter ASC' => Sobi::Txt( 'SEC.CFG.ENTRY_ORDER_BY_POPULARITY_ASCENDING' ),
			'counter DESC' => Sobi::Txt( 'SEC.CFG.ENTRY_ORDER_BY_POPULARITY_DESCENDING' ),
			'createdTime ASC' => Sobi::Txt( 'SEC.CFG.ENTRY_ORDER_BY_CREATION_DATE_ASC' ),
			'createdTime DESC' => Sobi::Txt( 'SEC.CFG.ENTRY_ORDER_BY_CREATION_DATE_DESC' ),
			'updatedTime ASC' => Sobi::Txt( 'SEC.CFG.ENTRY_ORDER_BY_UPDATE_DATE_ASC' ),
			'updatedTime DESC' => Sobi::Txt( 'SEC.CFG.ENTRY_ORDER_BY_UPDATE_DATE_DESC' ),
			'validUntil ASC' => Sobi::Txt( 'SEC.CFG.ENTRY_ORDER_BY_EXPIRATION_DATE_ASC' ),
			'validUntil DESc' => Sobi::Txt( 'SEC.CFG.ENTRY_ORDER_BY_EXPIRATION_DATE_DESC' ),
		);
		return SPHtml_Input::select( 'params[spOrder]', $fData, $selected );
	}
	
	public function fetchTooltip( $label )
	{
		switch ( $label ) {
			case 'SOBI_SELECT_SECTION':
				return JText::_( 'SOBI_SELECT_SECTION' );
			case 'sid':	
				return JText::_( 'SOBI_SELECTED_SECTION' );
			case 'spOrder':
				return JText::_( 'SOBI_ORDER_BY' );
			case 'tplFile':
				return JText::_( 'SOBI_TPL_FILE' );
		}		
	}
	
	private function tplFile( $selected = null )
	{
		if( 
			!( file_exists( SOBI_PATH.'/usr/templates/front/modules/entries/' ) ) ||
			( count( scandir( SOBI_PATH.'/usr/templates/front/modules/entries/' ) ) < 3 )
		) {
			$this->installTpl();
		}
		$files = scandir( SOBI_PATH.'/usr/templates/front/modules/entries/' );
		$tpls = array();
		if( count( $files ) ) {
			foreach ( $files as $file ) {
				$stack = explode( '.', $file );
				if( array_pop( $stack ) == 'xsl' ) {
					$tpls[ $file ] = $file;
				}
			}
		}
		return SPHtml_Input::select( 'params[tplFile]', $tpls, $selected );
	}
	
	private function installTpl()
	{
		SPFs::mkDir( SOBI_PATH.'/usr/templates/front/modules/entries/' );
		SPFactory::Instance( 'base.fs.directory', dirname( __FILE__ ).'/tmpl/' )
			->moveFiles( SOBI_PATH.'/usr/templates/front/modules/entries' );
	}
	
	private function settings()
	{
		static $settings = null;
		if( !( $settings ) ) {
			$mid = JRequest::getVar( 'cid', JRequest::getVar( 'id', array() ) );
			if( is_array( $mid ) ) {
				$mid = $mid[ 0 ];
			}
			$params = SPFactory::db()
				->select( 'params', '#__modules', array( 'id' => $mid ) )
				->loadResult();	
			$settings = new JParameter( $params );
		}
		return $settings;
	}
	
	
	public function fetchElement( $name, &$label )
	{
		if( $name == 'sid'  ) {
			$params = array( 'id' => 'sid', 'size' => 5, 'class' => 'text_area', 'style' => 'text-align: center;', 'readonly' => 'readonly' );
			return SPHtml_Input::text( 'params[sid]', $this->settings()->get( 'sid' ), $params );
		}
		if( $name == 'spOrder' ) {
			return $this->ordering( $this->settings()->get( 'spOrder' ) );
		}
		if( $name == 'tplFile' ) {
			return $this->tplFile( $this->settings()->get( 'tplFile' ) );
		}
		
		$sections = array();
		$sout = array();
		try {
			$sections = SPFactory::db()
					->select( '*', 'spdb_object', array( 'oType' => 'section' ), 'id' )
		 			->loadObjectList();
		}
		catch ( SPException $x ) {
			trigger_error( 'sobipro|admin_panel|cannot_get_section_list|500|'.$x->getMessage(), SPC::WARNING );
		}
		if( count( $sections ) ) {
			SPLoader::loadClass( 'models.datamodel' );
			SPLoader::loadClass( 'models.dbobject' );
			SPLoader::loadModel( 'section' );
			$sout[] = Sobi::Txt( 'SELECT_SECTION' );
			foreach ( $sections as $section ) {
				if( Sobi::Can( 'section', 'access', 'valid', $section->id ) ) {
					$s = new SPSection();
					$s->extend( $section );
					$sout[ $s->get( 'id' ) ] = $s->get( 'name' );
				}
			}
		}
		$params = array( 'id' => 'spsection', 'class' => 'text_area required' );
		$field = SPHtml_Input::select( 'params[sid]', $sout, $this->settings()->get( 'sid' ), false, $params );
		return "<div style=\"margin-top: 2px;\">{$field}</div>";
	}
	
	// not implemented yet
	private function getCat( $name )
	{
		return SPHtml_Input::button( 'sp_category', Sobi::Txt( 'SELECT_CATEGORY' ), array( 'id'=>'sp_category', 'class' => 'inputbox', 'style' => 'border: 1px solid silver;' ) );
	}
	
}
?>
