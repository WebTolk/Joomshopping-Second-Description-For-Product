<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

$name = 'JoomShopping Second Description For Product';
$type = 'plugin';
$element = 'second_description_for_product';
$folders = ['jshoppingproducts', 'jshoppingadmin'];
$version = '2.1.0';
$cache = '{"creationDate":"18.02.2015","author":"MAXXmarketing GmbH","authorEmail":"marketing@maxx-marketing.net","authorUrl":"https://www.webdesigner-profi.de","version":"' . $version . '"}';
$params = '{}';

$db = Factory::getContainer()->get('DatabaseDriver');

foreach ($folders as $folder) {
    $query = $db->getQuery(true)
        ->select($db->quoteName('extension_id'))
        ->from($db->quoteName('#__extensions'))
        ->where($db->quoteName('element') . ' = ' . $db->quote($element))
        ->where($db->quoteName('folder') . ' = ' . $db->quote($folder));

    $db->setQuery($query);

    $id = $db->loadResult();
    if (!$id) {
        $query->clear()
            ->insert($db->quoteName('#__extensions'))
            ->columns([
                $db->quoteName('name'),
                $db->quoteName('type'),
                $db->quoteName('element'),
                $db->quoteName('folder'),
                $db->quoteName('client_id'),
                $db->quoteName('enabled'),
                $db->quoteName('access'),
                $db->quoteName('protected'),
                $db->quoteName('manifest_cache'),
                $db->quoteName('params'),
            ])->values(implode(
                    ',',
                    [
                        $db->quote($name),
                        $db->quote($type),
                        $db->quote($element),
                        $db->quote($folder),
                        $db->quote('0'),
                        $db->quote('1'),
                        $db->quote('1'),
                        $db->quote('0'),
                        $db->quote($cache),
                        $db->quote($params)
                    ])
            );

    } else {
        $query->clear()
            ->update($db->quoteName('#__extensions'))
            ->set([
                $db->quoteName('name') . '=' . $db->quote($name),
                $db->quoteName('type') . '=' . $db->quote($type),
                $db->quoteName('element') . '=' . $db->quote($element),
                $db->quoteName('folder') . '=' . $db->quote($folder),
                $db->quoteName('client_id') . '=' . $db->quote('0'),
                $db->quoteName('enabled') . '=' . $db->quote('1'),
                $db->quoteName('access') . '=' . $db->quote('1'),
                $db->quoteName('protected') . '=' . $db->quote('0'),
                $db->quoteName('manifest_cache') . '=' . $db->quote($cache),
                $db->quoteName('params') . '=' . $db->quote($params)
            ])
            ->where($db->quoteName('extension_id') . '=' . $db->quote($id));
    }

    $db->setQuery($query);
    $db->execute();
}

$addon = \JSFactory::getTable('addon', 'jshop');
$addon->loadAlias($element);
$addon->set('name', $name);
$addon->set('version', $version);
$addon->set('uninstall', '/components/com_jshopping/addons/' . $element . '/uninstall.php');
$addon->store();
