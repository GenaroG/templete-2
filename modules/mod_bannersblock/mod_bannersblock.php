<?php 
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @module Pranon - Best Camera Slideshow Module
 * @copyright Copyright (C) Pranon www.pranon.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;
require_once (dirname(__FILE__).DS.'helper.php');
$list = modBlockHelper::getTabs($params);
require JModuleHelper::getLayoutPath('mod_bannersblock', $params->get('layout', 'default'));
