/* Ekiline for WordPress Theme, Copyright 2018 Uri Lazcano. Ekiline is distributed under the terms of the GNU GPL. http://ekiline.com */
window.onload = function() {

	ekiline_smoothNavigation('.smooth');
	ekiline_navBar_behavior();
	ekiline_navModal_behavior();
	ekiline_nestedDropdowns('.dropdown-menu a.dropdown-toggle');
	ekiline_transformarCarrusel('.carousel-multiple');

	/* Bootstrap: inicializar tooltips y popovers */
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	})

	var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
		popoverTriggerList.map(function (popoverTriggerEl) {
		return new bootstrap.Popover(popoverTriggerEl)
	})
}

/**
 * Scroll suave, aplica a enlaces con clase (.smooth).
 * @param {string} item Clase en link para ejecutar scroll.
 */
function ekiline_smoothNavigation(item = null){
	document.querySelectorAll('.smooth').forEach(anchor => {
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

/* Navegacion con scroll */
function ekiline_navBar_behavior(){

	var navbarSticky = document.querySelector('#primarySiteNavigation.navbar-sticky');

	if ( navbarSticky ){
		var last_scroll_top = 0, scroll_top;

		window.addEventListener('scroll', function() {

			scroll_top = this.scrollY;

			if( scroll_top < last_scroll_top ) {
				navbarSticky.classList.remove('scrolled-down');
				navbarSticky.classList.add('scrolled-up');
			} else {
				navbarSticky.classList.remove('scrolled-up');
				navbarSticky.classList.add('scrolled-down');
			}

			last_scroll_top = scroll_top;
		});
	}

	/**
	 * Hacks interesantes:
	 * Uso de teclado
	 * - document.addEventListener('keydown', (e) => { console.log(e.code) }, true );
	 * Verificar descencientes
	 * parentEl.contains(childEl)
	 */
	// Elemento activo.
	var domFocusitem = document.activeElement;
	domFocusitem.addEventListener('focus', () => {
		// Si navbar se muestra.
		var activeNav = document.querySelector('.navbar-collapse.show:not(.modal .navbar-collapse)');
		if ( activeNav ){
			// Cotejar si el objeto seleccionado es descendiente.
			var newDomFocusitem = document.activeElement;
			var isDescendant = activeNav.contains( newDomFocusitem );
			if ( ! isDescendant ){
				var closeNav = activeNav;
				// cerrar la navegacion.
				new bootstrap.Collapse(closeNav, {
					close: true
				})
			}
		}
	}, true);

}

/* Animar el boton del menu modal */
function ekiline_navModal_behavior(){

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

/* Ajuste en dropdown de widgets dentro de navbar */
function ekiline_nestedDropdowns(item = null){
	var dropdownList = [].slice.call(document.querySelectorAll(item))
	dropdownList.map(function (dropdownEl) {
		dropdownEl.addEventListener('click', function(e) {
			e.stopPropagation();
			e.preventDefault();
		}, false);
	})
}

/**
 * Modificar carrusel. Se ocupa en el Editor de bloques también.
 * @param {string} carrusel Idendifica la clase para reajustar el diseno.
 */
window.ekiline_transformarCarrusel = function(carrusel){

	// Si no hay carrusel cancelar todo.
	var loaditem = document.querySelector(carrusel);
	if ( !loaditem || 0 < loaditem.getElementsByTagName('figure').length ) {
		return;
	}

	// Funcion envoltorio (Wrapper).
	function envolver(fuente,col){
		var hijos = fuente.children;
		// crear envoltorio
		var wrapper = document.createElement('figure');
			wrapper.className = 'col-md-' + col;
		// envolver los hijos.
		for (var i = hijos.length - 1; i >= 0; i--) {
			wrapper.appendChild(hijos[i]);
		};
		fuente.appendChild(wrapper);
	}

	// Si hay carrusel,
	var siCarruseles = document.querySelectorAll(carrusel);

	// Cuantos son, modificar cada uno
	Array.prototype.forEach.call(siCarruseles, function(unCarrusel, i){

		// Objeto e indice. Vistas, columnas y grupo.
		var params = [ ['x2','6','0'],['x3','4','1'],['x4','3','2'],['x6','2','4'] ];
		var view, item;
		// Envoltorio extra para agrupar.
		for ( var i = 0; i < params.length; i++ ) {
			// Atributos por clase.
			if ( unCarrusel.classList.contains(params[i][0]) ) {
				item = params[i][1];
				view = params[i][2];
			}
		}

		// Resultado de seleccion por carrusel
		// Carrusel padre. Items para envoltorio.
		hijosCarrusel = unCarrusel.querySelectorAll('.carousel-item');

		// Carrusel hijo. Envoltorio por item.
		Array.prototype.forEach.call(hijosCarrusel, function(el,i){
			envolver(el,item);
		});

		// Loop grupos.
		Array.prototype.forEach.call(hijosCarrusel, function(el, i){
			// Copiar el primer slide y agregarlo.
			var next = el.nextElementSibling;
			if ( !next ) {
				next = el.parentNode.children[0];
			}

			// Elemento siguiente. Clonar.
			var firstChildClone = next.children[0].cloneNode(true);
			var firstChildSet = el.parentNode.children[i];
			firstChildSet.appendChild(firstChildClone);

			// Agrupar slides (view).
			for ( var i=0;i<view;i++ ) {
				next = next.nextElementSibling;
				if ( !next ) {
					next = el.parentNode.children[0];
				}
				firstChildClone = next.children[0].cloneNode(true);
				firstChildSet.appendChild(firstChildClone);
			}

		});
	});
};
