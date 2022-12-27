document.addEventListener('DOMContentLoaded', function () {
	let wt_quick_links_responsive_videos = Joomla.getOptions('wt_quick_links_responsive_videos');
	let wt_extension = Joomla.getOptions('mod_wt_quick_links');
	console.group(wt_extension.name);
	console.log('%cYou are using ' + wt_extension.name + ' Joomla ' + wt_extension.type + ' from ' + wt_extension.authorUrl + ' v.' + wt_extension.version + ' by ' + wt_extension.author + '. Creation date ' + wt_extension.creationDate, 'background-color:#0FA2E6;padding:7px;color:#fff;font-size:0.9rem');
	if (wt_quick_links_responsive_videos) {
		for (var module_id in wt_quick_links_responsive_videos) {
			if (document.querySelector("[data-wt-quick-links-responsive-videos= '" + module_id + "']")) {
				for (var video_number in wt_quick_links_responsive_videos[module_id]) {
					let video_selector = document.querySelector("#wt-quick-links-responsive-videos-" + module_id + "-" + video_number);

					for (var video_data in wt_quick_links_responsive_videos[module_id][video_number]) {
						let link_video_poster = wt_quick_links_responsive_videos[module_id][video_number][video_data].link_video_poster;
						let media_query = wt_quick_links_responsive_videos[module_id][video_number][video_data].media_query;
						let video_src = wt_quick_links_responsive_videos[module_id][video_number][video_data].video;
						if (video_selector && window.matchMedia(media_query).matches) {
							video_selector.setAttribute('poster', link_video_poster);
							video_selector.setAttribute('src', video_src);
						}
					}
				}
			}
		}
	} else {
		console.error('WT Quick links module: there is no wt_quick_links_responsive_videos object with responsive videos data or Joomla core.js are not present');
	}
	console.groupEnd();
});