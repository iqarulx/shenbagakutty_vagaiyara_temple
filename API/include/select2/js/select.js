// JavaScript Document
jQuery(function () {
  jQuery('select').each(function () {
    jQuery(this).select2({
      theme: 'bootstrap4',
      width: '100%',
      placeholder: jQuery(this).attr('placeholder'),
      allowClear: Boolean(jQuery(this).data('allow-clear')),
    });
  });
});