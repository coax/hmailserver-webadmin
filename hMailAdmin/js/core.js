jQuery(document).ready(function() {
	// Login animation
	if ($('.login .container').length) {
		function fly() {
			const items = Math.floor(Math.random() * 3) + 1;

			for (let i = 0; i < items; i++) {
				let $div = $('<div class="fly"></div>');
				let size = Math.random() * 25 + 10;
				$div.css({
					width: size + 'px',
					height: size + 'px'
				});

				let topPos = Math.random() * ($('.container').height() - size) + 'px';
				let speed = Math.random() * 5 + 3 + 's';
				let delay = Math.random() * 3 + 's';
				let leftToRight = Math.random() < 0.5;

				if (leftToRight) {
					$div.css({
						top: topPos,
						left: '-30px',
						animation: `fly-right ${speed} linear`,
						animationDelay: delay
					});
				} else {
					$div.css({
						top: topPos,
						right: '-30px', // Start from the right side
						animation: `fly-left ${speed} linear`,
						animationDelay: delay
					});
				}

				$('.container').append($div);

				setTimeout(function() {
					$div.css('opacity', 1);
				}, parseFloat(delay) * 1000);

				setTimeout(function() {
					$div.css('opacity', 0);
					setTimeout(function() {
						$div.remove();
					}, 1000);
				}, (parseFloat(speed) + parseFloat(delay)) * 1000);
			}
		}

		fly();

		setInterval(function() {
			fly();
		}, Math.random() * 1500 + 1500);
	}

	// Init "timeago" plugin
	$.timeago.settings.strings.prefixAgo = '';
	$.timeago.settings.strings.suffixAgo = '';
	$('time.timeago').timeago();

	// Init "autosize" plugin
	autosize($('textarea'));

	// Icons
	feather.replace();

	// Global submit with validation
	$('.form').submit(function(e) {
		e.preventDefault();
		var attr = $(this).attr('onsubmit');
		if (typeof attr == typeof undefined || attr == false) {
			$(this).validation();
		}
	});

	// Focus on login
	if ($('[name=username]').length) {
		$('[name=username]').focus();
	}

	// Init "stupidtable" plugin
	if ($('.tablesort').length) {
		$('.tablesort').stupidtable({
			'ipaddress': function(a,b) {
			aIP = ip_address_pre(a);
			//console.log(aIP)
			bIP = ip_address_pre(b);
			//console.log(bIP)
			return aIP - bIP;
			}
		});
	}

	// Init "facebox" plugin
	$('a[rel*=facebox]').facebox();

	// Init "datepicker" plugin
	if ($('[data-toggle="datepicker"]').length) {
		$('[data-toggle="datepicker"]').datepicker({
			format: 'yyyy-mm-dd',
			autoHide: true,
			weekStart: hmail_config.weekStart,
			language: 'en-GB'
		});
	}

	// Log parser
	if ($('#log-parser').length) {
		var clear = $('#clear').hide();
		$('#log-parser button').on('click', function() {
			var form = $('#log-parser'),
				result = $('#results'),
				button = $(this),
				width = button.width()+1,
				text = button.text();

			if (!$('#results').length) button.parent().after('<div id="results"></div>');
			var result = $('#results');

			$.ajax({
				type: 'post',
				url: './custom_logs.php',
				data: form.serialize(),
				cache: false,
				timeout: 50000,
				dataType: 'json',
				beforeSend: function() {
					result.html('Please wait... this might take some time.');
					button.prop('disabled', true).addClass('wait').width(width).text('.');
				},
				success: function(data) {
					result.html(parseLog(data));
					button.prop('disabled', false).removeClass('wait').width(width).text(text);
					contentHeight = $('#content').height() + 55;
					$('html, body').animate({
						scrollTop: result.offset().top
					}, 500);
					clear.show();
					clear.one('click', function() { clear.hide(); result.remove(); return false; });
				},
				error: function(data) {
					result.html('Error loading log file!');
					button.prop('disabled', false).removeClass('wait').width(width).text(text);
				}
			});
			return false;
		});
	}

	// Live logging
	if ($('#live-logging').length) {
		function ShowHide(state, result, autoscroll) {
			if (state=='enabled') {
				result.css('display','block');
				autoscroll.parent().css('display','inline-block');
			} else {
				result.css('display','none');
				autoscroll.parent().css('display','none');
			}
		}

		var autoscroll = $('#autoscroll'),
			button = $('#live-logging button'),
			state = button.data('state'),
			result = $('#results'),
			scrolling;

		ShowHide(state, result, autoscroll);

		autoscroll.change(function() {
			if (this.checked) {
				// Autoscrolling
				scrolling = setInterval(function() {
					result.animate({
						scrollTop: result[0].scrollHeight
					}, 1000);
				}, 2500);
			} else {
				clearInterval(scrolling);
			}
		});

		button.click(function() {
			var state = $(this).data('state') == 'enabled' ? 'disabled' : 'enabled',
				width = button.width(),
				text = button.text();

			ShowHide(state, result, autoscroll);

			$.ajax({
				type: 'post',
				url: './custom_logs.php',
				data: {
					LiveLogging: state
				},
				cache: false,
				beforeSend: function() {
					button.prop('disabled', true).addClass('wait').width(width).text('.');
				},
				success: function(data) {
					button.prop('disabled', false).removeClass('wait').width(width).data('state', state).text(data);
				},
				error: function(data) {
					result.html('Error loading log file!');
					button.prop('disabled', false).removeClass('wait').width(width).text(text);
				}
			});
			return false;
		});
	}

	// Dashboard live values refresh
	if ($('#processed, #sessions').length) {
		var activity_array = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
		var activity = new Chartist.Line('#activity', {
			series: [activity_array]
		}, {
			fullWidth: true,
			axisX: {
				offset: 0,
				showLabel: false,
				showGrid: false
			},
			axisY: {
				offset: 0,
				showLabel: false,
				showGrid: false,
				onlyInteger: true
			},
			showLine: true,
			showPoint: false,
			showArea: true
		});
		var processed = new Chartist.Pie('#processed', {
			labels: ['Processed', 'Virus', 'Spam'],
			series: [0, 0, 0]
		}, {
			donut: true,
			donutWidth: 18,
			chartPadding: 0,
			showLabel: true,
			ignoreEmptyValues: false,
			plugins: [
				Chartist.plugins.tooltip({
					appendToBody: true
				})
			],
			labelInterpolationFnc: function(value) {
				var total = processed.data.series.reduce(function(prev, series) {
					return prev + series;
				}, 0);
				return total;
			}
		});
		processed.on('draw', function(ctx) {
			if (ctx.type === 'label') {
				if (ctx.index === 0) {
					ctx.element.attr({
						dx: ctx.element.root().width() / 2,
						dy: ctx.element.root().height() / 2
					});
				} else {
					ctx.element.remove();
				}
			}
		});
		var sessions = new Chartist.Pie('#sessions', {
			labels: ['SMTP', 'POP3', 'IMAP'],
			series: [0, 0, 0]
		}, {
			donut: true,
			donutWidth: 18,
			chartPadding: 0,
			showLabel: true,
			ignoreEmptyValues: false,
			plugins: [
				Chartist.plugins.tooltip({
					appendToBody: true
				})
			],
			labelInterpolationFnc: function(value) {
				var total = sessions.data.series.reduce(function(prev, series) {
					return prev + series;
				}, 0);
				return total;
			}
		});
		sessions.on('draw', function(ctx) {
			if (ctx.type === 'label') {
				if (ctx.index === 0) {
					ctx.element.attr({
						dx: ctx.element.root().width() / 2,
						dy: ctx.element.root().height() / 2
					});
				} else {
					ctx.element.remove();
				}
			}
		});
		Refresh();
		// Dashboard > Delivery queue
		function Refresh() {
			$.getJSON('./custom_queue.php',
				function(json) {
					// Processed messages
					var data = {
						series: json[0]
					};
					processed.update(data);
					$('#legit').text(Number(json[0][0].toFixed(0)).toLocaleString());
					$('#virus').text(Number(json[0][1].toFixed(0)).toLocaleString());
					$('#spam').text(Number(json[0][2].toFixed(0)).toLocaleString());
					// Hack to replace colors
					//$processed = $('#processed');
					//$processed.find('.ct-series-c').removeClass('ct-series-c').addClass('ct-series-e');
					//$processed.find('.ct-series-b').removeClass('ct-series-b').addClass('ct-series-f');
					//$processed.find('.ct-series-a').removeClass('ct-series-a').addClass('ct-series-c');

					// Open sessions
					data = {
						series: json[1]
					};
					sessions.update(data);
					$('#smtp').text(Number(json[1][0].toFixed(0)).toLocaleString());
					$('#pop3').text(Number(json[1][1].toFixed(0)).toLocaleString());
					$('#imap').text(Number(json[1][2].toFixed(0)).toLocaleString());

					// Delivery queue
					var queue = '';
					if (json[2] !== 0) {
						$.each(json[2], function(key, data) {
							queue += '<tr><td><a href="#" onclick="$.facebox({ajax:\'./custom_view.php?q=' + data[5] + '\'}); return false;">' + data[0] + '</a></td><td>' + data[1] + '</td><td>' + data[2] + '</td><td>' + data[3] + '</td><td>' + data[4] + '</td><td>' + data[6] + '</td><td><a href="#" onclick="$.facebox({ajax:\'./custom_delete.php?q=' + data[0] + '\'}); return false;"></a></td></tr>';
						});
					}
					$('#queue tbody').html(queue);
					// Server
					activity_array.push(parseInt(json[4]));
					activity_array.splice(0, 1);
					data = {
						series: [activity_array]
					};
					activity.update(data);
					// Queue count
					$('#count').text(json[4]);

					// Live logging
					$('#results').append(parseLog(json[5]));
				});
		}
		setInterval(Refresh, 5000);
	}

	// Show-hide
	if ($('h3 a').length) {
		$('h3 a').on('click', function() {
			$(this).parent().next().slideToggle(150);
			return false;
		});
	}

	// Responsive navigation
	if ($('#sidebar').length) {
		// Cache DOM elements
		var mainContent = $('main'),
			header = $('header'),
			sidebar = $('#sidebar'),
			hamburger = $('#mobile'),
			topNavigation = $('#top');

		// Check if mobile
		function mobileDevice() {
			return window.getComputedStyle(document.querySelector('main'), '::before').content.replace(/'/g, "").replace(/"/g, "");
		}

		// Move top navigation
		var resizing = false;
		moveNavigation();
		$(window).on('resize', function() {
			if (!resizing) {
				(!window.requestAnimationFrame) ? setTimeout(moveNavigation, 300): window.requestAnimationFrame(moveNavigation);
				resizing = true;
			}
		});

		function moveNavigation() {
			var m = mobileDevice();
			if (m == 'mobile' && topNavigation.parents('#sidebar').length == 0) {
				detachElements();
				topNavigation.appendTo(sidebar);
			} else if ((m == 'tablet' || m == 'desktop') && topNavigation.parents('#sidebar').length > 0) {
				detachElements();
				topNavigation.appendTo(header);
			}
			checkSelected(m);
			resizing = false;
		}

		function detachElements() {
			topNavigation.detach();
		}

		// Remove added classes on desktop
		function checkSelected(m) {
			if (m == 'desktop') $('.has-children.selected').removeClass('selected');
		}

		// Mobile menu
		hamburger.on('click', function(e) {
			e.preventDefault();
			$([sidebar, hamburger]).toggleClass('is-visible');
		});

		// Tablet menu
		$('.has-children > a').on('click', function(e) {
			var m = mobileDevice(),
				selectedItem = $(this);

			if ((m == 'desktop') && (selectedItem.parent().parent().attr('id') !== 'top')) {
				// Nothing
			} else {
				e.preventDefault();

				if (selectedItem.parent('li').hasClass('selected')) {
					selectedItem.parent('li').removeClass('selected');
				} else {
					sidebar.find('.has-children.selected').removeClass('selected');
					selectedItem.parents('li').addClass('selected');
				}
			}
		});

		// Scroll fixed position
		var windowHeight = $(window).height();
		var sidebarHeight = $('#sidebar').height() + 55;
		var contentHeight = $('#content').height() + 55;
		$(window).scroll(function() {
			var scrollTop = $(this).scrollTop();
			if ((scrollTop + windowHeight > sidebarHeight) && (contentHeight > sidebarHeight)) {
				$('#sidebar').addClass('is-fixed');
			} else {
				$('#sidebar').removeClass('is-fixed');
			}
		});
	}

	// Distributionlists
	if ($('#DistributionListMode').length) {
		if ($('#DistributionListMode').find(':selected').val() != 2) {
			$('#RequireSenderAddress').hide();
		}
		$('select[name="Mode"]').on('change', function() {
			if ($(this).val() != '2') {
				$('#RequireSenderAddress').hide();
			} else {
				$('#RequireSenderAddress').show();
			}
		});
	}

	// Toggle password view
	$('#toggle-password').click(function() {
		var icon = $(this),
			input = icon.prev();
		if (input.attr('type') == 'password') {
			input.attr('type', 'text');
			icon.empty().html('<i data-feather="eye"></i>');
		} else {
			input.attr('type', 'password');
			icon.empty().html('<i data-feather="eye-off"></i>');
		}
		feather.replace();
	});
});

function ip_address_pre(ip){
	var i, item;
	var m, n, t;
	var x, xa;

	if (!ip) {
		return 0
	}

	ip = ip.replace(/<[\s\S]*?>/g, "");
	// IPv4:Port
			t = ip.split(":");
			if (t.length == 2){
					m = t[0].split(".");
			}
			else {
					m = ip.split(".");
			}
	n = ip.split(":");
	x = "";
	xa = "";

	if (m.length == 4) {
		// IPv4
		for(i = 0; i < m.length; i++) {
			item = m[i];

			if(item.length == 1) {
				x += "00" + item;
			}
			else if(item.length == 2) {
				x += "0" + item;
			}
			else {
				x += item;
			}
		}
	}
	else if (n.length > 0) {
		// IPv6
		var count = 0;
		for(i = 0; i < n.length; i++) {
			item = n[i];

			if (i > 0) {
				xa += ":";
			}

			if(item.length === 0) {
				count += 0;
			}
			else if(item.length == 1) {
				xa += "000" + item;
				count += 4;
			}
			else if(item.length == 2) {
				xa += "00" + item;
				count += 4;
			}
			else if(item.length == 3) {
				xa += "0" + item;
				count += 4;
			}
			else {
				xa += item;
				count += 4;
			}
		}

		// Padding the ::
		n = xa.split(":");
		var paddDone = 0;

		for (i = 0; i < n.length; i++) {
			item = n[i];

			if (item.length === 0 && paddDone === 0) {
				for (var padding = 0 ; padding < (32-count) ; padding++) {
					x += "0";
					paddDone = 1;
				}
			}
			else {
				x += item;
			}
		}
	}
	return x;
}

// Confirm delete
function Confirm(question, yes, no, action) {
	$.facebox('<div class="center"><p>' + question + '</p><a href="#" class="button" id="yes">' + yes + '</a><a href="#" class="button" onclick="$.facebox.close(); return false;" style="margin-left:30px;">' + no + '</a></div>');
	$('body').unbind('keypress').keypress(function(e) {
		if (e.which == 13) {
			$('#yes').click();
			return false;
		}
	});
	$('#yes').one('click', function(e) {
		e.preventDefault();
		$('body').unbind('keypress');
		location.href = action;
	});
	return false;
}

// Replace alert prompt
function alert(message) {
	$.facebox('<div style="margin:18px; text-align:center;"><p>' + message + '</p><p><a href="#" onclick="$.facebox.close();" class="button">OK</a></p></div>');
}

// Form validation
$.fn.validation = function(callback) {
	var error = 0,
		self = $(this),
		button = this.find('button').first(),
		width = button.width()+1,
		height = button.height(),
		text = button.text(),
		any = /^(.|\n)+$/, // Default alphanumeric regex
		numeric = /^[\d.-]+$/, // Numerics only
		email = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, // E-mail
		emails = /^\w+([-+.]\w+)*@([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}((([,;]{1})([\s]{1})?)\w+([-+.]\w+)*@([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,})*$/, // E-mails
		date = /^(\d{2})\.(\d{2})\.(\d{4})(\, (\d{1,2}):(\d{1,2}))?$/, // ISO date
		currency = /^[-]?[0-9]{1,3}(?:\.?[0-9]{3})*(?:\,[0-9]{2})?$/, // ISO currency
		unicode = /^[A-Za-z\u0041-\u005A\u0061-\u007A\u00AA\u00B5\u00BA\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02C1\u02C6-\u02D1\u02E0-\u02E4\u02EC\u02EE\u0370-\u0374\u0376\u0377\u037A-\u037D\u0386\u0388-\u038A\u038C\u038E-\u03A1\u03A3-\u03F5\u03F7-\u0481\u048A-\u0527\u0531-\u0556\u0559\u0561-\u0587\u05D0-\u05EA\u05F0-\u05F2\u0620-\u064A\u066E\u066F\u0671-\u06D3\u06D5\u06E5\u06E6\u06EE\u06EF\u06FA-\u06FC\u06FF\u0710\u0712-\u072F\u074D-\u07A5\u07B1\u07CA-\u07EA\u07F4\u07F5\u07FA\u0800-\u0815\u081A\u0824\u0828\u0840-\u0858\u08A0\u08A2-\u08AC\u0904-\u0939\u093D\u0950\u0958-\u0961\u0971-\u0977\u0979-\u097F\u0985-\u098C\u098F\u0990\u0993-\u09A8\u09AA-\u09B0\u09B2\u09B6-\u09B9\u09BD\u09CE\u09DC\u09DD\u09DF-\u09E1\u09F0\u09F1\u0A05-\u0A0A\u0A0F\u0A10\u0A13-\u0A28\u0A2A-\u0A30\u0A32\u0A33\u0A35\u0A36\u0A38\u0A39\u0A59-\u0A5C\u0A5E\u0A72-\u0A74\u0A85-\u0A8D\u0A8F-\u0A91\u0A93-\u0AA8\u0AAA-\u0AB0\u0AB2\u0AB3\u0AB5-\u0AB9\u0ABD\u0AD0\u0AE0\u0AE1\u0B05-\u0B0C\u0B0F\u0B10\u0B13-\u0B28\u0B2A-\u0B30\u0B32\u0B33\u0B35-\u0B39\u0B3D\u0B5C\u0B5D\u0B5F-\u0B61\u0B71\u0B83\u0B85-\u0B8A\u0B8E-\u0B90\u0B92-\u0B95\u0B99\u0B9A\u0B9C\u0B9E\u0B9F\u0BA3\u0BA4\u0BA8-\u0BAA\u0BAE-\u0BB9\u0BD0\u0C05-\u0C0C\u0C0E-\u0C10\u0C12-\u0C28\u0C2A-\u0C33\u0C35-\u0C39\u0C3D\u0C58\u0C59\u0C60\u0C61\u0C85-\u0C8C\u0C8E-\u0C90\u0C92-\u0CA8\u0CAA-\u0CB3\u0CB5-\u0CB9\u0CBD\u0CDE\u0CE0\u0CE1\u0CF1\u0CF2\u0D05-\u0D0C\u0D0E-\u0D10\u0D12-\u0D3A\u0D3D\u0D4E\u0D60\u0D61\u0D7A-\u0D7F\u0D85-\u0D96\u0D9A-\u0DB1\u0DB3-\u0DBB\u0DBD\u0DC0-\u0DC6\u0E01-\u0E30\u0E32\u0E33\u0E40-\u0E46\u0E81\u0E82\u0E84\u0E87\u0E88\u0E8A\u0E8D\u0E94-\u0E97\u0E99-\u0E9F\u0EA1-\u0EA3\u0EA5\u0EA7\u0EAA\u0EAB\u0EAD-\u0EB0\u0EB2\u0EB3\u0EBD\u0EC0-\u0EC4\u0EC6\u0EDC-\u0EDF\u0F00\u0F40-\u0F47\u0F49-\u0F6C\u0F88-\u0F8C\u1000-\u102A\u103F\u1050-\u1055\u105A-\u105D\u1061\u1065\u1066\u106E-\u1070\u1075-\u1081\u108E\u10A0-\u10C5\u10C7\u10CD\u10D0-\u10FA\u10FC-\u1248\u124A-\u124D\u1250-\u1256\u1258\u125A-\u125D\u1260-\u1288\u128A-\u128D\u1290-\u12B0\u12B2-\u12B5\u12B8-\u12BE\u12C0\u12C2-\u12C5\u12C8-\u12D6\u12D8-\u1310\u1312-\u1315\u1318-\u135A\u1380-\u138F\u13A0-\u13F4\u1401-\u166C\u166F-\u167F\u1681-\u169A\u16A0-\u16EA\u1700-\u170C\u170E-\u1711\u1720-\u1731\u1740-\u1751\u1760-\u176C\u176E-\u1770\u1780-\u17B3\u17D7\u17DC\u1820-\u1877\u1880-\u18A8\u18AA\u18B0-\u18F5\u1900-\u191C\u1950-\u196D\u1970-\u1974\u1980-\u19AB\u19C1-\u19C7\u1A00-\u1A16\u1A20-\u1A54\u1AA7\u1B05-\u1B33\u1B45-\u1B4B\u1B83-\u1BA0\u1BAE\u1BAF\u1BBA-\u1BE5\u1C00-\u1C23\u1C4D-\u1C4F\u1C5A-\u1C7D\u1CE9-\u1CEC\u1CEE-\u1CF1\u1CF5\u1CF6\u1D00-\u1DBF\u1E00-\u1F15\u1F18-\u1F1D\u1F20-\u1F45\u1F48-\u1F4D\u1F50-\u1F57\u1F59\u1F5B\u1F5D\u1F5F-\u1F7D\u1F80-\u1FB4\u1FB6-\u1FBC\u1FBE\u1FC2-\u1FC4\u1FC6-\u1FCC\u1FD0-\u1FD3\u1FD6-\u1FDB\u1FE0-\u1FEC\u1FF2-\u1FF4\u1FF6-\u1FFC\u2071\u207F\u2090-\u209C\u2102\u2107\u210A-\u2113\u2115\u2119-\u211D\u2124\u2126\u2128\u212A-\u212D\u212F-\u2139\u213C-\u213F\u2145-\u2149\u214E\u2183\u2184\u2C00-\u2C2E\u2C30-\u2C5E\u2C60-\u2CE4\u2CEB-\u2CEE\u2CF2\u2CF3\u2D00-\u2D25\u2D27\u2D2D\u2D30-\u2D67\u2D6F\u2D80-\u2D96\u2DA0-\u2DA6\u2DA8-\u2DAE\u2DB0-\u2DB6\u2DB8-\u2DBE\u2DC0-\u2DC6\u2DC8-\u2DCE\u2DD0-\u2DD6\u2DD8-\u2DDE\u2E2F\u3005\u3006\u3031-\u3035\u303B\u303C\u3041-\u3096\u309D-\u309F\u30A1-\u30FA\u30FC-\u30FF\u3105-\u312D\u3131-\u318E\u31A0-\u31BA\u31F0-\u31FF\u3400-\u4DB5\u4E00-\u9FCC\uA000-\uA48C\uA4D0-\uA4FD\uA500-\uA60C\uA610-\uA61F\uA62A\uA62B\uA640-\uA66E\uA67F-\uA697\uA6A0-\uA6E5\uA717-\uA71F\uA722-\uA788\uA78B-\uA78E\uA790-\uA793\uA7A0-\uA7AA\uA7F8-\uA801\uA803-\uA805\uA807-\uA80A\uA80C-\uA822\uA840-\uA873\uA882-\uA8B3\uA8F2-\uA8F7\uA8FB\uA90A-\uA925\uA930-\uA946\uA960-\uA97C\uA984-\uA9B2\uA9CF\uAA00-\uAA28\uAA40-\uAA42\uAA44-\uAA4B\uAA60-\uAA76\uAA7A\uAA80-\uAAAF\uAAB1\uAAB5\uAAB6\uAAB9-\uAABD\uAAC0\uAAC2\uAADB-\uAADD\uAAE0-\uAAEA\uAAF2-\uAAF4\uAB01-\uAB06\uAB09-\uAB0E\uAB11-\uAB16\uAB20-\uAB26\uAB28-\uAB2E\uABC0-\uABE2\uAC00-\uD7A3\uD7B0-\uD7C6\uD7CB-\uD7FB\uF900-\uFA6D\uFA70-\uFAD9\uFB00-\uFB06\uFB13-\uFB17\uFB1D\uFB1F-\uFB28\uFB2A-\uFB36\uFB38-\uFB3C\uFB3E\uFB40\uFB41\uFB43\uFB44\uFB46-\uFBB1\uFBD3-\uFD3D\uFD50-\uFD8F\uFD92-\uFDC7\uFDF0-\uFDFB\uFE70-\uFE74\uFE76-\uFEFC\uFF21-\uFF3A\uFF41-\uFF5A\uFF66-\uFFBE\uFFC2-\uFFC7\uFFCA-\uFFCF\uFFD2-\uFFD7\uFFDA-\uFFDC\s]+$/, // Unicode letters
		phone = /^((\+|00)[\d]{3})?(?=.*\d)[\d -]{9,12}$/, // Phone number
		ip = /((^\s*((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))\s*$)|(^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$))/; // IP address

	button.prop('disabled', true).toggleClass('wait').width(width).height(height).text('.');

	// Conditional checks
	if ($('#forwardenabled').length) {
		$('#forwardaddress').toggleClass('req', $('#forwardenabled').is(':checked'));
	}
	if ($('#DKIMSignEnabled').length) {
		$('#DKIMPrivateKeyFile').toggleClass('req', $('#DKIMSignEnabled').is(':checked'));
		$('#DKIMSelector').toggleClass('req', $('#DKIMSignEnabled').is(':checked'));
	}
	if ($('#clamwinenabled').length) {
		$('#clamwinexecutable').toggleClass('req', $('#clamwinenabled').is(':checked'));
		$('#clamwindbfolder').toggleClass('req', $('#clamwinenabled').is(':checked'));
	}
	if ($('#ClamAVEnabled').length) {
		$('#ClamAVHost').toggleClass('req', $('#ClamAVEnabled').is(':checked'));
	}
	if ($('#customscannerenabled').length) {
		$('#customscannerexecutable').toggleClass('req', $('#customscannerenabled').is(':checked'));
	}
	if ($('#SpamAssassinEnabled').length) {
		$('#SpamAssassinHost').toggleClass('req', $('#SpamAssassinEnabled').is(':checked'));
	}
	if ($('#DistributionListMode').length) {
		$('#RequireSenderAddress').toggleClass('req', $('#DistributionListMode').find(":selected").val() == 2);
	}
	if ($('#mirroremailaddress').val()==='') {
		$('#mirroremailaddress').removeClass('req');
	}

	$('.req', this).each(function() {
		var input = $(this),
			value = input.val();

		if (input.hasClass('num')) pattern = numeric;
		else if (input.hasClass('email')) pattern = email;
		else if (input.hasClass("emails")) pattern = emails;
		else if (input.hasClass('date')) pattern = date;
		else if (input.hasClass('curr')) pattern = currency;
		else if (input.hasClass('alpha')) pattern = unicode;
		else if (input.hasClass('phone')) pattern = phone;
		else if (input.hasClass('ip')) pattern = ip;
		else pattern = any;

		if (!value || !pattern.test(value)) {
			input.addClass('error');
			error++;
		} else {
			input.removeClass('error');
		}
	});

	if (error===0) {
		if (typeof callback == 'function') {
			button.prop('disabled', false).toggleClass('wait').width(width).height(height).text(text);
			callback.call(this);
		} else {
			this.unbind('submit').submit();
		}
	} else {
		new jBox('Notice', {
			animation: 'flip',
			content: 'Some required fields are empty.',
			color: 'red'
		});
		button.prop('disabled', false).toggleClass('wait').width(width).height(height).text(text);
	}
};

// Test antivirus/antispam
function TestScanner(check) {
	switch (check) {
		case 'ClamAV':
			var result = $('#ClamAVTestResult');
			result.html('');
			var csrftoken = $('[name=csrftoken]').val();
			var host = $('#ClamAVHost').val();
			var port = $('#ClamAVPort').val();
			var url = 'index.php?page=background_ajax_virustest&TestType=ClamAV&csrftoken=' + csrftoken + '&Hostname=' + host + '&Port=' + port;
			CallAjax(url, result);
			break;
		case 'ClamWin':
			result = $('#ClamWinTestResult');
			result.html('');
			csrftoken = $('[name=csrftoken]').val();
			exe = $('#clamwinexecutable').val();
			db = $('#clamwindbfolder').val();
			url = 'index.php?page=background_ajax_virustest&TestType=ClamWin&csrftoken=' + csrftoken + '&Executable=' + exe + '&DatabaseFolder=' + db;
			CallAjax(url, result);
			break;
		case 'External':
			result = $('#ExternalTestResult');
			result.html('');
			csrftoken = $('[name=csrftoken]').val();
			exe = $('#customscannerexecutable').val();
			val = $('#customscannerreturnvalue').val();
			url = 'index.php?page=background_ajax_virustest&TestType=External&csrftoken=' + csrftoken + '&Executable=' + encodeURIComponent(exe) + '&ReturnValue=' + val;
			CallAjax(url, result);
			break;
		case 'SpamAssassin':
			result = $('#SpamAssassinTestResult');
			result.html('');
			csrftoken = $('[name=csrftoken]').val();
			host = $('#SpamAssassinHost').val();
			port = $('#SpamAssassinPort').val();
			url = 'index.php?page=background_ajax_spamassassintest&csrftoken=' + csrftoken + '&Hostname=' + host + '&Port=' + port;
			CallAjax(url, result);
			break;
		default:
			alert(check);
			break;
	}
	return false;
}

function CallAjax(url, result) {
	$.ajax({
		type: 'post',
		url: url,
		cache: false,
		beforeSend: function() {
			result.html('Please wait...');
		},
		success: function(data) {
			if (data == "1") {
				data = 'Test succeeded';
			} else {
				data = 'Test failed';
			}
			result.html('<div class="warning bottom">' + data + '</div>');
		}
	});
}

// Log parsing
function parseLog(data) {
	if (typeof data == 'string') return data;
	let out = '';
	$.each(data, function(k, v) {
		out += parseLogGroup(v);
	});
	return out;
}

function parseLogGroup(data) {
	if (data[0][0] == 'RAW') return parseLogRaw(data);
	let out = '<div><span>' + data[0][0];
	if (data[0][0] == 'SMTPD' || data[0][0] == 'SMTPC' || data[0][0] == 'POP3D' || data[0][0] == 'POP3C' || data[0][0] == 'IMAPD')
		out += ' &nbsp;&ndash;&nbsp; ' + data[0][1] + ' &nbsp;&ndash;&nbsp; ' + data[0][2] + ' <sup><a href="https://href.li/?https://ipinfo.io/' + data[0][2] + '" target="_blank">?</a></sup></span><ul id="log' + data[0][1] + '">';
	else
		out += '</span><ul>';
	let rows = '';
	$.each(data[1], function(k, v) {
		let css = '';
		if (v[1].indexOf('RECEIVED:') > -1) css = 'recieved';
		else if (v[1].indexOf('SENT:') > -1) css = 'sent';
		rows += '<li class="' + css + '"><div>' + v[0] + '</div><div>' + v[1] + '</div></li>';
	});
	if (document.getElementById('log' + data[0][1]) == null)
		out += rows + '</ul></div>';
	else {
		$('#log' + data[0][1]).append(rows);
		out = '';
	}
	return out;
}

function parseLogRaw(data) {
	let out = '';
	$.each(data[1], function(k, v) {
		out += v + '<br>';
	});
	return out;
}

// Blacklist check (callback)
function blacklistCheck() {
	var form = $(this),
		button = form.find('button'),
		width = button.width(),
		text = button.text();

	if (!$('#results').length) button.parent().after('<div id="results"></div>');
	var result = $('#results');

	$.ajax({
		type: 'get',
		url: './custom_blacklists.php',
		data: form.serialize(),
		cache: false,
		timeout: 15000,
		dataType: 'text',
		beforeSend: function() {
			result.text('Please wait... this might take some time');
			button.prop('disabled', true).addClass('wait').width(width).text('.');
		},
		success: function(data) {
			result.html(data);
			button.prop('disabled', false).removeClass('wait').width(width).text(text);
		},
		error: function(data) {
			result.text('Error connecting to servers');
			button.prop('disabled', false).removeClass('wait').width(width).text(text);
		}
	});
	return true;
};