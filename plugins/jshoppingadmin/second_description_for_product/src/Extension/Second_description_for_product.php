<?php

namespace Joomla\Plugin\Jshoppingadmin\Second_description_for_product\Extension;

use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

final class Second_description_for_product extends CMSPlugin implements SubscriberInterface
{

	public string $separator = "{second_description}";

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   4.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onBeforeDisplaySaveProduct'     => 'onBeforeDisplaySaveProduct',
			'onBeforeDisplayEditProduct'     => 'onBeforeDisplayEditProduct',
			'onBeforeDisplayEditProductView' => 'onBeforeDisplayEditProductView',
		];
	}

	/**
	 * @param   Event  $event
	 *
	 * @return void
	 * @since 1.0.0
	 * @see   \Joomla\Component\Jshopping\Administrator\Model\ProductsModel::save
	 */
	function onBeforeDisplaySaveProduct(Event $event): void
	{
		/**
		 * @var $post
		 * @var $product
		 */
		[$post, $product] = array_values($event->getArguments());

		$_lang     = \JSFactory::getModel('Languages', 'JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
		foreach ($languages as $lang)
		{
			$post['description_' . $lang->language] .= $this->separator . $this->getApplication()->getInput()->get('second_description' . $lang->id, '', 'raw');
		}

		$event->setArgument(0, $post);
	}

	/**
	 * @param   Event  $event
	 *
	 * @return void
	 * @since 1.0.0
	 * @see   \Joomla\Component\Jshopping\Administrator\Controller\ProductsController::edit
	 */
	function onBeforeDisplayEditProduct(Event $event): void
	{
		[$product, $related_products, $lists, $listfreeattributes, $tax_value] = array_values($event->getArguments());

		$_lang     = \JSFactory::getModel('Languages', 'JshoppingModel');
		$languages = $_lang->getAllLanguages(1);

		foreach ($languages as $lang)
		{
			$product->{'second_description_' . $lang->language} = '';

			$tmp = explode($this->separator, $product->{'description_' . $lang->language});
			if (isset($tmp[1]))
			{
				$product->{'description_' . $lang->language}        = $tmp[0];
				$product->{'second_description_' . $lang->language} = $tmp[1];
			}
		}

		$event->setArgument(0, $product);
	}

	/**
	 * @param   Event  $event
	 *
	 * @return void
	 * @since 1.0.0
	 * @see   \Joomla\Component\Jshopping\Administrator\Controller\ProductsController::edit
	 */
	function onBeforeDisplayEditProductView(Event $event): void
	{
		/**
		 * @param $view object View product_edit object
		 */
		[$view] = array_values($event->getArguments());

		$_lang     = \JSFactory::getModel('Languages', 'JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
		foreach ($languages as $lang)
		{
			if (isset($view->product->{'second_description_' . $lang->language}))
			{
				if (!isset($view->{'plugin_template_description_' . $lang->language})) $view->{'plugin_template_description_' . $lang->language} = '';

				$user                                                     = $this->getApplication()->getIdentity();
				$editor                                                   = Editor::getInstance($user->getParam('editor', Factory::getContainer()->get('config')->get('editor')));
				$second_description                                       = "second_description_" . $lang->language;
				$view->{'plugin_template_description_' . $lang->language} .= '<tr><td class="key">Second ' . Text::_('JSHOP_DESCRIPTION') . '</td><td>' . $editor->display('second_description' . $lang->id, $view->product->$second_description, '100%', '350', '75', '20') . '</td></tr>';
			}
		}

		$event->setArgument(0, $view);
	}
}