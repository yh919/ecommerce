$(function () {
    'use strict';

 	// Hide Placeholder On Form Focus

	$('[placeholder]').focus(function () {

		$(this).attr('data-text', $(this).attr('placeholder'));

		$(this).attr('placeholder', '');

	}).blur(function () {

		$(this).attr('placeholder', $(this).attr('data-text'));

	});

	// Confirmation Message On Button

	$('.confirm').click(function () {

		return confirm('Are You Sure?');
	});



});


