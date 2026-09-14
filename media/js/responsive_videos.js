/**
 * @package    System - WT Custom menu item banner
 * @version    1.3.0
 * @Author     Sergey Tolkachyov, https://web-tolk.ru
 * @copyright  Copyright (c) 2022 - 2026 Sergey Tolkachyov. All rights reserved.
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @since      1.0.0
 */
(function () {
    'use strict';

    const initResponsiveVideo = function () {
        const responsiveVideos = Joomla.getOptions('wt_custom_menu_item_banner_responsive_videos');
        const video = document.querySelector('#wt-custom-menu-item-banner-responsive-video');

        if (!responsiveVideos || !video) {
            return;
        }

        const poster = document.querySelector('#wt-custom-menu-item-banner-responsive-video-poster');
        const posterImage = poster ? poster.querySelector('img') : null;
        const candidates = Object.values(responsiveVideos);
        const selectedVideo = candidates.find(function (candidate) {
            if (!candidate || !candidate.video) {
                return false;
            }

            if (!candidate.media_query) {
                return true;
            }

            try {
                return window.matchMedia(candidate.media_query).matches;
            } catch (error) {
                return false;
            }
        });

        if (!selectedVideo) {
            return;
        }

        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const saveData = navigator.connection && navigator.connection.saveData;

        if (reducedMotion || saveData) {
            return;
        }

        const resolveMediaUrl = function (url) {
            const cleanUrl = String(url).split('#joomlaImage:')[0];

            if (/^(?:[a-z][a-z0-9+.-]*:)?\/\//i.test(cleanUrl) || cleanUrl.charAt(0) === '/') {
                return cleanUrl;
            }

            const paths = Joomla.getOptions('system.paths') || {};
            const root = String(paths.root || '').replace(/\/$/, '');

            return root + '/' + cleanUrl.replace(/^\//, '');
        };

        let posterRevealed = false;

        const revealVideo = function () {
            if (!poster || posterRevealed) {
                return;
            }

            posterRevealed = true;
            poster.style.pointerEvents = 'none';

            if (typeof poster.animate !== 'function') {
                poster.style.opacity = '0';
                poster.style.visibility = 'hidden';
                return;
            }

            const fade = poster.animate(
                [{opacity: 1}, {opacity: 0}],
                {duration: 200, easing: 'ease', fill: 'forwards'}
            );

            fade.finished.then(function () {
                poster.style.opacity = '0';
                poster.style.visibility = 'hidden';
            }).catch(function () {
                poster.style.opacity = '0';
                poster.style.visibility = 'hidden';
            });
        };

        video.addEventListener('playing', function () {
            if (typeof video.requestVideoFrameCallback === 'function') {
                video.requestVideoFrameCallback(revealVideo);
                return;
            }

            window.requestAnimationFrame(revealVideo);
        }, {once: true});

        const loadVideo = function () {
            if (video.dataset.responsiveVideoLoading === 'true') {
                return;
            }

            video.dataset.responsiveVideoLoading = 'true';
            video.src = resolveMediaUrl(selectedVideo.video);
            video.load();

            const playPromise = video.play();

            if (playPromise) {
                playPromise.catch(function () {
                    delete video.dataset.responsiveVideoLoading;
                });
            }
        };

        if (!posterImage) {
            loadVideo();
            return;
        }

        const loadVideoAfterPoster = function () {
            if (typeof posterImage.decode !== 'function') {
                loadVideo();
                return;
            }

            posterImage.decode().catch(function () {
                return null;
            }).then(loadVideo);
        };

        if (posterImage.complete) {
            loadVideoAfterPoster();
            return;
        }

        posterImage.addEventListener('load', loadVideoAfterPoster, {once: true});
        posterImage.addEventListener('error', loadVideo, {once: true});
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initResponsiveVideo, {once: true});
    } else {
        initResponsiveVideo();
    }
}());
