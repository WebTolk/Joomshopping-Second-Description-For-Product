<?php

//use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

$name    = 'JoomShopping Second Description For Product';
$type    = 'plugin';
$element = 'second_description_for_product';
$folders = ['jshoppingproducts', 'jshoppingadmin'];
$version = '2.1.2';
$cache   = '{"creationDate":"18.02.2015","author":"MAXXmarketing GmbH","authorEmail":"marketing@maxx-marketing.net","authorUrl":"https://www.webdesigner-profi.de","version":"' . $version . '"}';
$params  = '{}';

$addon = \JSFactory::getTable('addon', 'jshop');
foreach ($folders as $folder)
{
	$addon->installJoomlaExtension(
		[
			'name'           => $name,
			'type'           => 'plugin',
			'element'        => $element,
			'folder'         => $folder,
			'client_id'      => 0,
			'enabled'        => 1,
			'access'         => 1,
			'protected'      => 0,
			'manifest_cache' => $cache,
			'params'         => $params
		]
	);
}

$addon->loadAlias($element);
$addon->set('name', $name);
$addon->set('version', $version);
$addon->set('uninstall', '/components/com_jshopping/addons/' . $element . '/uninstall.php');
$addon->store();
