(function () {
	'use strict';

	if (!window.Lenis) return;

	var MOBILE = 1000;

	function easing(t) {
		return Math.min(1, 1.001 - Math.pow(2, -10 * t));
	}

	function createLenis() {
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

	var lenis = createLenis();

	function raf(time) {
		lenis.raf(time);
		requestAnimationFrame(raf);
	}
	requestAnimationFrame(raf);
})();
