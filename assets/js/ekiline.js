/* Ekiline for WordPress Theme, Copyright 2018 Uri Lazcano. Ekiline is distributed under the terms of the GNU GPL. http://ekiline.com */
window.onload = function() {
	ekiline_extend_bootstrap_init_bundle_items();
	ekiline_safari_smooth_navigation('.smooth');
	ekiline_navbar_add_focus_behavior();
	ekiline_navbar_show_hide_scroll();
	ekiline_navbar_add_opacity('.has-navbar-opacity #primarySiteNavigation',300);
	ekiline_navbar_modal_behavior();
}

/**
 * Extension Bootstrap.
 * Inicializar / Init bootstrap bundle items as tooltips, popovers and toasts.
 */
function ekiline_extend_bootstrap_init_bundle_items(){

	document.querySelectorAll('[data-bs-toggle="tooltip"]')
		.forEach(function (tooltip) {
			new bootstrap.Tooltip(tooltip);
		});

	document.querySelectorAll('[data-bs-toggle="popover"]')
		.forEach(function (popover) {
		new bootstrap.Popover(popover);
		});

	document.querySelectorAll('.toast')
		.forEach(function (toastNode) {
			var toast = new bootstrap.Toast(toastNode, {
				autohide: false
			});
			// Dont run if 'hide' classname presents.
			if (!toastNode.classList.contains('hide')){
				toast.show();
			}
		});
}

/**
 * UX theme, smooth scroll in safari, apply each item with classname -smooth-.
 * @param {string} item, classname in links
 */
function ekiline_safari_smooth_navigation( item = null ){
	document.querySelectorAll(item).forEach(anchor => {
		var href = anchor.getAttribute('href');
		anchor.addEventListener('click', function (e) {
			e.preventDefault();
			document.querySelector( this.getAttribute('href') ).scrollIntoView({
				behavior: 'smooth'
			});
			window.location.hash = href;
		});
	});
}

/**
 * WP developers requirement, close navbar if user play with keyboard.
 * - Uso de teclado: document.addEventListener('keydown/focus/etc...', (e) => { console.log(e.code) }, true );
 * - Verificar descencientes: parentEl.contains(childEl)
 */
function ekiline_navbar_add_focus_behavior(){
	// Verificar elemento activo | Check active element (focus).
	var domFocusitem = document.activeElement;
	domFocusitem.addEventListener('focus', () => {
		// Si navbar se muestra | if navbar shows.
		var activeNav = document.querySelector('.navbar-collapse.show:not(.modal .navbar-collapse)');
		if ( activeNav ){
			// Cotejar si el objeto seleccionado es descendiente | Check descendant.
			var newDomFocusitem = document.activeElement;
			var isDescendant = activeNav.contains( newDomFocusitem );
			if ( ! isDescendant ){
				// Cerrar la navegacion | Close navigation.
				new bootstrap.Collapse(activeNav, {
					close: true
				});
			}
		}
	}, true);
}

/**
 * Customizer Ekiline Menu option:
 * Show and hide navbar when user scroll top/bottom.
 */
function ekiline_navbar_show_hide_scroll(){

	var stickynav = document.querySelector('#primarySiteNavigation.navbar-sticky');

	if ( stickynav ){

		var last_scroll_top = 0, scroll_top;

		window.addEventListener('scroll', function() {

			scroll_top = this.scrollY;

			if( scroll_top < last_scroll_top ) {
				stickynav.classList.remove('scrolled-down');
				stickynav.classList.add('scrolled-up');
			} else {
				stickynav.classList.remove('scrolled-up');
				stickynav.classList.add('scrolled-down');
			}

			last_scroll_top = scroll_top;
		});
	}
}

/**
 * Customizer Ekiline Header option:
 * Add opacity classname in navbar when user scroll top/bottom.
 */
function ekiline_navbar_add_opacity( selector = null, height = 0 ){
	// Validar selector.
	var navFx = document.querySelector( selector );
	if ( navFx ){
		// Agregar clase css default.
		navFx.classList.add('nav-opacity');
		// Listener.
		window.addEventListener('scroll',
			function() {
				var scroll_top = this.scrollY;
				if( scroll_top > height ) {
					navFx.classList.remove('nav-opacity');
				} else {
					navFx.classList.add('nav-opacity');
				}
			}
		);
	}
}

/**
 * Customizer Ekiline Menu Modal option:
 * Animate modal button.
 */
function ekiline_navbar_modal_behavior(){

	var modalTogglerBtn = document.querySelector('.modal-toggler');
	var modalNav = document.querySelector('.modal-nav');

	if ( modalTogglerBtn && modalNav ){
		modalTogglerBtn.addEventListener('click', function() {
			this.classList.remove('collapsed');
		}, false);

		modalNav.addEventListener('hidden.bs.modal', function () {
			modalTogglerBtn.classList.add('collapsed');
		}, false);

		// Cambiar el tamaño de modal.
		var modalResizeBtn = document.querySelector('.modal-resize');

		modalResizeBtn.addEventListener('click', function() {
			var modalOpen = document.querySelector('.modal-open');
			modalOpen.classList.toggle('modal-full');
			this.firstElementChild.classList.toggle('float-right');
		}, false);
	}
}
