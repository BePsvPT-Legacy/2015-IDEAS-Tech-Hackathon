'use strict';
module.exports = {
	URL2JSON : function (url) {
		var obj = new Object();
		var items = url.split("&");
		var tp;
		for (var i in items) {
			tp = items[i].split("=");
			obj[tp[0]] = tp[1];
		}
		return obj;
	}
}
