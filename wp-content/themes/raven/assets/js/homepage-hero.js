( function () {
	'use strict';

	class HeroSlideshow {
		constructor( el ) {
			this.el         = el;
			this.slides     = Array.from( el.querySelectorAll( '.hero-slide' ) );
			this.current    = 0;
			this.duration   = parseInt( el.dataset.duration, 10 ) || 5000;
			this.transition = el.dataset.transition || 'fade';
			this.loop       = el.dataset.loop !== 'false';
			this.timer      = null;

			if ( this.slides.length < 2 ) return;
			this.show( 0 );
			this.start();
		}

		show( index ) {
			this.slides.forEach( ( slide, i ) => {
				slide.classList.toggle( 'active', i === index );
			} );
			this.current = index;
		}

		next() {
			const isLast = this.current === this.slides.length - 1;
			if ( isLast && ! this.loop ) {
				clearInterval( this.timer );
				return;
			}
			this.show( ( this.current + 1 ) % this.slides.length );
		}

		start() {
			this.timer = setInterval( () => this.next(), this.duration );
		}
	}

	function sizeLogoFigures() {
		document.querySelectorAll( '.js-homepage-hero-nav' ).forEach( nav => {
			const figure = nav.querySelector( '.js-homepage-hero-logo-wrapper' );
			const svg    = figure && figure.querySelector( 'svg' );
			if ( ! svg ) return;

			const vb = svg.viewBox.baseVal;
			if ( ! vb || ! vb.height ) return;

			figure.style.width = ( figure.offsetHeight * vb.width / vb.height ) + 'px';
			nav.style.opacity  = '1';
		} );
	}

	let resizeTimer;
	window.addEventListener( 'resize', () => {
		clearTimeout( resizeTimer );
		resizeTimer = setTimeout( sizeLogoFigures, 100 );
	} );

	function init() {
		document.querySelectorAll( '.hero-slideshow' ).forEach( el => new HeroSlideshow( el ) );
		document.fonts.ready.then( sizeLogoFigures );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
