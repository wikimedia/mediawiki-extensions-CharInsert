<?php
/**
 * Extension to create new character inserts which can be used on
 * the edit page to make it easy to get at special characters and
 * such forth.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Extensions
 * @author Brion Vibber <brion at pobox.com>
 * @copyright Copyright (C) 2004,2006 Brion Vibber <brion@pobox.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die();
}

$wgHooks['ParserFirstCallInit'][] = 'CharInsert::onParserFirstCallInit';

$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'CharInsert',
	'author' => 'Brion Vibber',
	'url' => 'https://www.mediawiki.org/wiki/Extension:CharInsert',
	'descriptionmsg' => 'charinsert-desc',
);

$wgAutoloadClasses['CharInsert'] = __DIR__ . '/CharInsert.body.php';
$wgMessagesDirs['CharInsert'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['CharInsert'] = __DIR__ . '/CharInsert.i18n.php';
