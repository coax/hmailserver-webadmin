<style type="text/css">
.file_container, .log_container {position:relative; font-size:9pt;}
div.event {margin:18px 18px 0 18px; padding:4px;}
div.event .line div {display:inline-block; margin:0 18px 0; line-height:150%;}
div.event a.tag {display:block; float:right; background:#1784c7; color:#fff; text-align:center; padding:4px; margin:0 4px 4px 0;}
div.event a.tag-ip {clear:right}
div.event a.tag-ip img {display:inline-block; vertical-align:middle; margin-right:.4em}
div.event:hover {background: #f9fafa;}
div.event .line:hover {background:#f2f2f2;}
/* Clearfix */
.cf:before, .cf:after {content:' '; display:table;}
.cf:after {clear:both;}
.cf {*zoom:1;}
</style>
<script type="text/javascript">
var file, intv, size, position;

var options = {
	parseSize: 50, // Parses only last n kilobytes. Value of 0 will parse entire file but not recommended for performance reasons.
	displayLimit: 100, // Limits displayed event group count.  Value of 0 will display all events.
	updateInterval: 500, // Update interval, in miliseconds. Lower value means frequent updates.
	newLine: "\r\n",
	flagPath: 'https://raw.githubusercontent.com/hazarkarabay/hmailserver-logviewer/master/flags/'
};

// This holds known events and their formatting.
var events = {
	SMTPD: {
		groupId: 'group-{0}-{2}',
		groupHTML: '<div id="group-{0}-{2}" class="event type-{0} cf"><a class="tag">{0}</a><a class="tag tag-group">{2}</a><a class="tag tag-ip">{4}</a><div class="line-container"></div></div>',
		lineHTML: '<div class="line"><div class="column-3">{3}</div><div class="column-5">{5}</div></div>',
		columnFormatter: function(data) {

			// Long line snipper.
			if (data[5].length > 100) {
				data[5] = data[5].substr(0, 40) + '···8<···' + data[5].substr(data[5].length - 40, data[5].length);
			}

			// AUTH LOGIN decoder.
			// First we get a RECEIVED: AUTH LOGIN
			// The next RECEIVED: line contains login username which is e-mail address, base64 encoded.
			if (typeof window._cfDataStorage != "object")
				window._cfDataStorage = {};

			if (typeof window._cfDataStorage[data[0] + data[2]] != "undefined" && data[5].indexOf('RECEIVED: ') > -1) {
				// We got it.
				var base64 = data[5].substr(data[5].lastIndexOf(" ") + 1, data[5].length);
				data[5] = 'RECEIVED: ' + decodeBase64(base64);
				delete window._cfDataStorage[data[0] + data[2]];
			} else if (data[5].indexOf('RECEIVED: AUTH LOGIN') > -1) {
				// Wait for it.
				window._cfDataStorage[data[0] + data[2]] = true;
			}

			return data;
		}
	},
	ERROR: {
		groupHTML: '<div id="group-{0}-{2}" class="event type-{0} cf"><a class="tag">{0}</a><div class="line-container"></div></div>',
		lineHTML: '<div class="line"><div class="column-2">{2}</div><div class="column-3">{3}</div></div>'
	}
};

// SMTPC is same thing as SMTPD and I hate repeating things.
events.SMTPC = events.SMTPD;

$(function() {
	$("#logfile").change(function() {
		file = $(this).prop('files')[0];
		if (!file)
			alert('You need to select a log file.');

		// Initialize
		$("div.log_container").html('');
		window.clearInterval(intv);
		size = file.size;
		position = 0;

		if (options.parseSize > 0 && size > 1024 * options.parseSize) {
			// As we cut from middle of the file, this position most likely located at middle of the line.
			position = size - 1024 * options.parseSize;

			// We try to find next new line and put the start mark there.
			var reader = new FileReader();
			reader.onloadend = function(evt) {
				if (evt.target.readyState == FileReader.DONE) {
					position += evt.target.result.indexOf(options.newLine) + options.newLine.length;

					// Start the loop
					read(position);
					intv = window.setInterval(function() {
						read(position)
					}, options.updateInterval);
				}
			};
			var blob = file.slice(position, position + 1000);
			reader.readAsBinaryString(blob);
		} else {
			// Start the loop
			read(position);
			intv = window.setInterval(function() {
				read(position)
			}, options.updateInterval);
		}
	});

	// IP lookup loop
	window.setInterval(function() {
		IPLookup();
	}, options.updateInterval);
});

function read(startByte, endByte) {
	var reader = new FileReader();

	var start = parseInt(startByte) || 0;
	var end = parseInt(endByte) || file.size;

	if (start == end)
		return;

	reader.onloadend = function(evt) {
		if (evt.target.readyState == FileReader.DONE) {
			parse(evt.target.result);
			if (options.displayLimit > 0) {
				// To keep browser up and running, we're limiting the on-screen log threads.
				$("div.log_container .event").slice(options.displayLimit).remove();
			}
			position = end;
		}
	};

	var blob = file.slice(start, end);
	reader.readAsBinaryString(blob);
}

function parse(text) {
	var lines = text.split(options.newLine);
	var data, elem;
	var container = $("div.log_container");

	var parent = container.parent();
	container.detach();

	console.time('parse');

	$.each(lines, function(k, v) {
		data = v.replace(/"/g, "").split("\t");

		if (typeof events[data[0]] != "undefined") {
			var eTempCache = events[data[0]];

			// Run column formatters if exists
			if (typeof eTempCache.columnFormatter == 'function') {
				data = eTempCache.columnFormatter(data);
			}

			if (typeof eTempCache.groupId != "undefined") {
				// This event type has to be grouped.
				if ($("#" + eTempCache.groupId.substitute(data), container).length == 0) {
					// Let's create it.
					container.prepend($.parseHTML(eTempCache.groupHTML.substitute(data)));
				} // Else it is already exists.
				elem = $("#" + eTempCache.groupId.substitute(data) + ' div.line-container', container);
			} else {
				// This is one shot event.
				elem = $($.parseHTML(eTempCache.groupHTML.substitute(data))).prependTo(container).find('div.line-container');
			}

			// Add line
			elem.prepend($.parseHTML(eTempCache.lineHTML.substitute(data)));

		} else
			return true; // continue;
	});

	console.timeEnd('parse');

	console.time('repaint');
	parent.append(container);
	console.timeEnd('repaint');
}

function IPLookup() {
	if (typeof window._hmLogIPLookup != "object")
		window._hmLogIPLookup = {
			running: false,
			data: {}
		};

	var cache = window._hmLogIPLookup;

	if (cache.running)
		return false;

	// Loop each .event which contains a class="tag tag-ip"
	// We don't want to DDoS that free service, so we limit our parallel queries.
	if ($(".log_container a.tag-ip:not(.processed)").length) {
		cache.running = true;
		$.when($(".log_container a.tag-ip:not(.processed)").slice(0, 3).each(function() {
				var elem = $(this);
				var ip = elem.text();

				// Cache control
				if (cache.data[ip]) {
					var flag = options.flagPath + cache.data[ip]['country_code'].toLowerCase() + '.png';
					elem.prepend('<img src="' + flag + '" title="' + cache.data[ip]['country_name'] + ' ' + cache.data[ip]['region_name'] + '" />');
					elem.addClass('processed');
				} else {
					$.get("http://freegeoip.net/json/" + ip)
						.done(function(data) {
							console.log(data);
							cache.data[ip] = data;
							var flag = options.flagPath + cache.data[ip]['country_code'].toLowerCase() + '.png';
							elem.prepend('<img src="' + flag + '" title="' + cache.data[ip]['country_name'] + ' ' + cache.data[ip]['region_name'] + '" />');
							elem.addClass('processed');
						}, "json");
				}
			}))
			.then(function() {
				cache.running = false;
			});
	}

}

// Helper functions
var base64Matcher = new RegExp("^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{4})([=]{1,2})?$");

var entityMap = {
	"&": "&amp;",
	"<": "&lt;",
	">": "&gt;",
	'"': '&quot;',
	"'": '&#39;',
	"/": '&#x2F;'
};

function escapeHtml(string) {
	return String(string).replace(/[&<>"'\/]/g, function(s) {
		return entityMap[s];
	});
}

String.prototype.substitute = function(obj) {
	return String(this).replace(/\\?\{([^{}]+)\}/g, function(match, name) {
		if (match.charAt(0) == '\\')
			return match.slice(1);
		return (obj[name] != null) ? escapeHtml(obj[name]) : '';
	});
};

// http://stackoverflow.com/questions/15900485/correct-way-to-convert-size-in-bytes-to-kb-mb-gb-in-javascript
function bytesToSize(bytes) {
	var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	if (bytes == 0)
		return '0 Bytes';
	var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

// http://stackoverflow.com/a/15016605
decodeBase64 = function(s) {
	var e = {},
		i, b = 0,
		c, x, l = 0,
		a, r = '',
		w = String.fromCharCode,
		L = s.length;
	var A = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
	for (i = 0; i < 64; i++) {
		e[A.charAt(i)] = i;
	}
	for (x = 0; x < L; x++) {
		c = e[s.charAt(x)];
		b = (b << 6) + c;
		l += 6;
		while (l >= 8) {
			((a = (b >>> (l -= 8)) & 0xff) || (x < (L - 2))) && (r += w(a));
		}
	}
	return r;
};

// for debug purposes
function dump(arr, level) {
	var dumped_text = "";
	if (!level)
		level = 0;

	//The padding given at the beginning of the line.
	var level_padding = "";
	for (var j = 0; j < level + 1; j++)
		level_padding += "    ";

	if (typeof(arr) == 'object') { //Array/Hashes/Objects
		for (var item in arr) {
			var value = arr[item];

			if (typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value, level + 1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>" + arr + "<===(" + typeof(arr) + ")";
	}
	return dumped_text;
}
</script>
    <div class="box medium file_container">
      <h2>hMailServer Log Parser</h2>
      <div style="margin:0 18px 18px;">
        <p>Select your log file.</p>
        <div style="margin-top:9px;"><input type="file" name="file" id="logfile" /></div>
      </div>
    </div>

    <div class="box large log_container">
      <h2>Results</h2>
      <div style="margin:0 18px 18px;">
        <p>Your logs will show here.</p>
      </div>
    </div>