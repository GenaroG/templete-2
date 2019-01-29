<?php
/**
 * abstract controller class containing get,store,delete,publish and pagination
 *
 * This class provides the functions for the Views

 * This class provides the functions for the calculations
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (C) 2004-2014 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
// Load the view framework
jimport( 'joomla.application.component.view');
// Load default helpers
if (!class_exists('ShopFunctions')) require(VMPATH_ADMIN.DS.'helpers'.DS.'shopfunctions.php');
if (!class_exists('AdminUIHelper')) require(VMPATH_ADMIN.DS.'helpers'.DS.'adminui.php');
if (!class_exists('JToolBarHelper')) require(JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbar.php');

class VmView extends JViewLegacy {
	/**
	 * Sets automatically the shortcut for the language and the redirect path
	 *
	 * @author Max Milbers
	 */
	// public function __construct() {
		// parent::construct();
	// }
	var $lists = array();
	var $showVendors = null;
	protected $canDo;
	function __construct($config = array()) {
		parent::__construct($config);
		// What Access Permissions does this user have? What can (s)he do?
		$this->canDo = self::getActions();
	}
	
	/*
	* Override the display function to include ACL
	* Redirect to the control panel when user does not have access
	*/
	public function display($tpl = null)
	{
		$view = vRequest::getCmd('view', vRequest::getCmd('controller','virtuemart'));
		
		if ($view == 'virtuemart' //Virtuemart view is always allowed since this is the page we redirect to in case the user does not have the rights
			or $view == 'about' //About view always displayed
			or $this->canDo->get('core.admin')
			or $this->canDo->get('vm.'.$view) ) { //Super administrators always have access

			$result = $this->loadTemplate($tpl);
			if ($result instanceof Exception) {
				return $result;
			}

			echo $result;
			echo vmJsApi::writeJS();
			//vmdebug('my included files ',get_included_files());
			return true;
		} else {
			JFactory::getApplication()->redirect( 'index.php?option=com_virtuemart', vmText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}

	}
	

	/*
	* Get the ACL actions
	*/
	public static function getActions() {
		$user	= JFactory::getUser();
		$result	= new JObject;

		//Get the core actions
		$core_actions = JAccess::getActions('com_virtuemart','component');
		foreach ($core_actions as $action) {
			$result->set($action->name, $user->authorise($action->name, 'com_virtuemart'));
		}

		//Get the actions for each section
		$sections=array('product','category','manufacturer','orders','shop','other');
		foreach ($sections as $section) {
			$section_actions = JAccess::getActions('com_virtuemart',$section);
			foreach ($section_actions as $action) {
				$result->set($action->name, $user->authorise($action->name, 'com_virtuemart'));
			}
		}
		
		return $result;
	}


	/*
	 * set all commands and options for BE default.php views
	* return $list filter_order and
	*/
	function addStandardDefaultViewCommands($showNew=true, $showDelete=true, $showHelp=true) {


		$view = vRequest::getCmd('view', vRequest::getCmd('controller','virtuemart'));

		JToolBarHelper::divider();
		if ($this->canDo->get('core.admin') || $this->canDo->get('vm.'.$view.'.edit.state')) {
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
		}
		if ($this->canDo->get('core.admin') || $this->canDo->get('vm.'.$view.'.edit')) {
			JToolBarHelper::editList();
		}
		if ($this->canDo->get('core.admin') || $showNew && $this->canDo->get('vm.'.$view.'.create')) {
			JToolBarHelper::addNew();
		}
		if ($this->canDo->get('core.admin') || $showDelete && $this->canDo->get('vm.'.$view.'.delete')) {
			JToolBarHelper::deleteList();
		}
		self::showHelp ( $showHelp);


		if(JFactory::getApplication()->isSite()){
			$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'back', 'COM_VIRTUEMART_LEAVE', 'index.php?option=com_virtuemart&manage=0');
		} else {
			self::showACLPref($view);
		}
	}

	/*
	 * set pagination and filters
	* return Array() $list( filter_order and dir )
	*/

	function addStandardDefaultViewLists($model, $default_order = 0, $default_dir = 'DESC',$name = 'search') {

		//This function must be used after the listing
// 		$pagination = $model->getPagination();
// 		$this->assignRef('pagination', $pagination);

		/* set list filters */
		$option = vRequest::getCmd('option');
		$view = vRequest::getCmd('view', vRequest::getCmd('controller','virtuemart'));

		$app = JFactory::getApplication();
		$lists[$name] = $app->getUserStateFromRequest($option . '.' . $view . '.'.$name, $name, '', 'string');

		$lists['filter_order'] = $this->getValidFilterOrder($app,$model,$view,$default_order);

// 		if($default_dir===0){
			$toTest = $app->getUserStateFromRequest( 'com_virtuemart.'.$view.'.filter_order_Dir', 'filter_order_Dir', $default_dir, 'cmd' );

		$lists['filter_order_Dir'] = $model->checkFilterDir($toTest);

		$this->assignRef('lists', $lists);

	}

	function getValidFilterOrder($app,$model,$view,$default_order){

		if($default_order===0){
			$default_order = $model->getDefaultOrdering();
		}

		$toTest = $app->getUserStateFromRequest( 'com_virtuemart.'.$view.'.filter_order', 'filter_order', $default_order, 'cmd' );

// 		vmdebug('getValidFilterOrder '.$toTest.' '.$default_order, $model->_validOrderingFieldName);
		return $model->checkFilterOrder($toTest);
	}


	/*
	 * Add simple search to form
	* @param $searchLabel text to display before searchbox
	* @param $name 		 lists and id name
	* ??vmText::_('COM_VIRTUEMART_NAME')
	*/

	function displayDefaultViewSearch($searchLabel='COM_VIRTUEMART_NAME',$name ='search') {
		return vmText::_('COM_VIRTUEMART_FILTER') . ' ' . vmText::_($searchLabel) . ':
		<input type="text" name="' . $name . '" id="' . $name . '" value="' .$this->lists[$name] . '" class="text_area" />
		<button class="btn btn-small" onclick="this.form.submit();">' . vmText::_('COM_VIRTUEMART_GO') . '</button>
		<button class="btn btn-small" onclick="document.getElementById(\'' . $name . '\').value=\'\';this.form.submit();">' . vmText::_('COM_VIRTUEMART_RESET') . '</button>';
	}

	function addStandardEditViewCommands($id = 0,$object = null) {
        $view = vRequest::getCmd('view', vRequest::getCmd('controller','virtuemart'));

        //if (vRequest::getCmd('tmpl') =='component' ) {
            if (!class_exists('JToolBarHelper')) require(JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbar.php');
       	//}
		//else {
            // 		vRequest::setVar('hidemainmenu', true);
			JToolBarHelper::divider();
			if ($this->canDo->get('core.admin') || $this->canDo->get('vm.'.$view.'.edit')) {
				JToolBarHelper::save();
				JToolBarHelper::apply();
			}
			JToolBarHelper::cancel();
			self::showHelp();
			self::showACLPref($view);
	//	}
		$wait = '';
		if(JFactory::getApplication()->isSite()){
			$wait = 'alert(\''. vmText::_('COM_VIRTUEMART_PROCESSING') .'\');';
		}
		// javascript for cookies setting in case of press "APPLY"
		$document = JFactory::getDocument();
		$j = "
//<![CDATA[
	Joomla.submitbutton=function(a){

		var options = { path: '/', expires: 2}
		if (a == 'apply') {
			var idx = jQuery('#tabs li.current').index();
			jQuery.cookie('vmapply', idx, options);
		} else {
			jQuery.cookie('vmapply', '0', options);
		}
		jQuery( '#media-dialog' ).remove();
		form = document.getElementById('adminForm');
		form.task.value = a;
		form.submit();

		//jQuery.delay(1000);
		".$wait."

		//Joomla.submitform(a,form);
		return false;
	};
//]]>
	" ;
		vmJsApi::addJScript('submit', $j);

		// LANGUAGE setting

        $editView = vRequest::getCmd('view',vRequest::getCmd('controller','' ) );

		$params = JComponentHelper::getParams('com_languages');
		//$config =JFactory::getConfig();$config->getValue('language');
		$selectedLangue = $params->get('site', 'en-GB');

		$lang = strtolower(strtr($selectedLangue,'-','_'));
		// Get all the published languages defined in Language manager > Content
		$allLanguages	= JLanguageHelper::getLanguages();
		foreach ($allLanguages as $jlang) {
			$languagesByCode[$jlang->lang_code]=$jlang;
		}

		// only add if ID and view not null
		if ($editView and $id and (count(vmconfig::get('active_languages'))>1) ) {

			if ($editView =='user') $editView ='vendor';
			//$params = JComponentHelper::getParams('com_languages');
			jimport('joomla.language.helper');
            $lang = vRequest::getVar('vmlang', $lang);
			// list of languages installed in #__extensions (may be more than the ones in the Language manager > Content if the user did not added them)
			$languages = JLanguageHelper::createLanguageList($selectedLangue, constant('JPATH_SITE'), true);
			$activeVmLangs = (vmconfig::get('active_languages') );
			$flagCss="";
			foreach ($languages as $k => &$joomlaLang) {
				if (!in_array($joomlaLang['value'], $activeVmLangs) ) {
					unset($languages[$k] );
				} else {

					$key=$joomlaLang['value'];
					if(!isset($languagesByCode[$key])){
						$img = substr($key,0,2);//We try a fallback
						vmdebug('COM_VIRTUEMART_MISSING_FLAG',$img,$joomlaLang['text']);
					} else {
						$img=$languagesByCode[$key]->image;
					}
					$image_flag=JPATH_SITE."/media/mod_languages/images/".$img.".gif";
					$image_flag_url= JURI::root()."/media/mod_languages/images/".$img.".gif";

					if (!file_exists ($image_flag)) {
						vmerror(vmText::sprintf('COM_VIRTUEMART_MISSING_FLAG', $image_flag,$joomlaLang['text'] ) );
					} else {
						$flagCss .="td.flag-".$key.",.flag-".$key."{background: url( ".$image_flag_url.") no-repeat 0 0; padding-left:20px !important;}\n";
					}
				}
			}
			JFactory::getDocument()->addStyleDeclaration($flagCss);

			$langList = JHtml::_('select.genericlist',  $languages, 'vmlang', 'class="inputbox"', 'value', 'text', $selectedLangue , 'vmlang');
			$this->assignRef('langList',$langList);
			$this->assignRef('lang',$lang);

			if ($editView =='product') {
				$productModel = VmModel::getModel('product');
				$childproducts = $productModel->getProductChilds($id) ? $productModel->getProductChilds($id) : '';
			}

            $token = JSession::getFormToken();
			$j = '
			jQuery(function($) {
				var oldflag = "";
				$("select#vmlang").chosen().change(function() {
					langCode = $(this).find("option:selected").val();
					flagClass = "flag-"+langCode;
					$.getJSON( "index.php?option=com_virtuemart&view=translate&task=paste&format=json&lg="+langCode+"&id='.$id.'&editView='.$editView.'&'.$token.'=1" ,
						function(data) {
							var items = [];

							if (data.fields !== "error" ) {
								if (data.structure == "empty") alert(data.msg);
								$.each(data.fields , function(key, val) {
									cible = jQuery("#"+key);
									if (oldflag !== "") cible.parent().removeClass(oldflag)
									if (cible.parent().addClass(flagClass).children().hasClass("mce_editable") && data.structure !== "empty" ) tinyMCE.execInstanceCommand(key,"mceSetContent",false,val);
									else if (data.structure !== "empty") cible.val(val);
									});

							} else alert(data.msg);';

			if($editView =='product' && !empty($childproducts)) {
				foreach($childproducts as $child) {
					$j .= '
									$.getJSON( "index.php?option=com_virtuemart&view=translate&task=paste&format=json&lg="+langCode+"&id='.$child->virtuemart_product_id.'&editView='.$editView.'&'.$token.'=1" ,
										function(data) {
											cible = jQuery("#child'. $child->virtuemart_product_id .'product_name");
											cible.parent().removeClass(oldflag)
											cible.parent().addClass(flagClass);
											cible.val(data.fields["product_name"]);
											jQuery("#child'. $child->virtuemart_product_id .'slug").val(data.fields["slug"]);

											oldflag = flagClass ;
										}
									)
								';
				}
			}
			else $j .= 'oldflag = flagClass ;';

			$j .= '
						}
					)
				});
			})';
			vmJsApi::addJScript('vmlang', $j);
		} else {
			// $params = JComponentHelper::getParams('com_languages');
			// $lang = $params->get('site', 'en-GB');
			$jlang = JFactory::getLanguage();
			$langs = $jlang->getKnownLanguages();
			$defautName = $selectedLangue;
			$flagImg = $selectedLangue;
			if(isset($languagesByCode[$selectedLangue])){
				$defautName = $langs[$selectedLangue]['name'];
				$flagImg= JHtml::_('image', 'mod_languages/'. $languagesByCode[$selectedLangue]->image.'.gif',  $languagesByCode[$selectedLangue]->title_native, array('title'=> $languagesByCode[$selectedLangue]->title_native), true);
			} else {
				vmWarn(vmText::sprintf('COM_VIRTUEMART_MISSING_FLAG',$selectedLangue,$selectedLangue));
			}
			$langList = '<input name ="vmlang" type="hidden" value="'.$selectedLangue.'" >'.$flagImg.' <b> '.$defautName.'</b>';
			$this->assignRef('langList',$langList);
			$this->assignRef('lang',$lang);
		}

		if(JFactory::getApplication()->isSite()){
			$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'back', 'COM_VIRTUEMART_LEAVE', 'index.php?option=com_virtuemart&manage=0');
		}
	}

	function SetViewTitle($name ='', $msg ='',$icon ='') {

		$view = vRequest::getCmd('view', vRequest::getCmd('controller'));
		if ($name == '')
			$name = strtoupper($view);
		if ($icon == '')
			$icon = strtolower($view);
		if (!$task = vRequest::getCmd('task'))
			$task = 'list';

		if (!empty($msg)) {
			$msg = ' <span style="color: #666666; font-size: large;">' . $msg . '</span>';
		}

		$viewText = vmText::_('COM_VIRTUEMART_' . strtoupper($name));

		$taskName = ' <small><small>[ ' . vmText::_('COM_VIRTUEMART_' . $task) . ' ]</small></small>';

		JToolBarHelper::title($viewText . ' ' . $taskName . $msg, 'head vm_' . $icon . '_48');
		$this->assignRef('viewName',$viewText); //was $viewName?
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$doc->setTitle($app->getCfg('sitename'). ' - ' .vmText::_('JADMINISTRATION').' - '.strip_tags($msg));
	}

	function sort($orderby ,$name=null ){
		if (!$name) $name= 'COM_VIRTUEMART_'.strtoupper ($orderby);
		return JHtml::_('grid.sort' , vmText::_($name) , $orderby , $this->lists['filter_order_Dir'] , $this->lists['filter_order']);
	}

	public function addStandardHiddenToForm($controller=null, $task=''){
		if (!$controller) $controller = vRequest::getCmd('view');
		$option = vRequest::getCmd('option','com_virtuemart' );
		$hidden ='';
		if (array_key_exists('filter_order',$this->lists)) {
			$hidden ='
			<input type="hidden" name="filter_order" value="'.$this->lists['filter_order'].'" />
			<input type="hidden" name="filter_order_Dir" value="'.$this->lists['filter_order_Dir'].'" />';
		}

		if(vRequest::get('manage',false) or JFactory::getApplication()->isSite()){
			$hidden .='<input type="hidden" name="manage" value="1" />';
		}
		return  $hidden.'
		<input type="hidden" name="task" value="'.$task.'" />
		<input type="hidden" name="option" value="'.$option.'" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="controller" value="'.$controller.'" />
		<input type="hidden" name="view" value="'.$controller.'" />
		'. JHtml::_( 'form.token' );
	}

/*	static function getToolbar($vmView) {

		// add required stylesheets from admin template
		$document    = JFactory::getDocument();
		$document->addStyleSheet('administrator/templates/system/css/system.css');
		//now we add the necessary stylesheets from the administrator template
		//in this case i make reference to the bluestork default administrator template in joomla 1.6
		$document->addCustomTag(
			'<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />'."\n\n".
			'<!--[if IE 7]>'."\n".
			'<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />'."\n".
			'<![endif]-->'."\n".
			'<!--[if gte IE 8]>'."\n\n".
			'<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />'."\n".
			'<![endif]-->'."\n"
			);

		$html = '<div class="toolbar-list" id="toolbar">';
		$html .= '<ul>';
		$html .= '<li id="toolbar-save" class="button">';
		$html .= '<a class="toolbar" onclick="Virtuemart.submitbutton(event,\'save\')" >
<span class="icon-32-save"> </span>
		'.vmText::_('COM_VIRTUEMART_SAVE').'
</a>';
		$html .= '</li>';
		$html .= '<li id="toolbar-cancel" class="button">';
		$html .= '<a class="toolbar" onclick="Virtuemart.submitbutton(event,\'cancel\')" >
<span class="icon-32-cancel"> </span>
		'.vmText::_('COM_VIRTUEMART_CANCEL').'
</a>';
		$html .= '</li>';
		$html .= '</ul>';
		$html .= '<div class="clr"></div>';
		$html .= '</div>';
		return $html;

	}*/

	/**
	 * Additional grid function for custom toggles
	 *
	 * @return string HTML code to write the toggle button
	 */
	function toggle( $field, $i, $toggle, $imgY = 'tick.png', $imgX = 'publish_x.png', $untoggleable = false )
	{

		$img 	= $field ? $imgY : $imgX;
		if ($toggle == 'published') {
			// Stay compatible with grid.published
			$task 	= $field ? 'unpublish' : 'publish';
			$alt 	= $field ? vmText::_('COM_VIRTUEMART_PUBLISHED') : vmText::_('COM_VIRTUEMART_UNPUBLISHED');
			$action = $field ? vmText::_('COM_VIRTUEMART_UNPUBLISH_ITEM') : vmText::_('COM_VIRTUEMART_PUBLISH_ITEM');
		} else {
			$task 	= $field ? $toggle.'.0' : $toggle.'.1';
			$alt 	= $field ? vmText::_('COM_VIRTUEMART_PUBLISHED') : vmText::_('COM_VIRTUEMART_DISABLED');
			$action = $field ? vmText::_('COM_VIRTUEMART_DISABLE_ITEM') : vmText::_('COM_VIRTUEMART_ENABLE_ITEM');
		}

		$img = 'admin/' . $img;

		if ($untoggleable) {
			$attribs='style="opacity: 0.6;"';
		} else {
			$attribs='';
		}
		$image = JHtml::_('image', $img, $alt, $attribs, true);

		if($untoggleable) return $image;

		if (JVM_VERSION < 3){
			return ('<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $action .'">'
					. $image .'</a>');
		} else {
			$icon 	= $field ? 'publish' : 'unpublish';
			return ('<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $action .'">'
				. '<span class="icon-'.$icon.'"><span>' .'</a>');
		}


	}

	function gridPublished($name,$i) {
		if (JVM_VERSION < 3){
			$published = JHtml::_('grid.published', $name, $i );
		} else {
			$published = JHtml::_('jgrid.published', $name->published, $i );
		}
		return $published;
	}

	function showhelp(){
		/* http://docs.joomla.org/Help_system/Adding_a_help_button_to_the_toolbar */

			$task=vRequest::getCmd('task', '');
			$view=vRequest::getCmd('view', '');
			if ($task) {
				if ($task=="add") {
					$task="edit";
				}
				$task ="_".$task;
			}
			if (!class_exists( 'VmConfig' )) require(JPATH_COMPONENT_ADMINISTRATOR .'/helpers/config.php');
			VmConfig::loadConfig();
			VmConfig::loadJLang('com_virtuemart_help');
 		    $lang = JFactory::getLanguage();
 	        $key=  'COM_VIRTUEMART_HELP_'.$view.$task;
	         if ($lang->hasKey($key)) {
					$help_url  = vmText::_($key)."?tmpl=component";
 		            $bar = JToolBar::getInstance('toolbar');
					$bar->appendButton( 'Popup', 'help', 'JTOOLBAR_HELP', $help_url, 960, 500 );
	        }

	}

	function showACLPref(){
		
		if ($this->canDo->get('core.admin')) {
			JToolBarHelper::divider();
			$bar = JToolBar::getInstance('toolbar');
			if(JVM_VERSION<3){
				$bar->appendButton('Popup', 'lock', 'JCONFIG_PERMISSIONS_LABEL', 'index.php?option=com_config&amp;view=component&amp;component=com_virtuemart&amp;tmpl=component', 875, 550, 0, 0, '');
			} else {
				$bar->appendButton('Link', 'lock', 'JCONFIG_PERMISSIONS_LABEL', 'index.php?option=com_config&amp;view=component&amp;component=com_virtuemart&amp;tmpl=component');
			}

		}

	}

	/**
	 * Checks if we show multivendor related stuff for admins
	 * @return bool|null
	 */
	public function showVendors(){
		$user=JFactory::getUser();

		if($this->showVendors===null){
			if(VmConfig::get('multix','none')!='none' and $user->authorise('core.admin','com_virtuemart')){
				$this->showVendors = true;
			} else {
				$this->showVendors = false;
			}
		}
		return $this->showVendors;
	}

}