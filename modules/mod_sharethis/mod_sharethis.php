<?php
 
/* 
 * +--------------------------------------------------------------------------+
 * |   	ShareThis
 * |   	Copyright (c) 2010 ShareThis, Inc.
 * |	http://sharethis.com
 * +
 * |  	Released under the GPL license
 * |	http://www.opensource.org/licenses/gpl-license.php
 * | 
 * |	This is an add-on for Joomla 
 * |	http://www.joomla.org/
 * |
 * |	**********************************************************************
 * |	This program is distributed in the hope that it will be useful, but
 * |	WITHOUT ANY WARRANTY; without even the implied warranty of
 * |	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * | 	**********************************************************************
 * |
 * | 	Module Name: ShareThis
 * | 	Module URI: http://sharethis.com
 * |	Description: Let your visitors share a post/page with others. Supports e-mail and posting to social bookmarking sites. 
 * |	Version: 6.0
 * |	Author: Author URI: http://sharethis.com
 * +--------------------------------------------------------------------------+
 */ 
 

    /* no direct access */
	defined('_JEXEC') or die('Restricted access');
	
	$moduleclass_sfx = $params->get('moduleclass_sfx');
	if(!defined('DS')){ define('DS',DIRECTORY_SEPARATOR);} // DS is not supported by some Joomla versions
	require_once (dirname(__FILE__).DS.'helper.php');
	
	/*Properties holding module settings */
	$options['widget_type']		= $params->get('widget_type', '');
	$options['button_style']    = $params->get('button_style', '');
	$options['pubKey']          = $params->get('pubKey', '');
    $options['pinterest']       = $params->get('pinterest', '0');
	$options['linkedin']        = $params->get('linkedin', '0');
	$options['twitter']         = $params->get('twitter');
	$options['twittervia']      = $params->get('twittervia');
	$options['vinstagram']      = $params->get('vinstagram');
    $options['username']        = $params->get('username');
	$options['via']             = $params->get('via');
	$options['userinstagram']   = $params->get('userinstagram');
    $options['donotcopy'] 		= $params->get('donotcopy', NULL);
    $options['hashaddress'] 	= $params->get('hashaddress', NULL);
	$options['sharethis_callesi']= $params->get('sharethis_callesi', 1);
	
	$configValue = "";
	$configValue .= showWidget($options);
	?>