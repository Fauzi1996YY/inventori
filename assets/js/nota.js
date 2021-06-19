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
			, hargaSatuan = parseInt(nota.dataset.hargaSatuan)
			, canHaveBonus = parseInt(nota.dataset.bonus)
			, bonusPar = nota.querySelector('.bonus_field')
			, bonusNumber = nota.querySelector('.bonus_number')
			, bonus = 0
			, setTotalHarga = function() {
				var total = parseInt(esTabungBesar.value || 0) + parseInt(esTabungKecil.value || 0) + parseInt(esSerut.value || 0);
				totalHarga.value = formatter.format(total * hargaSatuan);
			},
			setBonus = function() {
				var total = parseInt(esTabungBesar.value || 0) + parseInt(esTabungKecil.value || 0) + parseInt(esSerut.value || 0);
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
		
		esTabungBesar.oninput = function(e) {
			nota = document.querySelector('.nota');
			canHaveBonus = parseInt(nota.dataset.bonus);
			
			if (hargaSatuan > 0) {
				setTotalHarga();
			}
			if (canHaveBonus > 0) {
				setBonus();
			}
		}

		esTabungKecil.oninput = function(e) {
			nota = document.querySelector('.nota');
			canHaveBonus = parseInt(nota.dataset.bonus);

			if (hargaSatuan > 0) {
				setTotalHarga();
			}
			if (canHaveBonus > 0) {
				setBonus();
			}
		}

		esSerut.oninput = function(e) {
			nota = document.querySelector('.nota');
			canHaveBonus = parseInt(nota.dataset.bonus);
			
			if (hargaSatuan > 0) {
				setTotalHarga();
			}
			if (canHaveBonus > 0) {
				setBonus();
			}
		}

		if (hargaSatuan > 0) {
			// totalHarga.setAttribute('readonly', true);
			// setTotalHarga();
			setBonus();
		}
		if (canHaveBonus < 1) {
			bonusPar.style.display = 'none';
		}
		
	}());

	/**
	* 
	**********************************************************************/
	(function() {
		
    console.log('assets/js/nota.js is loaded');

	}());

}());