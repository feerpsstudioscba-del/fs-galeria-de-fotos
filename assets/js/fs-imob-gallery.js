(function () {
	'use strict';

	var lightbox;
	var state = {
		gallery: null,
		images: [],
		index: 0
	};

	function ready(callback) {
		if (document.readyState !== 'loading') {
			callback();
			return;
		}
		document.addEventListener('DOMContentLoaded', callback);
	}

	function ensureLightbox() {
		if (lightbox) {
			return lightbox;
		}

		lightbox = document.createElement('div');
		lightbox.className = 'fs-imob-gallery-lightbox';
		lightbox.setAttribute('role', 'dialog');
		lightbox.setAttribute('aria-modal', 'true');
		lightbox.innerHTML = [
			'<button class="fs-imob-gallery-lightbox__button fs-imob-gallery-lightbox__close" type="button" aria-label="Fechar">×</button>',
			'<span class="fs-imob-gallery-lightbox__count" aria-live="polite"></span>',
			'<button class="fs-imob-gallery-lightbox__button fs-imob-gallery-lightbox__prev" type="button" aria-label="Imagem anterior">‹</button>',
			'<div class="fs-imob-gallery-lightbox__stage">',
			'<figure class="fs-imob-gallery-lightbox__figure">',
			'<img class="fs-imob-gallery-lightbox__image" alt="">',
			'</figure>',
			'</div>',
			'<button class="fs-imob-gallery-lightbox__button fs-imob-gallery-lightbox__next" type="button" aria-label="Proxima imagem">›</button>'
		].join('');

		document.body.appendChild(lightbox);
		lightbox.querySelector('.fs-imob-gallery-lightbox__close').addEventListener('click', close);
		lightbox.querySelector('.fs-imob-gallery-lightbox__prev').addEventListener('click', function () {
			move(-1);
		});
		lightbox.querySelector('.fs-imob-gallery-lightbox__next').addEventListener('click', function () {
			move(1);
		});
		lightbox.addEventListener('click', function (event) {
			if (event.target === lightbox || event.target.classList.contains('fs-imob-gallery-lightbox__stage')) {
				close();
			}
		});
		document.addEventListener('keydown', onKeyDown);

		return lightbox;
	}

	function onKeyDown(event) {
		if (!lightbox || !lightbox.classList.contains('is-open')) {
			return;
		}

		if (event.key === 'Escape') {
			close();
		} else if (event.key === 'ArrowLeft') {
			move(-1);
		} else if (event.key === 'ArrowRight') {
			move(1);
		}
	}

	function open(gallery, index) {
		var data = gallery.querySelector('.fs-imob-gallery__data');
		if (!data) {
			return;
		}

		try {
			state.images = JSON.parse(data.textContent || '[]');
		} catch (error) {
			state.images = [];
		}

		if (!state.images.length) {
			return;
		}

		state.gallery = gallery;
		state.index = index;
		ensureLightbox().classList.add('is-open');
		document.documentElement.style.overflow = 'hidden';
		render();
	}

	function close() {
		if (!lightbox) {
			return;
		}

		lightbox.classList.remove('is-open');
		document.documentElement.style.overflow = '';
	}

	function move(delta) {
		if (!state.images.length) {
			return;
		}

		state.index = (state.index + delta + state.images.length) % state.images.length;
		render();
	}

	function render() {
		var item = state.images[state.index];
		var image = lightbox.querySelector('.fs-imob-gallery-lightbox__image');
		var figure = lightbox.querySelector('.fs-imob-gallery-lightbox__figure');
		var count = lightbox.querySelector('.fs-imob-gallery-lightbox__count');
		var oldWatermark = figure.querySelector('.fs-imob-gallery-lightbox__watermark');

		if (oldWatermark) {
			oldWatermark.remove();
		}

		image.src = item.full || item.thumb || '';
		image.alt = item.alt || '';
		count.textContent = (state.index + 1) + '/' + state.images.length;

		if (state.gallery.dataset.watermark === 'yes' && state.gallery.dataset.wmSrc) {
			figure.appendChild(createWatermark(state.gallery));
		}
	}

	function createWatermark(gallery) {
		var wrapper = document.createElement('span');
		var image = document.createElement('img');

		wrapper.className = 'fs-imob-gallery-lightbox__watermark';
		wrapper.style.opacity = gallery.dataset.wmOpacity || '0.35';
		wrapper.style.width = gallery.dataset.wmWidth || '180px';
		wrapper.style.maxWidth = gallery.dataset.wmMax || '40%';
		wrapper.style.mixBlendMode = gallery.dataset.wmBlend || 'normal';
		wrapper.style.zIndex = gallery.dataset.wmZ || '2';
		wrapper.style.transform = 'translate(-50%, -50%) rotate(' + (gallery.dataset.wmRotate || '0') + 'deg)';
		wrapper.setAttribute('aria-hidden', 'true');

		image.src = gallery.dataset.wmSrc;
		image.alt = gallery.dataset.wmAlt || '';
		wrapper.appendChild(image);

		return wrapper;
	}

	function bindGallery(gallery) {
		if (gallery.dataset.fsGalleryBound === 'yes') {
			return;
		}

		gallery.dataset.fsGalleryBound = 'yes';
		gallery.addEventListener('click', function (event) {
			var item = event.target.closest('.fs-imob-gallery__item');
			if (!item || gallery.dataset.lightbox !== 'yes') {
				return;
			}

			event.preventDefault();
			open(gallery, parseInt(item.dataset.index || '0', 10));
		});
	}

	function init(context) {
		var root = context || document;
		root.querySelectorAll('.fs-imob-gallery').forEach(bindGallery);
	}

	ready(function () {
		init(document);
	});

	function registerElementorHook() {
		if (!window.elementorFrontend || !window.elementorFrontend.hooks) {
			return;
		}

		window.elementorFrontend.hooks.addAction('frontend/element_ready/fs_imob_gallery.default', function ($scope) {
			init($scope[0]);
		});
	}

	registerElementorHook();

	if (window.jQuery) {
		window.jQuery(window).on('elementor/frontend/init', registerElementorHook);
	}
}());
