<?php
/**
 * @package    System - WT Custom menu item banner
 * @version       1.2.2
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @copyright     Copyright (C) 2022-2024 Sergey Tolkachyov
 * @license       GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

use Joomla\CMS\HTML\HTMLHelper;

extract($displayData);
/**
 * @var object $wt_custom_menu_item_banner
 * @var object $menu
 * @var boolean $useBanner
 */


if ((($wt_custom_menu_item_banner->media_type == 'image' && !empty($wt_custom_menu_item_banner->link_image)) || $wt_custom_menu_item_banner->media_type == 'video' && (!empty($wt_custom_menu_item_banner->link_video) || count((array) $wt_custom_menu_item_banner->responsive_videos) > 0))) :?>
    <div class="card mx-2 overflow-hidden wt_custom_menu_item_banner">
        <?php
        /**
         * Тип баннера - изображения
         */
        if ($wt_custom_menu_item_banner->media_type == 'image'):
            /**
             * Use HTML5 picture tag for responsive images
             */
            if ($wt_custom_menu_item_banner->responsive_images && count((array) $wt_custom_menu_item_banner->responsive_images) > 0) : ?>

                <picture>
                <?php

                foreach ($wt_custom_menu_item_banner->responsive_images as $responsive_image):
                    $clean_image_path = HTMLHelper::cleanImageURL($responsive_image->image);
                    $clean_image_path = $clean_image_path->url;

                    ?>
                    <source srcset="<?php echo $clean_image_path; ?>"
                            media="<?php echo $responsive_image->media_query; ?>">

                <?php endforeach; ?>
            <?php endif; ?>
            <?php

            $clean_image_path                        = HTMLHelper::cleanImageURL($wt_custom_menu_item_banner->link_image);
            $clean_image_path->attributes['class']   = 'card-img w-100 h-auto';
            $clean_image_path->attributes['loading'] = 'lazy';
            $link_text                               = (!empty($wt_custom_menu_item_banner->link_text)) ? $wt_custom_menu_item_banner->link_text : '';
            echo HTMLHelper::image($clean_image_path->url, $link_text, $clean_image_path->attributes);
            // Use HTML5 picture tag for responsive images - Close picture tag
            if ($wt_custom_menu_item_banner->responsive_images && count((array) $wt_custom_menu_item_banner->responsive_images) > 0) : ?>
                </picture>

            <?php endif; ?>
        <?php elseif ($wt_custom_menu_item_banner->media_type == 'video'):
            /**
             * Banner type - video.
             * <video> tag must have id="wt-custom-menu-item-banner-responsive-video"
             * js-script works with it for video lazy loading
             *
             * You can modify CSS and other HTML as you want
             */
            ?>

            <?php if ($wt_custom_menu_item_banner->is_responsive_videos == 1) :
            /**
             * Responsive videos are enabled. That's why
             * poster and src attributes are empty. They will be filled by js script.
             */

            ?>
            <video id="wt-custom-menu-item-banner-responsive-video"
                   poster="/"
                   src="/"
                   class="card-img"
                   autoplay="autoplay"
                   muted="muted" loop="loop">
            </video>
        <?php else :
            /**
             * Responsive videos are disabled. Just render <video>.
             */
            ?>
            <video id="wt-custom-menu-item-banner-responsive-video" <?php echo($wt_custom_menu_item_banner->link_video_poster ? 'poster="' . $wt_custom_menu_item_banner->link_video_poster . '"' : ''); ?>
                   src="/<?php echo $wt_custom_menu_item_banner->link_video; ?>" class="card-img rounded-0"
                   autoplay="autoplay"
                   muted="muted" loop="loop">
            </video>
        <?php endif; ?>

        <?php endif; ?>
        <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center">
            <?php

            /**
             * Banner header output. You are free to change this code to suit your needs and objectives.
             * If the menu item settings use
             * "Page - Title on the page" - Show
             * if the "Page title" is specified, it is displayed.
             * If not specified, we display the name of the menu item.
             */

            if ($menu->getParams()->get('show_page_heading') == 1) : ?>
                <?php
                if (!empty($menu->getParams()->get('page_heading')))
                {
                    $h1 = $menu->getParams()->get('page_heading');
                }
                else
                {
                    $h1 = $menu->title;
                }
                ?>
                <h1 class="display-1 fw-bold text-center"><?php echo $h1; ?></h1>
            <?php endif; ?>
            <?php if (!empty($wt_custom_menu_item_banner->banner_text)):
                /**
                 * @var $wt_custom_menu_item_banner ->banner_text
                 *                                  Here we display additional text for the banner, where you can
                 *                                  write slogans, subheadings, etc.
                 */

                ?>
                <p class="text-center banner-text"><?php echo $wt_custom_menu_item_banner->banner_text; ?></p>
            <?php endif; ?>
        </div>
    </div>

<?php endif; // if $wt_custom_menu_item_banner ?>
