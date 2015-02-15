var acadefly = {
	URLParam: function(param, newValue) {
		var URLVariables, url;
		if(newValue === undefined) {
			url = window.location.search.substring(1);
			URLVariables = url.split("&");
			for(var i=0; i<URLVariables.length; i++) {
				var paramName = URLVariables[i].split("=");
				if(paramName[0] === param) {
					return unescape(paramName[1]);
				}
			}
			return false;
		} else {
			url = window.location.href;
			var betweenSlashes = url.split("/");
			afterSlashes = betweenSlashes[betweenSlashes.length-1];
			var newString;
			if(afterSlashes.indexOf("?") > -1 && afterSlashes.indexOf(param + "=") > -1) {
				//Change variable
				var currentValue = acadefly.URLParam(param);
				newString = afterSlashes.replace(param + "=" + currentValue, param + "=" + newValue);
			} else {
				//Set variable
				if(afterSlashes.indexOf("?") > -1) {
					newString = afterSlashes + "&" + param + "=" + newValue;
				} else {
					newString = "?" + param + "=" + newValue + afterSlashes;
				}
			}
			history.pushState(null, null, newString);
		}
	}
};

//Initialize everything
$(function() {
    tabs.init();
});

//Remove toolbars on mobile
window.scrollTo(0,1);