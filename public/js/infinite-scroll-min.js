!function($){$.fn.infiniteScroll=function(t){var n=$(this),i,o=!0,e=function(t){var n=$(window).scrollTop(),o=n+$(window).height(),e=t.offset().top,f=e+t.height();return f<=o+i.offset},f=function(){var t=n.find(i.selector+":last-child");if(""===n.html()||e(t)){var o=n.find(i.selector).length;return i.ajaxFunction(o),!0}return!1};"object"==typeof t?(i=$.extend({},$.fn.infiniteScroll.defaults,t),n.data("options",i),$(document).scroll(f)):(i=n.data("options"),"try"===t&&o?f():"stopTrying"===t?o=!1:"startTrying"===t&&(o=!0))},$.fn.infiniteScroll.defaults={ajaxFunction:function(){},selector:".item",offset:0}}(window.jQuery);