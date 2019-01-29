<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_olark_chat
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.application.module.helper' );

// Include the syndicate functions only once

$app = JFactory::getApplication();
$class_sfx = htmlspecialchars($params->get('class_sfx'));

require JModuleHelper::getLayoutPath('mod_olark_chat', $params->get('layout', 'default'));
