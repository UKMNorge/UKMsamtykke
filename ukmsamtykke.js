jQuery(document).on('click', '#showExtraPerson', function(){
	var num = jQuery(this).attr('data-count');
	num++;
	jQuery('#person-'+ num).slideDown();
	jQuery(this).attr('data-count', num);
});