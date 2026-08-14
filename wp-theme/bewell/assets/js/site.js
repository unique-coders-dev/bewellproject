/**
 * The only JavaScript the public site needs: the mobile navigation drawer.
 *
 * The React build shipped Radix UI to do this. Everything else on these pages
 * is static, so the whole interactive surface is one panel that slides in.
 */
(function () {
	'use strict';

	var drawer = document.getElementById( 'bewell-drawer' );

	if ( ! drawer ) {
		return;
	}

	var openers = document.querySelectorAll( '[data-bewell-drawer-open]' );
	var closers = drawer.querySelectorAll( '[data-bewell-drawer-close]' );
	var lastFocused = null;

	function focusables() {
		return drawer.querySelectorAll( 'a[href], button:not([disabled])' );
	}

	function open() {
		// Safari does not move focus to a button when it is clicked, so
		// document.activeElement is often <body> here. Falling back to the
		// opener means closing the drawer always returns focus somewhere
		// sensible instead of dumping the user at the top of the document.
		lastFocused = document.activeElement;

		if ( ! lastFocused || lastFocused === document.body || lastFocused === document.documentElement ) {
			lastFocused = openers[ 0 ] || null;
		}

		drawer.setAttribute( 'data-open', 'true' );
		document.documentElement.style.overflow = 'hidden';

		openers.forEach( function ( button ) {
			button.setAttribute( 'aria-expanded', 'true' );
		} );

		var first = focusables()[ 0 ];
		if ( first ) {
			first.focus();
		}
	}

	function close() {
		drawer.setAttribute( 'data-open', 'false' );
		document.documentElement.style.overflow = '';

		openers.forEach( function ( button ) {
			button.setAttribute( 'aria-expanded', 'false' );
		} );

		// Return focus to the hamburger, or a screen reader is left adrift at
		// the top of the document.
		if ( lastFocused && typeof lastFocused.focus === 'function' ) {
			lastFocused.focus();
		}
	}

	openers.forEach( function ( button ) {
		button.addEventListener( 'click', open );
	} );

	closers.forEach( function ( button ) {
		button.addEventListener( 'click', close );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( drawer.getAttribute( 'data-open' ) !== 'true' ) {
			return;
		}

		if ( event.key === 'Escape' ) {
			close();
			return;
		}

		// Keep Tab inside the panel while it is open.
		if ( event.key !== 'Tab' ) {
			return;
		}

		var items = focusables();
		if ( ! items.length ) {
			return;
		}

		var first = items[ 0 ];
		var last = items[ items.length - 1 ];

		if ( event.shiftKey && document.activeElement === first ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && document.activeElement === last ) {
			event.preventDefault();
			first.focus();
		}
	} );

	// A resize past the lg breakpoint reveals the desktop nav; leaving the
	// drawer open would trap scrolling on a page that no longer shows it.
	window.addEventListener( 'resize', function () {
		if ( window.innerWidth >= 1024 && drawer.getAttribute( 'data-open' ) === 'true' ) {
			close();
		}
	} );
})();
