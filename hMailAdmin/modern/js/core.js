jQuery(document).ready(function(){
	//timeago
	$.timeago.settings.strings.prefixAgo = '';
	$.timeago.settings.strings.suffixAgo = '';
	$('time.timeago').timeago();

	//focus login
	if($('[name=username]').length){
		$('[name=username]').focus();
	}

	//tablesorter
	if($('.tablesort').length){
		$('.tablesort').tablesort();
	}

	//bind facebox modal
	$('a[rel*=facebox]').facebox();

	//show datepicker
	if($('[data-toggle="datepicker"]').length){
		$('[data-toggle="datepicker"]').datepicker({format: 'yyyy-mm-dd', autoHide: true, weekStart: hmail_config.weekStart, language: 'en-GB'});
	}

	if($('#log-parser').length){
		$('#log-parser :submit').click(function() {
			var form = $('#log-parser'),
			result = $('#results'),
			params = form.serialize(),
			button = $(':submit', form);
			$.ajax({
				type: 'post',
				url: './modern/logview.php',
				data: params,
				cache: false,
				timeout: 50000,
				dataType: 'json',
				beforeSend: function() {
					result.html('Please wait... this might take some time.');
					button.prop('disabled', true).addClass('wait');
				},
				success: function(data) {
					result.html(parseLog(data));
					button.prop('disabled', false).removeClass('wait');
				},
				error: function(data) {
					result.html('Error loading log file!');
					button.prop('disabled', false).removeClass('wait');
				}
			});
			return false;
		});
		$('#log-parser [type=button]').click(function() {
			$('#results').html('Click on "Parse log" button');
		});
	}

	//grafs refresh
	if($('#processed, #sessions').length){
		// Chartist
		var activity_array = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
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
				labels : ['Processed', 'Virus', 'Spam'],
				series: [0, 0, 0]
			}, {
				donut: true,
				donutWidth: 22,
				chartPadding: 0,
				showLabel: true,
				ignoreEmptyValues: false,
				plugins: [
					Chartist.plugins.tooltip({appendToBody:true})
				],
				labelInterpolationFnc: function(value) {
					var total = processed.data.series.reduce(function(prev, series) {
						return prev + series;
					}, 0);
					return total
				}
		});
		processed.on('draw', function(ctx) {
			if (ctx.type === 'label') {
				if(ctx.index === 0) {
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
				labels : ['SMTP', 'POP3', 'IMAP'],
				series: [0, 0, 0]
			}, {
				donut: true,
				donutWidth: 22,
				chartPadding: 0,
				showLabel: true,
				ignoreEmptyValues: false,
				plugins: [
					Chartist.plugins.tooltip({appendToBody:true})
				],
				labelInterpolationFnc: function(value) {
					var total = sessions.data.series.reduce(function(prev, series) {
						return prev + series;
					}, 0);
					return total
				}
		});
		sessions.on('draw', function(ctx) {
			if (ctx.type === 'label') {
				if(ctx.index === 0) {
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
		function Refresh(){
			$.getJSON('modern/json.php',
			function(json) {
				//Processed messages
				var data = {series: json[0]};
				processed.update(data);
				$('#legit').text(Number(json[0][0].toFixed(0)).toLocaleString());
				$('#virus').text(Number(json[0][1].toFixed(0)).toLocaleString());
				$('#spam').text(Number(json[0][2].toFixed(0)).toLocaleString());
				//Open sessions
				data = {series: json[1]};
				sessions.update(data);
				$('#smtp').text(Number(json[1][0].toFixed(0)).toLocaleString());
				$('#pop3').text(Number(json[1][1].toFixed(0)).toLocaleString());
				$('#imap').text(Number(json[1][2].toFixed(0)).toLocaleString());
				//Delivery queue
				var queue = '';
				if (json[2] !== 0) {
					$.each(json[2], function(key, data){
						queue += '<tr><td><a href="#" onclick="$.facebox({ajax:\'modern/view.php?q=' + data[5] + '\'}); return false;">' + data[0] + '</a></td><td>' + data[1] + '</td><td>' + data[2] + '</td><td>' + data[3] + '</td><td>' + data[4] + '</td><td>' + data[6] + '</td></tr>';
					});
				}
				$('#queue').html(queue);
				//Server
				activity_array.push(parseInt(json[3]));
				activity_array.splice(0, 1);
				data = {series: [activity_array]};
				activity.update(data);
				//Queue count
				$('#count').text(json[4]);
			});
		}
		setInterval(Refresh, 5000);
	};

	//show-hide
	if($('h3 a').length){
		$('h3 a').on('click', function() {
			$(this).parent().next().slideToggle(150);
			return false;
		})
	}

	//responsive navigation
	if($('#sidebar').length){
		//cache DOM elements
		var mainContent = $('main'), header = $('header'), sidebar = $('#sidebar'), hamburger = $('#mobile'), topNavigation = $('#top');

		//check if mobile
		function mobileDevice() {
			return window.getComputedStyle(document.querySelector('main'), '::before').content.replace(/'/g, "").replace(/"/g, "");
		}

		//move top navigation
		var resizing = false;
		moveNavigation();
		$(window).on('resize', function(){
			if( !resizing ) {
				(!window.requestAnimationFrame) ? setTimeout(moveNavigation, 300) : window.requestAnimationFrame(moveNavigation);
				resizing = true;
			}
		});

		function moveNavigation(){
			var m = mobileDevice();
			if (m == 'mobile' && topNavigation.parents('#sidebar').length == 0) {
				detachElements();
				topNavigation.appendTo(sidebar);
			} else if ((m == 'tablet' || m == 'desktop') && topNavigation.parents('#sidebar').length > 0) {
				detachElements();
				topNavigation.appendTo(header.find('nav'));
			}
			checkSelected(m);
			resizing = false;
		}

		function detachElements() {
			topNavigation.detach();
		}

		//remove added classes on desktop
		function checkSelected(m) {
			if(m == 'desktop') $('.has-children.selected').removeClass('selected');
		}

		//mobile menu
		hamburger.on('click', function(e){
			e.preventDefault();
			$([sidebar, hamburger]).toggleClass('is-visible');
		});

		//tablet menu
		$('.has-children > a').on('click', function(e){
			var m = mobileDevice(), selectedItem = $(this);

			if ((m=='desktop') && (selectedItem.parent().parent().attr('id')!=='top')) {
				//nothing
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

		//scroll fixed position
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

	//abandoned idea, onclick loads domain
	//$('.domain').on('click', function(){
	//	var domain = $(this).attr('rel');
	//	$(this).parent().addClass('has-children');
	//	$('<ul>').load('./modern/domain.php?domainid=' + domain).insertAfter($(this));
	//});
});

//confirm delete
function Confirm(question, yes, no, action) {
	$.facebox('<div style="margin:36px 18px; text-align:center;"><p>' + question + '</p><input type="button" value="' + yes + '" id="yes"> &nbsp; <input type="button" value="' + no + '" onclick="$.facebox.close();"></div>');
	$('body').unbind('keypress').keypress(function(e) {
		if (e.which==13) {
			$('#yes').click();
			return false;
		}
	});
	$('#yes').one('click', function() {
		$('body').unbind('keypress');
			location.href = action;
		}
	);
	return false;
}

//replace alert
function alert(message) {
	$.facebox('<div style="margin:18px; text-align:center;"><p>' + message + '</p><p><a href="#" onclick="$.facebox.close();" class="button">OK</a></p></div>');
};

//validation
$.fn.validation = function() {
	var form = $(this);
	var error = 0;
	var button = form.find('[type=submit]');
	var action = form.attr('action');
	button.prop('disabled', true).toggleClass('wait');

	$('.req', this).each(function () {
		var input = $(this).val();
		var pattern = /^(.|\n)+$/;

		//number - deprecated due int plugin
		if ($(this).hasClass('number')) pattern = /^[\d]+$/;
		//email
		if ($(this).hasClass('email')) pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		//date
		if ($(this).hasClass('date')) pattern = /^(\d{2})\.(\d{2})\.(\d{4})(\, (\d{1,2}):(\d{1,2}))?$/;
		//currency
		if ($(this).hasClass('curr')) pattern = /^-?(\d{1,3}.(\d{3}.)*\d{3}|\d+)(,\d{1,2})?$/;
		//ip address
		if ($(this).hasClass('ip')) pattern = /((^\s*((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))\s*$)|(^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$))/;

		if (!input || !pattern.test(input)) {
			$(this).addClass('error');
			error++;
		}
		else {
			$(this).removeClass('error');
		}
	});

	if (error === 0) {
		form.unbind('submit');
		form[0].submit();
	} else {
		button.prop('disabled', false).toggleClass('wait');
		return false;
	}
};

//test antivirus/antispam
function TestScanner(check) {
	switch (check) {
		case 'ClamAV':
			var result = $('#ClamAVTestResult');
			result.html('');
			var csrftoken = $('[name=csrftoken]').val();
			var host = $('#ClamAVHost').val();
			var port = $('#ClamAVPort').val();
			var url = 'index.php?page=background_ajax_virustest&TestType=ClamAV&csrftoken=' + csrftoken + '&Hostname=' + host + '&Port=' + port;
			CallAjax(url,result);
			break;
		case 'ClamWin':
			var result = $('#ClamWinTestResult');
			result.html('');
			var csrftoken = $('[name=csrftoken]').val();
			var exe = $('#clamwinexecutable').val();
			var db = $('#clamwindbfolder').val();
			var url = 'index.php?page=background_ajax_virustest&TestType=ClamWin&csrftoken=' + csrftoken + '&Executable=' + exe + '&DatabaseFolder=' + db;
			CallAjax(url,result);
			break;
		case 'External':
			var result = $('#ExternalTestResult');
			result.html('');
			var csrftoken = $('[name=csrftoken]').val();
			var exe = $('#customscannerexecutable').val();
			var val = $('#customscannerreturnvalue').val();
			var url = 'index.php?page=background_ajax_virustest&TestType=External&csrftoken=' + csrftoken + '&Executable=' + exe + '&ReturnValue=' + val;
			CallAjax(url,result);
			break;
		case 'SpamAssassin':
			var result = $('#SpamAssassinTestResult');
			result.html('');
			var csrftoken = $('[name=csrftoken]').val();
			var host = $('#SpamAssassinHost').val();
			var port = $('#SpamAssassinPort').val();
			var url = 'index.php?page=background_ajax_spamassassintest&csrftoken=' + csrftoken + '&Hostname=' + host + '&Port=' + port;
			CallAjax(url,result);
			break;
		default:
			alert(check);
			break;
	}
	return false;
}

function CallAjax(url,result) {
	$.ajax({
		type: 'post',
		url: url,
		cache: false,
		beforeSend: function() {
			result.html('<p>Please wait...</p>');
		},
		success: function(data) {
			if (data=="1") { data = 'Test succeeded' } else { data = 'Test failed' }
			result.html('<div class="warning">' + data + '</div>');
		}
	});
}

//log parsing magic
function parseLog(data) {
	if(typeof data == 'string') return data;
	var out = '';
	$.each(data,function(k,v){
		out += parseLogGroup(v);
	});
	return out;
}

function parseLogGroup(data) {
	if(data[0][0]=='RAW') return parseLogRaw(data);
	var out = '<div><span>' + data[0][0];
	if(data[0][0]=='SMTPD'||data[0][0]=='SMTPC'||data[0][0]=='POP3D'||data[0][0]=='POP3C'||data[0][0]=='IMAPD')
		out += ' &nbsp;&ndash;&nbsp; ' + data[0][1] + ' &nbsp;&ndash;&nbsp; ' + data[0][2] + ' <sup><a href="https://href.li/?http://ip-api.com/line/' + data[0][2] + '" target="_blank">?</a></sup>';
	out += '</span><ul>';
	$.each(data[1],function(k,v){
		var css = '';
		if (v[1].indexOf('RECEIVED:') > -1) css = 'recieved';
		else if (v[1].indexOf('SENT:') > -1) css = 'sent';
		out += '<li class="' + css + '"><div>' + v[0] + '</div><div>' + v[1] + '</div></li>';
	});
	out += '</ul></div>';
	return out;
}

function parseLogRaw(data) {
	var out = '';
	$.each(data[1],function(k,v){
		out += v + '<br>';
	});
	return out;
}