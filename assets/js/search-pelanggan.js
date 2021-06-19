;(function(){

  'use strict';

  /**
	* Change `total_harga` for customers
	**********************************************************************/
	(function() {
		
    var searchBox = document.querySelector('.search-box');

		if (!searchBox) {
			return false;
		}

		var input = searchBox.querySelector('input[type="search"]')
			, hiddenInput = searchBox.querySelector('input[name="id_user"]')
			, form = searchBox.closest('form')
			, currentValueText = searchBox.nextElementSibling
			, result = searchBox.querySelector('ul.result')
			, waiting
			, baseURL = document.querySelector("meta[name='identifier-URL']").getAttribute('content')
			, http = new XMLHttpRequest()
			, hideSearchResult = function() {
				result.style.display = 'none';
			}
			, showSearchResult = function() {
				result.style.display = 'block';
			}
			, esTabungBesar = form.querySelector('input[name="es_tabung_besar"]')
			, esTabungKecil = form.querySelector('input[name="es_tabung_kecil"]')
			, esSerut = form.querySelector('input[name="es_serut"]')
			;
		
		if (!searchBox) {
			return false;
		}

		input.onfocus = function() {
			if (input.value != '' && result.hasChildNodes()) {
				showSearchResult();
			}
		};

		input.onblur = function() {
			setTimeout(function() {
				hideSearchResult();
			}, 100);
		};

		input.oninput = function(e) {

			if (input.value == '') {
				clearTimeout(waiting);
				http.abort();
				result.innerHTML = '';
				return false;
			}

			clearTimeout(waiting);
			http.abort();
			waiting = setTimeout(function() {
				http.open("POST", baseURL + '/pelanggan/box-search/', true);
				http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
   			http.send('idsj=' + searchBox.dataset.idsj + '&name=' + input.value);
				http.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var data = JSON.parse(this.responseText);
						result.innerHTML = data.items;
					}
				};
			}, 500);
		};

		result.onclick = function(e) {
			var id = e.target.dataset.id
				, nama = e.target.dataset.nama
				, bonus = e.target.dataset.bonus
				;

			if (id && id > 0) {
				console.log(id, nama);
				hiddenInput.value = id;
				currentValueText.innerHTML = '<span>' + nama + '</span>';
				form.dataset.bonus = bonus;

				/* This bonus is set from nota.js */
				if (bonus < 1) {
					var bonusPar = form.querySelector('.bonus_field')
						, bonusEsTabungKecil = form.querySelector('input[name="bonus_es_tabung_kecil"]')
						;

					bonusPar.style.display = 'none';
					bonusEsTabungKecil.value = 0;
				}

				setTimeout(function() {
					var event = new Event('input', {
						bubbles: true,
						cancelable: true,
					});

					esTabungBesar.dispatchEvent(event);
					esTabungKecil.dispatchEvent(event);
					esSerut.dispatchEvent(event);
				}, 100);
			}
		};
		
	}());

	/**
	* 
	**********************************************************************/
	(function() {
		
    console.log('assets/js/search-pelanggan.js is loaded');

	}());

}());