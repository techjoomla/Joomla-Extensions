<?php
/**
 * @version: $Id: view.php 1269 2011-04-22 07:51:24Z Sigrid Suski $
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
 
defined('_JEXEC') || die( 'Direct Access to this location is not allowed.' );
//SPLoader::loadView( 'section' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 04-Apr-2011 10:13:08
 */
class SPEntriesModView extends SPSectionView 
{
	public function display()
	{
		$data = array();
		$visitor = $this->get( 'visitor' );
		$current = $this->get( 'section' );
		$entries = $this->get( 'entries' );
		$debug = $this->get( 'debug' );		
		if( count( $entries ) ) {			
			foreach ( $entries as $entry ) {
				$data[ 'entries' ][] = array(
					'_complex' => 1,
					'_attributes' => array( 'id' => $entry->get( 'id' ),  'nid' => $entry->get( 'nid' ) ),
					'_data' => $this->entry( $entry, false, true )
				);
			}
		}
		$data[ 'visitor' ] = $this->visitorArray( $visitor );
		$this->_attr = $data;
		$this->_attr[ 'template_path' ] = Sobi::FixPath( str_replace( SOBI_ROOT, Sobi::Cfg( 'live_site' ), dirname( $this->_template.'.xsl' ) ) );
		$parserClass = SPLoader::loadClass( 'mlo.template_xslt' );
		$parser = new $parserClass();
		$parser->setData( $this->_attr );
		$parser->setType( 'EntriesModule' );
		$parser->setTemplate( $this->_template );
		$parser->setProxy( $this );
		if( $debug ) {
			echo $parser->XML();
		}
		else {
			echo $parser->display( 'html' );
		}
	}
}
?>
