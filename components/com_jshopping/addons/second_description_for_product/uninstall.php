<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;

defined('_JEXEC') or die('Restricted access');

$db = Factory::getContainer()->get('DatabaseDriver');
$query = $db->getQuery(true);

$query->delete('#__extensions')
    ->where($db->quoteName('element').' = '.$db->quote('second_description_for_product'))
    ->where($db->quoteName('folder').' = '.$db->quote('jshoppingadmin'))
    ->where($db->quoteName('type').' = '.$db->quote('plugin'));
$db->setQuery($query)->execute();

$query->clear()->delete('#__extensions')
    ->where($db->quoteName('element').' = '.$db->quote('second_description_for_product'))
    ->where($db->quoteName('folder').' = '.$db->quote('jshoppingproducts'))
    ->where($db->quoteName('type').' = '.$db->quote('plugin'));

$db->setQuery($query)->execute();

$folders = [
    'plugins/jshoppingproducts/second_description_for_product/',
    'plugins/jshoppingadmin/second_description_for_product/',
    'components/com_jshopping/addons/second_description_for_product/'
];
foreach ($folders as $folder)
{
    Folder::delete(JPATH_ROOT . '/' . $folder);
}

Factory::getApplication()->enqueueMessage('Addon second description for product successfully uninstalled','success');