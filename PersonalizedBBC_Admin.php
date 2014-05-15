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

/*	This file handles the Personalized BBC admin settings.

	void function PersonalizedBBC_Admin()
		- Executes correct function based on subaction
		- Fills $context with needed data from form(s)

	void function SettingsPersonalizedBBC()
		- Admin menu sub action
		- Logic for settings list admin menu
		- Loads the PersonalizedBBC admin template

	void function EntryPersonalizedBBC()
		- Admin menu sub action
		- Logic for revision admin menu
		- Loads the PersonalizedBBC admin template
*/

function PersonalizedBBC_Admin()
{
	global $scripturl, $txt, $context, $sourcedir, $settings, $modSettings, $smcFunc;
	require_once($sourcedir . '/Subs-PersonalizedBBC_Admin.php');

	if (version_compare(phpversion(), '5.3.0', '<'))
		fatal_error(str_replace('#@#$!', phpversion(), $txt['PersonalizedBBC_PHP_ErrorMessage']), false);

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

	if (isset($subActions[$_REQUEST['sa']][1]))
		isAllowedTo('admin_forum');

	$subActions[$_REQUEST['sa']][0]();
}

function SettingsPersonalizedBBC()
{
	global $txt, $scripturl, $context, $smcFunc, $sourcedir;
	require_once($sourcedir . '/ManagePermissions.php');

	if (!allowedTo('admin_forum'))
		fatal_lang_error('PersonalizedBBC_ErrorMessage',false);

	$context['robot_no_index'] = true;
	$tableName = 'personalized_bbc';
	$listArray = array('enable', 'display', 'delete');
	$context['personalizedBBC_membergroups'] = !empty($context['personalizedBBC_membergroups']) ? $context['personalizedBBC_membergroups'] : array();
	$context['PersonalizedBBC_display'] = array('page' => false, 'pages' => '0');

	$setting_types = array(
			'name' => array('string', false, 'name'),
			'description' => array('string', true, 'description'),
			'code' => array('code', false, 'code'),
			'image' => array('file', true, 'image'),
			'prior' => array('code', true, 'prior'),
			'after' => array('code', true, 'after'),
			'parse' => array('int', true, 'parse'),
			'trim' => array('int', true, 'trim'),
			'type' => array('int', true, 'type'),
			'block_lvl' => array('int', true, 'block_lvl'),
			'enable' => array('checkbox', true, 'enable'),
			'display' => array('checkbox', true, 'display'),
			'delete' => array('del', false, 'del'),
		);

	/*  Check for new settings values and save to database if necessary */
	if (isset($_GET['save']))
	{
		checkSession('request');

		$bbcTags = !empty($context['personalizedBBC']) ? $context['personalizedBBC'] : array();
		foreach ($bbcTags as $thisName => $context['personalizedBBC'])
		{
			$name = !empty($context['current_name']) ? cleanPersonalizedBBC_String($context['current_name']) : (!empty($context['personalizedBBC']['name']) ? cleanPersonalizedBBC_String($context['personalizedBBC']['name']) : cleanPersonalizedBBC_String($context['personalizedBBC']['current_name']));
			$context['current_name'] = !empty($context['current_name']) ? $context['current_name'] : $name;
			$type = !empty($context['personalizedBBC']['type']) ? $context['personalizedBBC']['type'] : '';
			$parse = !empty($context['personalizedBBC']['parse']) ? $context['personalizedBBC']['parse'] : '';
			$context['personalizedBBC']['code'] = !empty($context['personalizedBBC']['code']) ? $context['personalizedBBC']['code'] : '';
			$checkName = false;

			if ($type != 2)
			{
				if (!$parse)
				{
					if ($type == 1)
						$context['personalizedBBC']['code'] = preg_replace_callback(array('#\{option\}#si', '#\{content\}#si'),  function($m){return $m[0] === '{option}' ? '$1' : '$2';}, $context['personalizedBBC']['code']);
					else
						$context['personalizedBBC']['code'] = preg_replace_callback(array('#\{content\}#si'),  function($m){return $m[0] === '{option}' ? '' : '$1';}, $context['personalizedBBC']['code']);
				}
				else
				{
					if ((int)$parse == 2)
						$text = preg_replace_callback('#\{option\}#si', function(){return '$1';}, $context['personalizedBBC']['code']);
					else
						$text = preg_replace_callback('#\{option\}#si', function(){return '';}, $context['personalizedBBC']['code']);

					$pos = strpos($text, '{content}');
					if ($pos === false)
					{
						$context['personalizedBBC']['prior'] = $text;
						$context['personalizedBBC']['after'] = '';
					}
					else
					{
						$context['personalizedBBC']['prior'] = substr($text, 0, $pos);
						$context['personalizedBBC']['after'] = substr($text, $pos + 9);
					}
				}
			}
			else
				$context['personalizedBBC']['code'] = preg_replace_callback('#\{content\}|\{option\}#si',  function($m){return '&#32;';}, $context['personalizedBBC']['code']);

			if (!$name)
				continue;

			// this table does not auto increment by design therefore create a new default column where one does not exist (key = name)
			$result = $smcFunc['db_query']('', "
				SELECT *
				FROM {db_prefix}personalized_bbc
				WHERE name = {string:name}
				LIMIT 1",
				array('name' => $name)
			);
			while ($val = $smcFunc['db_fetch_assoc']($result))
			{
				$checkName = !empty($val['name']) ? $val['name'] : '';
				foreach ($setting_types as $column => $setting)
				{
					if (in_array($column, $listArray) && !empty($context['personalizedBBC']['list']))
					{
						$context['personalizedBBC'][$column] = !empty($context['personalizedBBC'][$column]) ? true : false;
						continue;
					}
					elseif (!empty($context['personalizedBBC']['list']))
						continue;
					elseif ($column === 'delete' || $column === 'name' || !$checkName)
						continue;

					$context['personalizedBBC'][$column] = !empty($val[$column]) && !isset($context['personalizedBBC'][$column]) ? $val[$column] : (isset($context['personalizedBBC'][$column]) ? $context['personalizedBBC'][$column] : '');
					$context['personalizedBBC']['block_lvl'] = !empty($context['personalizedBBC']['block_lvl']) ? $context['personalizedBBC']['block_lvl'] : 0;
					$context['personalizedBBC']['delete'] = 0;
				}
			}
			$smcFunc['db_free_result']($result);

			$result = $smcFunc['db_query']('', "
				SELECT permission, id_group, add_deny
				FROM {db_prefix}permissions
				WHERE permission = {string:perm}",
				array('perm' => 'personalized_bbc_' . trim($context['current_name']))
			);
			while ($val = $smcFunc['db_fetch_assoc']($result))
			{
				$context['personalizedBBC']['permissions'][$val['id_group']] = array(
					'id_group' => isset($val['id_group']) ? $val['id_group'] : null,
					'add_deny' => !empty($val['add_deny']) ? 1 : 0,
				);
			}
			$smcFunc['db_free_result']($result);

			// set default values prior to adding the new tag
			if (!$checkName && empty($context['personalizedBBC']['delete']))
			{
				$request = $smcFunc['db_insert']('insert', "
					{db_prefix}personalized_bbc",
					array('name' => 'string', 'code' => 'string', 'description' => 'string', 'image' => 'string', 'prior' => 'string', 'after' => 'string', 'parse' => 'int', 'trim' => 'int', 'type' => 'int', 'block_lvl' => 'int', 'enable' => 'int', 'display' => 'int'),
					array($name, '', '', '', '', '', 0, 0, 0, 0, 1, 1),
					array('name')
				);
				$type = 2;
			}

			foreach ($setting_types as $key => $data)
			{
				if (!in_array($key, $listArray) && !empty($context['personalizedBBC']['list']))
					continue;
				elseif (empty($context['personalizedBBC']['list']))
				{
					if ($data[1] && !isset($context['personalizedBBC'][$key]))
						$context['personalizedBBC'][$key] = '';
					elseif (!$data[1] && empty($context['personalizedBBC'][$key]))
					{
						$context['personalizedBBC'][$key] = false;
						continue;
					}
				}

				// delete the old bbc tag if the name was altered
				if (empty($context['personalizedBBC']['list']) && !empty($context['current_name']) && !empty($context['personalizedBBC']['name']))
				{
					if ($thisName !== $context['personalizedBBC']['name'] && $key === 'description')
					{
						$smcFunc['db_query']('', '
							DELETE FROM {db_prefix}{raw:tableName}
							WHERE name = {string:name}',
							array('name' => $thisName, 'tableName' => $tableName)
						);
						$smcFunc['db_query']('', '
							DELETE FROM {db_prefix}permissions
							WHERE permission LIKE {string:perm}',
							array('perm' => 'personalized_bbc_' . $thisName)
						);


						$context['current_name'] = $context['personalizedBBC']['name'];
					}
				}

				$value = $key === 'name' ? cleanPersonalizedBBC_String(trim($context['personalizedBBC'][$key])) : cleanPersonalizedBBC_Query(trim($context['personalizedBBC'][$key]));
				switch ($data[0])
				{
					case 'string':
						$val = $smcFunc['htmlspecialchars'](filter_var($value, FILTER_SANITIZE_STRING));
						createPersonalizedBBC_setting($tableName, $key, $val, $name);
						continue 2;
					case 'int':
						$val = (int)$value > 0 ? (int)$value : ($value === 'on' ? 1 : 0);

						if ($name)
							$request = $smcFunc['db_query']('', "
								UPDATE {db_prefix}{raw:tableName}
								SET {raw:key} = {int:val}
								WHERE name = {string:name}
								LIMIT 1",
								array('tableName' => $tableName, 'key' => $key, 'val' => $val, 'name' => $name)
							);
						continue 2;
					case 'enable_int':
						if (!empty($val))
							$val = ($value == 2) ? 2 : 1;
						$request = $smcFunc['db_query']('', "
									UPDATE {db_prefix}{raw:tableName}
									SET type = {int:val}
									WHERE name = {string:name}
									LIMIT 1",
									array('val' => (int)$val, 'name' => $name, 'tableName' => $tableName)
								);
						continue 2;
					case 'file':
						$val = PersonalizedBBC_CheckImage(array($name => $value));
						$val = preg_replace('/\.[^.]*$/', '', $val);
						createPersonalizedBBC_setting($tableName, $key, $val, $name);
						continue 2;
					case 'code':
						$val = cleanPersonalizedBBC_Code($context['personalizedBBC']['code'], $context['personalizedBBC']['trim']);
						createPersonalizedBBC_setting($tableName, $key, $val, $name);
						continue 2;
					case 'checkbox':
						$val = !empty($context['personalizedBBC'][$key]) ? 1 : 0;
						if (!empty($context['personalizedBBC']['current_name']))
							$request = $smcFunc['db_query']('', "
								UPDATE {db_prefix}{raw:tableName}
								SET {raw:key} = {int:val}
								WHERE name = {string:name}
								LIMIT 1",
								array('tableName' => $tableName, 'key' => $key, 'val' => $val, 'name' => $context['personalizedBBC']['current_name'])
							);
						continue 2;
					case 'del':
						if ($key === 'delete' && $context['personalizedBBC']['delete'] == 1)
						{
							$smcFunc['db_query']('', '
								DELETE FROM {db_prefix}{raw:tableName}
								WHERE name = {string:name}',
								array('name' => $context['personalizedBBC']['current_name'], 'tableName' => $tableName)
							);
							$smcFunc['db_query']('', '
								DELETE FROM {db_prefix}permissions
								WHERE permission LIKE {string:perm}',
								array('perm' => 'personalized_bbc_' . $context['personalizedBBC']['current_name'])
							);
						}
						continue 2;
				}
			}
		}

		// Adjust membergroup permissions
		foreach ($context['personalizedBBC_membergroups'] as $group => $value)
		{
			$value = isset($value) ? $value : (isset($context['personalizedBBC']['permissions'][$group]['add_deny']) ? $context['personalizedBBC']['permissions'][$group]['add_deny'] : null);
			$value = !isset($value) ? '' : ($value == 1 ? 'on' : 'deny');
			$_POST['personalized_bbc_' . $name][$group] = $value;
		}
		if (!empty($name))
			save_inline_permissions(array('personalized_bbc_' . $name));
	}

	// Gather data from configurations stored in the settings table
	list($context['personalizedBBC_list'], $context['personalizedBBC']) = array(array(), array());
	$result = $smcFunc['db_query']('', "
		SELECT *
		FROM {db_prefix}personalized_bbc
		ORDER BY LENGTH(name), name ASC"
	);

	while ($val = $smcFunc['db_fetch_assoc']($result))
	{
		$id = $val['name'];
		foreach ($setting_types as $key => $setting_type)
			$context['personalizedBBC_list'][$id][$key] = !empty($val[$key]) ? $val[$key] : false;

		if (empty($context['personalizedBBC_list'][$id]['parse']))
		{
			if (!empty($context['personalizedBBC_list'][$id]['code']) && (int)$context['personalizedBBC_list'][$id]['type'] == 1)
				$context['personalizedBBC_list'][$id]['code'] = preg_replace_callback(array('#\$1#', '#\$2#'), function($m){if ($m[0] === '$1'){return '{option}';}return '{content}';}, $context['personalizedBBC_list'][$id]['code']);
			elseif (!empty($context['personalizedBBC_list'][$id]['code']))
				$context['personalizedBBC_list'][$id]['code'] = preg_replace_callback(array('#\$1#', '#\$2#'), function($m){if ($m[0] === '$1'){return '{content}';}return '';}, $context['personalizedBBC_list'][$id]['code']);

		}
		else
		{
			$context['personalizedBBC_list'][$id]['code'] = '';
			if (!empty($context['personalizedBBC_list'][$id]['prior']))
				$context['personalizedBBC_list'][$id]['code'] = preg_replace_callback('#\$1#', function(){return '{option}';}, $context['personalizedBBC_list'][$id]['prior']) . '{content}';

			if (!empty($context['personalizedBBC_list'][$id]['after']))
				$context['personalizedBBC_list'][$id]['code'] .= preg_replace_callback('#\$1#', function(){return '{option}';}, $context['personalizedBBC_list'][$id]['after']);
		}
	}
	$smcFunc['db_free_result']($result);

	// Set the $context for the display template
	$context['settings_title'] = $txt['PersonalizedBBC_Settings'];
	$context['post_url'] = $scripturl . '?action=admin;area=PersonalizedBBC;sa=personalizedBBC_Settings;' . $context['session_var'] . '=' . $context['session_id'] . ';save';
	$context['sub_template'] = 'personalizedBBC_List';

	$context['personalizedBBC_confirm'] = '
	<script type="text/javascript"><!-- // --><![CDATA[
		function confirmSubmit()
		{
			var agree=confirm("' . $txt['personalizedBBC_confirm'] . '");
			if (agree)
				return true ;
			else
				return false ;
		}
	// ]]></script>';

	$context['personalizedBBC'] = PersonalizedBBC_pagination($context['personalizedBBC_list'], $scripturl . '?action=admin;area=PersonalizedBBC;', 10);
	$context['PersonalizedBBC_display'] = PersonalizedBBC_pages($txt['PersonalizedBBC_page'], '#page_top', $scripturl . '?action=admin;area=PersonalizedBBC;', $context['current_pages']);

	loadTemplate('PersonalizedBBC_Admin');

}

function EntryPersonalizedBBC()
{
	global $txt, $scripturl, $context, $smcFunc, $sourcedir;

	if (!allowedTo('admin_forum'))
		fatal_lang_error('PersonalizedBBC_ErrorMessage',false);

	$context['robot_no_index'] = true;
	$context['current_name'] = !empty($_REQUEST['name']) ? $smcFunc['strtolower'](cleanPersonalizedBBC_Query(trim($_REQUEST['name']))) : '';
	$context['PersonalizedBBC_Images'] = PersonalizedBBC_images();
	$setting_types = array('name', 'description', 'code', 'image', 'prior', 'after', 'parse', 'trim', 'type', 'block_lvl', 'enable', 'display', 'delete', 'current_name');
	$tableName = 'personalized_bbc';

	foreach ($setting_types as $setting_type)
		$context['personalizedBBC'][$setting_type] = '';
	$context['personalizedBBC']['image'] = $context['PersonalizedBBC_Images'][0];

	// Gather data from configurations stored in the settings table
	if (!empty($context['current_name']))
	{
		$result = $smcFunc['db_query']('', "
			SELECT *
			FROM {db_prefix}personalized_bbc
			WHERE name = {string:name}",
			array('name' => $context['current_name'])
		);
		while ($val = $smcFunc['db_fetch_assoc']($result))
		{
			$val['image'] = !empty($val['image']) ? str_replace(array('personalizedBBC/', '.gif'), '', $val['image']) . '.gif' : $context['PersonalizedBBC_Images'][0];

			foreach ($setting_types as $setting_type)
				$context['personalizedBBC'][$setting_type] = isset($val[$setting_type]) ? $val[$setting_type] : '';

			if (empty($val['parse']))
			{
				if ((int)$context['personalizedBBC']['type'] == 1)
					$context['personalizedBBC']['code'] = preg_replace_callback(array('#\$1#', '#\$2#'),  function($m){if ($m[0] === '$1'){return '{option}';}return '{content}';}, $context['personalizedBBC']['code']);
				else
					$context['personalizedBBC']['code'] = preg_replace_callback(array('#\$1#', '#\$2#'),  function($m){if ($m[0] === '$1'){return '{content}';}return '';}, $context['personalizedBBC']['code']);
			}
			else
			{
				$context['personalizedBBC']['code'] = '';
				if (isset($context['personalizedBBC']['prior']))
					$context['personalizedBBC']['code'] = preg_replace_callback('#\$1#',  function(){return '{option}';}, $context['personalizedBBC']['prior']) . '{content}';

				if (isset($context['personalizedBBC']['after']))
					$context['personalizedBBC']['code'] .= preg_replace_callback('#\$1#',  function(){return '{option}';}, $context['personalizedBBC']['after']);
			}
		}
		$smcFunc['db_free_result']($result);

		$result = $smcFunc['db_query']('', "
			SELECT permission, id_group, add_deny
			FROM {db_prefix}permissions
			WHERE permission = {string:perm}",
			array('perm' => 'personalized_bbc_' . trim($context['current_name']))
		);
		while ($val = $smcFunc['db_fetch_assoc']($result))
		{
			$context['personalizedBBC']['permissions'][$val['id_group']] = array(
				'id_group' => isset($val['id_group']) ? $val['id_group'] : null,
				'add_deny' => !empty($val['add_deny']) ? 1 : 0,
			);
		}
		$smcFunc['db_free_result']($result);
	}

	// Set the $context for the display template
	$context['PersonalizedBBC_Membergroups'] = PersonalizedBBC_load_membergroups();
	$context['settings_title'] = $txt['PersonalizedBBC_Settings'];
	$context['post_url'] = $scripturl . '?action=admin;area=PersonalizedBBC;sa=personalizedBBC_Settings;' . $context['session_var'] . '=' . $context['session_id'] . ';save';
	$context['sub_template'] = 'personalizedBBC_Edit';

	loadTemplate('PersonalizedBBC_Admin');
}
?>