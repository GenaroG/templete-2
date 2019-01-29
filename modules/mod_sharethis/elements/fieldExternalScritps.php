<?php

defined('_JEXEC') or die('Restricted Access');

/*Add CSS*/
if(version_compare(JVERSION,'3.2.0','l')) {
	JHTML::stylesheet('modules/mod_sharethis/st_css/msharethis_25.css', array('rel'=>'stylesheet'), false, false, false, false);
} else {
	JHTML::stylesheet('modules/mod_sharethis/st_css/msharethis_32.css', array('rel'=>'stylesheet'), false, false, false, false);
}

/*Add javascript*/
if(version_compare(JVERSION,'3.2.0','l')) {
	JHTML::script('modules/mod_sharethis/st_libraries/jquery.min.js', false, false, false, false, false);
}
JHTML::script('http://w.sharethis.com/button/buttons.js', false, false, false, false, false);
JHTML::script('modules/mod_sharethis/st_js/sharethis.js', false, false, false, false, false);

/*
//TODO: For future implementation

//jimport('libraries.cms.html.html');
//require_once('../libraries/cms/html/html.php');

class fieldExternalScripts extends JFormField {

	public function getInput() {
		$val = $this->element['jsScript']; print_r($this->element);
		return JHTML::script($this->element['jsScript'], false, false, false, false, false);
	}
}

$FieldExternalScripts = new FieldExternalScripts();
$FieldExternalScripts->getInput();
*/