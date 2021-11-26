/* Ekiline for WordPress Theme, Copyright 2018 Uri Lazcano. Ekiline is distributed under the terms of the GNU GPL. http://ekiline.com */
// 1) Colores existentes.
const themeColors = [
	{name:'--bs-primary',id:'b4_primary'},
	{name:'--bs-secondary',id:'b4_secondary'},
	{name:'--bs-success',id:'b4_success'},
	{name:'--bs-info',id:'b4_info'},
	{name:'--bs-warning',id:'b4_warning'},
	{name:'--bs-danger',id:'b4_danger'},
	{name:'--bs-light',id:'b4_light'},
	{name:'--bs-dark',id:'b4_dark'},
	{name:'--bs-body-bg',id:'background_color'},
	{name:'--bs-body-color',id:'text_color'}
];
// 1.1) Matriz de variables para tonos, ordenado de oscuro a claro.
const coloresVariables = [
	'alert-link',
	'alert',
	'btn-active',
	'link-hover',
	'btn-hover',
	'form-control-focus',
	'form-range-active',
	'table-active-bg',
	'table-hover-bg',
	'table-striped-bg',
	'table-bg'
];

// 2) Agregar instrucciones CSS en TEXTAREA.
updateFieldWithCss(themeColors,coloresVariables);

/**
 * Funcion: Insertar/Actualizar css en ekiline_textarea_css.
 * @param {*} arrayColors Arreglo con opciones de color en customizer.
 * @param {*} colorVariables Arreglo con variables de color, incrementan resultado.
 */
function updateFieldWithCss(arrayColors,colorVariables){
	// 2) Por cada color en el arreglo.
	arrayColors.forEach(function(itemColor){
		// 2.1) Actualiza textarea con CSS (string).
		wp.customize( itemColor.id, 'ekiline_textarea_css', function( field1, field2 ) {
			// 2.2) Desde cada cambio que se pueda generar en los inputs.
			field1.bind( function( item ) {
				// 2.3) Actualizar arrayColors con colores definitivos (funcion).
				item = coloresHexadecimales(arrayColors);
				// 2.4) Crear nuevo arreglo con colores definitivos y sus variables (funcion).
				item = crearColores(item,colorVariables);
				// 2.5) Crear estilos CSS Root.
				item = construirCssRoot(item);
				// 2.6) Agregar estilos CSS generales.
				item = item + construirCssGeneral();
				// 2.1) Llenar campo.
				field2.set( item );
			} );
		});
	});
}

/**
 * 2.3) Actualizar arrayColors con colores definitivos en Hexadecimal.
 * @param {*} arreglo Matriz de colores.
 * @returns Develve nuevo arreglo con valores hexadecimales.
 */
function coloresHexadecimales(arreglo){
	// Arreglo nuevo.
	let nuevoArreglo = [];
	arreglo.forEach(function(item){
		let nombre = item.name;
		let color = wp.customize.value( item.id )();
		nuevoArreglo.push({name:nombre, hexCol:color});
	});
	return nuevoArreglo;
}

/**
 * 2.4) Crear variables de color a partir de los valores predeterminados de bootstrap.
 * @param {*} arreglo recibe matriz de colores
 * @param {*} variables nuevo array con colores variables
 * @returns devuelve arreglo con todas las variables de color.
 */
 function crearColores(arreglo, variables){
	let colorAll = [];
	// Por cada tono, crear variables comenzando en -25 (default).
	for (var color of arreglo) {
		// 2.4.1) Agrupar los datos en un solo arreglo.
		colorAll = colorAll.concat(tonosVariables(color, variables));
	}
	// Agrupar todos los tonos HEX.
	let nuevoArray = arreglo.concat(colorAll);
	// 2.4.2) Agregar tonos RGB con función y obtener nuevo arreglo.
	nuevoArray = agregarTonosRGB(nuevoArray);
	return nuevoArray;
}

/**
 * 2.4.1) Modificar colores a partir de color principal, modificar HSL.
 * @param {*} color STR Recibe el valor hexadecimal.
 * @param {*} tonos ARR Recibe arreglo de tonos variables, de oscuro a claro.
 * @param {*} media STR -50 a 50, regula luz de color.
 * @returns Devuelve cada variable segun el color.
 */
function tonosVariables( color, tonos, media = -25 ){
	let colorVars = [],
		newColor = '',
		sufijo = '',
		newName = '';

    for (let index = 0; index < tonos.length; index++) {
		// Cambiar la escala de tonos por indice.
		// Tonos oscuros (negativo a positivo).
		if ( index > 0 ){
			// Aumentar la escala en multiplos de 5.
			media = media + 5;
		}
		// Tonos claros (positivos).
		if ( index > 4 ){
			// tonos claros recalcular 25 ++3.
			if (index === 5){
				media = 25;
			}
			if ( index > 6 ){
				media = media - 5 + 3;
			}
		}
		// 3.1) funcion para modificar tonos.
		newColor = HexAHslvar(color.hexCol, 0, 0, media);
		sufijo = '-'+tonos[index];
		newName = color.name + sufijo;
		colorVars.push({name:newName, hexCol:newColor});
    }
	return colorVars;
}

/**
 * 2.4.2) Agregar los valores RGB
 * @param {*} arreglo recibe grupo de colores predeterminados y variables.
 * @returns Devuelve nuevo array con todos los colores y sus variables en RGB.
 */
function agregarTonosRGB(arreglo){
	// 3.2) convertir a RGB.
	const newValRgb = [];
	for (var color of arreglo) {
		newValRgb.push({
					name:color.name,
					hexCol:color.hexCol,
					alias:color.name + '-rgb',
					rgbCol: hexToRgb(color.hexCol)
				});
	}
	return newValRgb;
}

/** 3) Formulas para cambio de colores. */

/**
 * Hexadecimal hsl ekiline, recibe Hexadecimal y con variables de luz modifica el tono.
 * @param {*} hex recibe HEX
 * @param {*} nh modificador hue
 * @param {*} ns modificador saturation
 * @param {*} nl modificador lightness
 * @returns Devuelve HEX modificado.
 */
 function HexAHslvar(hex, nh, ns, nl) {

	hex = hex.replace('#', '');

	var r = parseInt(hex.substring(0, 2), 16);
	var g = parseInt(hex.substring(2, 4), 16);
	var b = parseInt(hex.substring(4, 6), 16);
	//para mantener la opacidad la extraemos en una variable alternativa
	var opa = hex.substring(6, 8);

	r /= 255;
	g /= 255;
	b /= 255;

	var max = Math.max(r, g, b),
		min = Math.min(r, g, b);
	var h,
		s,
		l = (max + min) / 2;

	if (max == min) {
		h = s = 0;
		// achromatic
	} else {
		var d = max - min;
		s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
		switch(max) {
		case r:
			h = (g - b) / d + (g < b ? 6 : 0);
			break;
		case g:
			h = (b - r) / d + 2;
			break;
		case b:
			h = (r - g) / d + 4;
			break;
		}
		h /= 6;
	}

	s = s * 100;
	s = Math.round(s);
	l = l * 100;
	l = Math.round(l);
	h = Math.round(360 * h);

	h = h + nh;
	s = s + ns;
	l = l + nl;

	var r2,
		g2,
		b2,
		m,
		c,
		x;

	if (!isFinite(h))
		h = 0;
	if (!isFinite(s))
		s = 0;
	if (!isFinite(l))
		l = 0;

	h /= 60;
	if (h < 0)
		h = 6 - (-h % 6);
	h %= 6;

	s = Math.max(0, Math.min(1, s / 100));
	l = Math.max(0, Math.min(1, l / 100));

	c = (1 - Math.abs((2 * l) - 1)) * s;
	x = c * (1 - Math.abs((h % 2) - 1));

	if (h < 1) {
		r2 = c;
		g2 = x;
		b2 = 0;
	} else if (h < 2) {
		r2 = x;
		g2 = c;
		b2 = 0;
	} else if (h < 3) {
		r2 = 0;
		g2 = c;
		b2 = x;
	} else if (h < 4) {
		r2 = 0;
		g2 = x;
		b2 = c;
	} else if (h < 5) {
		r2 = x;
		g2 = 0;
		b2 = c;
	} else {
		r2 = c;
		g2 = 0;
		b2 = x;
	}

	m = l - c / 2;
	r2 = Math.round((r2 + m) * 255);
	g2 = Math.round((g2 + m) * 255);
	b2 = Math.round((b2 + m) * 255);

	var fullrgb = [r2, g2, b2];

	function hextract(x) {
		return ('0' + parseInt(x).toString(16)).slice(-2);
	}

	return '#' + hextract(fullrgb[0]) + hextract(fullrgb[1]) + hextract(fullrgb[2]) + opa;
}

/**
 * 3.2) Formulas para cambio de colores.
 * Hexadecimal a RGBA, variable extra en caso de necesitar controlar la opacidad.
 * @param {*} hex recibe color duro HEX.
 * @param {*} op establece opacidad 1.
 * @returns color hexadecimal con opacidad.
 */
 function hexToRgb(hex) {
	var c = hex.substring(1).split('');
		if (c.length == 3) {
			c = [c[0], c[0], c[1], c[1], c[2], c[2]];
		}
		c = '0x' + c.join('');
		return [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',');
}

/**
 * 2.5) Crear ROOT de colores totales.
 * @param {*} arreglo Recibe conjunto de colores.
 * @returns devuelve fragmento CSS con variables ROOT.
 */
 function construirCssRoot(arreglo){
	 let listItem = '';
	 arreglo.forEach(
		 function (element) {
			 listItem += element.name + ':' + element.hexCol + ';\n';
		 }
	 );
	 arreglo.forEach(
		 function (element) {
			 listItem += element.alias + ':' + element.rgbCol + ';\n';
		 }
	 );
	 listItem = ':root {\n' + listItem + '}\n';
	 return listItem;
 }

/**
 * Texto CSS con valores de bootstrap, solo modifica la variable [var(--###)].
 * @returns Devuleve CSS integro.
 */
function construirCssGeneral(){
	let cssall = '';
	/*General*/
	cssall += 'body{color:var(--bs-body-color);background-color:var(--bs-body-bg);-webkit-tap-highlight-color:rgba(var(--bs-black-rgb),0);}\n';
	cssall += 'mark,.mark{background-color:var(--bs-warning-table-bg);}\n';
	cssall += 'a{color:var(--bs-primary);}\n';
	cssall += 'a:hover{color:var(--bs-primary-link-hover);}\n';
	cssall += 'code{color:var(--bs-pink);}\n';
	cssall += 'kbd{color:var(--bs-white);background-color:var(--bs-gray-900);}\n';
	cssall += 'caption{color:var(--bs-gray-600);}\n';
	cssall += '.blockquote-footer{color:var(--bs-gray-600);}\n';
	cssall += '.img-thumbnail{background-color:var(--bs-white);border:1px solid var(--bs-gray-300);}\n';
	cssall += '.figure-caption{color:var(--bs-gray-600);}\n';
	/**tablas**/
	cssall += '.table{--bs-table-bg:transparent;--bs-table-accent-bg:transparent;--bs-table-striped-color:var(--bs-gray-900);--bs-table-striped-bg:rgba(var(--bs-black-rgb),0.05);--bs-table-active-color:var(--bs-gray-900);--bs-table-active-bg:rgba(var(--bs-black-rgb),0.1);--bs-table-hover-color:var(--bs-gray-900);--bs-table-hover-bg:rgba(var(--bs-black-rgb),0.075);color:var(--bs-gray-900);border-color:var(--bs-gray-300);}\n';
	cssall += '.table>:not(caption)>*>*{background-color:var(--bs-table-bg);box-shadow:inset 0 0 0 9999px var(--bs-table-accent-bg);}\n';
	cssall += '.table-striped>tbody>tr:nth-of-type(odd){--bs-table-accent-bg:var(--bs-table-striped-bg);color:var(--bs-table-striped-color);}\n';
	cssall += '.table-active{--bs-table-accent-bg:var(--bs-table-active-bg);color:var(--bs-table-active-color);}\n';
	cssall += '.table-hover>tbody>tr:hover{--bs-table-accent-bg:var(--bs-table-hover-bg);color:var(--bs-table-hover-color);}\n';
	cssall += '.table-primary{--bs-table-bg:var(--bs-primary-table-bg);--bs-table-striped-bg:var(--bs-primary-table-striped-bg);--bs-table-striped-color:var(--bs-black);--bs-table-active-bg:var(--bs-primary-table-active-bg);--bs-table-active-color:var(--bs-black);--bs-table-hover-bg:var(--bs-primary-table-hover-bg);--bs-table-hover-color:var(--bs-black);color:var(--bs-black);border-color:var(--bs-primary-table-active-bg);}\n';
	cssall += '.table-secondary{--bs-table-bg:var(--bs-secondary-table-bg);--bs-table-striped-bg:var(--bs-secondary-table-striped-bg);--bs-table-striped-color:var(--bs-black);--bs-table-active-bg:var(--bs-secondary-table-active-bg);--bs-table-active-color:var(--bs-black);--bs-table-hover-bg:var(--bs-secondary-table-hover-bg);--bs-table-hover-color:var(--bs-black);color:var(--bs-black);border-color:var(--bs-secondary-table-active-bg);}\n';
	cssall += '.table-success{--bs-table-bg:var(--bs-success-table-bg);--bs-table-striped-bg:var(--bs-success-table-striped-bg);--bs-table-striped-color:var(--bs-black);--bs-table-active-bg:var(--bs-success-table-active-bg);--bs-table-active-color:var(--bs-black);--bs-table-hover-bg:var(--bs-success-table-hover-bg);--bs-table-hover-color:var(--bs-black);color:var(--bs-black);border-color:var(--bs-success-table-active-bg);}\n';
	cssall += '.table-info{--bs-table-bg:var(--bs-info-table-bg);--bs-table-striped-bg:var(--bs-info-table-striped-bg);--bs-table-striped-color:var(--bs-black);--bs-table-active-bg:var(--bs-info-table-active-bg);--bs-table-active-color:var(--bs-black);--bs-table-hover-bg:var(--bs-info-table-hover-bg);--bs-table-hover-color:var(--bs-black);color:var(--bs-black);border-color:var(--bs-info-table-active-bg);}\n';
	cssall += '.table-warning{--bs-table-bg:var(--bs-warning-table-bg);--bs-table-striped-bg:var(--bs-warning-table-striped-bg);--bs-table-striped-color:var(--bs-black);--bs-table-active-bg:var(--bs-warning-table-active-bg);--bs-table-active-color:var(--bs-black);--bs-table-hover-bg:var(--bs-warning-table-hover-bg);--bs-table-hover-color:var(--bs-black);color:var(--bs-black);border-color:var(--bs-warning-table-active-bg);}\n';
	cssall += '.table-danger{--bs-table-bg:var(--bs-danger-table-bg);--bs-table-striped-bg:var(--bs-danger-table-striped-bg);--bs-table-striped-color:var(--bs-black);--bs-table-active-bg:var(--bs-danger-table-active-bg);--bs-table-active-color:var(--bs-black);--bs-table-hover-bg:var(--bs-danger-table-hover-bg);--bs-table-hover-color:var(--bs-black);color:var(--bs-black);border-color:var(--bs-danger-table-active-bg);}\n';
	cssall += '.table-light{--bs-table-bg:var(--bs-light);--bs-table-striped-bg:var(--bs-light-table-striped-bg);--bs-table-striped-color:var(--bs-black);--bs-table-active-bg:var(--bs-light-table-active-bg);--bs-table-active-color:var(--bs-black);--bs-table-hover-bg:var(--bs-light-table-hover-bg);--bs-table-hover-color:var(--bs-black);color:var(--bs-black);border-color:var(--bs-light-table-active-bg);}\n';
	cssall += '.table-dark{--bs-table-bg:var(--bs-dark);--bs-table-striped-bg:var(--bs-dark-table-striped-bg);--bs-table-striped-color:var(--bs-black);--bs-table-active-bg:var(--bs-dark-table-active-bg);--bs-table-active-color:var(--bs-black);--bs-table-hover-bg:var(--bs-dark-table-hover-bg);--bs-table-hover-color:var(--bs-black);color:var(--bs-black);border-color:var(--bs-dark-table-active-bg);}\n';
	/**Formularios**/
	cssall += '.form-text{color:var(--bs-gray-600);}\n';
	cssall += '.form-control{color:var(--bs-gray-900);background-color:var(--bs-white);border:1px solid var(--bs-gray-400);}\n';
	cssall += '.form-control:focus{color:var(--bs-gray-900);background-color:var(--bs-white);border-color:var(--bs-primary-form-control-focus);box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.25);}\n';
	cssall += '.form-control::-moz-placeholder{color:var(--bs-gray-600);}\n';
	cssall += '.form-control::placeholder{color:var(--bs-gray-600);}\n';
	cssall += '.form-control:disabled,.form-control[readonly]{background-color:var(--bs-gray-200);}\n';
	cssall += '.form-control::file-selector-button{color:var(--bs-gray-900);background-color:var(--bs-gray-200);}\n';
	cssall += '.form-control:hover:not(:disabled):not([readonly])::file-selector-button{background-color:var(--bs-gray-300);}\n';
	cssall += '.form-control::-webkit-file-upload-button{color:var(--bs-gray-900);background-color:var(--bs-gray-200);}\n';
	cssall += '.form-control:hover:not(:disabled):not([readonly])::-webkit-file-upload-button{background-color:var(--bs-gray-300);}\n';
	cssall += '.form-control-plaintext{color:var(--bs-gray-900);}\n';
	cssall += '.form-select{color:var(--bs-gray-900);background-color:var(--bs-white);border:1px solid var(--bs-gray-400);}\n';
	cssall += '.form-select:focus{border-color:var(--bs-primary-form-control-focus);box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.25);}\n';
	cssall += '.form-select:disabled{background-color:var(--bs-gray-200);}\n';
	cssall += '.form-select:-moz-focusring{text-shadow:0 0 0 var(--bs-gray-900);}\n';
	cssall += '.form-check-input{background-color:var(--bs-white);border:1px solid rgba(var(--bs-black-rgb),0.25);}\n';
	cssall += '.form-check-input:focus{border-color:var(--bs-primary-form-control-focus);box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.25);}\n';
	cssall += '.form-check-input:checked{background-color:var(--bs-primary);border-color:var(--bs-primary);}\n';
	cssall += '.form-check-input[type="checkbox"]:indeterminate{background-color:var(--bs-primary);border-color:var(--bs-primary);}\n';
	cssall += '.btn-check{clip:rect(var(--bs-black-rgb),0);}\n';
	cssall += '.form-range:focus::-webkit-slider-thumb{box-shadow:0 0 0 1px var(--bs-white),0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.25);}\n';
	cssall += '.form-range:focus::-moz-range-thumb{box-shadow:0 0 0 1px var(--bs-white),0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.25);}\n';
	cssall += '.form-range::-webkit-slider-thumb{background-color:var(--bs-primary);}\n';
	cssall += '.form-range::-webkit-slider-thumb:active{background-color:var(--bs-primary-form-range-active);}\n';
	cssall += '.form-range::-webkit-slider-runnable-track{background-color:var(--bs-gray-300);}\n';
	cssall += '.form-range::-moz-range-thumb{background-color:var(--bs-primary);}\n';
	cssall += '.form-range::-moz-range-thumb:active{background-color:var(--bs-primary-form-range-active);}\n';
	cssall += '.form-range::-moz-range-track{background-color:var(--bs-gray-300);}\n';
	cssall += '.form-range:disabled::-webkit-slider-thumb{background-color:var(--bs-gray-500);}\n';
	cssall += '.form-range:disabled::-moz-range-thumb{background-color:var(--bs-gray-500);}\n';
	cssall += '.input-group-text{color:var(--bs-gray-900);background-color:var(--bs-gray-200);border:1px solid var(--bs-gray-400);}\n';
	cssall += '.valid-feedback{color:var(--bs-success);}\n';
	cssall += '.valid-tooltip{color:var(--bs-white);background-color:rgba(var(--bs-success-rgb),0.9);}\n';
	cssall += '.was-validated .form-control:valid,.form-control.is-valid{border-color:var(--bs-success);}\n';
	cssall += '.was-validated .form-control:valid:focus,.form-control.is-valid:focus{border-color:var(--bs-success);box-shadow:0 0 0 0.25rem rgba(var(--bs-success-rgb),0.25);}\n';
	cssall += '.was-validated .form-select:valid,.form-select.is-valid{border-color:var(--bs-success);}\n';
	cssall += '.was-validated .form-select:valid:focus,.form-select.is-valid:focus{border-color:var(--bs-success);box-shadow:0 0 0 0.25rem rgba(var(--bs-success-rgb),0.25);}\n';
	cssall += '.was-validated .form-check-input:valid,.form-check-input.is-valid{border-color:var(--bs-success);}\n';
	cssall += '.was-validated .form-check-input:valid:checked,.form-check-input.is-valid:checked{background-color:var(--bs-success);}\n';
	cssall += '.was-validated .form-check-input:valid:focus,.form-check-input.is-valid:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-success-rgb),0.25);}\n';
	cssall += '.was-validated .form-check-input:valid~.form-check-label,.form-check-input.is-valid~.form-check-label{color:var(--bs-success);}\n';
	cssall += '.invalid-feedback{color:var(--bs-danger);}\n';
	cssall += '.invalid-tooltip{color:var(--bs-white);background-color:rgba(var(--bs-danger-rgb),0.9);}\n';
	cssall += '.was-validated .form-control:invalid,.form-control.is-invalid{border-color:var(--bs-danger);}\n';
	cssall += '.was-validated .form-control:invalid:focus,.form-control.is-invalid:focus{border-color:var(--bs-danger);box-shadow:0 0 0 0.25rem rgba(var(--bs-danger-rgb),0.25);}\n';
	cssall += '.was-validated .form-select:invalid,.form-select.is-invalid{border-color:var(--bs-danger);}\n';
	cssall += '.was-validated .form-select:invalid:focus,.form-select.is-invalid:focus{border-color:var(--bs-danger);box-shadow:0 0 0 0.25rem rgba(var(--bs-danger-rgb),0.25);}\n';
	cssall += '.was-validated .form-check-input:invalid,.form-check-input.is-invalid{border-color:var(--bs-danger);}\n';
	cssall += '.was-validated .form-check-input:invalid:checked,.form-check-input.is-invalid:checked{background-color:var(--bs-danger);}\n';
	cssall += '.was-validated .form-check-input:invalid:focus,.form-check-input.is-invalid:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-danger-rgb),0.25);}\n';
	cssall += '.was-validated .form-check-input:invalid~.form-check-label,.form-check-input.is-invalid~.form-check-label{color:var(--bs-danger);}\n';
	/**Botones**/
	cssall += '.btn{color:var(--bs-gray-900);}\n';
	cssall += '.btn:hover{color:var(--bs-gray-900);}\n';
	cssall += '.btn-check:focus+.btn,.btn:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.25);}\n';
	cssall += '.btn-primary{color:var(--bs-white);background-color:var(--bs-primary);border-color:var(--bs-primary);}\n';
	cssall += '.btn-primary:hover{color:var(--bs-white);background-color:var(--bs-primary-btn-hover);border-color:var(--bs-primary-link-hover);}\n';
	cssall += '.btn-check:focus+.btn-primary,.btn-primary:focus{color:var(--bs-white);background-color:var(--bs-primary-btn-hover);border-color:var(--bs-primary-link-hover);box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-primary,.btn-check:active+.btn-primary,.btn-primary:active,.btn-primary.active,.show>.btn-primary.dropdown-toggle{color:var(--bs-white);background-color:var(--bs-primary-link-hover);border-color:var(--bs-primary-btn-active);}\n';
	cssall += '.btn-check:checked+.btn-primary:focus,.btn-check:active+.btn-primary:focus,.btn-primary:active:focus,.btn-primary.active:focus,.show>.btn-primary.dropdown-toggle:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.5);}\n';
	cssall += '.btn-primary:disabled,.btn-primary.disabled{color:var(--bs-white);background-color:var(--bs-primary);border-color:var(--bs-primary);}\n';
	cssall += '.btn-secondary{color:var(--bs-white);background-color:var(--bs-secondary);border-color:var(--bs-secondary);}\n';
	cssall += '.btn-secondary:hover{color:var(--bs-white);background-color:var(--bs-secondary-btn-hover);border-color:var(--bs-secondary-btn-active);}\n';
	cssall += '.btn-check:focus+.btn-secondary,.btn-secondary:focus{color:var(--bs-white);background-color:var(--bs-secondary-btn-hover);border-color:var(--bs-secondary-btn-active);box-shadow:0 0 0 0.25rem rgba(var(--bs-secondary-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-secondary,.btn-check:active+.btn-secondary,.btn-secondary:active,.btn-secondary.active,.show>.btn-secondary.dropdown-toggle{color:var(--bs-white);background-color:var(--bs-secondary-btn-active);border-color:var(--bs-secondary-link-hover);}\n';
	cssall += '.btn-check:checked+.btn-secondary:focus,.btn-check:active+.btn-secondary:focus,.btn-secondary:active:focus,.btn-secondary.active:focus,.show>.btn-secondary.dropdown-toggle:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-secondary-rgb),0.5);}\n';
	cssall += '.btn-secondary:disabled,.btn-secondary.disabled{color:var(--bs-white);background-color:var(--bs-secondary);border-color:var(--bs-secondary);}\n';
	cssall += '.btn-success{color:var(--bs-white);background-color:var(--bs-success);border-color:var(--bs-success);}\n';
	cssall += '.btn-success:hover{color:var(--bs-white);background-color:var(--bs-success-btn-hover);border-color:var(--bs-success-btn-active);}\n';
	cssall += '.btn-check:focus+.btn-success,.btn-success:focus{color:var(--bs-white);background-color:var(--bs-success-btn-hover);border-color:var(--bs-success-btn-active);box-shadow:0 0 0 0.25rem rgba(var(--bs-success-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-success,.btn-check:active+.btn-success,.btn-success:active,.btn-success.active,.show>.btn-success.dropdown-toggle{color:var(--bs-white);background-color:var(--bs-success-btn-active);border-color:var(--bs-success-link-hover);}\n';
	cssall += '.btn-check:checked+.btn-success:focus,.btn-check:active+.btn-success:focus,.btn-success:active:focus,.btn-success.active:focus,.show>.btn-success.dropdown-toggle:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-success-rgb),0.5);}\n';
	cssall += '.btn-success:disabled,.btn-success.disabled{color:var(--bs-white);background-color:var(--bs-success);border-color:var(--bs-success);}\n';
	cssall += '.btn-info{color:var(--bs-black);background-color:var(--bs-info);border-color:var(--bs-info);}\n';
	cssall += '.btn-info:hover{color:var(--bs-black);background-color:var(--bs-info-btn-hover);border-color:var(--bs-info-btn-active);}\n';
	cssall += '.btn-check:focus+.btn-info,.btn-info:focus{color:var(--bs-black);background-color:var(--bs-info-btn-hover);border-color:var(--bs-info-btn-active);box-shadow:0 0 0 0.25rem rgba(var(--bs-info-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-info,.btn-check:active+.btn-info,.btn-info:active,.btn-info.active,.show>.btn-info.dropdown-toggle{color:var(--bs-black);background-color:var(--bs-info-link-hover);border-color:var(--bs-info-btn-active);}\n';
	cssall += '.btn-check:checked+.btn-info:focus,.btn-check:active+.btn-info:focus,.btn-info:active:focus,.btn-info.active:focus,.show>.btn-info.dropdown-toggle:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-info-rgb),0.5);}\n';
	cssall += '.btn-info:disabled,.btn-info.disabled{color:var(--bs-black);background-color:var(--bs-info);border-color:var(--bs-info);}\n';
	cssall += '.btn-warning{color:var(--bs-black);background-color:var(--bs-warning);border-color:var(--bs-warning);}\n';
	cssall += '.btn-warning:hover{color:var(--bs-black);background-color:var(--bs-warning-btn-hover);border-color:var(--bs-warning-btn-active);}\n';
	cssall += '.btn-check:focus+.btn-warning,.btn-warning:focus{color:var(--bs-black);background-color:var(--bs-warning-btn-hover);border-color:var(--bs-warning-btn-active);box-shadow:0 0 0 0.25rem rgba(var(--bs-warning-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-warning,.btn-check:active+.btn-warning,.btn-warning:active,.btn-warning.active,.show>.btn-warning.dropdown-toggle{color:var(--bs-black);background-color:var(--bs-warning-link-hover);border-color:var(--bs-warning-btn-active);}\n';
	cssall += '.btn-check:checked+.btn-warning:focus,.btn-check:active+.btn-warning:focus,.btn-warning:active:focus,.btn-warning.active:focus,.show>.btn-warning.dropdown-toggle:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-warning-rgb),0.5);}\n';
	cssall += '.btn-warning:disabled,.btn-warning.disabled{color:var(--bs-black);background-color:var(--bs-warning);border-color:var(--bs-warning);}\n';
	cssall += '.btn-danger{color:var(--bs-white);background-color:var(--bs-danger);border-color:var(--bs-danger);}\n';
	cssall += '.btn-danger:hover{color:var(--bs-white);background-color:var(--bs-danger-btn-hover);border-color:var(--bs-danger-btn-active);}\n';
	cssall += '.btn-check:focus+.btn-danger,.btn-danger:focus{color:var(--bs-white);background-color:var(--bs-danger-btn-hover);border-color:var(--bs-danger-btn-active);box-shadow:0 0 0 0.25rem rgba(var(--bs-danger-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-danger,.btn-check:active+.btn-danger,.btn-danger:active,.btn-danger.active,.show>.btn-danger.dropdown-toggle{color:var(--bs-white);background-color:var(--bs-danger-btn-active);border-color:var(--bs-danger-link-hover);}\n';
	cssall += '.btn-check:checked+.btn-danger:focus,.btn-check:active+.btn-danger:focus,.btn-danger:active:focus,.btn-danger.active:focus,.show>.btn-danger.dropdown-toggle:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-danger-rgb),0.5);}\n';
	cssall += '.btn-danger:disabled,.btn-danger.disabled{color:var(--bs-white);background-color:var(--bs-danger);border-color:var(--bs-danger);}\n';
	cssall += '.btn-light{color:var(--bs-black);background-color:var(--bs-light);border-color:var(--bs-light);}\n';
	cssall += '.btn-light:hover{color:var(--bs-black);background-color:var(--bs-light-btn-hover);border-color:var(--bs-light-btn-active);}\n';
	cssall += '.btn-check:focus+.btn-light,.btn-light:focus{color:var(--bs-black);background-color:var(--bs-light-btn-hover);border-color:var(--bs-light-btn-active);box-shadow:0 0 0 0.25rem rgba(var(--bs-light-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-light,.btn-check:active+.btn-light,.btn-light:active,.btn-light.active,.show>.btn-light.dropdown-toggle{color:var(--bs-black);background-color:var(--bs-light-link-hover);border-color:var(--bs-light-btn-active);}\n';
	cssall += '.btn-check:checked+.btn-light:focus,.btn-check:active+.btn-light:focus,.btn-light:active:focus,.btn-light.active:focus,.show>.btn-light.dropdown-toggle:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-light-rgb),0.5);}\n';
	cssall += '.btn-light:disabled,.btn-light.disabled{color:var(--bs-black);background-color:var(--bs-light);border-color:var(--bs-light);}\n';
	cssall += '.btn-dark{color:var(--bs-black);background-color:var(--bs-dark);border-color:var(--bs-dark);}\n';
	cssall += '.btn-dark:hover{color:var(--bs-black);background-color:var(--bs-dark-btn-hover);border-color:var(--bs-dark-btn-active);}\n';
	cssall += '.btn-check:focus+.btn-dark,.btn-dark:focus{color:var(--bs-black);background-color:var(--bs-dark-btn-hover);border-color:var(--bs-dark-btn-active);box-shadow:0 0 0 0.25rem rgba(var(--bs-dark-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-dark,.btn-check:active+.btn-dark,.btn-dark:active,.btn-dark.active,.show>.btn-dark.dropdown-toggle{color:var(--bs-black);background-color:var(--bs-dark-link-hover);border-color:var(--bs-dark-btn-active);}\n';
	cssall += '.btn-check:checked+.btn-dark:focus,.btn-check:active+.btn-dark:focus,.btn-dark:active:focus,.btn-dark.active:focus,.show>.btn-dark.dropdown-toggle:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-dark-rgb),0.5);}\n';
	cssall += '.btn-dark:disabled,.btn-dark.disabled{color:var(--bs-black);background-color:var(--bs-dark);border-color:var(--bs-dark);}\n';
	cssall += '.btn-outline-primary{color:var(--bs-primary);border-color:var(--bs-primary);}\n';
	cssall += '.btn-outline-primary:hover{color:var(--bs-white);background-color:var(--bs-primary);border-color:var(--bs-primary);}\n';
	cssall += '.btn-check:focus+.btn-outline-primary,.btn-outline-primary:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-outline-primary,.btn-check:active+.btn-outline-primary,.btn-outline-primary:active,.btn-outline-primary.active,.btn-outline-primary.dropdown-toggle.show{color:var(--bs-white);background-color:var(--bs-primary);border-color:var(--bs-primary);}\n';
	cssall += '.btn-check:checked+.btn-outline-primary:focus,.btn-check:active+.btn-outline-primary:focus,.btn-outline-primary:active:focus,.btn-outline-primary.active:focus,.btn-outline-primary.dropdown-toggle.show:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.5);}\n';
	cssall += '.btn-outline-primary:disabled,.btn-outline-primary.disabled{color:var(--bs-primary);}\n';
	cssall += '.btn-outline-secondary{color:var(--bs-secondary);border-color:var(--bs-secondary);}\n';
	cssall += '.btn-outline-secondary:hover{color:var(--bs-white);background-color:var(--bs-secondary);border-color:var(--bs-secondary);}\n';
	cssall += '.btn-check:focus+.btn-outline-secondary,.btn-outline-secondary:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-secondary-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-outline-secondary,.btn-check:active+.btn-outline-secondary,.btn-outline-secondary:active,.btn-outline-secondary.active,.btn-outline-secondary.dropdown-toggle.show{color:var(--bs-white);background-color:var(--bs-secondary);border-color:var(--bs-secondary);}\n';
	cssall += '.btn-check:checked+.btn-outline-secondary:focus,.btn-check:active+.btn-outline-secondary:focus,.btn-outline-secondary:active:focus,.btn-outline-secondary.active:focus,.btn-outline-secondary.dropdown-toggle.show:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-secondary-rgb),0.5);}\n';
	cssall += '.btn-outline-secondary:disabled,.btn-outline-secondary.disabled{color:var(--bs-secondary);}\n';
	cssall += '.btn-outline-success{color:var(--bs-success);border-color:var(--bs-success);}\n';
	cssall += '.btn-outline-success:hover{color:var(--bs-white);background-color:var(--bs-success);border-color:var(--bs-success);}\n';
	cssall += '.btn-check:focus+.btn-outline-success,.btn-outline-success:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-success-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-outline-success,.btn-check:active+.btn-outline-success,.btn-outline-success:active,.btn-outline-success.active,.btn-outline-success.dropdown-toggle.show{color:var(--bs-white);background-color:var(--bs-success);border-color:var(--bs-success);}\n';
	cssall += '.btn-check:checked+.btn-outline-success:focus,.btn-check:active+.btn-outline-success:focus,.btn-outline-success:active:focus,.btn-outline-success.active:focus,.btn-outline-success.dropdown-toggle.show:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-success-rgb),0.5);}\n';
	cssall += '.btn-outline-success:disabled,.btn-outline-success.disabled{color:var(--bs-success);}\n';
	cssall += '.btn-outline-info{color:var(--bs-info);border-color:var(--bs-info);}\n';
	cssall += '.btn-outline-info:hover{color:var(--bs-black);background-color:var(--bs-info);border-color:var(--bs-info);}\n';
	cssall += '.btn-check:focus+.btn-outline-info,.btn-outline-info:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-info-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-outline-info,.btn-check:active+.btn-outline-info,.btn-outline-info:active,.btn-outline-info.active,.btn-outline-info.dropdown-toggle.show{color:var(--bs-black);background-color:var(--bs-info);border-color:var(--bs-info);}\n';
	cssall += '.btn-check:checked+.btn-outline-info:focus,.btn-check:active+.btn-outline-info:focus,.btn-outline-info:active:focus,.btn-outline-info.active:focus,.btn-outline-info.dropdown-toggle.show:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-info-rgb),0.5);}\n';
	cssall += '.btn-outline-info:disabled,.btn-outline-info.disabled{color:var(--bs-info);}\n';
	cssall += '.btn-outline-warning{color:var(--bs-warning);border-color:var(--bs-warning);}\n';
	cssall += '.btn-outline-warning:hover{color:var(--bs-black);background-color:var(--bs-warning);border-color:var(--bs-warning);}\n';
	cssall += '.btn-check:focus+.btn-outline-warning,.btn-outline-warning:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-warning-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-outline-warning,.btn-check:active+.btn-outline-warning,.btn-outline-warning:active,.btn-outline-warning.active,.btn-outline-warning.dropdown-toggle.show{color:var(--bs-black);background-color:var(--bs-warning);border-color:var(--bs-warning);}\n';
	cssall += '.btn-check:checked+.btn-outline-warning:focus,.btn-check:active+.btn-outline-warning:focus,.btn-outline-warning:active:focus,.btn-outline-warning.active:focus,.btn-outline-warning.dropdown-toggle.show:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-warning-rgb),0.5);}\n';
	cssall += '.btn-outline-warning:disabled,.btn-outline-warning.disabled{color:var(--bs-warning);}\n';
	cssall += '.btn-outline-danger{color:var(--bs-danger);border-color:var(--bs-danger);}\n';
	cssall += '.btn-outline-danger:hover{color:var(--bs-white);background-color:var(--bs-danger);border-color:var(--bs-danger);}\n';
	cssall += '.btn-check:focus+.btn-outline-danger,.btn-outline-danger:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-danger-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-outline-danger,.btn-check:active+.btn-outline-danger,.btn-outline-danger:active,.btn-outline-danger.active,.btn-outline-danger.dropdown-toggle.show{color:var(--bs-white);background-color:var(--bs-danger);border-color:var(--bs-danger);}\n';
	cssall += '.btn-check:checked+.btn-outline-danger:focus,.btn-check:active+.btn-outline-danger:focus,.btn-outline-danger:active:focus,.btn-outline-danger.active:focus,.btn-outline-danger.dropdown-toggle.show:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-danger-rgb),0.5);}\n';
	cssall += '.btn-outline-danger:disabled,.btn-outline-danger.disabled{color:var(--bs-danger);}\n';
	cssall += '.btn-outline-light{color:var(--bs-light);border-color:var(--bs-light);}\n';
	cssall += '.btn-outline-light:hover{color:var(--bs-black);background-color:var(--bs-light);border-color:var(--bs-light);}\n';
	cssall += '.btn-check:focus+.btn-outline-light,.btn-outline-light:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-light-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-outline-light,.btn-check:active+.btn-outline-light,.btn-outline-light:active,.btn-outline-light.active,.btn-outline-light.dropdown-toggle.show{color:var(--bs-black);background-color:var(--bs-light);border-color:var(--bs-light);}\n';
	cssall += '.btn-check:checked+.btn-outline-light:focus,.btn-check:active+.btn-outline-light:focus,.btn-outline-light:active:focus,.btn-outline-light.active:focus,.btn-outline-light.dropdown-toggle.show:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-light-rgb),0.5);}\n';
	cssall += '.btn-outline-light:disabled,.btn-outline-light.disabled{color:var(--bs-light);}\n';
	cssall += '.btn-outline-dark{color:var(--bs-dark);border-color:var(--bs-dark);}\n';
	cssall += '.btn-outline-dark:hover{color:var(--bs-black);background-color:var(--bs-dark);border-color:var(--bs-dark);}\n';
	cssall += '.btn-check:focus+.btn-outline-dark,.btn-outline-dark:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-dark-rgb),0.5);}\n';
	cssall += '.btn-check:checked+.btn-outline-dark,.btn-check:active+.btn-outline-dark,.btn-outline-dark:active,.btn-outline-dark.active,.btn-outline-dark.dropdown-toggle.show{color:var(--bs-black);background-color:var(--bs-dark);border-color:var(--bs-dark);}\n';
	cssall += '.btn-check:checked+.btn-outline-dark:focus,.btn-check:active+.btn-outline-dark:focus,.btn-outline-dark:active:focus,.btn-outline-dark.active:focus,.btn-outline-dark.dropdown-toggle.show:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-dark-rgb),0.5);}\n';
	cssall += '.btn-outline-dark:disabled,.btn-outline-dark.disabled{color:var(--bs-dark);}\n';
	cssall += '.btn-link{color:var(--bs-primary);}\n';
	cssall += '.btn-link:hover{color:var(--bs-primary-link-hover);}\n';
	cssall += '.btn-link:disabled,.btn-link.disabled{color:var(--bs-gray-600);}\n';
	/**Dropdowns**/
	cssall += '.dropdown-menu{color:var(--bs-gray-900);background-color:var(--bs-white);border:1px solid rgba(var(--bs-black-rgb),0.15);}\n';
	cssall += '.dropdown-divider{border-top:1px solid rgba(var(--bs-black-rgb),0.15);}\n';
	cssall += '.dropdown-item{color:var(--bs-gray-900);}\n';
	cssall += '.dropdown-item:hover,.dropdown-item:focus{color:var(--bs-gray-800);background-color:var(--bs-gray-200);}\n';
	cssall += '.dropdown-item.active,.dropdown-item:active{color:var(--bs-white);background-color:var(--bs-primary);}\n';
	cssall += '.dropdown-item.disabled,.dropdown-item:disabled{color:var(--bs-gray-500);}\n';
	cssall += '.dropdown-header{color:var(--bs-gray-600);}\n';
	cssall += '.dropdown-item-text{color:var(--bs-gray-900);}\n';
	cssall += '.dropdown-menu-dark{color:var(--bs-gray-300);background-color:var(--bs-gray-800);border-color:rgba(var(--bs-black-rgb),0.15);}\n';
	cssall += '.dropdown-menu-dark .dropdown-item{color:var(--bs-gray-300);}\n';
	cssall += '.dropdown-menu-dark .dropdown-item:hover,.dropdown-menu-dark .dropdown-item:focus{color:var(--bs-white);background-color:rgba(var(--bs-white-rgb),0.15);}\n';
	cssall += '.dropdown-menu-dark .dropdown-item.active,.dropdown-menu-dark .dropdown-item:active{color:var(--bs-white);background-color:var(--bs-primary);}\n';
	cssall += '.dropdown-menu-dark .dropdown-item.disabled,.dropdown-menu-dark .dropdown-item:disabled{color:var(--bs-gray-500);}\n';
	cssall += '.dropdown-menu-dark .dropdown-divider{border-color:rgba(var(--bs-black-rgb),0.15);}\n';
	cssall += '.dropdown-menu-dark .dropdown-item-text{color:var(--bs-gray-300);}\n';
	cssall += '.dropdown-menu-dark .dropdown-header{color:var(--bs-gray-500);}\n';
	/**Navs**/
	cssall += '.nav-link{color:var(--bs-primary);}\n';
	cssall += '.nav-link:hover,.nav-link:focus{color:var(--bs-primary-link-hover);}\n';
	cssall += '.nav-link.disabled{color:var(--bs-gray-600);}\n';
	cssall += '.nav-tabs{border-bottom:1px solid var(--bs-gray-300);}\n';
	cssall += '.nav-tabs .nav-link:hover,.nav-tabs .nav-link:focus{border-color:var(--bs-gray-200) var(--bs-gray-200) var(--bs-gray-300);}\n';
	cssall += '.nav-tabs .nav-link.disabled{color:var(--bs-gray-600);}\n';
	cssall += '.nav-tabs .nav-link.active,.nav-tabs .nav-item.show .nav-link{color:var(--bs-gray-700);background-color:var(--bs-white);border-color:var(--bs-gray-300) var(--bs-gray-300) var(--bs-white);}\n';
	cssall += '.nav-pills .nav-link.active,.nav-pills .show>.nav-link{color:var(--bs-white);background-color:var(--bs-primary);}\n';
	cssall += '.navbar-toggler:focus{box-shadow:0 0 0 0.25rem;}\n';
	cssall += '.navbar-light .navbar-brand{color:rgba(var(--bs-black-rgb),0.9);}\n';
	cssall += '.navbar-light .navbar-brand:hover,.navbar-light .navbar-brand:focus{color:rgba(var(--bs-black-rgb),0.9);}\n';
	cssall += '.navbar-light .navbar-nav .nav-link{color:rgba(var(--bs-black-rgb),0.55);}\n';
	cssall += '.navbar-light .navbar-nav .nav-link:hover,.navbar-light .navbar-nav .nav-link:focus{color:rgba(var(--bs-black-rgb),0.7);}\n';
	cssall += '.navbar-light .navbar-nav .nav-link.disabled{color:rgba(var(--bs-black-rgb),0.3);}\n';
	cssall += '.navbar-light .navbar-nav .show>.nav-link,.navbar-light .navbar-nav .nav-link.active{color:rgba(var(--bs-black-rgb),0.9);}\n';
	cssall += '.navbar-light .navbar-toggler{color:rgba(var(--bs-black-rgb),0.55);border-color:rgba(var(--bs-black-rgb),0.1);}\n';
	cssall += '.navbar-light .navbar-text{color:rgba(var(--bs-black-rgb),0.55);}\n';
	cssall += '.navbar-light .navbar-text a,.navbar-light .navbar-text a:hover,.navbar-light .navbar-text a:focus{color:rgba(var(--bs-black-rgb),0.9);}\n';
	cssall += '.navbar-dark .navbar-brand{color:var(--bs-white);}\n';
	cssall += '.navbar-dark .navbar-brand:hover,.navbar-dark .navbar-brand:focus{color:var(--bs-white);}\n';
	cssall += '.navbar-dark .navbar-nav .nav-link{color:rgba(var(--bs-white-rgb),0.55);}\n';
	cssall += '.navbar-dark .navbar-nav .nav-link:hover,.navbar-dark .navbar-nav .nav-link:focus{color:rgba(var(--bs-white-rgb),0.75);}\n';
	cssall += '.navbar-dark .navbar-nav .nav-link.disabled{color:rgba(var(--bs-white-rgb),0.25);}\n';
	cssall += '.navbar-dark .navbar-nav .show>.nav-link,.navbar-dark .navbar-nav .nav-link.active{color:var(--bs-white);}\n';
	cssall += '.navbar-dark .navbar-toggler{color:rgba(var(--bs-white-rgb),0.55);border-color:rgba(var(--bs-white-rgb),0.1);}\n';
	cssall += '.navbar-dark .navbar-text{color:rgba(var(--bs-white-rgb),0.55);}\n';
	cssall += '.navbar-dark .navbar-text a,.navbar-dark .navbar-text a:hover,.navbar-dark .navbar-text a:focus{color:var(--bs-white);}\n';
	/**cards**/
	cssall += '.card{background-color:var(--bs-white);border:1px solid rgba(var(--bs-black-rgb),0.125);}\n';
	cssall += '.card-header{background-color:rgba(var(--bs-black-rgb),0.03);border-bottom:1px solid rgba(var(--bs-black-rgb),0.125);}\n';
	cssall += '.card-footer{background-color:rgba(var(--bs-black-rgb),0.03);border-top:1px solid rgba(var(--bs-black-rgb),0.125);}\n';
	cssall += '.accordion-button{color:var(--bs-gray-900);background-color:var(--bs-white);}\n';
	cssall += '.accordion-button:not(.collapsed){color:var(--bs-primary);background-color:var(--bs-primary-table-bg);box-shadow:inset 0 -1px 0 rgba(var(--bs-black-rgb),0.125);}\n';
	cssall += '.accordion-button:focus{border-color:var(--bs-primary-form-control-focus);box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.25);}\n';
	cssall += '.accordion-item{background-color:var(--bs-white);border:1px solid rgba(var(--bs-black-rgb),0.125);}\n';
	/**breadcrumb**/
	cssall += '.breadcrumb-item+.breadcrumb-item::before{color:var(--bs-gray-600);}\n';
	cssall += '.breadcrumb-item.active{color:var(--bs-gray-600);}\n';
	/**pagelinks**/
	cssall += '.page-link{color:var(--bs-primary);background-color:var(--bs-white);border:1px solid var(--bs-gray-300);}\n';
	cssall += '.page-link:hover{color:var(--bs-primary-link-hover);background-color:var(--bs-gray-200);border-color:var(--bs-gray-300);}\n';
	cssall += '.page-link:focus{color:var(--bs-primary-link-hover);background-color:var(--bs-gray-200);box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.25);}\n';
	cssall += '.page-item.active .page-link{color:var(--bs-white);background-color:var(--bs-primary);border-color:var(--bs-primary);}\n';
	cssall += '.page-item.disabled .page-link{color:var(--bs-gray-600);background-color:var(--bs-white);border-color:var(--bs-gray-300);}\n';
	/**badge**/
	cssall += '.badge{color:var(--bs-white);}\n';
	/**alerts**/
	cssall += '.alert-primary{color:var(--bs-primary-alert);background-color:var(--bs-primary-table-bg);border-color:var(--bs-primary-form-range-active);}\n';
	cssall += '.alert-primary .alert-link{color:var(--bs-primary-alert-link);}\n';
	cssall += '.alert-secondary{color:var(--bs-secondary-alert);background-color:var(--bs-secondary-table-bg);border-color:var(--bs-secondary-table-active-bg);}\n';
	cssall += '.alert-secondary .alert-link{color:var(--bs-secondary-alert-link);}\n';
	cssall += '.alert-success{color:var(--bs-success-alert);background-color:var(--bs-success-table-bg);border-color:var(--bs-success-table-active-bg);}\n';
	cssall += '.alert-success .alert-link{color:var(--bs-success-alert-link);}\n';
	cssall += '.alert-info{color:var(--bs-info-alert);background-color:var(--bs-info-table-bg);border-color:var(--bs-info-table-active-bg);}\n';
	cssall += '.alert-info .alert-link{color:var(--bs-info-alert-link);}\n';
	cssall += '.alert-warning{color:var(--bs-warning-alert);background-color:var(--bs-warning-table-bg);border-color:var(--bs-warning-table-active-bg);}\n';
	cssall += '.alert-warning .alert-link{color:var(--bs-warning-alert-link);}\n';
	cssall += '.alert-danger{color:var(--bs-danger-alert);background-color:var(--bs-danger-table-bg);border-color:var(--bs-danger-table-active-bg);}\n';
	cssall += '.alert-danger .alert-link{color:var(--bs-danger-alert-link);}\n';
	cssall += '.alert-light{color:var(--bs-light-alert);background-color:var(--bs-light-table-bg);border-color:var(--bs-light-table-active-bg);}\n';
	cssall += '.alert-light .alert-link{color:var(--bs-light-alert-link);}\n';
	cssall += '.alert-dark{color:var(--bs-dark-alert);background-color:var(--bs-dark-table-bg);border-color:var(--bs-dark-table-active-bg);}\n';
	cssall += '.alert-dark .alert-link{color:var(--bs-dark-alert-link);}\n';
	/**progress**/
	cssall += '.progress{background-color:var(--bs-gray-200);}\n';
	cssall += '.progress-bar{color:var(--bs-white);background-color:var(--bs-primary);}\n';
	cssall += '.progress-bar-striped{background-image:linear-gradient(45deg,rgba(var(--bs-white-rgb),0.15) 25%,transparent 25%,transparent 50%,rgba(var(--bs-white-rgb),0.15) 50%,rgba(var(--bs-white-rgb),0.15) 75%,transparent 75%,transparent);}\n';
	/**listgroup**/
	cssall += '.list-group-item-action{color:var(--bs-gray-700);}\n';
	cssall += '.list-group-item-action:hover,.list-group-item-action:focus{color:var(--bs-gray-700);background-color:var(--bs-gray-100);}\n';
	cssall += '.list-group-item-action:active{color:var(--bs-gray-900);background-color:var(--bs-gray-200);}\n';
	cssall += '.list-group-item{color:var(--bs-gray-900);background-color:var(--bs-white);border:1px solid rgba(var(--bs-black-rgb),0.125);}\n';
	cssall += '.list-group-item.disabled,.list-group-item:disabled{color:var(--bs-gray-600);background-color:var(--bs-white);}\n';
	cssall += '.list-group-item.active{color:var(--bs-white);background-color:var(--bs-primary);border-color:var(--bs-primary);}\n';
	cssall += '.list-group-item-primary{color:var(--bs-primary-alert);background-color:var(--bs-primary-table-bg);}\n';
	cssall += '.list-group-item-primary.list-group-item-action:hover,.list-group-item-primary.list-group-item-action:focus{color:var(--bs-primary-alert);background-color:var(--bs-primary-table-active-bg);}\n';
	cssall += '.list-group-item-primary.list-group-item-action.active{color:var(--bs-white);background-color:var(--bs-primary-alert);border-color:var(--bs-primary-alert);}\n';
	cssall += '.list-group-item-secondary{color:var(--bs-secondary-alert);background-color:var(--bs-secondary-table-bg);}\n';
	cssall += '.list-group-item-secondary.list-group-item-action:hover,.list-group-item-secondary.list-group-item-action:focus{color:var(--bs-secondary-alert);background-color:var(--bs-secondary-table-active-bg);}\n';
	cssall += '.list-group-item-secondary.list-group-item-action.active{color:var(--bs-white);background-color:var(--bs-secondary-alert);border-color:var(--bs-secondary-alert);}\n';
	cssall += '.list-group-item-success{color:var(--bs-success-alert);background-color:var(--bs-success-table-bg);}\n';
	cssall += '.list-group-item-success.list-group-item-action:hover,.list-group-item-success.list-group-item-action:focus{color:var(--bs-success-alert);background-color:var(--bs-success-table-active-bg);}\n';
	cssall += '.list-group-item-success.list-group-item-action.active{color:var(--bs-white);background-color:var(--bs-success-alert);border-color:var(--bs-success-alert);}\n';
	cssall += '.list-group-item-info{color:var(--bs-info-alert);background-color:var(--bs-info-table-bg);}\n';
	cssall += '.list-group-item-info.list-group-item-action:hover,.list-group-item-info.list-group-item-action:focus{color:var(--bs-info-alert);background-color:var(--bs-info-table-active-bg);}\n';
	cssall += '.list-group-item-info.list-group-item-action.active{color:var(--bs-white);background-color:var(--bs-info-alert);border-color:var(--bs-info-alert);}\n';
	cssall += '.list-group-item-warning{color:var(--bs-warning-alert);background-color:var(--bs-warning-table-bg);}\n';
	cssall += '.list-group-item-warning.list-group-item-action:hover,.list-group-item-warning.list-group-item-action:focus{color:var(--bs-warning-alert);background-color:var(--bs-warning-table-active-bg);}\n';
	cssall += '.list-group-item-warning.list-group-item-action.active{color:var(--bs-white);background-color:var(--bs-warning-alert);border-color:var(--bs-warning-alert);}\n';
	cssall += '.list-group-item-danger{color:var(--bs-danger-alert);background-color:var(--bs-danger-table-bg);}\n';
	cssall += '.list-group-item-danger.list-group-item-action:hover,.list-group-item-danger.list-group-item-action:focus{color:var(--bs-danger-alert);background-color:var(--bs-danger-table-active-bg);}\n';
	cssall += '.list-group-item-danger.list-group-item-action.active{color:var(--bs-white);background-color:var(--bs-danger-alert);border-color:var(--bs-danger-alert);}\n';
	cssall += '.list-group-item-light{color:var(--bs-light-alert);background-color:var(--bs-light-table-bg);}\n';
	cssall += '.list-group-item-light.list-group-item-action:hover,.list-group-item-light.list-group-item-action:focus{color:var(--bs-light-alert);background-color:var(--bs-light-table-active-bg);}\n';
	cssall += '.list-group-item-light.list-group-item-action.active{color:var(--bs-white);background-color:var(--bs-light-alert);border-color:var(--bs-light-alert);}\n';
	cssall += '.list-group-item-dark{color:var(--bs-dark-alert);background-color:var(--bs-dark-table-bg);}\n';
	cssall += '.list-group-item-dark.list-group-item-action:hover,.list-group-item-dark.list-group-item-action:focus{color:var(--bs-dark-alert);background-color:var(--bs-dark-table-active-bg);}\n';
	cssall += '.list-group-item-dark.list-group-item-action.active{color:var(--bs-white);background-color:var(--bs-dark-alert);border-color:var(--bs-dark-alert);}\n';
	/**closeicon**/
	cssall += '.btn-close{color:var(--bs-black);}\n';
	cssall += '.btn-close:hover{color:var(--bs-black);}\n';
	cssall += '.btn-close:focus{box-shadow:0 0 0 0.25rem rgba(var(--bs-primary-rgb),0.25);}\n';
	cssall += '.toast{background-color:rgba(var(--bs-white-rgb),0.85);border:1px solid rgba(var(--bs-black-rgb),0.1);box-shadow:0 0.5rem 1rem rgba(var(--bs-black-rgb),0.15);}\n';
	cssall += '.toast-header{color:var(--bs-gray-600);background-color:rgba(var(--bs-white-rgb),0.85);border-bottom:1px solid rgba(var(--bs-black-rgb),0.05);}\n';
	/**modal**/
	cssall += '.modal-content{background-color:var(--bs-white);border:1px solid rgba(var(--bs-black-rgb),0.2);}\n';
	cssall += '.modal-backdrop{background-color:var(--bs-black);}\n';
	cssall += '.modal-header{border-bottom:1px solid var(--bs-gray-300);}\n';
	cssall += '.modal-footer{border-top:1px solid var(--bs-gray-300);}\n';
	/**tooltip**/
	cssall += '.bs-tooltip-top .tooltip-arrow::before,.bs-tooltip-auto[data-popper-placement^="top"] .tooltip-arrow::before{border-top-color:var(--bs-black);}\n';
	cssall += '.bs-tooltip-end .tooltip-arrow::before,.bs-tooltip-auto[data-popper-placement^="right"] .tooltip-arrow::before{border-right-color:var(--bs-black);}\n';
	cssall += '.bs-tooltip-bottom .tooltip-arrow::before,.bs-tooltip-auto[data-popper-placement^="bottom"] .tooltip-arrow::before{border-bottom-color:var(--bs-black);}\n';
	cssall += '.bs-tooltip-start .tooltip-arrow::before,.bs-tooltip-auto[data-popper-placement^="left"] .tooltip-arrow::before{border-left-color:var(--bs-black);}\n';
	cssall += '.tooltip-inner{color:var(--bs-white);background-color:var(--bs-black);}\n';
	/**popover**/
	cssall += '.popover{background-color:var(--bs-white);border:1px solid rgba(var(--bs-black-rgb),0.2);}\n';
	cssall += '.bs-popover-top>.popover-arrow::before,.bs-popover-auto[data-popper-placement^="top"]>.popover-arrow::before{border-top-color:rgba(var(--bs-black-rgb),0.25);}\n';
	cssall += '.bs-popover-top>.popover-arrow::after,.bs-popover-auto[data-popper-placement^="top"]>.popover-arrow::after{border-top-color:var(--bs-white);}\n';
	cssall += '.bs-popover-end>.popover-arrow::before,.bs-popover-auto[data-popper-placement^="right"]>.popover-arrow::before{border-right-color:rgba(var(--bs-black-rgb),0.25);}\n';
	cssall += '.bs-popover-end>.popover-arrow::after,.bs-popover-auto[data-popper-placement^="right"]>.popover-arrow::after{border-right-color:var(--bs-white);}\n';
	cssall += '.bs-popover-bottom>.popover-arrow::before,.bs-popover-auto[data-popper-placement^="bottom"]>.popover-arrow::before{border-bottom-color:rgba(var(--bs-black-rgb),0.25);}\n';
	cssall += '.bs-popover-bottom>.popover-arrow::after,.bs-popover-auto[data-popper-placement^="bottom"]>.popover-arrow::after{border-bottom-color:var(--bs-white);}\n';
	cssall += '.bs-popover-bottom .popover-header::before,.bs-popover-auto[data-popper-placement^="bottom"] .popover-header::before{border-bottom:1px solid var(--bs-gray-200);}\n';
	cssall += '.bs-popover-start>.popover-arrow::before,.bs-popover-auto[data-popper-placement^="left"]>.popover-arrow::before{border-left-color:rgba(var(--bs-black-rgb),0.25);}\n';
	cssall += '.bs-popover-start>.popover-arrow::after,.bs-popover-auto[data-popper-placement^="left"]>.popover-arrow::after{border-left-color:var(--bs-white);}\n';
	cssall += '.popover-header{background-color:var(--bs-gray-200);border-bottom:1px solid rgba(var(--bs-black-rgb),0.2);}\n';
	cssall += '.popover-body{color:var(--bs-gray-900);}\n';
	/**carousel**/
	cssall += '.carousel-control-prev,.carousel-control-next{color:var(--bs-white);}\n';
	cssall += '.carousel-control-prev:hover,.carousel-control-prev:focus,.carousel-control-next:hover,.carousel-control-next:focus{color:var(--bs-white);}\n';
	cssall += '.carousel-indicators [data-bs-target]{background-color:var(--bs-white);}\n';
	cssall += '.carousel-caption{color:var(--bs-white);}\n';
	cssall += '.carousel-dark .carousel-indicators [data-bs-target]{background-color:var(--bs-black);}\n';
	cssall += '.carousel-dark .carousel-caption{color:var(--bs-black);}\n';
	cssall += '.offcanvas{background-color:var(--bs-white);}\n';
	cssall += '.offcanvas-backdrop{background-color:var(--bs-black);}\n';
	cssall += '.offcanvas-start{border-right:1px solid rgba(var(--bs-black-rgb),0.2);}\n';
	cssall += '.offcanvas-end{border-left:1px solid rgba(var(--bs-black-rgb),0.2);}\n';
	cssall += '.offcanvas-top{border-bottom:1px solid rgba(var(--bs-black-rgb),0.2);}\n';
	cssall += '.offcanvas-bottom{border-top:1px solid rgba(var(--bs-black-rgb),0.2);}\n';
	cssall += '.placeholder-wave{-webkit-mask-image:linear-gradient(130deg,var(--bs-black) 55%,rgba(var(--bs-black-rgb),0.8) 75%,var(--bs-black) 95%);mask-image:linear-gradient(130deg,var(--bs-black) 55%,rgba(var(--bs-black-rgb),0.8) 75%,var(--bs-black) 95%);}\n';
	cssall += '.link-primary{color:var(--bs-primary);}\n';
	cssall += '.link-primary:hover,.link-primary:focus{color:var(--bs-primary-link-hover);}\n';
	cssall += '.link-secondary{color:var(--bs-secondary);}\n';
	cssall += '.link-secondary:hover,.link-secondary:focus{color:var(--bs-secondary-btn-active);}\n';
	cssall += '.link-success{color:var(--bs-success);}\n';
	cssall += '.link-success:hover,.link-success:focus{color:var(--bs-success-btn-active);}\n';
	cssall += '.link-info{color:var(--bs-info);}\n';
	cssall += '.link-info:hover,.link-info:focus{color:var(--bs-info-link-hover);}\n';
	cssall += '.link-warning{color:var(--bs-warning);}\n';
	cssall += '.link-warning:hover,.link-warning:focus{color:var(--bs-warning-link-hover);}\n';
	cssall += '.link-danger{color:var(--bs-danger);}\n';
	cssall += '.link-danger:hover,.link-danger:focus{color:var(--bs-danger-btn-active);}\n';
	cssall += '.link-light{color:var(--bs-light);}\n';
	cssall += '.link-light:hover,.link-light:focus{color:var(--bs-light-link-hover);}\n';
	cssall += '.link-dark{color:var(--bs-dark);}\n';
	cssall += '.link-dark:hover,.link-dark:focus{color:var(--bs-dark-link-hover);}\n';
	cssall += '.visually-hidden,.visually-hidden-focusable:not(:focus):not(:focus-within){clip:rect(var(--bs-black-rgb),0) !important;}\n';
	/**Shadows**/
	cssall += '.shadow{box-shadow:0 0.5rem 1rem rgba(var(--bs-black-rgb),0.15) !important;}\n';
	cssall += '.shadow-sm{box-shadow:0 0.125rem 0.25rem rgba(var(--bs-black-rgb),0.075) !important;}\n';
	cssall += '.shadow-lg{box-shadow:0 1rem 3rem rgba(var(--bs-black-rgb),0.175) !important;}\n';
	/**Borders**/
	cssall += '.border{border:1px solid var(--bs-gray-300) !important;}\n';
	cssall += '.border-top{border-top:1px solid var(--bs-gray-300) !important;}\n';
	cssall += '.border-end{border-right:1px solid var(--bs-gray-300) !important;}\n';
	cssall += '.border-bottom{border-bottom:1px solid var(--bs-gray-300) !important;}\n';
	cssall += '.border-start{border-left:1px solid var(--bs-gray-300) !important;}\n';
	cssall += '.border-primary{border-color:var(--bs-primary) !important;}\n';
	cssall += '.border-secondary{border-color:var(--bs-secondary) !important;}\n';
	cssall += '.border-success{border-color:var(--bs-success) !important;}\n';
	cssall += '.border-info{border-color:var(--bs-info) !important;}\n';
	cssall += '.border-warning{border-color:var(--bs-warning) !important;}\n';
	cssall += '.border-danger{border-color:var(--bs-danger) !important;}\n';
	cssall += '.border-light{border-color:var(--bs-light) !important;}\n';
	cssall += '.border-dark{border-color:var(--bs-dark) !important;}\n';
	cssall += '.border-white{border-color:var(--bs-white) !important;}\n';
	/**Text**/
	cssall += '.text-primary{color:rgba(var(--bs-primary-rgb),1) !important;}\n';
	cssall += '.text-secondary{color:rgba(var(--bs-secondary-rgb),1) !important;}\n';
	cssall += '.text-success{color:rgba(var(--bs-success-rgb),1) !important;}\n';
	cssall += '.text-info{color:rgba(var(--bs-info-rgb),1) !important;}\n';
	cssall += '.text-warning{color:rgba(var(--bs-warning-rgb),1) !important;}\n';
	cssall += '.text-danger{color:rgba(var(--bs-danger-rgb),1) !important;}\n';
	cssall += '.text-light{color:rgba(var(--bs-light-rgb),1) !important;}\n';
	cssall += '.text-dark{color:rgba(var(--bs-dark-rgb),1) !important;}\n';
	cssall += '.text-black{color:rgba(var(--bs-black-rgb),1) !important;}\n';
	cssall += '.text-white{color:rgba(var(--bs-white-rgb),1) !important;}\n';
	cssall += '.text-body{color:rgba(var(--bs-body-color-rgb),1) !important;}\n';
	cssall += '.text-muted{color:var(--bs-gray-600) !important;}\n';
	cssall += '.text-black-50{color:rgba(var(--bs-black-rgb),0.5) !important;}\n';
	cssall += '.text-white-50{color:rgba(var(--bs-white-rgb),0.5) !important;}\n';
	/**Block Editor**/
	cssall += '.editor-styles-wrapper{color:var(--bs-body-color);background-color:var(--bs-body-bg);}\n';
	cssall += '.has-primary-color, .hola-tu{color:var(--bs-primary);}\n';
	cssall += '.has-primary-background-color{background-color:var(--bs-primary);}\n';
	cssall += '.has-secondary-color{color:var(--bs-secondary);}\n';
	cssall += '.has-secondary-background-color{background-color:var(--bs-secondary);}\n';
	cssall += '.has-success-color{color:var(--bs-success);}\n';
	cssall += '.has-success-background-color{background-color:var(--bs-success);}\n';
	cssall += '.has-info-color{color:var(--bs-info);}\n';
	cssall += '.has-info-background-color{background-color:var(--bs-info);}\n';
	cssall += '.has-warning-color{color:var(--bs-warning);}\n';
	cssall += '.has-warning-background-color{background-color:var(--bs-warning);}\n';
	cssall += '.has-danger-color{color:var(--bs-danger);}\n';
	cssall += '.has-danger-background-color{background-color:var(--bs-danger);}\n';
	cssall += '.has-light-color{color:var(--bs-light);}\n';
	cssall += '.has-light-background-color{background-color:var(--bs-light);}\n';
	cssall += '.has-dark-color{color:var(--bs-dark);}\n';
	cssall += '.has-dark-background-color{background-color:var(--bs-dark);}\n';
	return cssall;
}
