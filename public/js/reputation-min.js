!function($){"use strict";$.fn.voteBox=function(){return this.each(function(){var o=$(this),r={paths:{vote:config.ajaxPath+"/vote.php",getVoteBox:config.ajaxPath+"/getVoteBox.php"},upArrow:o.find(".up-arrow"),rep:o.find(".rep"),downArrow:o.find(".down-arrow")},t=function(){$.ajax({method:"GET",url:r.paths.getVoteBox,data:{postID:o.data("id")},success:function(t){o.html(t),r.upArrow=$(o.find(".up-arrow")),r.rep=o.find(".rep"),r.downArrow=o.find(".down-arrow"),r.upArrow.click(function(){e("up")}),r.downArrow.click(function(){e("down")}),o.validify({position:"right"})}})},e=function(t){$.ajax({method:"POST",url:r.paths.vote,data:{postID:o.data("id"),direction:t},success:function(t){var e=$.parseJSON(t);e.success?(r.upArrow.removeClass("active"),r.downArrow.removeClass("active"),e.newVote&&("up"===e.newVote?r.upArrow.addClass("active"):r.downArrow.addClass("active")),r.rep.text(parseInt(r.rep.text(),10)+e.scoreChange)):o.validify(e.error)},error:function(){o.displayError()}})};t()})}}(window.jQuery);
//# sourceMappingURL=./reputation-min.map