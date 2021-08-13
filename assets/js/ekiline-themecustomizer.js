/* Ekiline for WordPress Theme, Copyright 2018 Uri Lazcano. Ekiline is distributed under the terms of the GNU GPL. http://ekiline.com */
jQuery(document).ready(function($){

	// console.log('hola ' + wp.customize.value('background_color')() );

	// 1) renombrar cada color, segun el metodo de extracción.
	var misColores = [
		{txt:'primary',id:'b4_primary'},
		{txt:'secondary',id:'b4_secondary'},
		{txt:'success',id:'b4_success'},
		{txt:'danger',id:'b4_danger'},
		{txt:'warning',id:'b4_warning'},
		{txt:'info',id:'b4_info'},
		{txt:'light',id:'b4_light'},
		{txt:'dark',id:'b4_dark'},
		{txt:'background',id:'background_color'},
		{txt:'text',id:'text_color'}
	];

	function proColores(tonos){

		var objTonoExt = {};

		$.each( tonos , function( index, value ) {

			var absoluto = wp.customize.value( value['id'] )(); //$( value['id'] ).val(); //link, borde input
			// Bajar el tono para hover
			var aHColor = HexAHslvar(absoluto, 0, 0, -10); //hover
			var btnHBdc = HexAHslvar(absoluto, 0, 0, -15); //hoverBorder
			var btnNotBdc = HexAHslvar(absoluto, 0, 0, -20); //hoverNotBorder
			var btnFocBx = rgbaToHexOp(hexToRgbA(HexAHslvar(absoluto, 0, 0, 35))); //focusBoxshadow, fondo alerta
			var btnFocHBx = rgbaToHexOp(hexToRgbA(HexAHslvar(absoluto, 0, 0, 10))); //focusHoverBoxshadow, focus borde input, borde nav
			// Alertas
			var altTx = rgbaToHexOp(hexToRgbA(HexAHslvar(absoluto, 0, 0, -20))); // alertas TXT
			var altLk = rgbaToHexOp(hexToRgbA(HexAHslvar(absoluto, 0, 0, -35))); // alertas LINK
			// Bordes y textos de navegaciones
			var nvLigNvTxc = rgbaToHexOp(hexToRgbA(absoluto,.5)); // light texto. borde HR y borde thumbnail
			var nvLigNvHTxc = rgbaToHexOp(hexToRgbA(absoluto,.7)); // light hover
			var nvLigNvDisTxc = rgbaToHexOp(hexToRgbA(absoluto,.3)); // light disabled y fondo de tarjetas, box shadow input
			// Varios fondos y bordes dependen del color del texto
			var thLigBgc = rgbaToHexOp(hexToRgbA( HexAHslvar(absoluto, 0, 0, 20) , .20)); //fondo de breadcrub
			// fondo de tablas
			var tabBgc05 = rgbaToHexOp(hexToRgbA(absoluto,.05));
			var tabBgc075 = rgbaToHexOp(hexToRgbA(absoluto,.075));
			var crdBdc = rgbaToHexOp(hexToRgbA(absoluto,.1));

			var item = {
					'puro' : absoluto,
					'hover' : aHColor,
					'borde' : btnHBdc,
					'clic' : btnNotBdc,
					'focus' : btnFocBx,
					'focusHv' : btnFocHBx,
					'alertTx' : altTx,
					'alertLk' : altLk,
					'navTx' : nvLigNvTxc,
					'navHov' : nvLigNvHTxc,
					'navDis' : nvLigNvDisTxc,
					'breadBg' : thLigBgc,
					'tabBg' : tabBgc05,
					'tabBgHv' : tabBgc075,
					'tabBd' : crdBdc
				};

			objTonoExt[ value['txt'] ] = item;

		});

		return objTonoExt;
	}

	// console.log( proColores(misColores) );
	// console.log( proColores(misColores).primary.absoluto );

	/** -------------------- funciones que cambian los colores -------------------- **/

	//Hexadecimal a RGBA, variable extra en caso de necesitar controlar la opacidad.
	function hexToRgbA(hex,op) {
		var c;
		var op;
		if (op==''||op==null){op=1;}
		if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
			c = hex.substring(1).split('');
			if (c.length == 3) {
				c = [c[0], c[0], c[1], c[1], c[2], c[2]];
			}
			c = '0x' + c.join('');
			// return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ',1)';
			return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ','+op+')';
		} else {
			// si el valor no es hexadecimal entonces es rgba, no requiere transformar.
			return hex;
		}
	}

	//RGBA a hexadecimal con opacidad
	function rgbaToHexOp(rgba) {
		var a,
			isPercent,
			rgb = rgba.replace(/\s/g, '').match(/^rgba?\((\d+),(\d+),(\d+),?([^,\s)]+)?/i),
			alpha = (rgb && rgb[4] || "").trim(),
			hex = rgb ? (rgb[1] | 1 << 8).toString(16).slice(1) + (rgb[2] | 1 << 8).toString(16).slice(1) + (rgb[3] | 1 << 8).toString(16).slice(1) : rgba;
		if (alpha !== "") {
			a = alpha;
		} else {
			a = 01;
		}

		a = Math.round(a * 100) / 100;
		var alpha = Math.round(a * 255);
		// var hexAlpha = (alpha + 0x10000).toString(16).substr(-2).toUpperCase();
		// si es un color solido (ff) descartar.
		var hexAlpha = (alpha + 0x10000).toString(16).substr(-2).replace('ff','');
		hex = hex + hexAlpha;

		return '#' + hex;
	}

	//hexadecimal hsl ekiline
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

		// aquí llamo mis nuevas variables y modifico la salida del color
		// var nh = nh;
		// var ns = ns;
		// var nl = nl;

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

	/** -------------------- fin de funciones -------------------- **/

	// function cssOrders(extraccion){
	// 	var cssall = '';
	// 		/**General**/
	// 		cssall += 'body{color:' + extraccion.text.puro + ';background-color:' + extraccion.background.puro + ';}';
	// 	return cssall;
	// }

/************************************************************************************************************************************************************************/

	function cssOrders(extraccion) {

        var cssall = '';

			/**General**/
			cssall += 'body{color:' + extraccion.text.puro + ';background-color:' + extraccion.background.puro + ';}\n';
			cssall += 'a{color:' + extraccion.primary.puro + ';}\n';
			cssall += 'a:hover{color:' + extraccion.primary.hover + ';}\n';
			cssall += 'caption{color:' + extraccion.secondary.puro + ';}\n';
			cssall += 'hr{border-top-color:' + extraccion.text.navTx + ';}\n';
			cssall += 'mark,.mark{background-color:' + extraccion.warning.focus + ';}\n';
			cssall += '.blockquote-footer{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.img-thumbnail{background-color:' + extraccion.text.breadBg + ';border-color:' + extraccion.secondary.focus + ';}\n';
			cssall += '.figure-caption{color:' + extraccion.secondary.puro + ';}\n';
			cssall += 'code{color:' + extraccion.danger.focusHv + ';}\n';
			cssall += 'kbd{color:' + extraccion.light.focus + ';background-color:' + extraccion.text.puro + ';}\n';
			cssall += 'pre{color:' + extraccion.text.puro + ';}\n';

			/**tablas**/
			cssall += '.table th,.table td{border-top-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.table thead th{border-bottom-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.table tbody + tbody{border-top-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.table .table{background-color:' + extraccion.background.puro + ';}\n';
			cssall += '.table-bordered{border-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.table-bordered th,.table-bordered td{border-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.table-striped tbody tr:nth-of-type(odd){background-color:' + extraccion.text.tabBg + ';}\n';
			cssall += '.table-hover tbody tr:hover{background-color:' + extraccion.text.tabBgHv + ';}\n';

			cssall += '.table-primary,.table-primary > th,.table-primary > td{background-color:' + extraccion.primary.navDis + ';}\n';
			cssall += '.table-hover .table-primary:hover{background-color:' + extraccion.primary.navHov + ';}\n';
			cssall += '.table-hover .table-primary:hover > td,.table-hover .table-primary:hover > th{background-color:' + extraccion.primary.navHov + ';}\n';
			cssall += '.table-secondary,.table-secondary > th,.table-secondary > td{background-color:' + extraccion.secondary.navDis + ';}\n';
			cssall += '.table-hover .table-secondary:hover{background-color:' + extraccion.secondary.navHov + ';}\n';
			cssall += '.table-hover .table-secondary:hover > td,.table-hover .table-secondary:hover > th{background-color:' + extraccion.secondary.navHov + ';}\n';
			cssall += '.table-success,.table-success > th,.table-success > td{background-color:' + extraccion.success.navDis + ';}\n';
			cssall += '.table-hover .table-success:hover{background-color:' + extraccion.success.navHov + ';}\n';
			cssall += '.table-hover .table-success:hover > td,.table-hover .table-success:hover > th{background-color:' + extraccion.success.navHov + ';}\n';
			cssall += '.table-info,.table-info > th,.table-info > td{background-color:' + extraccion.info.navDis + ';}\n';
			cssall += '.table-hover .table-info:hover{background-color:' + extraccion.info.navHov + ';}\n';
			cssall += '.table-hover .table-info:hover > td,.table-hover .table-info:hover > th{background-color:' + extraccion.info.navHov + ';}\n';
			cssall += '.table-warning,.table-warning > th,.table-warning > td{background-color:' + extraccion.warning.navDis + ';}\n';
			cssall += '.table-hover .table-warning:hover{background-color:' + extraccion.warning.navHov + ';}\n';
			cssall += '.table-hover .table-warning:hover > td,.table-hover .table-warning:hover > th{background-color:' + extraccion.warning.navHov + ';}\n';
			cssall += '.table-danger,.table-danger > th,.table-danger > td{background-color:' + extraccion.danger.navDis + ';}\n';
			cssall += '.table-hover .table-danger:hover{background-color:' + extraccion.danger.navHov + ';}\n';
			cssall += '.table-hover .table-danger:hover > td,.table-hover .table-danger:hover > th{background-color:' + extraccion.danger.navHov + ';}\n';
			cssall += '.table-light,.table-light > th,.table-light > td{background-color:' + extraccion.light.navDis + ';}\n';
			cssall += '.table-hover .table-light:hover{background-color:' + extraccion.light.navHov + ';}\n';
			cssall += '.table-hover .table-light:hover > td,.table-hover .table-light:hover > th{background-color:' + extraccion.light.navHov + ';}\n';
			cssall += '.table-dark,.table-dark > th,.table-dark > td{background-color:' + extraccion.dark.navDis + ';}\n';
			cssall += '.table-hover .table-dark:hover{background-color:' + extraccion.dark.navHov + ';}\n';
			cssall += '.table-hover .table-dark:hover > td,.table-hover .table-dark:hover > th{background-color:' + extraccion.dark.navHov + ';}\n';
			cssall += '.table-active,.table-active > th,.table-active > td{background-color:' + extraccion.text.tabBgHv + ';}\n';
			cssall += '.table-hover .table-active:hover{background-color:' + extraccion.text.tabBgHv + ';}\n';
			cssall += '.table-hover .table-active:hover > td,.table-hover .table-active:hover > th{background-color:' + extraccion.text.tabBgHv + ';}\n';
			cssall += '.table .thead-dark th{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.navDis + ';border-color:' + extraccion.dark.tabBd + ';}\n';
			cssall += '.table .thead-light th{color:' + extraccion.text.puro + ';background-color:' + extraccion.text.navDis + ';border-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.table-dark{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.puro + ';}\n';
			cssall += '.table-dark th,.table-dark td,.table-dark thead th{border-color:' + extraccion.light.tabBd + ';}\n';
			cssall += '.table-dark.table-striped tbody tr:nth-of-type(odd){background-color:' + extraccion.light.tabBg + ';}\n';
			cssall += '.table-dark.table-hover tbody tr:hover{background-color:' + extraccion.light.tabBgHv + ';}\n';

			/**Formularios**/
			cssall += '.form-control{color:' + extraccion.text.puro + ';background-color:' + extraccion.light.focus + ';border-color:' + extraccion.secondary.focus + ';}\n';
			cssall += '.form-control:focus{color:' + extraccion.info.alertTx + ';background-color:' + extraccion.light.focus + ';border-color:' + extraccion.info.focusHv + ';box-shadow:0 0 0.2rem 0.2rem ' + extraccion.info.focus + ';}\n';
			cssall += '.form-control::-webkit-input-placeholder{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.form-control::-moz-placeholder{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.form-control:-ms-input-placeholder{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.form-control::-ms-input-placeholder{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.form-control::placeholder{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.form-control:disabled,.form-control[readonly]{background-color:' + extraccion.secondary.breadBg + ';}\n';
			cssall += 'select.form-control:focus::-ms-value{color:' + extraccion.text.focusHv + ';background-color:' + extraccion.light.focus + ';}\n';

			cssall += '.form-control-plaintext{color:' + extraccion.text.puro + ';}\n';
			cssall += '.form-check-input:disabled ~';
			cssall += '.form-check-label{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.valid-feedback{color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.valid-tooltip{color:' + extraccion.light.focus + ';background-color:' + extraccion.success.alertTx + ';}\n';
			cssall += '.was-validated .form-control:valid,.form-control.is-valid,.was-validated';
			cssall += '.custom-select:valid,.custom-select.is-valid{border-color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.was-validated .form-control:valid:focus,.form-control.is-valid:focus,.was-validated';
			cssall += '.custom-select:valid:focus,.custom-select.is-valid:focus{border-color:' + extraccion.success.focusHv + ';box-shadow:0 0 0.2rem 0.2rem ' + extraccion.success.focusHv + ';}\n';

			cssall += '.was-validated .form-check-input:valid ~ .form-check-label,.form-check-input.is-valid ~';
			cssall += '.form-check-label{color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.was-validated .custom-control-input:valid ~ .custom-control-label,.custom-control-input.is-valid ~';
			cssall += '.custom-control-label{color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.was-validated .custom-control-input:valid ~ .custom-control-label::before,.custom-control-input.is-valid ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.success.puro + ';}\n';
			cssall += '.was-validated .custom-control-input:valid:checked ~ .custom-control-label::before,.custom-control-input.is-valid:checked ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.success.puro + ';}\n';
			cssall += '.was-validated .custom-control-input:valid:focus ~ .custom-control-label::before,.custom-control-input.is-valid:focus ~';
			cssall += '.custom-control-label::before{box-shadow:0 0 0 1px ' + extraccion.background.puro + ',0 0 0 0.2rem ' + extraccion.danger.puro + ';}\n';
			cssall += '.was-validated .custom-file-input:valid ~ .custom-file-label,.custom-file-input.is-valid ~';
			cssall += '.custom-file-label{border-color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.was-validated .custom-file-input:valid:focus ~ .custom-file-label,.custom-file-input.is-valid:focus ~';
			cssall += '.custom-file-label{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.danger.puro + ';}\n';

			cssall += '.invalid-feedback{color:' + extraccion.danger.puro + ';}\n';
			cssall += '.invalid-tooltip{color:' + extraccion.light.focus + ';background-color:' + extraccion.danger.focusHv + ';}\n';
			cssall += '.was-validated .form-control:invalid,.form-control.is-invalid,.was-validated';
			cssall += '.custom-select:invalid,.custom-select.is-invalid{border-color:' + extraccion.danger.puro + ';}\n';
			cssall += '.was-validated .form-control:invalid:focus,.form-control.is-invalid:focus,.was-validated';
			cssall += '.custom-select:invalid:focus,.custom-select.is-invalid:focus{border-color:' + extraccion.danger.puro + ';box-shadow:0 0 0.2rem 0.2rem ' + extraccion.danger.focusHv + ';}\n';
			cssall += '.was-validated .form-check-input:invalid ~ .form-check-label,.form-check-input.is-invalid ~';
			cssall += '.form-check-label{color:' + extraccion.danger.puro + ';}\n';
			cssall += '.was-validated .custom-control-input:invalid ~ .custom-control-label,.custom-control-input.is-invalid ~';
			cssall += '.custom-control-label{color:' + extraccion.danger.puro + ';}\n';
			cssall += '.was-validated .custom-control-input:invalid ~ .custom-control-label::before,.custom-control-input.is-invalid ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.danger.puro + ';}\n';
			cssall += '.was-validated .custom-control-input:invalid:checked ~ .custom-control-label::before,.custom-control-input.is-invalid:checked ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.danger.puro + ';}\n';
			cssall += '.was-validated .custom-control-input:invalid:focus ~ .custom-control-label::before,.custom-control-input.is-invalid:focus ~';
			cssall += '.custom-control-label::before{box-shadow:0 0 0 1px ' + extraccion.background.puro + ',0 0 0 0.2rem ' + extraccion.danger.focusHv + ';}\n';
			cssall += '.was-validated .custom-file-input:invalid ~ .custom-file-label,.custom-file-input.is-invalid ~';
			cssall += '.custom-file-label{border-color:' + extraccion.danger.puro + ';}\n';
			cssall += '.was-validated .custom-file-input:invalid:focus ~ .custom-file-label,.custom-file-input.is-invalid:focus ~';
			cssall += '.custom-file-label{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.danger.focusHv + ';}\n';

			/**Botones**/
			cssall += '.btn:focus,.btn.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.text.focus + ';}\n';
			cssall += '.btn-primary{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.puro + ';border-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.btn-primary:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.hover + ';border-color:' + extraccion.primary.borde + ';}\n';
			cssall += '.btn-primary:focus,.btn-primary.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.primary.focusHv + ';}\n';
			cssall += '.btn-primary.disabled,.btn-primary:disabled{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.puro + ';border-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.btn-primary:not(:disabled):not(.disabled):active,.btn-primary:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-primary.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.borde + ';border-color:' + extraccion.primary.clic + ';}\n';
			cssall += '.btn-primary:not(:disabled):not(.disabled):active:focus,.btn-primary:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-primary.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.primary.focusHv + ';}\n';

			cssall += '.btn-secondary{color:' + extraccion.light.focus + ';background-color:' + extraccion.secondary.puro + ';border-color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.btn-secondary:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.secondary.hover + ';border-color:' + extraccion.secondary.borde + ';}\n';
			cssall += '.btn-secondary:focus,.btn-secondary.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.secondary.focusHv + ';}\n';
			cssall += '.btn-secondary.disabled,.btn-secondary:disabled{color:' + extraccion.light.focus + ';background-color:' + extraccion.secondary.puro + ';border-color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.btn-secondary:not(:disabled):not(.disabled):active,.btn-secondary:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-secondary.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.secondary.borde + ';border-color:' + extraccion.secondary.clic + ';}\n';
			cssall += '.btn-secondary:not(:disabled):not(.disabled):active:focus,.btn-secondary:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-secondary.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.secondary.focusHv + ';}\n';

			cssall += '.btn-success{color:' + extraccion.light.focus + ';background-color:' + extraccion.success.puro + ';border-color:' + extraccion.success.puro + ';}\n';
			cssall += '.btn-success:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.success.hover + ';border-color:' + extraccion.success.borde + ';}\n';
			cssall += '.btn-success:focus,.btn-success.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.success.focusHv + ';}\n';
			cssall += '.btn-success.disabled,.btn-success:disabled{color:' + extraccion.light.focus + ';background-color:' + extraccion.success.focusHv + ';border-color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.btn-success:not(:disabled):not(.disabled):active,.btn-success:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-success.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.success.borde + ';border-color:' + extraccion.success.clic + ';}\n';
			cssall += '.btn-success:not(:disabled):not(.disabled):active:focus,.btn-success:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-success.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.success.focusHv + ';}\n';

			cssall += '.btn-info{color:' + extraccion.light.focus + ';background-color:' + extraccion.info.puro + ';border-color:' + extraccion.info.puro + ';}\n';
			cssall += '.btn-info:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.info.hover + ';border-color:' + extraccion.info.borde + ';}\n';
			cssall += '.btn-info:focus,.btn-info.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.info.focusHv + ';}\n';
			cssall += '.btn-info.disabled,.btn-info:disabled{color:' + extraccion.light.focus + ';background-color:' + extraccion.info.puro + ';border-color:' + extraccion.info.puro + ';}\n';
			cssall += '.btn-info:not(:disabled):not(.disabled):active,.btn-info:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-info.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.info.borde + ';border-color:' + extraccion.info.clic + ';}\n';
			cssall += '.btn-info:not(:disabled):not(.disabled):active:focus,.btn-info:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-info.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.info.focusHv + ';}\n';

			cssall += '.btn-warning{color:' + extraccion.text.puro + ';background-color:' + extraccion.warning.puro + ';border-color:' + extraccion.warning.puro + ';}\n';
			cssall += '.btn-warning:hover{color:' + extraccion.text.puro + ';background-color:' + extraccion.warning.hover + ';border-color:' + extraccion.warning.borde + ';}\n';
			cssall += '.btn-warning:focus,.btn-warning.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.warning.focusHv + ';}\n';
			cssall += '.btn-warning.disabled,.btn-warning:disabled{color:' + extraccion.text.puro + ';background-color:' + extraccion.warning.puro + ';border-color:' + extraccion.warning.puro + ';}\n';
			cssall += '.btn-warning:not(:disabled):not(.disabled):active,.btn-warning:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-warning.dropdown-toggle{color:' + extraccion.text.puro + ';background-color:' + extraccion.warning.borde + ';border-color:' + extraccion.warning.clic + ';}\n';
			cssall += '.btn-warning:not(:disabled):not(.disabled):active:focus,.btn-warning:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-warning.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.warning.focusHv + ';}\n';

			cssall += '.btn-danger{color:' + extraccion.light.focus + ';background-color:' + extraccion.danger.puro + ';border-color:' + extraccion.danger.puro + ';}\n';
			cssall += '.btn-danger:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.danger.hover + ';border-color:' + extraccion.danger.borde + ';}\n';
			cssall += '.btn-danger:focus,.btn-danger.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.danger.focusHv + ';}\n';
			cssall += '.btn-danger.disabled,.btn-danger:disabled{color:' + extraccion.light.focus + ';background-color:' + extraccion.danger.puro + ';border-color:' + extraccion.danger.puro + ';}\n';
			cssall += '.btn-danger:not(:disabled):not(.disabled):active,.btn-danger:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-danger.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.danger.borde + ';border-color:' + extraccion.danger.clic + ';}\n';
			cssall += '.btn-danger:not(:disabled):not(.disabled):active:focus,.btn-danger:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-danger.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.danger.focusHv + ';}\n';

			cssall += '.btn-light{color:' + extraccion.text.puro + ';background-color:' + extraccion.light.puro + ';border-color:' + extraccion.light.puro + ';}\n';
			cssall += '.btn-light:hover{color:' + extraccion.text.puro + ';background-color:' + extraccion.light.hover + ';border-color:' + extraccion.light.borde + ';}\n';
			cssall += '.btn-light:focus,.btn-light.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.light.focusHv + ';}\n';
			cssall += '.btn-light.disabled,.btn-light:disabled{color:' + extraccion.text.puro + ';background-color:' + extraccion.light.puro + ';border-color:' + extraccion.light.puro + ';}\n';
			cssall += '.btn-light:not(:disabled):not(.disabled):active,.btn-light:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-light.dropdown-toggle{color:' + extraccion.text.puro + ';background-color:' + extraccion.light.borde + ';border-color:' + extraccion.light.clic + ';}\n';
			cssall += '.btn-light:not(:disabled):not(.disabled):active:focus,.btn-light:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-light.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.light.focusHv + ';}\n';

			cssall += '.btn-dark{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.puro + ';border-color:' + extraccion.dark.puro + ';}\n';
			cssall += '.btn-dark:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.hover + ';border-color:' + extraccion.dark.borde + ';}\n';
			cssall += '.btn-dark:focus,.btn-dark.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.dark.focusHv + ';}\n';
			cssall += '.btn-dark.disabled,.btn-dark:disabled{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.puro + ';border-color:' + extraccion.dark.puro + ';}\n';
			cssall += '.btn-dark:not(:disabled):not(.disabled):active,.btn-dark:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-dark.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.borde + ';border-color:' + extraccion.dark.clic + ';}\n';
			cssall += '.btn-dark:not(:disabled):not(.disabled):active:focus,.btn-dark:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-dark.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.dark.focusHv + ';}\n';

			cssall += '.btn-outline-primary{color:' + extraccion.primary.puro + ';border-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.btn-outline-primary:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.puro + ';border-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.btn-outline-primary:focus,.btn-outline-primary.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.primary.focusHv + ';}\n';
			cssall += '.btn-outline-primary.disabled,.btn-outline-primary:disabled{color:' + extraccion.primary.puro + ';}\n';
			cssall += '.btn-outline-primary:not(:disabled):not(.disabled):active,.btn-outline-primary:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-outline-primary.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.puro + ';border-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.btn-outline-primary:not(:disabled):not(.disabled):active:focus,.btn-outline-primary:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-outline-primary.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.primary.focusHv + ';}\n';

			cssall += '.btn-outline-secondary{color:' + extraccion.secondary.puro + ';border-color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.btn-outline-secondary:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.secondary.puro + ';border-color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.btn-outline-secondary:focus,.btn-outline-secondary.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.secondary.focusHv + ';}\n';
			cssall += '.btn-outline-secondary.disabled,.btn-outline-secondary:disabled{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.btn-outline-secondary:not(:disabled):not(.disabled):active,.btn-outline-secondary:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-outline-secondary.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.secondary.puro + ';border-color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.btn-outline-secondary:not(:disabled):not(.disabled):active:focus,.btn-outline-secondary:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-outline-secondary.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.secondary.focusHv + ';}\n';

			cssall += '.btn-outline-success{color:' + extraccion.success.focusHv + ';border-color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.btn-outline-success:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.success.focusHv + ';border-color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.btn-outline-success:focus,.btn-outline-success.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.success.focusHv + ';}\n';
			cssall += '.btn-outline-success.disabled,.btn-outline-success:disabled{color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.btn-outline-success:not(:disabled):not(.disabled):active,.btn-outline-success:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-outline-success.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.success.focusHv + ';border-color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.btn-outline-success:not(:disabled):not(.disabled):active:focus,.btn-outline-success:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-outline-success.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.success.focusHv + ';}\n';

			cssall += '.btn-outline-info{color:' + extraccion.info.puro + ';border-color:' + extraccion.info.puro + ';}\n';
			cssall += '.btn-outline-info:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.info.puro + ';border-color:' + extraccion.info.puro + ';}\n';
			cssall += '.btn-outline-info:focus,.btn-outline-info.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.info.focusHv + ';}\n';
			cssall += '.btn-outline-info.disabled,.btn-outline-info:disabled{color:' + extraccion.info.puro + ';}\n';
			cssall += '.btn-outline-info:not(:disabled):not(.disabled):active,.btn-outline-info:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-outline-info.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.info.puro + ';border-color:' + extraccion.info.puro + ';}\n';
			cssall += '.btn-outline-info:not(:disabled):not(.disabled):active:focus,.btn-outline-info:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-outline-info.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.info.focusHv + ';}\n';

			cssall += '.btn-outline-warning{color:' + extraccion.warning.puro + ';border-color:' + extraccion.warning.puro + ';}\n';
			cssall += '.btn-outline-warning:hover{color:' + extraccion.text.puro + ';background-color:' + extraccion.warning.puro + ';border-color:' + extraccion.warning.puro + ';}\n';
			cssall += '.btn-outline-warning:focus,.btn-outline-warning.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.warning.focusHv + ';}\n';
			cssall += '.btn-outline-warning.disabled,.btn-outline-warning:disabled{color:' + extraccion.warning.puro + ';}\n';
			cssall += '.btn-outline-warning:not(:disabled):not(.disabled):active,.btn-outline-warning:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-outline-warning.dropdown-toggle{color:' + extraccion.text.puro + ';background-color:' + extraccion.warning.puro + ';border-color:' + extraccion.warning.puro + ';}\n';
			cssall += '.btn-outline-warning:not(:disabled):not(.disabled):active:focus,.btn-outline-warning:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-outline-warning.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.warning.focusHv + ';}\n';

			cssall += '.btn-outline-danger{color:' + extraccion.danger.puro + ';border-color:' + extraccion.danger.puro + ';}\n';
			cssall += '.btn-outline-danger:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.danger.puro + ';border-color:' + extraccion.danger.puro + ';}\n';
			cssall += '.btn-outline-danger:focus,.btn-outline-danger.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.danger.focusHv + ';}\n';
			cssall += '.btn-outline-danger.disabled,.btn-outline-danger:disabled{color:' + extraccion.danger.puro + ';}\n';
			cssall += '.btn-outline-danger:not(:disabled):not(.disabled):active,.btn-outline-danger:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-outline-danger.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.danger.puro + ';border-color:' + extraccion.danger.puro + ';}\n';
			cssall += '.btn-outline-danger:not(:disabled):not(.disabled):active:focus,.btn-outline-danger:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-outline-danger.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.danger.focusHv + ';}\n';

			cssall += '.btn-outline-light{color:' + extraccion.light.puro + ';border-color:' + extraccion.light.puro + ';}\n';
			cssall += '.btn-outline-light:hover{color:' + extraccion.text.puro + ';background-color:' + extraccion.light.puro + ';border-color:' + extraccion.light.puro + ';}\n';
			cssall += '.btn-outline-light:focus,.btn-outline-light.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.light.focusHv + ';}\n';
			cssall += '.btn-outline-light.disabled,.btn-outline-light:disabled{color:' + extraccion.light.puro + ';}\n';
			cssall += '.btn-outline-light:not(:disabled):not(.disabled):active,.btn-outline-light:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-outline-light.dropdown-toggle{color:' + extraccion.text.puro + ';background-color:' + extraccion.light.puro + ';border-color:' + extraccion.light.puro + ';}\n';
			cssall += '.btn-outline-light:not(:disabled):not(.disabled):active:focus,.btn-outline-light:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-outline-light.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.light.focusHv + ';}\n';

			cssall += '.btn-outline-dark{color:' + extraccion.dark.puro + ';border-color:' + extraccion.dark.puro + ';}\n';
			cssall += '.btn-outline-dark:hover{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.puro + ';border-color:' + extraccion.dark.puro + ';}\n';
			cssall += '.btn-outline-dark:focus,.btn-outline-dark.focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.dark.focusHv + ';}\n';
			cssall += '.btn-outline-dark.disabled,.btn-outline-dark:disabled{color:' + extraccion.dark.puro + ';}\n';
			cssall += '.btn-outline-dark:not(:disabled):not(.disabled):active,.btn-outline-dark:not(:disabled)';
			cssall += ':not(.disabled).active,.show > .btn-outline-dark.dropdown-toggle{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.puro + ';border-color:' + extraccion.dark.puro + ';}\n';
			cssall += '.btn-outline-dark:not(:disabled):not(.disabled):active:focus,.btn-outline-dark:not(:disabled)';
			cssall += ':not(.disabled).active:focus,.show > .btn-outline-dark.dropdown-toggle:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.dark.focusHv + ';}\n';

			cssall += '.btn-link{color:' + extraccion.primary.puro + ';}\n';
			cssall += '.btn-link:hover{color:' + extraccion.primary.hover + ';}\n';
			cssall += '.btn-link:disabled,.btn-link.disabled{color:' + extraccion.secondary.puro + ';}\n';

			/**Dropdowns**/
			cssall += '.dropdown-menu{color:' + extraccion.secondary.puro + ';background-color:' + extraccion.light.focus + ';border-color:' + extraccion.secondary.breadBg + ';}\n';
			cssall += '.dropdown-divider{border-top-color:' + extraccion.dark.breadBg + ';}\n';
			cssall += '.dropdown-item{color:' + extraccion.secondary.alertLk + ';}\n';
			cssall += '.dropdown-item:hover,.dropdown-item:focus{color:' + extraccion.primary.puro + ';background-color:' + extraccion.primary.breadBg + ';}\n';
			cssall += '.dropdown-item.active,.dropdown-item:active{color:' + extraccion.primary.puro + ';background-color:' + extraccion.primary.breadBg + ';}\n';
			cssall += '.dropdown-item.disabled,.dropdown-item:disabled{color:' + extraccion.secondary.navDis + ';}\n';
			cssall += '.dropdown-item-text{color:' + extraccion.secondary.puro + ';}\n';

			/**Buttongroup**/
			cssall += '.input-group-text{color:' + extraccion.text.focusHv + ';background-color:' + extraccion.text.breadBg + ';border-color:' + extraccion.text.breadBg + ';}\n';
			cssall += '.custom-control-input:checked ~';
			cssall += '.custom-control-label::before{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.custom-control-input:focus ~';
			cssall += '.custom-control-label::before{box-shadow:0 0 0 1px ' + extraccion.background.puro + ',0 0 0 0.2rem ' + extraccion.text.focus + ';}\n';
			cssall += '.custom-control-input:active ~';
			cssall += '.custom-control-label::before{color:' + extraccion.light.focus + ';background-color:' + extraccion.text.tabBgHv + ';}\n';
			cssall += '.custom-control-input:disabled ~';
			cssall += '.custom-control-label{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.custom-control-input:disabled ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.text.breadBg + ';}\n';
			cssall += '.custom-control-label::before{background-color:' + extraccion.text.tabBd + ';}\n';

			cssall += '.custom-checkbox .custom-control-input:checked ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.custom-checkbox .custom-control-input:indeterminate ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.custom-checkbox .custom-control-input:disabled:checked ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.primary.focusHv + ';}\n';
			cssall += '.custom-checkbox .custom-control-input:disabled:indeterminate ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.primary.focusHv + ';}\n';

			cssall += '.custom-radio .custom-control-input:checked ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.custom-radio .custom-control-input:disabled:checked ~';
			cssall += '.custom-control-label::before{background-color:' + extraccion.primary.focusHv + ';}\n';

			cssall += '.custom-select{color:' + extraccion.secondary.focusHv + ';background-color:' + extraccion.light.focus + '; border-color:' + extraccion.secondary.focus + ';}\n';
			cssall += '.custom-select:focus{border-color:' + extraccion.info.focusHv + ';box-shadow:inset 0 1px 2px ' + extraccion.info.tabBgHv + ' ,0 0 5px ' + extraccion.info.focus + ';}\n';
			cssall += '.custom-select:focus::-ms-value{color:' + extraccion.text.focusHv + ';background-color:' + extraccion.light.focus + ';}\n';
			cssall += '.custom-select:disabled{color:' + extraccion.secondary.focusHv + ';background-color:' + extraccion.secondary.breadBg + ';}\n';

			cssall += '.custom-file-input:focus ~';
			cssall += '.custom-file-label{border-color:' + extraccion.secondary.focus + ';box-shadow:0 0 0.2rem 0.2rem ' + extraccion.info.focus + ';}\n';
			cssall += '.custom-file-input:focus ~';
			cssall += '.custom-file-label::after{border-color:' + extraccion.secondary.focus + ';}\n';
			cssall += '.custom-file-label{color:' + extraccion.secondary.focusHv + ';background-color:' + extraccion.light.focus + ';border-color:' + extraccion.secondary.focus + ';}\n';
			cssall += '.custom-file-label::after{color:' + extraccion.secondary.focusHv + ';background-color:' + extraccion.secondary.breadBg + ';border-left-color:' + extraccion.secondary.focusHv + ';}\n';

			cssall += '.custom-range::-webkit-slider-thumb{background-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.custom-range::-webkit-slider-thumb:focus{box-shadow:0 0 0 1px ' + extraccion.background.puro + ',0 0 0 0.2rem ' + extraccion.text.focus + ';}\n';
			cssall += '.custom-range::-webkit-slider-thumb:active{background-color:' + extraccion.text.tabBgHv + ';}\n';
			cssall += '.custom-range::-webkit-slider-runnable-track{background-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.custom-range::-moz-range-thumb{background-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.custom-range::-moz-range-thumb:focus{box-shadow:0 0 0 1px ' + extraccion.background.puro + ',0 0 0 0.2rem ' + extraccion.text.focus + ';}\n';
			cssall += '.custom-range::-moz-range-thumb:active{background-color:' + extraccion.text.tabBgHv + ';}\n';
			cssall += '.custom-range::-moz-range-track{background-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.custom-range::-ms-thumb{background-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.custom-range::-ms-thumb:focus{box-shadow:0 0 0 1px ' + extraccion.background.puro + ',0 0 0 0.2rem ' + extraccion.text.focus + ';}\n';
			cssall += '.custom-range::-ms-thumb:active{background-color:' + extraccion.text.tabBgHv + ';}\n';
			cssall += '.custom-range::-ms-fill-lower{background-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.custom-range::-ms-fill-upper{background-color:' + extraccion.text.tabBd + ';}\n';

			/**Navs**/

			cssall += '.nav-link.disabled{color:' + extraccion.secondary.puro + ';}\n';

			cssall += '.nav-tabs{border-bottom-color:' + extraccion.secondary.focus + ';}\n';
			cssall += '.nav-tabs .nav-link:hover,.nav-tabs .nav-link:focus{border-color:' + extraccion.secondary.breadBg + ' ' + extraccion.secondary.breadBg + ' ' + extraccion.secondary.tabBd + ';}\n';
			cssall += '.nav-tabs .nav-link.disabled{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.nav-tabs .nav-link.active,.nav-tabs .nav-item.show .nav-link{color:' + extraccion.primary.puro + ';background-color:' + extraccion.background.puro + ';border-color:' + extraccion.secondary.focus + ' ' + extraccion.secondary.focus + ' ' + extraccion.background.puro + ';}\n';

			cssall += '.nav-pills .nav-link.active,.nav-pills .show > .nav-link{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.puro + ';}\n';

			cssall += '.navbar-light .navbar-brand{color:' + extraccion.primary.puro + ';}\n';
			cssall += '.navbar-light .navbar-brand:hover,.navbar-light .navbar-brand:focus{color:' + extraccion.primary.focusHv + ';}\n';
			cssall += '.navbar-light .navbar-nav .nav-link{color:' + extraccion.secondary.navHov + ';}\n';
			cssall += '.navbar-light .navbar-nav .nav-link:hover,.navbar-light .navbar-nav .nav-link:focus{color:' + extraccion.secondary.focus + ';}\n';
			cssall += '.navbar-light .navbar-nav .nav-link.disabled{color:' + extraccion.secondary.navDis + ';}\n';
			cssall += '.navbar-light .navbar-nav .show > .nav-link,.navbar-light .navbar-nav .active > .nav-link,.navbar-light .navbar-nav .nav-link.show,.navbar-light .navbar-nav .nav-link.active{color:' + extraccion.primary.puro + ';}\n';

			cssall += '.navbar-light .navbar-toggler{color:' + extraccion.text.navHov + ';border-color:' + extraccion.text.navTx + ';}\n';

			cssall += '.navbar-light .navbar-text{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.navbar-light .navbar-text a{color:' + extraccion.primary.puro + ';}\n';
			cssall += '.navbar-light .navbar-text a:hover,.navbar-light .navbar-text a:focus{color:' + extraccion.primary.focusHv + ';}\n';

			cssall += '.navbar-dark .navbar-brand{color:' + extraccion.background.focus + ';}\n';
			cssall += '.navbar-dark .navbar-brand:hover,.navbar-dark .navbar-brand:focus{color:' + extraccion.light.focus + ';}\n';
			cssall += '.navbar-dark .navbar-nav .nav-link{color:' + extraccion.light.navHov + ';}\n';
			cssall += '.navbar-dark .navbar-nav .nav-link:hover,.navbar-dark .navbar-nav .nav-link:focus{color:' + extraccion.light.focus + ';}\n';
			cssall += '.navbar-dark .navbar-nav .nav-link.disabled{color:' + extraccion.light.navDis + ';}\n';
			cssall += '.navbar-dark .navbar-nav .show > .nav-link,.navbar-dark .navbar-nav .active > .nav-link,.navbar-dark .navbar-nav .nav-link.show,.navbar-dark .navbar-nav .nav-link.active{color:' + extraccion.light.focus + ';}\n';

			cssall += '.navbar-dark .navbar-toggler{color:' + extraccion.light.navHov + ';border-color:' + extraccion.light.focus + ';}\n';

			cssall += '.navbar-dark .navbar-text{color:' + extraccion.light.navHov + ';}\n';
			cssall += '.navbar-dark .navbar-text a{color:' + extraccion.light.focus + ';}\n';
			cssall += '.navbar-dark .navbar-text a:hover,.navbar-dark .navbar-text a:focus{color:' + extraccion.light.focus + ';}\n';

			/**cards**/
			cssall += '.card{color:' + extraccion.dark.puro + ';background-color:' + extraccion.light.focus + ';border-color:' + extraccion.secondary.focus + ';}\n';
			cssall += '.card-header{background-color: ' + extraccion.secondary.breadBg + ';border-bottom-color:' + extraccion.secondary.focus + ';}\n';
			cssall += '.card-footer{background-color:' + extraccion.secondary.breadBg + ';border-top-color:' + extraccion.secondary.focus + ';}\n';

			/**breadcrumb**/
			cssall += '.breadcrumb{background-color:' + extraccion.secondary.breadBg + ';}\n';
			cssall += '.breadcrumb-item + .breadcrumb-item::before{color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.breadcrumb-item.active{color:' + extraccion.secondary.puro + ';}\n';

			/**pagelinks**/
			cssall += '.page-link{color:' + extraccion.primary.puro + ';background-color:' + extraccion.text.tabBg + ';border-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.page-link:hover{color:' + extraccion.primary.hover + ';background-color:' + extraccion.text.breadBg + ';border-color:' + extraccion.text.tabBd + ';}\n';
			cssall += '.page-link:focus{box-shadow:0 0 0.2rem 0.2rem ' + extraccion.text.focus + ';}\n';
			cssall += '.page-item.active .page-link{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.puro + ';border-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.page-item.disabled .page-link{color:' + extraccion.secondary.puro + ';background-color:' + extraccion.background.puro + ';border-color:' + extraccion.text.tabBd + ';}\n';

			/**badge**/
			cssall += '.badge-primary{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.puro + ';}\n';
			cssall += '.badge-primary[href]:hover,.badge-primary[href]:focus{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.borde + ';}\n';
			cssall += '.badge-secondary{color:' + extraccion.light.focus + ';background-color:' + extraccion.secondary.puro + ';}\n';
			cssall += '.badge-secondary[href]:hover,.badge-secondary[href]:focus{color:' + extraccion.light.focus + ';background-color:' + extraccion.secondary.borde + ';}\n';
			cssall += '.badge-success{color:' + extraccion.light.focus + ';background-color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.badge-success[href]:hover,.badge-success[href]:focus{color:' + extraccion.light.focus + ';background-color:' + extraccion.success.borde + ';}\n';
			cssall += '.badge-info{color:' + extraccion.light.focus + ';background-color:' + extraccion.info.puro + ';}\n';
			cssall += '.badge-info[href]:hover,.badge-info[href]:focus{color:' + extraccion.light.focus + ';background-color:' + extraccion.info.borde + ';}\n';
			cssall += '.badge-warning{color:' + extraccion.text.puro + ';background-color:' + extraccion.warning.puro + ';}\n';
			cssall += '.badge-warning[href]:hover,.badge-warning[href]:focus{color:' + extraccion.text.puro + ';background-color:' + extraccion.warning.borde + ';}\n';
			cssall += '.badge-danger{color:' + extraccion.light.focus + ';background-color:' + extraccion.danger.puro + ';}\n';
			cssall += '.badge-danger[href]:hover,.badge-danger[href]:focus{color:' + extraccion.light.focus + ';background-color:' + extraccion.danger.borde + ';}\n';
			cssall += '.badge-light{color:' + extraccion.text.puro + ';background-color:' + extraccion.light.puro + ';}\n';
			cssall += '.badge-light[href]:hover,.badge-light[href]:focus{color:' + extraccion.text.puro + ';background-color:' + extraccion.light.borde + ';}\n';
			cssall += '.badge-dark{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.puro + ';}\n';
			cssall += '.badge-dark[href]:hover,.badge-dark[href]:focus{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.borde + ';}\n';

			/**jumbotron**/
			cssall += '.jumbotron{color:' + extraccion.dark.puro + ';background-color:' + extraccion.text.navDis + ';}\n';

			/**alerts**/
			cssall += '.alert-primary{color:' + extraccion.primary.alertTx + ';background-color:' + extraccion.primary.focus + ';border-color:' + extraccion.primary.tabBg + ';}\n';
			cssall += '.alert-primary hr{border-top-color:' + extraccion.primary.tabBgHv + ';}\n';
			cssall += '.alert-primary .alert-link{color:' + extraccion.primary.alertLk + ';}\n';
			cssall += '.alert-secondary{color:' + extraccion.secondary.alertTx + ';background-color:' + extraccion.secondary.focus + ';border-color:' + extraccion.secondary.tabBg + ';}\n';
			cssall += '.alert-secondary hr{border-top-color:' + extraccion.secondary.tabBgHv + ';}\n';
			cssall += '.alert-secondary .alert-link{color:' + extraccion.secondary.alertLk + ';}\n';
			cssall += '.alert-success{color:' + extraccion.success.alertTx + ';background-color:' + extraccion.success.focus + ';border-color:' + extraccion.success.tabBg + ';}\n';
			cssall += '.alert-success hr{border-top-color:' + extraccion.success.tabBgHv + ';}\n';
			cssall += '.alert-success .alert-link{color:' + extraccion.success.alertLk + ';}\n';
			cssall += '.alert-info{color:' + extraccion.info.alertTx + ';background-color:' + extraccion.info.focus + ';border-color:' + extraccion.info.tabBg + ';}\n';
			cssall += '.alert-info hr{border-top-color:' + extraccion.info.tabBgHv + ';}\n';
			cssall += '.alert-info .alert-link{color:' + extraccion.info.alertLk + ';}\n';
			cssall += '.alert-warning{color:' + extraccion.warning.alertTx + ';background-color:' + extraccion.warning.focus + ';border-color:' + extraccion.warning.tabBg + ';}\n';
			cssall += '.alert-warning hr{border-top-color:' + extraccion.warning.tabBgHv + ';}\n';
			cssall += '.alert-warning .alert-link{color:' + extraccion.warning.alertLk + ';}\n';
			cssall += '.alert-danger{color:' + extraccion.danger.alertTx + ';background-color:' + extraccion.danger.focus + ';border-color:' + extraccion.danger.tabBg + ';}\n';
			cssall += '.alert-danger hr{border-top-color:' + extraccion.danger.tabBgHv + ';}\n';
			cssall += '.alert-danger .alert-link{color:' + extraccion.danger.alertLk + ';}\n';

			cssall += '.alert-light{color:' + extraccion.light.alertTx + ';background-color:' + extraccion.light.focus + ';border-color:' + extraccion.light.tabBg + ';}\n';
			cssall += '.alert-light hr{border-top-color:' + extraccion.light.tabBgHv + ';}\n';
			cssall += '.alert-light .alert-link{color:' + extraccion.light.alertLk + ';}\n';

			cssall += '.alert-dark{color:' + extraccion.dark.alertTx + ';background-color:' + extraccion.dark.focus + ';border-color:' + extraccion.dark.tabBg + ';}\n';
			cssall += '.alert-dark hr{border-top-color:' + extraccion.dark.tabBgHv + ';}\n';
			cssall += '.alert-dark .alert-link{color:' + extraccion.dark.alertLk + ';}\n';

			/**progress**/
			cssall += '.progress{background-color:' + extraccion.text.navDis + ';}\n';
			cssall += '.progress-bar{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.puro + ';}\n';

			/**listgroup**/
			cssall += '.list-group-item-action{color:' + extraccion.text.navHov + ';}\n';
			cssall += '.list-group-item-action:hover,.list-group-item-action:focus{color:' + extraccion.dark.puro + ';background-color:' + extraccion.light.navHov + ';}\n';
			cssall += '.list-group-item-action:active{color:' + extraccion.text.alertTx + ';background-color:' + extraccion.light.focus + ';}\n';

			cssall += '.list-group-item{background-color:' + extraccion.light.navDis + ';border-color:' + extraccion.secondary.breadBg + ';}\n';
			cssall += '.list-group-item.disabled,.list-group-item:disabled{color:' + extraccion.text.navHov + ';background-color:' + extraccion.background.focusHv + ';}\n';
			cssall += '.list-group-item.active{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.puro + ';border-color:' + extraccion.primary.puro + ';}\n';

			cssall += '.list-group-item-primary{color:' + extraccion.primary.alertTx + ';background-color:' + extraccion.primary.navHov + ';}\n';
			cssall += '.list-group-item-primary.list-group-item-action:hover,.list-group-item-primary.list-group-item-action:focus{color:' + extraccion.primary.alertLk + ';background-color:' + extraccion.primary.focusHv + ';}\n';
			cssall += '.list-group-item-primary.list-group-item-action.active{color:' + extraccion.light.focus + ';background-color:' + extraccion.primary.focusHv + ';border-color:' + extraccion.primary.navHov + ';}\n';

			cssall += '.list-group-item-secondary{color:' + extraccion.secondary.alertTx + ';background-color:' + extraccion.secondary.navHov + ';}\n';
			cssall += '.list-group-item-secondary.list-group-item-action:hover,.list-group-item-secondary.list-group-item-action:focus{color:' + extraccion.primary.alertLk + ';background-color:' + extraccion.secondary.focusHv + ';}\n';
			cssall += '.list-group-item-secondary.list-group-item-action.active{color:' + extraccion.light.focus + ';background-color:' + extraccion.secondary.focusHv + ';border-color:' + extraccion.secondary.navHov + ';}\n';

			cssall += '.list-group-item-success{color:' + extraccion.success.alertTx + ';background-color:' + extraccion.success.navHov + ';}\n';
			cssall += '.list-group-item-success.list-group-item-action:hover,.list-group-item-success.list-group-item-action:focus{color:' + extraccion.success.alertLk + ';background-color:' + extraccion.success.focusHv + ';}\n';
			cssall += '.list-group-item-success.list-group-item-action.active{color:' + extraccion.light.focus + ';background-color:' + extraccion.success.focusHv + ';border-color:' + extraccion.success.navHov + ';}\n';

			cssall += '.list-group-item-info{color:' + extraccion.info.alertTx + ';background-color:' + extraccion.info.navHov + ';}\n';
			cssall += '.list-group-item-info.list-group-item-action:hover,.list-group-item-info.list-group-item-action:focus{color:' + extraccion.info.alertLk + ';background-color:' + extraccion.info.focusHv + ';}\n';
			cssall += '.list-group-item-info.list-group-item-action.active{color:' + extraccion.light.focus + ';background-color:' + extraccion.info.focusHv + ';border-color:' + extraccion.info.navHov + ';}\n';

			cssall += '.list-group-item-warning{color:' + extraccion.warning.alertTx + ';background-color:' + extraccion.warning.navHov + ';}\n';
			cssall += '.list-group-item-warning.list-group-item-action:hover,.list-group-item-warning.list-group-item-action:focus{color:' + extraccion.warning.alertLk + ';background-color:' + extraccion.warning.focusHv + ';}\n';
			cssall += '.list-group-item-warning.list-group-item-action.active{color:' + extraccion.light.focus + ';background-color:' + extraccion.warning.focusHv + ';border-color:' + extraccion.warning.navHov + ';}\n';

			cssall += '.list-group-item-danger{color:' + extraccion.danger.alertTx + ';background-color:' + extraccion.danger.navHov + ';}\n';
			cssall += '.list-group-item-danger.list-group-item-action:hover,.list-group-item-danger.list-group-item-action:focus{color:' + extraccion.danger.alertLk + ';background-color:' + extraccion.danger.focusHv + ';}\n';
			cssall += '.list-group-item-danger.list-group-item-action.active{color:' + extraccion.light.focus + ';background-color:' + extraccion.danger.focusHv + ';border-color:' + extraccion.danger.navHov + ';}\n';

			cssall += '.list-group-item-light{color:' + extraccion.light.alertTx + ';background-color:' + extraccion.light.navHov + ';}\n';
			cssall += '.list-group-item-light.list-group-item-action:hover,.list-group-item-light.list-group-item-action:focus{color:' + extraccion.dark.alertLk + ';background-color:' + extraccion.light.focusHv + ';}\n';
			cssall += '.list-group-item-light.list-group-item-action.active{color:' + extraccion.dark.puro + ';background-color:' + extraccion.light.focusHv + ';border-color:' + extraccion.light.navHov + ';}\n';

			cssall += '.list-group-item-dark{color:' + extraccion.dark.alertTx + ';background-color:' + extraccion.dark.navHov + ';}\n';
			cssall += '.list-group-item-dark.list-group-item-action:hover,.list-group-item-dark.list-group-item-action:focus{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.focusHv + ';}\n';
			cssall += '.list-group-item-dark.list-group-item-action.active{color:' + extraccion.light.focus + ';background-color:' + extraccion.dark.focusHv + ';border-color:' + extraccion.dark.navHov + ';}\n';

			/**closeicon**/
			// cssall += '.close{color:' + extraccion.dark.puro + ';text-shadow:0 1px 0' + extraccion.secondary.puro + ';}\n';
			// cssall += '.close:hover,.close:focus{color:' + extraccion.dark.puro + ';}\n';

			/**modal**/
			// cssall += '.modal-content{background-color:' + extraccion.light.focus + ';border-color:' + extraccion.secondary.tabBgHv + ';}\n';
			cssall += '.modal-content{border-color:' + extraccion.secondary.tabBgHv + ';}\n';
			cssall += '.modal-backdrop{background-color:' + extraccion.dark.puro + ';}\n';
			cssall += '.modal-header{border-bottom-color:' + extraccion.secondary.tabBgHv + ';}\n';
			cssall += '.modal-footer{border-top-color:' + extraccion.secondary.tabBgHv + ';}\n';

			/**tooltip**/
			// cssall += '.bs-tooltip-top .arrow::before,.bs-tooltip-auto[x-placement^="top"] .arrow::before{border-top-color:' + extraccion.dark.alertLk + ';}\n';
			// cssall += '.bs-tooltip-right .arrow::before,.bs-tooltip-auto[x-placement^="right"] .arrow::before{border-right-color:' + extraccion.dark.alertLk + ';}\n';
			// cssall += '.bs-tooltip-bottom .arrow::before,.bs-tooltip-auto[x-placement^="bottom"] .arrow::before{border-bottom-color:' + extraccion.dark.alertLk + ';}\n';
			// cssall += '.bs-tooltip-left .arrow::before,.bs-tooltip-auto[x-placement^="left"] .arrow::before{border-left-color:' + extraccion.dark.alertLk + ';}\n';
			// cssall += '.tooltip-inner{color:' + extraccion.background.focus + ';background-color:' + extraccion.dark.alertLk + ';}\n';

			/**popover**/
			cssall += '.popover{background-color:' + extraccion.light.focus + ';border-color:' + extraccion.secondary.navDis + ';}\n';
			cssall += '.bs-popover-top .arrow::before,.bs-popover-auto[x-placement^="top"] .arrow::before{border-top-color:' + extraccion.secondary.navDis + ';}\n';
			cssall += '.bs-popover-top .arrow::after,.bs-popover-auto[x-placement^="top"] .arrow::after{border-top-color:' + extraccion.light.focus + ';}\n';
			cssall += '.bs-popover-right .arrow::after,.bs-popover-auto[x-placement^="right"] .arrow::after{border-right-color:' + extraccion.light.focus + ';}\n';
			cssall += '.bs-popover-bottom .arrow::before,.bs-popover-auto[x-placement^="bottom"] .arrow::before{border-bottom-color:' + extraccion.secondary.navDis + ';}\n';
			cssall += '.bs-popover-bottom .arrow::after,.bs-popover-auto[x-placement^="bottom"] .arrow::after{border-bottom-color:' + extraccion.light.focus + ';}\n';
			cssall += '.bs-popover-bottom .popover-header::before,.bs-popover-auto[x-placement^="bottom"] .popover-header::before{border-bottom-color:' + extraccion.secondary.navDis + ';}\n';
			cssall += '.bs-popover-left .arrow::before,.bs-popover-auto[x-placement^="left"] .arrow::before{border-left-color:' + extraccion.secondary.navDis + ';}\n';
			cssall += '.bs-popover-left .arrow::after,.bs-popover-auto[x-placement^="left"] .arrow::after{border-left-color:' + extraccion.light.focus + ';}\n';
			cssall += '.popover-header{color:' + extraccion.primary.puro + ';background-color:' + extraccion.secondary.breadBg + ';border-bottom-color:' + extraccion.secondary.navDis + ';}\n';
			cssall += '.popover-body{color:' + extraccion.dark.alertTx + ';}\n';

			/**carousel**/
			// cssall += '.carousel-control-prev,.carousel-control-next{color:' + extraccion.light.focus + ';}\n';
			// cssall += '.carousel-control-prev:hover,.carousel-control-prev:focus,.carousel-control-next:hover,.carousel-control-next:focus{color:' + extraccion.light.focus + ';}\n';
			// cssall += '.carousel-indicators li{background-color:' + extraccion.light.navHov + ';}\n';
			cssall += '.carousel-indicators .active{background-color:' + extraccion.primary.puro + ';}\n';
			// cssall += '.carousel-caption{color:' + extraccion.text.puro + ';}\n';

			/**Backgrounds**/

			cssall += '.bg-primary{background-color:' + extraccion.primary.puro + ' !important;}\n';
			cssall += 'a.bg-primary:hover,a.bg-primary:focus,button.bg-primary:hover,button.bg-primary:focus{background-color:' + extraccion.primary.borde + ' !important;}\n';
			cssall += '.bg-secondary{background-color:' + extraccion.secondary.puro + ' !important;}\n';
			cssall += 'a.bg-secondary:hover,a.bg-secondary:focus,button.bg-secondary:hover,button.bg-secondary:focus{background-color:' + extraccion.secondary.borde + ' !important;}\n';
			cssall += '.bg-success{background-color:' + extraccion.success.focusHv + ' !important;}\n';
			cssall += 'a.bg-success:hover,a.bg-success:focus,button.bg-success:hover,button.bg-success:focus{background-color:' + extraccion.success.borde + ' !important;}\n';
			cssall += '.bg-info{background-color:' + extraccion.info.puro + ' !important;}\n';
			cssall += 'a.bg-info:hover,a.bg-info:focus,button.bg-info:hover,button.bg-info:focus{background-color:' + extraccion.info.borde + ' !important;}\n';
			cssall += '.bg-warning{background-color:' + extraccion.warning.puro + ' !important;}\n';
			cssall += 'a.bg-warning:hover,a.bg-warning:focus,button.bg-warning:hover,button.bg-warning:focus{background-color:' + extraccion.warning.borde + ' !important;}\n';
			cssall += '.bg-danger{background-color:' + extraccion.danger.puro + ' !important;}\n';
			cssall += 'a.bg-danger:hover,a.bg-danger:focus,button.bg-danger:hover,button.bg-danger:focus{background-color:' + extraccion.danger.borde + ' !important;}\n';
			cssall += '.bg-light{background-color:' + extraccion.light.puro + ' !important;}\n';
			cssall += 'a.bg-light:hover,a.bg-light:focus,button.bg-light:hover,button.bg-light:focus{background-color:' + extraccion.light.borde + ' !important;}\n';
			cssall += '.bg-dark{background-color:' + extraccion.dark.puro + ' !important;}\n';
			cssall += 'a.bg-dark:hover,a.bg-dark:focus,button.bg-dark:hover,button.bg-dark:focus{background-color:' + extraccion.dark.borde + ' !important;}\n';
			// cssall += '.bg-white{background-color:' + extraccion.light.focus + ' !important;}\n';

			/**Borders**/
			cssall += '.border{border-color:' + extraccion.text.tabBd + ' !important;}\n';
			cssall += '.border-top{border-top-color:' + extraccion.text.tabBd + ' !important;}\n';
			cssall += '.border-bottom{border-bottom-color:' + extraccion.text.tabBd + ' !important;}\n';
			cssall += '.border-right-color{border-right-color:' + extraccion.text.tabBd + ' !important;}\n';
			cssall += '.border-bottom-color{border-bottom-color:' + extraccion.text.tabBd + ' !important;}\n';
			cssall += '.border-left-color{border-left-color:' + extraccion.text.tabBd + ' !important;}\n';
			cssall += '.border-primary{border-color:' + extraccion.primary.puro + ' !important;}\n';
			cssall += '.border-secondary{border-color:' + extraccion.secondary.puro + ' !important;}\n';
			cssall += '.border-success{border-color:' + extraccion.success.focusHv + ' !important;}\n';
			cssall += '.border-info{border-color:' + extraccion.info.puro + ' !important;}\n';
			cssall += '.border-warning{border-color:' + extraccion.warning.puro + ' !important;}\n';
			cssall += '.border-danger{border-color:' + extraccion.danger.puro + ' !important;}\n';
			cssall += '.border-light{border-color:' + extraccion.light.puro + ' !important;}\n';
			cssall += '.border-dark{border-color:' + extraccion.dark.puro + ' !important;}\n';
			// cssall += '.border-white{border-color:' + extraccion.background.puro + ' !important;}\n';

			/**Shadows**/
			cssall += '.shadow-sm{box-shadow:0 0.125rem 0.25rem ' + extraccion.dark.tabBgHv + ' !important;}\n';
			cssall += '.shadow{box-shadow:0 0.5rem 1rem ' + extraccion.dark.breadBg + ' !important;}\n';
			cssall += '.shadow-lg{box-shadow:0 1rem 3rem ' + extraccion.dark.breadBg + ' !important;}\n';

			/**Text**/
			// cssall += '.text-white{color:' + extraccion.light.focus + ' !important;}\n';
			cssall += '.text-primary{color:' + extraccion.primary.puro + ' !important;}\n';
			cssall += 'a.text-primary:hover,a.text-primary:focus{color:' + extraccion.primary.borde + ' !important;}\n';
			cssall += '.text-secondary{color:' + extraccion.secondary.puro + ' !important;}\n';
			cssall += 'a.text-secondary:hover,a.text-secondary:focus{color:' + extraccion.secondary.borde + ' !important;}\n';
			cssall += '.text-success{color:' + extraccion.success.focusHv + ' !important;}\n';
			cssall += 'a.text-success:hover,a.text-success:focus{color:' + extraccion.success.borde + ' !important;}\n';
			cssall += '.text-info{color:' + extraccion.info.puro + ' !important;}\n';
			cssall += 'a.text-info:hover,a.text-info:focus{color:' + extraccion.info.borde + ' !important;}\n';
			cssall += '.text-warning{color:' + extraccion.warning.puro + ' !important;}\n';
			cssall += 'a.text-warning:hover,a.text-warning:focus{color:' + extraccion.warning.borde + ' !important;}\n';
			cssall += '.text-danger{color:' + extraccion.danger.puro + ' !important;}\n';
			cssall += 'a.text-danger:hover,a.text-danger:focus{color:' + extraccion.danger.borde + ' !important;}\n';
			cssall += '.text-light{color:' + extraccion.light.puro + ' !important;}\n';
			cssall += 'a.text-light:hover,a.text-light:focus{color:' + extraccion.light.borde + ' !important;}\n';
			cssall += '.text-dark{color:' + extraccion.dark.puro + ' !important;}\n';
			cssall += 'a.text-dark:hover,a.text-dark:focus{color:' + extraccion.dark.borde + ' !important;}\n';
			cssall += '.text-body{color:' + extraccion.text.puro + ' !important;}\n';
			cssall += '.text-muted{color:' + extraccion.secondary.puro + ' !important;}\n';
			// cssall += '.text-black-50{color:' + extraccion.text.navHov + ' !important;}\n';
			// cssall += '.text-white-50{color:' + extraccion.light.navHov + ' !important;}\n';

			/**Block Editor**/
			cssall += '.has-primary-color{color:' + extraccion.primary.puro + ' !important;}\n';
			cssall += '.has-primary-background-color{background-color:' + extraccion.primary.puro + ' !important;}\n';
			cssall += '.has-secondary-color{color:' + extraccion.secondary.puro + ' !important;}\n';
			cssall += '.has-secondary-background-color{background-color:' + extraccion.secondary.puro + ' !important;}\n';
			cssall += '.has-success-color{color:' + extraccion.success.puro + ' !important;}\n';
			cssall += '.has-success-background-color{background-color:' + extraccion.success.puro + ' !important;}\n';
			cssall += '.has-info-color{color:' + extraccion.info.puro + ' !important;}\n';
			cssall += '.has-info-background-color{background-color:' + extraccion.info.puro + ' !important;}\n';
			cssall += '.has-warning-color{color:' + extraccion.warning.puro + ' !important;}\n';
			cssall += '.has-warning-background-color{background-color:' + extraccion.warning.puro + ' !important;}\n';
			cssall += '.has-danger-color{color:' + extraccion.danger.puro + ' !important;}\n';
			cssall += '.has-danger-background-color{background-color:' + extraccion.danger.puro + ' !important;}\n';
			cssall += '.has-light-color{color:' + extraccion.light.puro + ' !important;}\n';
			cssall += '.has-light-background-color{background-color:' + extraccion.light.puro + ' !important;}\n';
			cssall += '.has-dark-color{color:' + extraccion.dark.puro + ' !important;}\n';
			cssall += '.has-dark-background-color{background-color:' + extraccion.dark.puro + ' !important;}\n';


		// console.log(cssall);

        return cssall;

	}

/************************************************************************************************************************************************************************/

	$.each( misColores, function( index, value ) {

		value = value['id']; // id de cada objeto en mi array principal

		wp.customize( value, 'ekiline_textarea_css', function( field1, field2 ) {

			field1.bind( function( item ) {

				item = cssOrders( proColores( misColores ) );
				field2.set( item );

			} );

		});

	});

}); // jQuery(document)
