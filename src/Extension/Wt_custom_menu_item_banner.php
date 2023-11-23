<?php
/**
 * @package       WT SEO Meta templates
 * @version       1.1.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @copyright     Copyright (C) 2023 Sergey Tolkachyov
 * @license       GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

// No direct access
namespace Joomla\Plugin\System\Wt_custom_menu_item_banner\Extension;
defined('_JEXEC') or die;

use Joomla\CMS\Form\Form;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\Event\SubscriberInterface;

class Wt_custom_menu_item_banner extends CMSPlugin implements SubscriberInterface
{
	protected $autoloadLanguage = true;
	protected $allowLegacyListeners = false;

	/**
	 *
	 * @return array
	 *
	 * @throws \Exception
	 * @since 4.1.0
	 *
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onContentPrepareForm' => 'onContentPrepareForm',
			'onBeforeCompileHead' => 'onBeforeCompileHead',
		];
	}

	public function onContentPrepareForm($event) : void
	{
		$form = $event->getAttribute(0);
		$data = $event->getAttribute(1);

		$app = $this->getApplication();

		if (!$app->isClient('administrator'))
		{
			return;
		}
		if ($app->getInput()->get('option') !== 'com_menus')
		{
			return;
		}
		if (!($form instanceof Form))
		{
			return;
		}
		Form::addFormPath(JPATH_SITE . '/plugins/system/wt_custom_menu_item_banner/src/Subform');
		$form->loadFile('fields', false);

		$lang      = $app->getLanguage();
		$extension = 'plg_system_wt_custom_menu_item_banner';
		$base_dir  = JPATH_ADMINISTRATOR;
		$reload    = true;
		$lang->load($extension, $base_dir, $lang->getTag(), $reload);
	}

	/**
	 * Add a responsive videos data to Joomla script options for frontend
	 *
	 * @throws \Exception
	 * @since 1.0.0
	 */
	public function onBeforeCompileHead($event) : void
	{
		$app = $this->getApplication();
		if (!($app->isClient('site')))
		{
			return;
		}
		$doc = $app->getDocument();
		// We are work only in HTML, not JSON, RSS etc.
		if (!($doc instanceof \Joomla\CMS\Document\HtmlDocument))
		{
			return;
		}
		$wt_custom_menu_item_banner = $app->getMenu()->getActive()->getParams()->get('wt_custom_menu_item_banner');
		if (!$wt_custom_menu_item_banner)
		{
			return;
		}
		/**
		 * Use js script only for responsive videos
		 */
		if ($wt_custom_menu_item_banner->media_type == 'video' && $wt_custom_menu_item_banner->is_responsive_videos == 1)
		{
			$doc->addScriptOptions('wt_custom_menu_item_banner_responsive_videos', $wt_custom_menu_item_banner->responsive_videos);
			$doc->getWebAssetManager()->useScript('core')
				->registerAndUseScript('wt_custom_menu_item_banner.responsive_videos', 'plg_system_wt_custom_menu_item_banner/responsive_videos.js', ['relative' => true, 'version' => 'auto']);
		}
	}
}
