jQuery().ready(function(){
    jQuery('#the-list').on('click', 'a.editinline', function(){
        $classes    = jQuery(this).closest('tr').find('.thumb input[type="hidden"]').val();
        jQuery('#quick_preview_category_icon').removeClass(jQuery('#pa_prev_class').val()).addClass($classes.replace("|",' '));
        jQuery('#pa_prev_class').val($classes.replace("|",' '));
        jQuery('fieldset input[name="category_icon"]').val($classes);
        return false;
    });
});



