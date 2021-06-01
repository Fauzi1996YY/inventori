;(function(){

  'use strict';

  /**
	* Change `total_harga` for customers
	**********************************************************************/
	(function() {
		
    var nota = document.querySelector('.nota');
		
		if (!nota) {
			return false;
		}

		var esTabungBesar = nota.querySelector('input[name="es_tabung_besar"]')
			, esTabungKecil = nota.querySelector('input[name="es_tabung_kecil"]')
			, esSerut = nota.querySelector('input[name="es_serut"]')
			, bonusEsTabungKecil = nota.querySelector('input[name="bonus_es_tabung_kecil"]')
			, totalHarga = nota.querySelector('input[name="total_harga"]')
			, hargaSatuan = nota.dataset.hargaSatuan
			, bonusPar = nota.querySelector('.bonus_field')
			, bonusNumber = nota.querySelector('.bonus_number')
			, bonus = 0
			, sum = function() {
				var total = parseInt(esTabungBesar.value || 0) + parseInt(esTabungKecil.value || 0) + parseInt(esSerut.value || 0);
				totalHarga.value = formatter.format(total * hargaSatuan);
				bonus = Math.floor(total / 10);
				bonusEsTabungKecil.value = bonus;
				bonusNumber.innerHTML = bonus;
				if (bonus > 0) {
					bonusPar.style.display = 'block';
				}
				else {
					bonusPar.style.display = 'none';
				}
			},
			formatter = new Intl.NumberFormat('id-ID', {
				maximumSignificantDigits: 3
			})
			;
		
		if (hargaSatuan > 0) {
			totalHarga.setAttribute('readonly', true);

			sum();

			esTabungBesar.oninput = function(e) {
				sum();
			}

			esTabungKecil.oninput = function(e) {
				sum();
			}

			esSerut.oninput = function(e) {
				sum();
			}
		}
		
	}());

	/**
	* 
	**********************************************************************/
	(function() {
		
    console.log('assets/js/nota.js is loaded');

	}());

}());