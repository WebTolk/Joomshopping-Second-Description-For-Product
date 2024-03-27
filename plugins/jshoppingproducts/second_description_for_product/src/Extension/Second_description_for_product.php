<?php

namespace Joomla\Plugin\Jshoppingproducts\Second_description_for_product\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;

defined('_JEXEC') or die('Restricted access');

final class Second_description_for_product extends CMSPlugin implements SubscriberInterface
{

	public string $separator = '{second_description}';

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
			'onBeforeDisplayProduct' => 'onBeforeDisplayProduct',
		];
	}

	function onBeforeDisplayProduct(Event $event): void
	{

		/**
		 * @var   $product           object
		 * @var   $view              object
		 * @var   $product_images    array
		 * @var   $product_videos    array
		 * @var   $product_demofiles array
		 */
		[$product, $view, $product_images, $product_videos, $product_demofiles] = array_values($event->getArguments());

        if(empty($product->description))
        {
            return;
        }

        if (!str_contains($product->description, $this->separator))
        {
            return;
        }

		$tmp = explode($this->separator, $product->description);

		if (array_key_exists(1, $tmp) && !empty($tmp[1]))
		{
			$product_tmp_var = $this->params->get('product_tmp_var', '_tmp_product_html_after_buttons');
			if ($product_tmp_var == 'custom_position')
			{
				$product_tmp_var = $this->params->get('custom_position', '_tmp_product_html_after_buttons');
			}

			$product->description        = $tmp[0];
			$product->second_description = $tmp[1];

			if (property_exists($view, $product_tmp_var) && !empty($view->$product_tmp_var))
			{
				$view->$product_tmp_var .= '<div class="second_description">' . $product->second_description . '</div>';
			}
			else
			{
				$view->$product_tmp_var = '<div class="second_description">' . $product->second_description . '</div>';
			}

			$event->setArgument(1, $view);
		} else {
			$product->description = str_replace($this->separator,'', $product->description);
			$event->setArgument(0, $product);
		}
	}
}