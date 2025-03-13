# WT Custom menu item banner Joomla plugin
Custom banners for Joomla menu items like Louis Vuitton website. Responsive images and videos. For output, use the sample code.
### Demo video (Russian language)
[![](https://img.youtube.com/vi/9n15dZML-Qc/0.jpg)](https://www.youtube.com/watch?v=9n15dZML-Qc)
## Please note!
Plugin - does not provide a 1 click solution. You make up the banner output yourself! Carefully study the sample code below.
# Plugin features
- Responsive both images and videos is supported. For desktops (desktops, laptops, TV), specify the image in horizontal orientation. For mobile devices - vertical.
- You can specify any number of breakpoints (ranges of screen resolutions for which a particular image or video is used). The CSS syntax of @media queries is used.
- Lazy loading of video files for adaptive video banners. The Js script checks whether the specified videos correspond to the screen parameters and loads the desired one.
# Preparing images for image banners
You can specify either one image for the banner, or several different ones. The selection of image sizes for each resolution should be done by the designer. Not necessarily these will be versions of the same image. 
As an example, for desktop screen resolutions, you can use a horizontal banner with the size of 2880x1200 (1440x600) for FullHD monitors and higher (the ratio is 12:5).
For mobile devices, the size of images in vertical orientation can be 600x750 (the proportion 4:5).
It is preferable to use modern image formats - WebP and others.
# Preparation of video files for image banners
One of the most common video file formats is - mp4. It is better to use typical proportions for videos (as on YouTube, for example): 1280x720, 1920x1080 for horizontal videos. For mobile devices, a square aspect ratio is suitable, or, for example, 450x800 - (the proportion of 4:5).
The recommended video duration for desktops is up to 10-15 seconds.
The recommended video duration for mobile devices is up to 7-10 seconds.
Please note that it is not recommended to make the file size larger than 10 MB for desktop versions of videos and larger than 2 MB for mobile devices. This is regulated by the bitrate settings and the frame rate (25 frames per second, the bitrate is selected experimentally).

The file size is especially important for mobile devices, as this is due to the peculiarity of the network equipment.Even if your tariff has an Internet speed limit - the first approximately 1.5-2 megabytes of data are downloaded at full speed, then the tariff limit starts working and the site resources exceeding this volume are downloaded at the speed of your tariff. 

# Sample code for displaying custom banners of menu items on the Joomla website
The necessary explanations are given in the comments in the code. It is assumed that you know HTML, CSS and the basics of PHP to read and understand the code. This code example uses the Bootstrap 5 CSS framework. You can use this code directly in index.php of your template or create your own layout for the HTML code type module in the modules/mod_custom/tmpl folder (copy the default.php file, rename, insert code into it, save in the same folder and select this layout in the module settings).
```
<?php

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * @var \Joomla\CMS\Factory::getApplication $app
 */
$app = Factory::getApplication();
/**
 * @var Joomla\CMS\WebAsset\WebAssetManager $wa
 */
$wa = $app->getDocument()->getWebAssetManager();
/**
 * Parameters of the current menu item.
 * The paths to images, videos, etc. are saved in the parameters.
 */
$menu = $app->getMenu()->getActive();
/**
 * Getting the banner parameters
 */
$wt_custom_menu_item_banner = $menu->getParams()->get('wt_custom_menu_item_banner');
/**
 * If there are parameters, output the following code
 */
$wt_custom_menu_item_banner = $menu->getParams()->get('wt_custom_menu_item_banner');
/**
 * The "Use banner" button from menu item params
 */
$useBanner = $wt_custom_menu_item_banner->use_banner ?? false;
if($wt_custom_menu_item_banner && $useBanner)
{
    echo \Joomla\CMS\Layout\LayoutHelper::render(
        ($wt_custom_menu_item_banner?->pluginlayout ?? 'default'),
        [
            'wt_custom_menu_item_banner' => $wt_custom_menu_item_banner,
            'menu' => $menu,
            'useBanner' => $useBanner,
        ],
        JPATH_SITE.'/plugins/system/wt_custom_menu_item_banner/tmpl'
    );
}
```