var FastInit = {
	onload : function() {
		if (FastInit.done) { return; }
		FastInit.done = true;
		for(var x = 0, al = FastInit.f.length; x < al; x++) {
			FastInit.f[x]();
		}
	},
	addOnLoad : function() {
		var a = arguments;
		for(var x = 0, al = a.length; x < al; x++) {
			if(typeof a[x] === 'function') { FastInit.f.push(a[x]); }
		}
	},
	listen : function() {
		if (/WebKit|khtml/i.test(navigator.userAgent)) {
			FastInit.timer = setInterval(function() {
				if (/loaded|complete/.test(document.readyState)) {
					clearInterval(FastInit.timer);
					delete FastInit.timer;
					FastInit.onload();
				}}, 10);
		} else if (document.addEventListener) {
			document.addEventListener('DOMContentLoaded', FastInit.onload, false);
		} else if(!FastInit.iew32) {
			Event.observe(window, 'load', FastInit.onload);
		}
	},
	f:[],done:false,timer:null,iew32:false
};
/*@cc_on @*/
/*@if (@_win32)
FastInit.iew32 = true;
document.write('<script id="__ie_onload" defer src="' + ((location.protocol == 'https:') ? '//0' : 'javascript:void(0)') + '"><\/script>');
document.getElementById('__ie_onload').onreadystatechange = function(){if (this.readyState == 'complete') { FastInit.onload(); }};
/*@end @*/
FastInit.listen();