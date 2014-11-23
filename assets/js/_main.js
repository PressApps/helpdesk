/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can 
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Google CDN, Latest jQuery
 * To use the default WordPress version of jQuery, go to lib/config.php and
 * remove or comment out: add_theme_support('jquery-cdn');
 * ======================================================================== */

(function($) {

// Use this variable to set up the common and page specific functions. If you 
// rename this variable, you will also need to rename the namespace below.
var Roots = {
  // All pages
  common: {
    init: function() {
      // JavaScript to be fired on all pages
      $(".main").fitVids();
    }
  },
  // Home page
  home: {
    init: function() {
      // JavaScript to be fired on the home page
    }
  },
  // About us page, note the change from about-us to about_us.
  about_us: {
    init: function() {
      // JavaScript to be fired on the about us page
    }
  }
};

// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Roots;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');

    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
  }
};

$(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.



/***************************************************
      Docs Voting
***************************************************/
jQuery().ready(function(){
  jQuery('a.like-btn').click(function(){
    response_div = jQuery(this).parent().parent();
    jQuery.ajax({
      url         : PAAV.base_url,
      data        : {'vote_like':jQuery(this).attr('post_id')},
      beforeSend  : function(){},
      success     : function(data){
        response_div.hide().html(data).fadeIn(400);
      },
      complete    : function(){}
    });
  });
  
  jQuery('a.dislike-btn').click(function(){
    response_div = jQuery(this).parent().parent();
    jQuery.ajax({
      url         : PAAV.base_url,
      data        : {'vote_dislike':jQuery(this).attr('post_id')},
      beforeSend  : function(){},
      success     : function(data){
        response_div.hide().html(data).fadeIn(400);
      },
      complete    : function(){}
    });
  });
});

/***************************************************
      Live Search
***************************************************/
var _url = '';
jQuery(function ($) {
    'use strict';

    jQuery.Autocomplete.prototype.suggest = function () {
      
        if (this.suggestions.length === 0) {
            this.hide();
            return;
        }

        var that = this,
            formatResult = that.options.formatResult,
            value = that.getQuery(that.currentValue),
            className = that.classes.suggestion,
            classSelected = that.classes.selected,
            container = $(that.suggestionsContainer),
            html = '';
        // Build suggestions inner HTML
        $.each(that.suggestions, function (i, suggestion) {
            html += '<div class="' + className + '" data-index="' + i + '"><h4>'+suggestion.icon + formatResult(suggestion, value) + '</h4></div>';
        });

        container.html(html).show();
        that.visible = true;

        // Select first value by default:
        if (that.options.autoSelectFirst) {
            that.selectedIndex = 0;
            container.children().first().addClass(classSelected);
        }
    };
    
 // Initialize ajax autocomplete:
    $('#live').autocomplete({
        serviceUrl: _url + '/wp-admin/admin-ajax.php',
        params: {'action':'search_title'},
        minChars: 2,
        maxHeight: 450,
        onSelect: function(suggestion) {
          window.location = suggestion.data.url;
        }
    });
});

