<?php
/* @package JMod TweetDisplay for Joomla 2.5!  
 * @link       http://jmodules.com/ 
 * @copyright (C) 2012- Sean Casco
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html 
 */
	// no direct access
	defined('_JEXEC') or die;
?>

<div id="jmod">
<?php if(isset($curlDisabled)): ?>
Your PHP doesn't have cURL extension enabled. Please contact your host and ask them to enable it.
<?php else: ?>
It seems that module parameters haven't been configured properly. Please make sure that you are using a valid twitter username, and
that you have inserted the correct keys. Detailed instructions are written in the module settings page.
<?php endif; ?>
</div>
