<?php


defined( '_JEXEC' ) or die( 'Restricted access' );
abstract class modBlockHelper{

	static function getTabs($params) {
		$mods						= $params->get('mods');
		$options 					= array('style' => 'none');

		$items 						= array();

		for ($i=0;$i<count($mods);$i++) {

			$items[$i]->order 	= modBlockHelper::getModule($mods[$i])->ordering;
			$items[$i]->title 		= modBlockHelper::getModule($mods[$i])->title;
			$items[$i]->id 		= modBlockHelper::getModule($mods[$i])->id;
			$items[$i]->content 	= JModuleHelper::renderModule(  modBlockHelper::getModule($mods[$i]), $options);
		}

		//($ordering_direction=='ASC') ? asort ($items) : arsort ($items);//sorting

		return $items;

	}

	static function getModule($id)
	{

		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$groups		= implode(',', $user->getAuthorisedViewLevels());
		$lang 		= JFactory::getLanguage()->getTag();
		$clientId 	= (int) $app->getClientId();

		$db	= JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('m.id, m.title, m.module, m.position, m.ordering, m.content, m.showtitle, m.params');
		$query->from('#__modules AS m');
		$query->where('m.published = 1');
		$query->where('m.id = ' . $id);

		$date = JFactory::getDate();
		$now = $date->toMySQL();
		$nullDate = $db->getNullDate();
		$query->where('(m.publish_up = '.$db->Quote($nullDate).' OR m.publish_up <= '.$db->Quote($now).')');
		$query->where('(m.publish_down = '.$db->Quote($nullDate).' OR m.publish_down >= '.$db->Quote($now).')');

		$query->where('m.access IN ('.$groups.')');
		$query->where('m.client_id = '. $clientId);

		// Filter by language
		if ($app->isSite() && $app->getLanguageFilter()) {
			$query->where('m.language IN (' . $db->Quote($lang) . ',' . $db->Quote('*') . ')');
		}

		// Set the query
		$db->setQuery($query);

		$module = $db->loadObject();

		if (!$module) return null;

		$file				= $module->module;
		$custom				= substr($file, 0, 4) == 'mod_' ?  0 : 1;
		$module->user		= $custom;
		$module->name		= $custom ? $module->title : substr($file, 4);
		$module->style		= null;
		$module->position	= strtolower($module->position);
		$clean[$module->id]	= $module;

		return $module;
	}
}