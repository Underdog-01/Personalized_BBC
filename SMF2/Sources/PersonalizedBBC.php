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

/*	This file handles all of the Personalized BBC integrated hooks

	void function PersonalizedBBC_array_insert(&$input, $key, $insert, $where = 'before', $strict = false)
		- General function to insert data into array

	void function PersonalizedBBC_load_permissions(&$actionArray)
		- Inserts necessary data into SMF permissions array

	void function PersonalizedBBC_admin_areas(&$admin_areas)
		- Loads Personalized BBC admin file

	void function PersonalizedBBC_codes()
		- Adda Personalized BBCs to the bbc array

	void function PersonalizedBBC_buttons()
		- Adda Personalized BBCs to the message editor bbc array

	void function PersonalizedBBC_load()
		- Loads necessary language file
		- Adds varying permission data to the language array

	void function PersonalizedBBC_parser($text)
		- Parses message body for restricted BBC tags
*/

function PersonalizedBBC_array_insert(&$input, $key, $insert, $where = 'before', $strict = false)
{
	$position = array_search($key, array_keys($input), $strict);

	// Key not found -> insert as last
	if ($position === false)
	{
		$input = array_merge($input, $insert);
		return;
	}

	if ($where === 'after')
		$position += 1;

	// Insert as first
	if ($position === 0)
		$input = array_merge($insert, $input);
	else
		$input = array_merge(
			array_slice($input, 0, $position, true),
			$insert,
			array_slice($input, $position, null, true)
		);
}

function PersonalizedBBC_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	global $context, $smcFunc;
	loadLanguage('PersonalizedBBC');
	$permNames = array();
	$key = 0;

	$request = $smcFunc['db_query']('', '
			SELECT name
			FROM {db_prefix}personalized_bbc
			ORDER BY LENGTH(name), name ASC'
		);

	while ($row = $smcFunc['db_fetch_assoc']($request))
		$permNames[] = $row['name'];
	
	$smcFunc['db_free_result']($request);

	foreach ($permNames as $permission)
	{
		$key++;
		$BBC_Name = !empty($permission) ? $smcFunc['strtolower'](trim('personalized_bbc_' . $permission)) : 'personalized_bbc_' . (string)$key;

		$permissionList['membergroup'] += array(
				$BBC_Name => array(false, 'PersonalizedBBC_perms', 'PersonalizedBBC_perms'),
		);

		/*
		$context['non_guest_permissions'] = array_merge(
			$context['non_guest_permissions'],
			array(
				$BBC_Name,
			)
		);
		*/
	}

	if (!empty($permNames))
	{
		$permissionGroups['membergroup']['simple'] += array(
			'PersonalizedBBC_perms',
		);
		$permissionGroups['membergroup']['classic'] += array(
			'PersonalizedBBC_perms',
		);
	}
}

function PersonalizedBBC_admin_areas(&$admin_areas)
{
	global $context, $modSettings, $scripturl, $txt;
	loadLanguage('PersonalizedBBC');

	PersonalizedBBC_array_insert($admin_areas, 'members',
		array(
			'PersonalizedBBC' => array(
				'title' => $txt['PersonalizedBBC_tabtitle'],
				'permission' => array('PersonalizedBBC_settings'),
				'areas' => array(
					'PersonalizedBBC' => array(
						'label' => $txt['PersonalizedBBC_AdminSettings'],
						'file' => 'PersonalizedBBC_Admin.php',
						'function' => 'PersonalizedBBC_Admin',
						'icon' => 'PersonalizedBBC_settings.png',
						'permission' => allowedTo('admin_forum'),
						'subsections' => array(),
					),
				),
			),
		)
	);
}

function PersonalizedBBC_codes(&$codes)
{
	global $smcFunc, $txt;
	loadLanguage('PersonalizedBBC');
	$key = 0;

	$request = $smcFunc['db_query']('', '
			SELECT name, description, image, code, prior, after, parse, trim, type, block_lvl, enable, display
			FROM {db_prefix}personalized_bbc
			ORDER BY LENGTH(name), name ASC'
		);

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		$key++;
		$tag = !empty($row['name']) ? $smcFunc['strtolower'](trim($row['name'])) : 'personalized_bbc_' . (string)$key;
		if (!empty($row['enable']) && !empty($row['code']) && allowedTo('personalized_bbc_' . $tag))
		{
			$parsed = !empty($row['parse']) ? (int)$row['parse'] : 0;
			$tagType = !empty($row['type']) ? (int)$row['type'] : 0;
			$trim = !empty($row['trim']) && $row['trim'] == 1 ? 'inside' : (!empty($row['trim']) && $row['trim'] == 2 ? 'outside' : '');

			if ($tagType == 3)
			{
				$type = 'closed';
				$parsed = 0;
			}
			elseif (empty($parsed))
			{
				if ($tagType == 1)
					$type = 'unparsed_equals_content';
				elseif ($tagType == 2)
					$type = 'unparsed_commas_content';
				else
					$type = 'unparsed_content';
			}
			else
			{
				if ($tagType == 1 && $parsed == 1)
					$type = 'unparsed_equals';
				elseif ($tagType == 1)
					$type = 'parsed_equals';
				elseif ($tagType == 2)
					$type = 'unparsed_commas';
				else
					$type = '';
			}

			$codes[] = array(
				'tag' => $tag,
				'content' => empty($parsed) ? $smcFunc['db_unescape_string']($row['code']) : '',
				'before' => !empty($parsed) && !empty($row['prior']) ? $smcFunc['db_unescape_string']($row['prior']) : '',
				'after' => !empty($parsed) && !empty($row['after']) ? $smcFunc['db_unescape_string']($row['after']) : '',
				'trim' => $trim,
				'type' => !empty($type) ? $type : null,
				'block_level' => !empty($row['block_lvl']) ? true : false,
				'enable' => !empty($row['enable']) ? true : false,
				'show' => !empty($row['display']) ? true : false,
			);
		}
	}
	$smcFunc['db_free_result']($request);
}

function PersonalizedBBC_buttons(&$bbc_buttons)
{
	global $context, $smcFunc, $txt;
	loadLanguage('PersonalizedBBC');
	$datums = array();
	$key = 0;

	$request = $smcFunc['db_query']('', '
			SELECT name, description, image, code, prior, after, parse, trim, type, block_lvl, enable, display
			FROM {db_prefix}personalized_bbc
			WHERE display = {int:display}
			ORDER BY LENGTH(name), name ASC',
			array('display' => 1)
		);

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		$key++;
		$name = !empty($row['name']) ? trim($smcFunc['db_unescape_string']($row['name'])) : 'personalized_bbc_' . (string)$key;
		if (!empty($row['enable']) && !empty($row['code']) && allowedTo('personalized_bbc_' . $name))
		{
			$parsed = !empty($row['parse']) ? (int)$row['parse'] : 0;
			$tagType = !empty($row['type']) ? (int)$row['type'] : 0;

			if ($tagType == 3)
			{
				$type = 'closed';
				$parsed = 0;
			}
			elseif (empty($parsed))
			{
				if ($tagType == 1)
					$type = 'unparsed_equals_content';
				elseif ($tagType == 2)
					$type = 'unparsed_commas_content';
				else
					$type = 'unparsed_content';
			}
			else
			{
				if ($tagType == 1 && $parsed == 1)
					$type = 'unparsed_equals';
				elseif ($tagType == 1)
					$type = 'parsed_equals';
				elseif ($tagType == 2)
					$type = 'unparsed_commas';
				else
					$type = '';
			}

			$datums[] = array(
				'name' => $name,
				'description' => !empty($row['description']) ? $row['description'] : $name,
				'code' => empty($parsed) ? $smcFunc['db_unescape_string']($row['code']) : '',
				'before' => !empty($parsed) && !empty($row['prior']) ? $smcFunc['db_unescape_string']($row['prior']) : '',
				'after' => !empty($parsed) && !empty($row['after']) ? $smcFunc['db_unescape_string']($row['after']) : '',
				'image' => !empty($row['image']) ? str_replace('.gif', '', $row['image']) : trim($name),
				'show' => !empty($row['display']) ? true : false,
				'enable' => !empty($row['enable']) ? true : false,
				'type' => $type,
			);
		}
	}
	$smcFunc['db_free_result']($request);


	if (!empty($bbc_codes))
		$bbc_buttons[2][] = array();

	foreach ($datums as $datum)
	{
		if (!empty($datum['show']) && !empty($datum['enable']))
		{
			if (strpos($datum['type'], 'commas') !== false)
				$before = '[' . $datum['name'] . '=0,0]';
			elseif ($datum['type'] === 'unparsed_equals' || $datum['type'] === 'parsed_equals' || $datum['type'] === 'unparsed_equals_content')
				$before = '[' . $datum['name'] . '=]';
			else
				$before = '[' . $datum['name'] . ']';

			$bbc_buttons[2][] = array(
					'image' => $datum['image'],
					'code' => $datum['code'],
					'description' => $datum['description'],
					'before' => $before,
					'after' => ($datum['type'] !== 'closed' ? '[/' . $datum['name'] . ']' : ''),
			);
		}
	}
}

function PersonalizedBBC_load()
{
	global $smcFunc, $txt, $helptxt, $personalized_BBC;
	loadLanguage('PersonalizedBBC');
	$personalized_BBC = array();
	$key = 0;

	// BB Code permissions are varying
	$request = $smcFunc['db_query']('', '
			SELECT name, type
			FROM {db_prefix}personalized_bbc
			ORDER BY name ASC'
		);

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		$personalized_BBC[] = array('name' => $smcFunc['db_unescape_string']($row['name']), 'type' => (!empty($row['type']) ? (int)$row['type'] : 0));
	}
	$smcFunc['db_free_result']($request);

	foreach ($personalized_BBC as $bbc)
	{
		$key++;
		$permission = $bbc['name'];
		$BBC_Name = !empty($permission) ? $smcFunc['strtolower'](trim('personalized_bbc_' . $permission)) : 'personalized_bbc_' . (string)$key;
		$txt['permissionname_' . $BBC_Name] = trim(str_replace('#@#$!', $permission, $txt['permissionname_PersonalizedBBC_perm']));
		$txt['permissionhelp_' . $BBC_Name] = trim(str_replace('#@#$!', $permission, $txt['permissionhelp_PersonalizedBBC_perm']));
	}
}

function PersonalizedBBC_parser($text)
{
	global $personalized_BBC;
	$personalized_BBC = !empty($personalized_BBC) ? $personalized_BBC : array();

	foreach ($personalized_BBC as $parseBBC)
	{
		if (!allowedTo('personalized_bbc_' . $parseBBC['name']))
		{
			if ((!empty($parseBBC['type'])) && (int)$parseBBC['type'] == 3)
				$text = preg_replace("~\[" . $parseBBC['name'] . "\].*?(?=\<br( />)|\\n|\\r)~mi", "", $text);
			else
				$text = preg_replace(array("~\[" . $parseBBC['name'] . "\](.*?)\[\/" . $parseBBC['name'] . "\]~i", "~\[" . $parseBBC['name'] . "=(.*?)\](.*?)\[\/" . $parseBBC['name'] . "\]~i"), array('', ''), $text);
		}
	}

	return $text;
}
?>