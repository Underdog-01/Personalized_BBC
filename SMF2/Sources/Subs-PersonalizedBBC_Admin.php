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

// This file contains the Personalized BBC admin sub functions.
if (!defined('SMF'))
	die('Hacking attempt...');

/*
	void function createPersonalizedBBC_setting($tableName, $columnName, $value, $name)
		- Standard function to update a specified db column

	void function PersonalizedBBC_CheckImage($imageArray)
		- Ensures the bbc image file is transferred to the appropriate theme directory
		- Failure defaults to: bbc-name.gif

	void function PersonalizedBBC_FileTypes($file)
		- Ensures the bbc image file type is gif
		- This function may only be necessary where one enters file names via phpmyadmin or the like

	void function PersonalizedBBC_SanitizeFileName($filename)
		- Ensures the bbc image file name does not contain illegal characters that may cause an error
		- This function may only be necessary where one enters file names via phpmyadmin or the like

	void function PersonalizedBBC_copyDirectory($source, $destination)
		- Copies an entire directory

	void function PersonalizedBBC_copyFile($source, $destination)
		- Copies a specified file

	void function cleanPersonalizedBBC_String($string)
		- Filters name/description inputs for specific HTML characters

	void function cleanPersonalizedBBC_Code($string)
		- Filters code/before/after inputs for specific characters such as unnecessary white spaces

	void function PersonalizedBBC_load_membergroups()
		- Queries existing membgroup data for bbc permission settings

	void function PersonalizedBBC_images()
		- Gathers gif icon file names from the ../Themes/default/images/bbc/personalizedBBC directory

	void function PersonalizedBBC_pagination($content, $count)
		- First tier of custom pagination routine
		- Returns part of Personalized BBC list array depending on current page number

	void function PersonalizedBBC_pages($lang, $anchor, $link, $pages, $sort, $order)
		- Second tier of custom pagination routine
		- Sets pages and links for the display template
*/

function createPersonalizedBBC_setting($tableName, $columnName, $value, $name)
{
	global $smcFunc;

	if (empty($tableName) || empty($columnName) || empty($name) || $columnName === 'delete')
		return;
	elseif (empty($value))
		$value = '';

	$request = $smcFunc['db_query']('', "
		UPDATE {db_prefix}{raw:tableName}
		SET {raw:columnName} = {string:value}
		WHERE name = {string:name}
		LIMIT {int:limit}",
		array('name' => $name, 'limit' => 1, 'tableName' => $tableName, 'columnName' => $columnName, 'value' => $value)
	);
}

function PersonalizedBBC_CheckImage($image = array('bbc' => 'bbc.gif'))
{
	global $settings;

	$key = key($image);
	$file = PersonalizedBBC_SanitizeFileName($image[$key]);

	if (PersonalizedBBC_FileTypes($file) && @file_exists($settings['theme_dir'] . '/images/bbc/personalizedBBC/' . $file))
		return 'personalizedBBC/' . $file;
	elseif (PersonalizedBBC_FileTypes($file) && @file_exists($settings['default_theme_dir'] . '/images/bbc/personalizedBBC/' . $file))
	{
		if ((is_dir($settings['theme_dir'] . '/images/bbc/personalizedBBC')) && PersonalizedBBC_copyFile($settings['default_theme_dir'] . '/images/bbc/personalizedBBC/' . $file, $settings['theme_dir'] . '/images/bbc/personalizedBBC/' . $file))
			return 'personalizedBBC/' . $file;
		elseif (PersonalizedBBC_copyDirectory($settings['default_theme_dir'] . '/images/bbc/personalizedBBC', $settings['theme_dir'] . '/images/bbc/personalizedBBC'))
			return 'personalizedBBC/' . $file;
	}

	return $key . '.gif';
}

// Check for valid file type (gif only)
function PersonalizedBBC_FileTypes($file = '')
{
	if (strripos($file, '.gif') !== false)
		return true;

	return false;
}

function PersonalizedBBC_SanitizeFileName($filename)
{
        $filename_raw = $filename;
        $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));
        $filename = str_replace($special_chars, '', $filename);
        $filename = preg_replace('/[\s-]+/', '-', $filename);
        $filename = trim($filename, '.-_');

        $parts = explode('.', $filename);

        if (count($parts) <= 2)
		return $filename;

        $filename = array_shift($parts);
        $extension = array_pop($parts);
        $mimes = array('gif');

	foreach ((array)$parts as $part)
	{
		$filename .= '.' . $part;

		if (preg_match("/^[a-zA-Z]{2,5}\d?$/", $part))
		{
			$allowed = false;
		        foreach ($mimes as $ext_preg => $mime_match)
			{
				$ext_preg = '!^(' . $ext_preg . ')$!i';
				if (preg_match($ext_preg, $part))
				{
					$allowed = true;
					break;
				}
			}
			if (!$allowed)
				$filename .= '_';
		}
	}

	$filename .= '.' . $extension;

	return $filename;
}

function PersonalizedBBC_copyDirectory($source, $destination)
{
	if (@is_dir($source))
	{
		if (@!is_dir($destination))
			@mkdir($destination);

		$directory = @dir($source);
		while (FALSE !== ($readdirectory = $directory->read()))
		{
			if ($readdirectory == '.' || $readdirectory == '..')
				continue;

			$PathDir = $source . '/' . $readdirectory;
			if (@is_dir($PathDir))
			{
				PersonalizedBBC_copyDirectory($PathDir, $destination . '/' . $readdirectory);
				continue;
			}

			copy($PathDir, $destination . '/' . $readdirectory);
		}

		$directory->close();
	}
	elseif (@file_exists($source) && !@file_exists($destination))
		@copy($source, $destination);
	else
		return false;

	return true;
}

function PersonalizedBBC_copyFile($source, $destination)
{
	if (@file_exists($source) && !@file_exists($destination))
		@copy($source, $destination);
	else
		return false;

	return true;
}

function cleanPersonalizedBBC_String($string = false)
{
	$string = preg_replace(array('/[^a-zA-Z0-9_\+\s]/', '#\s{2,}#', '/\s+/'), array('', '_', '_'), (string)$string);
	return trim($string);
}

function cleanPersonalizedBBC_Code($string = false, $trim = 0)
{
	if ($trim == 3)
	{
		// trim every whitespace
		$string = preg_replace(array("#(\s+)(?![^<|>])(?=[^>]*(<|$))#", "#\s*$^\s*#m", "#(?:(\\n|\\r|\<br( />)){1,})#m"), "", $string);
		$string = preg_replace(array('#(?:(\s|\\n|\\r|&nbsp;|\\x0B)){1,}(?![^<|>]*>)#x', '#\s+(?![^>]*(\<|$))#'), ' ', trim($string));
		$string = preg_replace(array('#(\>\s){1,}#', '#(\s\<){1,}#'), array('>', '<'), $string);
	}
	elseif ($trim == 2)
	{
		// trim whitespace outside html tags
		$string = preg_replace("#(\s+)(?![^<|>])(?=[^>]*(<|$))#", "", $string);
		$string = preg_replace_callback('#(.*?)\<#', function($m){return preg_replace('#\s+#', ' ', $m[0]);}, trim($string), 1);
		$string = preg_replace_callback('#(\>(?:.(?!\>))+$)#', function($m){return preg_replace('#\s+#', ' ', $m[0]);}, trim($string), 1);
	}
	elseif ($trim == 1)
	{
		// trim whitespace inside all html tags (ignore outside)
		$string = preg_replace("#(\s+)(?![^<|>])(?=[^>]*(<|$))#", "", $string);
		$string = preg_replace('#\s+(?![^>]*(\<|$))#', ' ', $string);
		$string = preg_replace_callback('#\>(.*?)\<#', function($m){return preg_replace('#(\s|\\x0B){1,}#i', ' ', $m[0]);}, $string);
		$string = preg_replace(array('#(\>\s){1,}#', '#(\s\<){1,}#'), array('>', '<'), $string);
	}

	return $string;
}

function PersonalizedBBC_load_membergroups()
{
	global $smcFunc, $txt;

	loadLanguage('ManageBoards');

	$groups = array(
		-1 => $txt['parent_guests_only'],
		0 => $txt['parent_members_only'],
	);

	$request = $smcFunc['db_query']('', '
		SELECT group_name, id_group, min_posts
		FROM {db_prefix}membergroups
		WHERE id_group NOT IN ({raw:groups})
		ORDER BY min_posts, group_name',
		array(
			'groups' => '"1", "3"',
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$groups[(int)$row['id_group']] = trim($row['group_name']);
	$smcFunc['db_free_result']($request);

	return $groups;
}

function PersonalizedBBC_images()
{
	global $settings;
	list($imagesetlist, $count) = array(array('0' => 'no_image.gif'), 0);

	if (is_dir($settings['default_theme_dir'] . '/images/bbc/personalizedBBC'))
		$imagesDir = @opendir($settings['default_theme_dir'] . '/images/bbc/personalizedBBC');


	if ($imagesDir)
	{
		while (($file = readdir($imagesDir)) !== false)
		{
			if (preg_match('#\.(?:gif)$#', $file))
			{
				$imagesetlist[$count] = $file;
				$count++;
			}
		}
		closedir($imagesDir);
	}

	return $imagesetlist;
}

function PersonalizedBBC_pagination($content, $redirect, $count = 10)
{
	/* PHP pagination - max 7 visible integers and 6 periods (all links) - current page encircled with square brackets
	 * This php pagination code was developed by Underdog copyright 2013, 2014
	 * http://webdevelop.comli.com
	 * Licensed under the GNU Public License: http://www.gnu.org/licenses/gpl.html
	*/

	// This particular function is only used when opting an entire table else it is not necessary
	global $context, $scripturl;

	/*  Set the $context variables for the display template  */
	$context['current_count'] = count($content);
	$context['current_pages'] = ((int)($context['current_count'] / $count)) + 1;
	$redirect = !empty($redirect) ? $redirect : $scripturl;
	$maxPages = 1;

	if (count($content) <= $count || $count < 2)
	{
		$context['current_pages'] = 0;
		return $content;
	}

	// Did they enter an invalid page via the url?
	if ((int)($context['current_count']/$count) == $context['current_count']/$count)
		$maxPages = $context['current_count']/$count;
	elseif($context['current_count'] %$count != 0)
		$maxPages += ($context['current_count'] - ($context['current_count'] % $count)) / $count;

	if ((int)$context['current_page']+1 > $maxPages)
		redirectexit($redirect . 'current_page=' . $maxPages . ';#page_top');
	elseif ((int)$context['current_page'] < 0)
		redirectexit($redirect . 'current_page=1;#page_top');

	// Now calculate the part of the array to display
	if (($context['current_count'] / $count) == (int)($context['current_count'] / $count))
		$context['current_pages'] = ($context['current_count'] / $count);

	$context['current_showResults'] = array(((int)$context['current_page'] * $count), (((int)$context['current_page'] + 1) * $count) - 1);

	if ((int)$context['current_page']+1 == (int)$context['current_pages'])
	    $context['current_showResults'][1] = count($content);
	else
	    $context['current_showResults'][1] = (int)$context['current_page']*$count + ($count-1);

	foreach($content as $key => $var)
	{
		$counter = array_search($key , array_keys($content));
		if ($counter >= (int)$context['current_showResults'][0] && $counter <= (int)$context['current_showResults'][1])
			$new_content[$key] = $var;
	}

	if (!empty($new_content))
		$context['current_showResults'][1] = ((int)$context['current_page']*$count) + count($new_content);
	else
		$new_content = $content;

	return $new_content;
}

function PersonalizedBBC_pages($lang, $anchor, $link, $pages, $sort=false, $order=false)
{
	/* PHP pagination - max 7 visible integers and 6 periods (all links) - current page encircled with square brackets
	 * This php pagination code was developed by Underdog copyright 2013, 2014
	 * http://webdevelop.comli.com
	 * Licensed under the GNU Public License: http://www.gnu.org/licenses/gpl.html
	*/
	global $context, $txt, $scripturl;

	$pageCount = 1;
	$display = array('page' => false, 'pages' => '0');
	$page = !empty($context['current_page']) ? (int)$context['current_page'] : 0;
	$display['pages'] = !empty($pages) ? (int)$pages : 1;

	if ($display['pages'] > 1)
	{
            $display['page'] =  '
    <script type="text/javascript"><!-- // --><![CDATA[
        function changeColor(s)
	{
                document.getElementById("link"+s).style.color = "red";
	}
	function changeColorBack(s)
	{
		document.getElementById("link"+s).style.color = "blue";
	}
    // ]]></script>
    <span style="text-align:center;position:relative;width:99%;display:inline-block;">
        ' . $lang . '<br />';

            while ($pageCount < (int)$display['pages']+1)
            {
		$current_page = (int)$page+1;
		$total = (int)$display['pages'];

		if ($pageCount == 1 || $pageCount == $total || $pageCount == $current_page || $pageCount == $current_page+1 ||
		    $pageCount == $current_page+2 || $pageCount == $current_page-1 || $pageCount == $current_page-2)
		{
                    if ((int)$pageCount == (int)$page+1)
			$display['page'] .= '
        <a onclick="this.href=\'javascript: void(0)\';" onmouseout="changeColor('. $pageCount . ')" onmouseover="changeColorBack(' . $pageCount . ')" id="link' . $pageCount . '" style="color:red;text-decoration:none;" href="'. $link . ';current_page=' . $pageCount . ';' . $sort . $order . $anchor . '">[' . $pageCount . ']</a> ';
                    else
			$display['page'] .= '
        <a onmouseout="changeColorBack(' . $pageCount . ')" onmouseover="changeColor(' . $pageCount . ')" id="link' . $pageCount . '" style="color:blue;text-decoration:none;" href="' . $link . ';current_page=' . $pageCount . ';' . $sort . $order . $anchor . '">' . $pageCount . '</a> ';
		}
		elseif ($pageCount < $current_page-2 && $pageCount > $current_page-6)
			$display['page'] .= '
        <a onmouseout="changeColorBack(' . $pageCount . ')" onmouseover="changeColor(' . $pageCount . ')" id="link' . $pageCount . '" style="color:blue;text-decoration:none;" href="' . $link . ';current_page=' . $pageCount . ';' . $sort . $order . $anchor . '">.</a> ';
		elseif ($pageCount > $current_page+2 && $pageCount < $current_page+6)
			$display['page'] .= '
        <a onmouseout="changeColorBack(' . $pageCount . ')" onmouseover="changeColor(' . $pageCount . ')" id="link' . $pageCount . '" style="color:blue;text-decoration:none;" href="' . $link . ';current_page=' . $pageCount . ';' . $sort . $order . $anchor . '">.</a> ';

                $pageCount++;
            }

            $display['page'] .= '
    </span>';
	}

	return $display;
}
?>