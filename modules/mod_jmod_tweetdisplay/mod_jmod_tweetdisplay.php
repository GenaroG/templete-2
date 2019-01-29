<?php
/* @package JMod TweetDisplay for Joomla 2.5!  
 * @link       http://jmodules.com/ 
 * @copyright (C) 2012- Sean Casco
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html 
 */
// no direct access
defined('_JEXEC') or die;

// Include the helper file
require_once dirname(__FILE__).'/helper.php';

// if cURL is disabled, then extension cannot work
if(!is_callable('curl_init')){
	$data = false;
	$curlDisabled = true;
}
else {
	$model = new modjmod_tweetdisplayHelper();
	$model->addStyles($params);
	$data = $model->getData($params);
}

if($data) {
	require JModuleHelper::getLayoutPath('mod_jmod_tweetdisplay', $params->get('layout', 'default'));
}
else {
	require JModuleHelper::getLayoutPath('mod_jmod_tweetdisplay', 'error/error');
}
