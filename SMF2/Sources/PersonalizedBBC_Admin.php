<?php
/*
	<id>underdog:PersonalizedBBC</id>
	<name>Personalized BBC</name>
	<version>1.0</version>
	<type>modification</type>
*/

/*
 * Personalized BBC was developed for SMF forums c/o Underdog @ http://webdevelop.comli.com
 * Copyright 2014 underdog@webdevelop.comli.com
 * Distributed under the CC BY-ND 4.0 License (http://creativecommons.org/licenses/by-nd/4.0/)
*/

if (!defined('SMF'))
	die('Hacking attempt...');

/*	This file initiates the appropriate Personalized BBC admin subactions.

	void function PersonalizedBBC_Admin()
		- Fatal error for environments not using at least PHP 5.3
		- Executes correct function based on subaction
		- Fills $context with needed data from form(s)
*/

function PersonalizedBBC_Admin()
{
	global $txt, $context, $sourcedir, $smcFunc;

	if (version_compare(phpversion(), '5.3.0', '<'))
		fatal_error(str_replace('#@#$!', phpversion(), $txt['PersonalizedBBC_PHP_ErrorMessage']), false);
	else
	{
		require_once($sourcedir . '/PersonalizedBBC_AdminSettings.php');
		require_once($sourcedir . '/Subs-PersonalizedBBC_Admin.php');
	}

	// Fill $context with name, page and possible input data
	$personalizedBBC_settings = array('name', 'description', 'code', 'image', 'prior', 'after', 'parse', 'trim', 'type', 'block_lvl', 'enable', 'display', 'delete', 'current_name', 'list');
	$context['current_name'] = (!empty($_REQUEST['name'])) && !is_array($_REQUEST['name']) ? $smcFunc['strtolower'](trim(cleanPersonalizedBBC_String($_REQUEST['name']))) : '';
	$context['current_page'] = !empty($context['current_page']) ? (int)$context['current_page'] : 1;
	$context['current_page'] = (!empty($_REQUEST['current_page']) ? (int)$_REQUEST['current_page'] : $context['current_page']) -1;

	foreach ($personalizedBBC_settings as $setting_type)
	{
		$_REQUEST[$setting_type] = isset($_REQUEST[$setting_type]) ? $_REQUEST[$setting_type] : array();

		if (is_array($_REQUEST[$setting_type]))
			foreach ($_REQUEST[$setting_type] as $id => $data)
				$context['personalizedBBC'][$id][$setting_type] = $data;
		elseif ($context['current_name'])
			$context['personalizedBBC'][$context['current_name']][$setting_type] = $_REQUEST[$setting_type];
	}
	if (!empty($_POST['membergroups']) && is_array($_POST['membergroups']))
	{
		foreach ($_POST['membergroups'] as $id => $value)
		{
			if ($value == 1)
				$context['personalizedBBC_membergroups'][$id] = 1;
			elseif (isset($value))
				$context['personalizedBBC_membergroups'][$id] = 0;
		}
	}

	$subActions = array(
		'personalizedBBC_Settings' => array('SettingsPersonalizedBBC'),
		'personalizedBBC_Entry' => array('EntryPersonalizedBBC'),
	);

	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'personalizedBBC_Settings';
	isAllowedTo('admin_forum');
	$subActions[$_REQUEST['sa']][0]();
}
?>