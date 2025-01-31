/**
 * @package    System - WT Custom menu item banner
 * @version       1.2.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @copyright     Copyright (C) 2023 Sergey Tolkachyov
 * @license       GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */
document.addEventListener('DOMContentLoaded', function () {
	let wt_custom_menu_item_banner_responsive_videos = Joomla.getOptions('wt_custom_menu_item_banner_responsive_videos');
	if (wt_custom_menu_item_banner_responsive_videos) {
		if (document.querySelector("#wt-custom-menu-item-banner-responsive-video")) {
			let video_selector = document.querySelector("#wt-custom-menu-item-banner-responsive-video");

			for (var video_data in wt_custom_menu_item_banner_responsive_videos) {
				console.log(video_data);
				let link_video_poster = wt_custom_menu_item_banner_responsive_videos[video_data].link_video_poster;
				let media_query = wt_custom_menu_item_banner_responsive_videos[video_data].media_query;
				let video_src = wt_custom_menu_item_banner_responsive_videos[video_data].video;

				if (video_selector && window.matchMedia(media_query).matches) {
					console.log(video_src);
					video_selector.setAttribute('poster', link_video_poster);
					video_selector.setAttribute('src', video_src);
				}
			}
		}
	} else {
		console.error('WT Custom menu item banner: there is no wt_custom_menu_item_banner_responsive_videos object with responsive videos data or Joomla core.js are not present');
	}
});