(function () {
	'use strict';

	if (!window.gsap) return;

	gsap.registerPlugin(ScrollTrigger, SplitText);

	var config = window.escargotConfig || {};
	var MOBILE = config.mobileBreakpoint || 1000;
	var lenis = null;

	/* -----------------------------------------------------------------------
	 * Lenis smooth scroll
	 * --------------------------------------------------------------------- */
	function initLenis() {
		if (!window.Lenis) return;

		var easing = function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); };

		function create() {
			var isMobile = window.innerWidth <= MOBILE;
			return new Lenis({
				easing: easing,
				duration: isMobile ? 0.8 : 1.2,
				smooth: true,
				smoothWheel: true,
				syncTouch: true,
				wheelMultiplier: 1,
				orientation: 'vertical',
				smoothTouch: isMobile,
				touchMultiplier: isMobile ? 1.5 : 2,
				lerp: isMobile ? 0.09 : 0.1,
				infinite: false
			});
		}

		lenis = create();

		function raf(time) {
			lenis.raf(time);
			requestAnimationFrame(raf);
		}
		requestAnimationFrame(raf);

		window.__escargotLenis = lenis;
	}

	/* -----------------------------------------------------------------------
	 * Copy / SplitText reveal
	 * --------------------------------------------------------------------- */
	function initCopy() {
		var requiredFonts = ['Host Grotesk', 'DM Mono', 'Playfair Display'];

		function waitForFonts() {
			return new Promise(function (resolve) {
				if (document.fonts && document.fonts.ready) {
					document.fonts.ready.then(function () {
						requiredFonts.forEach(function (f) {
							try { document.fonts.check('16px "' + f + '"'); } catch (e) {}
						});
						setTimeout(resolve, 100);
					}).catch(function () {
						setTimeout(resolve, 200);
					});
				} else {
					setTimeout(resolve, 200);
				}
			});
		}

		document.querySelectorAll('[data-copy]').forEach(function (el) {
			build(el);
		});

		function build(el) {
			var type = el.getAttribute('data-type') || 'lines';
			var animate = el.getAttribute('data-animate') || 'scroll';
			var delay = parseFloat(el.getAttribute('data-delay') || '0') || 0;
			var trigger = el.getAttribute('data-trigger') || null;
			var start = el.getAttribute('data-start') || 'top 80%';

			waitForFonts().then(function () {
				if (!document.body.contains(el)) return;

				var childEls = Array.prototype.slice.call(el.children);
				var targets = childEls.length ? childEls : [el];
				var isWords = type === 'words';

				var units = [];
				targets.forEach(function (element) {
					var split = isWords
						? SplitText.create(element, { type: 'words', mask: 'words', wordsClass: 'word' })
						: SplitText.create(element, { type: 'lines', mask: 'lines', linesClass: 'line', lineThreshold: 0.1 });

					var u = isWords ? split.words : split.lines;

					var cs = window.getComputedStyle(element);
					var indent = cs.textIndent;
					if (indent && indent !== '0px' && u.length) {
						u[0].style.paddingLeft = indent;
						element.style.textIndent = '0';
					}

					units = units.concat(u);
				});

				gsap.set(units, { y: '110%' });

				var reveal = gsap.to(units, {
					y: '0%',
					duration: 1,
					ease: 'power4.out',
					stagger: 0.1,
					delay: delay,
					paused: true
				});

				if (animate === 'false') {
					reveal.play();
				} else if (animate.indexOf('event:') === 0) {
					window.addEventListener(animate.slice(6), function () { reveal.play(); }, { once: true });
				} else {
					var triggerEl = trigger ? (document.querySelector(trigger) || el) : el;
					ScrollTrigger.create({
						trigger: triggerEl,
						start: start,
						animation: reveal,
						once: true,
						refreshPriority: -1
					});
				}
			});
		}
	}

	/* -----------------------------------------------------------------------
	 * Slide button hover
	 * --------------------------------------------------------------------- */
	function initButtons() {
		document.querySelectorAll('.slide-button').forEach(function (button) {
			var defaultChars = button.querySelectorAll('.char-default');
			var hoverChars = button.querySelectorAll('.char-hover');
			if (!defaultChars.length) return;

			var hoverTimeline = null;
			gsap.set(defaultChars, { yPercent: 0 });
			gsap.set(hoverChars, { yPercent: -100 });

			var enter = function () {
				if (hoverTimeline) hoverTimeline.kill();
				hoverTimeline = gsap.timeline();
				hoverTimeline.to(defaultChars, { yPercent: 100, duration: 0.3, ease: 'power3.out', stagger: 0.01 }, 0);
				hoverTimeline.to(hoverChars, { yPercent: 0, duration: 0.3, ease: 'power3.out', stagger: 0.01 }, 0.1);
			};

			var leave = function () {
				if (hoverTimeline) hoverTimeline.kill();
				hoverTimeline = gsap.timeline();
				hoverTimeline.to(hoverChars, { yPercent: -100, duration: 0.4, ease: 'power3.inOut', stagger: 0.01 }, 0);
				hoverTimeline.to(defaultChars, { yPercent: 0, duration: 0.4, ease: 'power3.inOut', stagger: 0.01 }, 0.15);
			};

			var isActive = false;
			var enable = function () {
				if (isActive) return;
				button.addEventListener('mouseenter', enter);
				button.addEventListener('mouseleave', leave);
				isActive = true;
			};
			var disable = function () {
				if (!isActive) return;
				button.removeEventListener('mouseenter', enter);
				button.removeEventListener('mouseleave', leave);
				gsap.set(defaultChars, { yPercent: 0 });
				gsap.set(hoverChars, { yPercent: -100 });
				if (hoverTimeline) hoverTimeline.kill();
				isActive = false;
			};
			var onResize = function () {
				if (window.innerWidth < MOBILE) disable();
				else enable();
			};
			onResize();
			window.addEventListener('resize', onResize);
		});
	}

	/* -----------------------------------------------------------------------
	 * Navigation overlay
	 * --------------------------------------------------------------------- */
	function initNav() {
		var toggle = document.getElementById('nav-toggle');
		var overlay = document.querySelector('.nav-menu-overlay');
		var content = document.querySelector('.nav-menu-content');
		var page = document.getElementById('page-wrapper');
		var previewImg = document.getElementById('nav-preview-img');

		if (!toggle || !overlay || !content) return;

		var toggleOpen = document.getElementById('nav-toggle-open');
		var toggleClose = document.getElementById('nav-toggle-close');
		var allText = ['.nav-link a', '.nav-social a', '.nav-menu-footer p span'].join(', ');

		var isOpen = false;
		var isAnimating = false;
		var defaultPreviewSrc = previewImg && previewImg.querySelector('img')
			? previewImg.querySelector('img').getAttribute('src')
			: '';

		function lockScroll() { if (lenis) lenis.stop(); document.body.style.overflow = 'hidden'; }
		function unlockScroll() { document.body.style.overflow = ''; if (lenis) lenis.start(); }

		function resetPreview() {
			if (!previewImg) return;
			previewImg.innerHTML = '';
			if (defaultPreviewSrc) {
				var img = document.createElement('img');
				img.src = defaultPreviewSrc;
				previewImg.appendChild(img);
			}
		}

		function prunePreview() {
			if (!previewImg) return;
			var imgs = previewImg.querySelectorAll('img');
			while (imgs.length > 3) previewImg.removeChild(imgs[0]);
		}

		function animateLabel(opening) {
			gsap.to(opening ? toggleOpen : toggleClose, { x: -5, y: opening ? -10 : 10, rotation: opening ? -5 : 5, opacity: 0, delay: 0.25, duration: 0.5, ease: 'power2.out' });
			gsap.to(opening ? toggleClose : toggleOpen, { x: 0, y: 0, rotation: 0, opacity: 1, delay: 0.5, duration: 0.5, ease: 'power2.out' });
		}

		function killText() { gsap.killTweensOf(allText); }

		function resetHidden() {
			gsap.set('.nav-link a, .nav-social a', { y: '140%', opacity: 0.25 });
			gsap.set('.nav-menu-footer p span', { y: '120%', opacity: 0.25 });
		}

		function openMenu() {
			if (isAnimating || isOpen) return;
			isAnimating = true;

			var scrollY = window.scrollY;
			lockScroll();

			if (page) {
				page.style.transformOrigin = 'right ' + scrollY + 'px';
				gsap.to(page, { rotation: 10, x: 300, y: 450, scale: 1.5, duration: 1.25, ease: 'power4.inOut' });
			}

			animateLabel(true);
			killText();
			resetHidden();

			gsap.to(content, { rotation: 0, x: 0, y: 0, scale: 1, opacity: 1, duration: 1.25, ease: 'power4.inOut' });
			gsap.to(allText, { y: '0%', opacity: 1, delay: 0.75, duration: 1, ease: 'power3.out', stagger: 0.1 });
			gsap.to(overlay, {
				clipPath: 'polygon(0% 0%, 100% 0%, 100% 175%, 0% 100%)',
				duration: 1.25,
				ease: 'power4.inOut',
				onComplete: function () { isOpen = true; isAnimating = false; }
			});
		}

		function closeMenu() {
			if (isAnimating || !isOpen) return;
			isAnimating = true;

			if (page) {
				gsap.to(page, {
					rotation: 0, x: 0, y: 0, scale: 1, duration: 1.25, ease: 'power4.inOut',
					onComplete: function () { gsap.set(page, { clearProps: 'all' }); page.style.transformOrigin = ''; }
				});
			}

			animateLabel(false);
			killText();

			gsap.to(content, { rotation: -15, x: -100, y: -100, scale: 1.5, opacity: 0.25, duration: 1.25, ease: 'power4.inOut' });
			gsap.to(overlay, {
				clipPath: 'polygon(0% 0%, 100% 0%, 100% 0%, 0% 0%)',
				duration: 1.25,
				ease: 'power4.inOut',
				onComplete: function () {
					isOpen = false;
					isAnimating = false;
					resetHidden();
					resetPreview();
					unlockScroll();
				}
			});
		}

		function forceClose() {
			if (page) { gsap.set(page, { clearProps: 'all' }); page.style.transformOrigin = ''; }
			killText();
			gsap.set(overlay, { clipPath: 'polygon(0% 0%, 100% 0%, 100% 0%, 0% 0%)' });
			gsap.set(content, { rotation: -15, x: -100, y: -100, scale: 1.5, opacity: 0.25 });
			resetHidden();
			gsap.set(toggleOpen, { x: 0, y: 0, rotation: 0, opacity: 1 });
			gsap.set(toggleClose, { x: -5, y: 10, rotation: 5, opacity: 0 });
			isOpen = false;
			isAnimating = false;
			resetPreview();
			unlockScroll();
		}

		toggle.addEventListener('click', function () {
			if (!isOpen) openMenu();
			else closeMenu();
		});

		document.querySelectorAll('.nav-link a').forEach(function (link) {
			link.addEventListener('mouseover', function () {
				if (!isOpen || isAnimating) return;
				if (!previewImg) return;
				var imageSrc = link.getAttribute('data-img');
				if (!imageSrc) return;

				var imgs = previewImg.querySelectorAll('img');
				if (imgs.length && imgs[imgs.length - 1].src === imageSrc) return;

				var newImg = document.createElement('img');
				newImg.src = imageSrc;
				newImg.style.opacity = '0';
				newImg.style.transform = 'scale(1.25) rotate(10deg)';
				previewImg.appendChild(newImg);
				prunePreview();
				gsap.to(newImg, { opacity: 1, scale: 1, rotation: 0, duration: 0.75, ease: 'power2.out' });
			});
		});
	}

	/* -----------------------------------------------------------------------
	 * Preloader
	 * --------------------------------------------------------------------- */
	function initPreloader() {
		var preloader = document.getElementById('preloader');
		if (!preloader) return;

		var fill = document.getElementById('preloader-fill');
		var loading = document.getElementById('preloader-loading');
		var buttonWrap = document.getElementById('preloader-button-wrap');
		var enter = document.getElementById('preloader-enter');

		var duration = 2600;
		var hasFinished = false;
		var isExiting = false;

		if (lenis) lenis.stop();
		document.body.style.overflow = 'hidden';

		var startTime = performance.now();
		function tick(now) {
			var elapsed = now - startTime;
			var ratio = Math.min(Math.max(elapsed / duration, 0), 1);
			var percent = Math.round(ratio * 100);

			if (fill) fill.style.width = percent + '%';
			if (loading) loading.textContent = 'Loading... ' + percent + '%';

			if (percent >= 100) {
				hasFinished = true;
				if (loading) loading.classList.add('is-hidden');
				if (buttonWrap) buttonWrap.classList.add('is-visible');
				return;
			}
			requestAnimationFrame(tick);
		}
		requestAnimationFrame(tick);

		if (enter) {
			enter.addEventListener('click', function (e) {
				e.preventDefault();
				if (!hasFinished || isExiting) return;
				isExiting = true;
				preloader.classList.add('is-exiting');
				document.body.style.overflow = '';
				if (lenis) lenis.start();
				window.dispatchEvent(new CustomEvent('escargot:enter'));
				setTimeout(function () { preloader.style.display = 'none'; }, 700);
			});
		}
	}

	/* -----------------------------------------------------------------------
	 * Footer
	 * --------------------------------------------------------------------- */
	function initFooter() {
		var footer = document.getElementById('footer');
		if (!footer) return;

		var heading = footer.querySelector('.footer-heading');
		var buttonContainer = document.getElementById('footer-button-container');
		var button = footer.querySelector('.footer-button-container .slide-button');
		var postcardsContainer = document.getElementById('footer-postcards');
		var cards = footer.querySelectorAll('.footer-postcard');

		if (heading && buttonContainer) {
			gsap.set(buttonContainer, { autoAlpha: 0, y: 40 });
			ScrollTrigger.create({
				trigger: heading,
				start: 'top 50%',
				once: true,
				onEnter: function () {
					gsap.to(buttonContainer, { autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out' });
				}
			});
		}

		if (button && postcardsContainer && cards.length) {
			var postcardData = [
				{ rotation: -8, x: '-25%' },
				{ rotation: 6, x: '20%' },
				{ rotation: -4, x: '-15%' },
				{ rotation: 10, x: '25%' },
				{ rotation: -12, x: '-10%' }
			];

			var tl = gsap.timeline({ paused: true });
			Array.prototype.forEach.call(cards, function (card, index) {
				var data = postcardData[index] || { rotation: 0, x: '0%' };
				tl.fromTo(card,
					{ yPercent: 250, xPercent: 0, rotation: 0 },
					{ yPercent: 55, xPercent: parseFloat(data.x), rotation: data.rotation, duration: 0.8, ease: 'power3.out' },
					index * 0.07);
			});

			var enter = function () { tl.play(); };
			var leave = function () { tl.reverse(); };
			var isHoverActive = false;

			var enable = function () {
				if (isHoverActive) return;
				button.addEventListener('mouseenter', enter);
				button.addEventListener('mouseleave', leave);
				postcardsContainer.style.display = '';
				isHoverActive = true;
			};
			var disable = function () {
				if (!isHoverActive) return;
				button.removeEventListener('mouseenter', enter);
				button.removeEventListener('mouseleave', leave);
				tl.pause(0);
				postcardsContainer.style.display = 'none';
				isHoverActive = false;
			};
			var onResize = function () {
				if (window.innerWidth < MOBILE) disable();
				else enable();
			};
			onResize();
			window.addEventListener('resize', onResize);
		}
	}

	/* -----------------------------------------------------------------------
	 * Home: about gallery
	 * --------------------------------------------------------------------- */
	function initHomeAbout() {
		var about = document.querySelector('.about');
		if (!about) return;

		var ctx = gsap.context(function () {
			gsap.utils.toArray('.about-img').forEach(function (image) {
				var tl = gsap.timeline({
					scrollTrigger: { trigger: image, start: 'top bottom', end: 'bottom top', scrub: true }
				});
				tl.fromTo(image, { scale: 0.5 }, { scale: 1.25, ease: 'none' })
					.to(image, { scale: 0.5, ease: 'none' });
			});

			gsap.to('.about-header h3', {
				opacity: 0,
				ease: 'power1.out',
				scrollTrigger: { trigger: '.about-imgs', start: 'bottom bottom', end: 'bottom 30%', scrub: true }
			});
		}, about);
	}

	/* -----------------------------------------------------------------------
	 * Dining menu (home preview with category switcher)
	 * --------------------------------------------------------------------- */
	function initDiningMenu() {
		var section = document.querySelector('.dining-menu');
		if (!section) return;

		var cards = section.querySelectorAll('.dining-preview-card');
		var minimap = section.querySelectorAll('.dining-minimap-item');
		var prevBtn = section.querySelector('.dining-nav-button[data-dir="prev"]');
		var nextBtn = section.querySelector('.dining-nav-button[data-dir="next"]');
		var count = cards.length;
		var active = 0;

		function setActive(index) {
			if (index < 0 || index >= count) return;
			active = index;

			Array.prototype.forEach.call(cards, function (card, i) {
				var isActive = i === active;
				card.classList.toggle('active', isActive);
				if (isActive) {
					gsap.fromTo(card, { autoAlpha: 0, scale: 0.9 }, { autoAlpha: 1, scale: 1, duration: 0.5, ease: 'power2.out' });
				}
			});

			Array.prototype.forEach.call(minimap, function (item, i) {
				item.classList.toggle('active', i === active);
			});

			if (prevBtn) prevBtn.classList.toggle('disabled', active === 0);
			if (nextBtn) nextBtn.classList.toggle('disabled', active === count - 1);
		}

		if (prevBtn) prevBtn.addEventListener('click', function () { setActive(active - 1); });
		if (nextBtn) nextBtn.addEventListener('click', function () { setActive(active + 1); });
		Array.prototype.forEach.call(minimap, function (item, i) {
			item.addEventListener('click', function () { setActive(i); });
		});

		var navWrappers = section.querySelectorAll('.dining-nav-button-wrapper');
		var previewCard = section.querySelector('.dining-preview-card.active');

		gsap.set(navWrappers, { scale: 0 });
		if (previewCard) gsap.set(previewCard, { autoAlpha: 0 });
		gsap.set(minimap, { autoAlpha: 0, y: 30 });

		ScrollTrigger.create({
			trigger: section,
			start: 'top 30%',
			once: true,
			onEnter: function () {
				gsap.to(navWrappers, { scale: 1, duration: 1, ease: 'power3.out', stagger: 0.1 });
				if (previewCard) gsap.fromTo(previewCard, { autoAlpha: 0, y: 50, scale: 0.85 }, { autoAlpha: 1, y: 0, scale: 1, duration: 1, ease: 'power3.out' });
				gsap.to(minimap, { autoAlpha: 1, y: 0, duration: 1, ease: 'power3.out', stagger: 0.1 });
			}
		});

		setActive(0);
	}

	/* -----------------------------------------------------------------------
	 * Testimonials
	 * --------------------------------------------------------------------- */
	function initTestimonials() {
		var section = document.querySelector('.testimonials');
		if (!section) return;

		var track = section.querySelector('.testimonials-track');
		var navButtons = section.querySelectorAll('.testimonials-nav-button-wrapper');
		var cards = section.querySelectorAll('.testimonial-card');

		gsap.set(navButtons, { scale: 0 });
		gsap.set(cards, { scale: 0.85, autoAlpha: 0 });

		ScrollTrigger.create({
			trigger: section,
			start: 'top 75%',
			once: true,
			onEnter: function () {
				gsap.to(navButtons, { scale: 1, duration: 0.6, ease: 'back.out(1.7)', stagger: 0.1, delay: 0.4 });
				gsap.to(cards, { scale: 1, autoAlpha: 1, duration: 0.7, ease: 'power3.out', stagger: 0.1, delay: 0.3 });
			}
		});

		if (!track || !cards.length) return;

		var CARD_GAP = 20;
		var LERP_FACTOR = 0.075;
		var VELOCITY_DAMPING = 0.95;
		var VELOCITY_THRESHOLD = 0.05;

		var cardWidth = cards[0].offsetWidth;
		var itemWidth = cardWidth + CARD_GAP;
		var totalWidth = cards.length * itemWidth;

		gsap.set(cards, { position: 'absolute', left: 0, top: 0, x: function (i) { return i * itemWidth; } });
		gsap.set(track, { height: cards[0].offsetHeight });

		var wrapPosition = gsap.utils.wrap(-itemWidth, totalWidth - itemWidth);

		var targetX = 0;
		var currentX = 0;
		var isDragging = false;
		var dragStartPointerX = 0;
		var dragStartTargetX = 0;
		var velocityX = 0;
		var lastPointerX = 0;
		var lastPointerTime = 0;

		function slideBy(direction) {
			velocityX = 0;
			targetX += direction * itemWidth;
		}

		function updateCardPositions() {
			if (!isDragging) {
				targetX += velocityX;
				velocityX *= VELOCITY_DAMPING;
				if (Math.abs(velocityX) < VELOCITY_THRESHOLD) velocityX = 0;
			}
			currentX += (targetX - currentX) * LERP_FACTOR;
			Array.prototype.forEach.call(cards, function (card, index) {
				gsap.set(card, { x: wrapPosition(index * itemWidth + currentX) });
			});
		}
		gsap.ticker.add(updateCardPositions);

		function handlePointerDown(e) {
			isDragging = true;
			dragStartPointerX = e.clientX;
			dragStartTargetX = targetX;
			velocityX = 0;
			lastPointerX = e.clientX;
			lastPointerTime = Date.now();
			track.setPointerCapture(e.pointerId);
			track.style.cursor = 'grabbing';
		}
		function handlePointerMove(e) {
			if (!isDragging) return;
			var dragDelta = e.clientX - dragStartPointerX;
			targetX = dragStartTargetX + dragDelta;
			var now = Date.now();
			var timeDelta = now - lastPointerTime;
			if (timeDelta > 0) {
				velocityX = ((e.clientX - lastPointerX) / timeDelta) * 16;
				lastPointerX = e.clientX;
				lastPointerTime = now;
			}
		}
		function handlePointerUp() {
			isDragging = false;
			track.style.cursor = 'grab';
		}

		var isDragEnabled = false;
		function enableDrag() {
			if (isDragEnabled) return;
			track.addEventListener('pointerdown', handlePointerDown);
			track.addEventListener('pointermove', handlePointerMove);
			track.addEventListener('pointerup', handlePointerUp);
			track.addEventListener('pointercancel', handlePointerUp);
			track.style.cursor = 'grab';
			track.style.touchAction = 'none';
			isDragEnabled = true;
		}
		function disableDrag() {
			if (!isDragEnabled) return;
			track.removeEventListener('pointerdown', handlePointerDown);
			track.removeEventListener('pointermove', handlePointerMove);
			track.removeEventListener('pointerup', handlePointerUp);
			track.removeEventListener('pointercancel', handlePointerUp);
			track.style.cursor = 'default';
			track.style.touchAction = 'auto';
			isDragging = false;
			isDragEnabled = false;
		}

		var prevBtn = section.querySelector('.testimonials-nav-button[data-dir="prev"]');
		var nextBtn = section.querySelector('.testimonials-nav-button[data-dir="next"]');
		if (prevBtn) prevBtn.addEventListener('click', function () { slideBy(1); });
		if (nextBtn) nextBtn.addEventListener('click', function () { slideBy(-1); });

		function onResize() {
			if (window.innerWidth < MOBILE) disableDrag();
			else enableDrag();
		}
		onResize();
		window.addEventListener('resize', onResize);
	}

	/* -----------------------------------------------------------------------
	 * CTA
	 * --------------------------------------------------------------------- */
	function initCTA() {
		var section = document.querySelector('.cta');
		if (!section) return;

		var circlePath = section.querySelector('.cta-button-svg path');
		var image = section.querySelector('.cta-image');
		var button = section.querySelector('.cta-button');

		if (circlePath) {
			var pathLength = circlePath.getTotalLength();
			gsap.set(circlePath, { strokeDasharray: pathLength, strokeDashoffset: pathLength, rotation: -90, transformOrigin: 'center center' });

			if (image) gsap.set(image, { autoAlpha: 0, scale: 0.75 });

			ScrollTrigger.create({
				trigger: section,
				start: 'top 75%',
				once: true,
				onEnter: function () {
					gsap.to(circlePath, { strokeDashoffset: 0, duration: 1.2, delay: 0.6, ease: 'power2.inOut' });
					if (image) gsap.to(image, { autoAlpha: 1, scale: 1, duration: 1, ease: 'power3.out' });
				}
			});

			if (button) {
				var hoverTimeline = null;
				button.addEventListener('mouseenter', function () {
					if (hoverTimeline) hoverTimeline.kill();
					hoverTimeline = gsap.timeline();
					hoverTimeline
						.set(circlePath, { strokeDashoffset: 0, strokeDasharray: pathLength, scale: 1 })
						.to(circlePath, { strokeDashoffset: -pathLength, duration: 0.75, ease: 'power2.inOut' })
						.set(circlePath, { strokeDashoffset: pathLength })
						.to(circlePath, { strokeDashoffset: 0, duration: 0.75, ease: 'power2.inOut' });
				});
			}
		}
	}

	/* -----------------------------------------------------------------------
	 * Essence (about) hero + parallax
	 * --------------------------------------------------------------------- */
	function initEssenceHero() {
		var hero = document.querySelector('.about-hero');
		if (!hero) return;

		var heroImage = hero.querySelector('.hero-image');
		if (heroImage) {
			gsap.fromTo(heroImage, { autoAlpha: 0, scale: 0.75, y: 50 }, { autoAlpha: 1, scale: 1, y: 0, duration: 1, delay: 1.25, ease: 'power3.out' });
		}

		var ctx;
		function build() {
			if (ctx) ctx.revert();
			var isMobile = window.innerWidth < 1000;
			var headerOffsetY = isMobile ? '200vh' : '175vh';
			var headerOffsetX = isMobile ? -100 : -150;

			ctx = gsap.context(function () {
				var tl = gsap.timeline({
					scrollTrigger: { trigger: '.about-hero', start: 'top top', end: 'bottom +=1200%', scrub: true }
				});
				tl.to(['.hero-heading .heading-line-1', '.hero-heading .heading-line-3'], { scale: 2, y: headerOffsetY, xPercent: headerOffsetX }, 'scroll')
					.to('.hero-heading .heading-line-2', { scale: 2, y: headerOffsetY, xPercent: -headerOffsetX }, 'scroll')
					.to('.hero-image', { scaleY: 2.5, yPercent: 300 }, 'scroll')
					.to('.hero-image img', { scaleX: 2.5 }, 'scroll');
			}, hero);
		}

		var wasMobile = window.innerWidth < 1000;
		build();
		window.addEventListener('resize', function () {
			var isMobile = window.innerWidth < 1000;
			if (isMobile !== wasMobile) {
				wasMobile = isMobile;
				build();
			}
		});
	}

	/* -----------------------------------------------------------------------
	 * Marquee
	 * --------------------------------------------------------------------- */
	function initMarquee() {
		var section = document.querySelector('.marquee');
		if (!section) return;

		var track = section.querySelector('.marquee-track');
		var items = track ? track.querySelectorAll('.marquee-item') : [];
		if (!items.length) return;

		var REPEAT = 12;
		var itemWidth = items[0].offsetWidth;
		var halfLoop = itemWidth * (REPEAT / 2);

		var tween = gsap.to(track, {
			x: -halfLoop,
			duration: 30,
			ease: 'none',
			repeat: -1,
			modifiers: { x: gsap.utils.unitize(function (x) { return parseFloat(x) % halfLoop; }) }
		});

		var pill = track.parentElement;
		pill.addEventListener('mouseenter', function () { gsap.to(tween, { timeScale: 0, duration: 0.5 }); });
		pill.addEventListener('mouseleave', function () { gsap.to(tween, { timeScale: 1, duration: 0.5 }); });
	}

	/* -----------------------------------------------------------------------
	 * Sticky cards
	 * --------------------------------------------------------------------- */
	function initStickyCards() {
		var section = document.querySelector('.sticky-cards');
		if (!section) return;

		var ctx;
		function build() {
			if (ctx) ctx.revert();

			var cardElements = section.querySelectorAll('.sticky-card');
			Array.prototype.forEach.call(cardElements, function (card) { gsap.set(card, { clearProps: 'all' }); });

			if (window.innerWidth < 1000) {
				section.classList.add('sticky-cards-mobile');
				return;
			}
			section.classList.remove('sticky-cards-mobile');

			requestAnimationFrame(function () {
				ctx = gsap.context(function () {
					var cards = gsap.utils.toArray('.sticky-card', section);
					var totalCards = cards.length;
					var lastLeft = totalCards % 2 === 0 ? totalCards - 2 : totalCards - 1;
					var lastRight = totalCards % 2 === 0 ? totalCards - 1 : totalCards - 2;

					cards.forEach(function (card, index) {
						if (index === lastLeft || index === lastRight) return;
						gsap.timeline({ scrollTrigger: { trigger: card, start: 'bottom top', end: '+=100%', scrub: true } })
							.to(card, { yPercent: -100, ease: 'none' });
					});

					ScrollTrigger.refresh();
				}, section);
			});
		}

		var wasMobile = window.innerWidth < 1000;
		build();
		window.addEventListener('resize', function () {
			var isMobile = window.innerWidth < 1000;
			if (isMobile !== wasMobile) {
				wasMobile = isMobile;
				build();
			}
		});
	}

	/* -----------------------------------------------------------------------
	 * Chefs
	 * --------------------------------------------------------------------- */
	function initChefs() {
		var section = document.querySelector('.chefs');
		if (!section) return;

		var avatarContainer = section.querySelector('.chefs-avatars');
		var avatars = section.querySelectorAll('.chefs-avatar');
		var chefNames = section.querySelectorAll('.chefs-name:not(.chefs-name--default)');
		var defaultHeading = section.querySelector('.chefs-name--default');
		if (!defaultHeading || !avatars.length || !avatarContainer) return;

		var defaultLetters = defaultHeading.querySelectorAll('.letter');
		gsap.set(defaultLetters, { y: '0%' });

		var handlers = [];
		var containerEnter = null;
		var containerLeave = null;

		function enable() {
			Array.prototype.forEach.call(avatars, function (avatar, index) {
				var nameEl = chefNames[index];
				if (!nameEl) return;
				var nameLetters = nameEl.querySelectorAll('.letter');

				var onEnter = function () {
					gsap.to(avatar, { width: 120, height: 120, duration: 0.5, ease: 'power4.out' });
					gsap.to(defaultLetters, { y: '-110%', duration: 0.75, ease: 'power4.out', stagger: { each: 0.025, from: 'center' } });
					gsap.to(nameLetters, { y: '0%', duration: 0.75, ease: 'power4.out', stagger: { each: 0.025, from: 'center' } });
				};
				var onLeave = function () {
					gsap.to(avatar, { width: 70, height: 70, duration: 0.5, ease: 'power4.out' });
					gsap.to(nameLetters, { y: '110%', duration: 0.75, ease: 'power4.out', stagger: { each: 0.025, from: 'center' } });
				};

				avatar.addEventListener('mouseenter', onEnter);
				avatar.addEventListener('mouseleave', onLeave);
				handlers.push({ element: avatar, onEnter: onEnter, onLeave: onLeave });
			});

			containerEnter = function () {
				gsap.to(defaultLetters, { y: '-110%', duration: 0.75, ease: 'power4.out', stagger: { each: 0.025, from: 'center' } });
			};
			containerLeave = function () {
				Array.prototype.forEach.call(chefNames, function (name) {
					gsap.to(name.querySelectorAll('.letter'), { y: '110%', duration: 0.75, ease: 'power4.out', stagger: { each: 0.025, from: 'center' } });
				});
				gsap.to(defaultLetters, { y: '0%', duration: 0.75, ease: 'power4.out', stagger: { each: 0.025, from: 'center' } });
			};

			avatarContainer.addEventListener('mouseenter', containerEnter);
			avatarContainer.addEventListener('mouseleave', containerLeave);
		}

		function disable() {
			handlers.forEach(function (h) {
				h.element.removeEventListener('mouseenter', h.onEnter);
				h.element.removeEventListener('mouseleave', h.onLeave);
			});
			handlers = [];
			if (containerEnter) { avatarContainer.removeEventListener('mouseenter', containerEnter); containerEnter = null; }
			if (containerLeave) { avatarContainer.removeEventListener('mouseleave', containerLeave); containerLeave = null; }

			gsap.set(defaultLetters, { y: '0%' });
			Array.prototype.forEach.call(chefNames, function (name) { gsap.set(name.querySelectorAll('.letter'), { y: '110%' }); });
			Array.prototype.forEach.call(avatars, function (avatar) { gsap.set(avatar, { clearProps: 'width,height' }); });
		}

		var wasDesktop = window.innerWidth >= 1000;
		if (wasDesktop) enable();
		window.addEventListener('resize', function () {
			var isDesktop = window.innerWidth >= 1000;
			if (isDesktop !== wasDesktop) {
				wasDesktop = isDesktop;
				if (isDesktop) enable();
				else disable();
			}
		});
	}

	/* -----------------------------------------------------------------------
	 * Menu page grid
	 * --------------------------------------------------------------------- */
	function initMenuPage() {
		var list = document.querySelector('.menu-list');
		if (!list) return;

		gsap.utils.toArray('.menu-grid', list).forEach(function (grid) {
			var cards = grid.querySelectorAll('.menu-grid-card');
			gsap.set(cards, { opacity: 0, y: 30, scale: 0.75 });
			ScrollTrigger.create({
				trigger: grid,
				start: 'top 70%',
				once: true,
				onEnter: function () {
					gsap.to(cards, { opacity: 1, y: 0, scale: 1, duration: 0.6, ease: 'power2.out', stagger: 0.08 });
				}
			});
		});
	}

	/* -----------------------------------------------------------------------
	 * Reservation (book) page
	 * --------------------------------------------------------------------- */
	function initReservation() {
		var heroBtn = document.querySelector('.reservation-hero-button');
		if (heroBtn) {
			gsap.fromTo(heroBtn, { autoAlpha: 0, y: 40 }, { autoAlpha: 1, y: 0, duration: 0.9, delay: 1.55, ease: 'power3.out' });
		}

		var infoSection = document.querySelector('.reservation-info');
		if (!infoSection) return;

		var isDesktop = window.innerWidth >= 1000;
		var ctx;

		function build() {
			if (ctx) ctx.revert();
			infoSection.classList.remove('reservation-info-mobile');

			if (!isDesktop) {
				infoSection.classList.add('reservation-info-mobile');
				return;
			}

			ctx = gsap.context(function () {
				var panels = gsap.utils.toArray(infoSection.querySelectorAll('.info-panel'));
				var cards = gsap.utils.toArray(infoSection.querySelectorAll('.info-card'));

				ScrollTrigger.create({
					trigger: infoSection,
					start: 'top bottom',
					end: 'top top',
					scrub: 1,
					onUpdate: function (self) {
						var progress = self.progress;
						panels.forEach(function (panel, index) {
							var staggerDelay = 0.15;
							var duration = 0.7;
							var start = index * staggerDelay;
							var end = start + duration;

							if (progress >= start && progress <= end) {
								var norm = (progress - start) / duration;
								gsap.set(panel, { y: (125 - norm * 125) + '%' });
								var icon = panel.querySelector('.info-panel-icon');
								var iconThreshold = 0.4;
								var iconProgress = Math.max(0, (norm - iconThreshold) / (1 - iconThreshold));
								gsap.set(icon, { scale: iconProgress });
							} else if (progress > end) {
								gsap.set(panel, { y: '0%' });
								gsap.set(panel.querySelector('.info-panel-icon'), { scale: 1 });
							}
						});
					}
				});

				ScrollTrigger.create({
					trigger: infoSection,
					start: 'top top',
					end: '+=' + (window.innerHeight * 3),
					pin: true,
					scrub: 1,
					onUpdate: function (self) {
						var progress = self.progress;
						cards.forEach(function (card, index) {
							var slideStagger = 0.075;
							var slideDuration = 0.4;
							var slideStart = index * slideStagger;
							var slideEnd = slideStart + slideDuration;

							if (progress >= slideStart && progress <= slideEnd) {
								var norm = (progress - slideStart) / slideDuration;
								var initialX = 350 - index * 100;
								var targetX = -50;
								var currentX = initialX + norm * (targetX - initialX);
								var currentRotation = 20 - norm * 20;
								gsap.set(card, { x: currentX + '%', rotation: currentRotation });
							} else if (progress > slideEnd) {
								gsap.set(card, { x: '-50%', rotation: 0 });
							}

							var scaleStagger = 0.12;
							var scaleStart = 0.4 + index * scaleStagger;
							var scaleEnd = 1;

							if (progress >= scaleStart && progress <= scaleEnd) {
								var norm2 = (progress - scaleStart) / (scaleEnd - scaleStart);
								gsap.set(card, { scale: 0.75 + norm2 * 0.25 });
							} else if (progress > scaleEnd) {
								gsap.set(card, { scale: 1 });
							}
						});
					}
				});
			}, infoSection);
		}

		build();
		window.addEventListener('resize', function () {
			var newIsDesktop = window.innerWidth >= 1000;
			if (newIsDesktop !== isDesktop) {
				isDesktop = newIsDesktop;
				build();
			}
		});
	}

	/* -----------------------------------------------------------------------
	 * Boot
	 * --------------------------------------------------------------------- */
	document.addEventListener('DOMContentLoaded', function () {
		initLenis();
		initCopy();
		initButtons();
		initNav();
		initPreloader();
		initFooter();

		if (document.querySelector('.about-imgs')) initHomeAbout();
		if (document.querySelector('.dining-menu')) initDiningMenu();
		if (document.querySelector('.testimonials')) initTestimonials();
		if (document.querySelector('.cta')) initCTA();
		if (document.querySelector('.about-hero')) initEssenceHero();
		if (document.querySelector('.marquee')) initMarquee();
		if (document.querySelector('.sticky-cards')) initStickyCards();
		if (document.querySelector('.chefs')) initChefs();
		if (document.querySelector('.menu-list')) initMenuPage();
		if (document.querySelector('.reservation-info')) initReservation();

		window.addEventListener('load', function () {
			ScrollTrigger.refresh();
		});
	});
})();
