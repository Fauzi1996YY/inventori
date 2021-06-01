;(function(){

  'use strict';

	/**
	* 
	**********************************************************************/
	(function() {
		
    var toggle = document.querySelector('.header span.toggle')
			, sidebar = document.querySelector('.sidebar')
			;

		if (!toggle) {
			return false;
		}

		toggle.onclick = function () {
			sidebar.classList.toggle('show');
		};

	}());

	/**
	* 
	**********************************************************************/
	(function() {
		
    var inputNumber = document.querySelectorAll('input[type="number"]')
			;
		
		if (inputNumber.length < 1) {
			return;
		}

		for (var i = 0; i < inputNumber.length; i++) {
			inputNumber[i].onfocus = function (e) {
				e.target.select();
			};
		}

	}());

	/**
	* 
	**********************************************************************/
	(function() {
		
    console.log('assets/js/global.js is loaded');

	}());

}());