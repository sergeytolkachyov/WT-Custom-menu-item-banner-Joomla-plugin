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
if ($wt_custom_menu_item_banner) :?>
	<?php

	/**
	 * These css can be moved to a css file and not specified here.
	 * In this case, this css code will be output inline
	 */
	$wt_custom_menu_item_banner_css = '
		.wt_custom_menu_item_banner {
		position: absolute;top: 30%;left: 50%;z-index: 2;color: #fff;-webkit-transform: translateX(-50%) translateY(-50%);transform: translateX(-50%) translateY(-50%);pointer-events: none;
		}
		';
	$wa->addInlineStyle($wt_custom_menu_item_banner_css);

	?>

	<section class="container-fluid container-xxl">
		<div class="row">
			<div class="col-12 p-0 position-relative">
				<div class="position-absolute bg-dark opacity-50 w-100 h-100" style="z-index: 1;"></div>
				<div class="wt_custom_menu_item_banner d-flex flex-column align-items-center">

					<img src="media/templates/site/webtolk/images/logo.svg" width="300" height="100"
						 alt="YOUR LOGO IMAGE ALT"/>
					<?php

					/**
					 * Banner header output. You are free to change this code to suit your needs and tasks.
					 * If the menu item settings use
					 * "Page - Title on page" - Show
					 * if the "Page title" is specified, then it is displayed.
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
						<h1 class="text-white text-uppercase fw-bold text-center">(<?php echo $h1; ?>)</h1>
					<?php endif; ?>
				</div>
				<?php
				/**
				 * Banner image type
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
					$clean_image_path->attributes['class']   = 'w-100 h-auto';
					$clean_image_path->attributes['loading'] = 'lazy';
					echo HTMLHelper::image($clean_image_path->url, $wt_custom_menu_item_banner->link_text, $clean_image_path->attributes);
					// Use HTML5 picture tag for responsive images - Close picture tag
					if ($wt_custom_menu_item_banner->responsive_images && count((array) $wt_custom_menu_item_banner->responsive_images) > 0) : ?>
						</picture>

					<?php endif; ?>
				<?php elseif ($wt_custom_menu_item_banner->media_type == 'video'):
					/**
					 * Banner type - video.
					 * The video tag must have id="wt-custom-menu-item-banner-responsive-video"
					 * The js script for lazy file loading is guided by it
					 *
					 * CSS classes and other HTML code you are free to change to suit your needs and tasks
					 */
					?>

					<?php if ($wt_custom_menu_item_banner->is_responsive_videos == 1) :
					/**
					 * Adaptive videos are included. Therefore attributes
					 * poster and src are empty. They are filled with a js script.
					 */

					?>
					<video id="wt-custom-menu-item-banner-responsive-video"
						   poster=""
						   src=""
						   class="card-img"
						   autoplay="autoplay"
						   muted="muted" loop="loop" style="border-radius: 0rem;">
					</video>
				<?php else :
					/**
					 * Responsive videos are not used. We output the video immediately.
					 */
					?>
					<video id="wt-custom-menu-item-banner-responsive-video" <?php echo($wt_custom_menu_item_banner->link_video_poster ? 'poster="' . $wt_custom_menu_item_banner->link_video_poster . '"' : ''); ?>
						   src="<?php echo $wt_custom_menu_item_banner->link_video; ?>" class="card-img"
						   autoplay="autoplay"
						   muted="muted" loop="loop" style="border-radius: 0rem;">
					</video>
				<?php endif; ?>

				<?php endif; ?>
				<?php if (!empty($wt_custom_menu_item_banner->banner_text)):
					/**
					 * @var $wt_custom_menu_item_banner- >banner_text
					 * Here we output additional text for the banner, where you can
					 * write slogans, subheadings, etc.
					 */

					?>
					<div class="position-absolute bottom-0 left-0 col-12 col-md-6 ps-3 pb-3" style="z-index: 2;">
						<p class="h2 text-white"><?php echo $wt_custom_menu_item_banner->banner_text; ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; // if count($wt_custom_menu_item_banner) > 0	?>
```
