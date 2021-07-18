;(function(){

  'use strict';

  /**
	* Change `total_harga` for customers
	**********************************************************************/
	(function() {
		
		var kategori = document.querySelector('#id_kategori_jurnal')
			, subKategori = document.querySelector('#id_sub_kategori_jurnal')
			;

		if (!kategori || !subKategori) {
			return false;
		}

		var setSubKategori = function(el) {
			var currentData = subKategoriJurnal[el.value]
				, html = '<option value="">Pilih sub kategori jurnal</option>'
				;
			
			if (currentData) {
				for (var property in currentData) {
					html+= '<option value="' + property + '" ' + (idSubKategoriJurnalValue == property ? 'selected' : '') + '>' + currentData[property] + '</option>';
				}
			}
			subKategori.innerHTML = html;
		};

		setSubKategori(kategori);
    kategori.onchange = function(e) {
			setSubKategori(this);
		}

	}());

	/**
	* 
	**********************************************************************/
	(function() {
		
    console.log('assets/js/jurnal-umum.js is loaded');

	}());

}());