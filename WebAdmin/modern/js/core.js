jQuery(document).ready(function(){
	//timeago
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

	//grafs refresh
	if($('#processed, #sessions').length){
		// Chartist
		var processed = new Chartist.Pie('#processed', {
				labels : ['Legit', 'Virus', 'Spam'],
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
		// Imperavi Grafs
		//var processed = new Grafs.Donut('#processed', {data: [1, 0, 0]});
		//var sessions = new Grafs.Donut('#sessions', {data: [1, 0, 0]});
		Refresh();
		function Refresh(){
			$.getJSON("modern/json.php?q=1",
			function(json) {
				var data = {
					series: json,
				};
				processed.update(data);
				$('#legit').text(Number(json[0].toFixed(0)).toLocaleString());
				$('#virus').text(Number(json[1].toFixed(0)).toLocaleString());
				$('#spam').text(Number(json[2].toFixed(0)).toLocaleString());
			});
			$.getJSON("modern/json.php?q=2",
			function(json) {
				var data = {
					series: json,
				};
				sessions.update(data);
				$('#smtp').text(Number(json[0].toFixed(0)).toLocaleString());
				$('#pop3').text(Number(json[1].toFixed(0)).toLocaleString());
				$('#imap').text(Number(json[2].toFixed(0)).toLocaleString());
			});

		// Imperavi Grafs
		//	$.getJSON("modern/json.php?q=1",
		//	function(json) {
		//		var data = {
		//			labels: ["Legit", "Virus", "Spam"],
		//			data: json,
		//			colors: ['#96d759', '#ff8c42', '#ff4053']
		//		};
		//		var options = {
		//			circleWidth: 20,
		//			tooltip: '<b>[[ label ]]</b><br />[[ value ]] / [[ percentage ]]%',
		//			summary: '<span class="grafs-summary-desc">Total messages</span> [[ total ]]'
		//		};
		//		processed.update(data, options);
		//		$('#legit').text(Number(json[0].toFixed(0)).toLocaleString());
		//		$('#virus').text(Number(json[1].toFixed(0)).toLocaleString());
		//		$('#spam').text(Number(json[2].toFixed(0)).toLocaleString());
		//	});
		//	$.getJSON("modern/json.php?q=2",
		//	function(json) {
		//		var data = {
		//			labels: ["SMTP", "POP3", "IMAP"],
		//			data: json
		//		};
		//		var options = {
		//			circleWidth: 20,
		//			tooltip: '<b>[[ label ]]</b><br />[[ value ]] / [[ percentage ]]%',
		//		};
		//		sessions.update(data, options);
		//		$('#smtp').text(Number(json[0].toFixed(0)).toLocaleString());
		//		$('#pop3').text(Number(json[1].toFixed(0)).toLocaleString());
		//		$('#imap').text(Number(json[2].toFixed(0)).toLocaleString());
		//	});
		//	$("#queue").load("modern/json.php?q=3");
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

	//cache DOM elements
	var mainContent = $('.cd-main-content'),
		header = $('.cd-main-header'),
		sidebar = $('.cd-side-nav'),
		sidebarTrigger = $('.cd-nav-trigger'),
		topNavigation = $('.cd-top-nav'),
		searchForm = $('.cd-search'),
		accountInfo = $('.account');

	//on resize, move search and top nav position according to window width
	var resizing = false;
	moveNavigation();
	$(window).on('resize', function(){
		if( !resizing ) {
			(!window.requestAnimationFrame) ? setTimeout(moveNavigation, 300) : window.requestAnimationFrame(moveNavigation);
			resizing = true;
		}
	});

	//on window scrolling - fix sidebar nav
	var scrolling = false;
	checkScrollbarPosition();
	$(window).on('scroll', function(){
		if( !scrolling ) {
			(!window.requestAnimationFrame) ? setTimeout(checkScrollbarPosition, 300) : window.requestAnimationFrame(checkScrollbarPosition);
			scrolling = true;
		}
	});

	//mobile only - open sidebar when user clicks the hamburger menu
	sidebarTrigger.on('click', function(event){
		event.preventDefault();
		$([sidebar, sidebarTrigger]).toggleClass('nav-is-visible');
	});

	//click on item and show submenu
	$('.has-children > a').on('click', function(event){
		var mq = checkMQ(),
			selectedItem = $(this);
		if( mq == 'mobile' || mq == 'tablet' ) {
			event.preventDefault();
			if( selectedItem.parent('li').hasClass('selected')) {
				selectedItem.parent('li').removeClass('selected');
			} else {
				sidebar.find('.has-children.selected').removeClass('selected');
				accountInfo.removeClass('selected');
				selectedItem.parent('li').addClass('selected');
			}
		}
	});

	//click on account and show submenu - desktop version only
	accountInfo.children('a').on('click', function(event){
		var mq = checkMQ(),
			selectedItem = $(this);
		if( mq == 'desktop') {
			event.preventDefault();
			accountInfo.toggleClass('selected');
			sidebar.find('.has-children.selected').removeClass('selected');
		}
	});

	$(document).on('click', function(event){
		if( !$(event.target).is('.has-children a') ) {
			sidebar.find('.has-children.selected').removeClass('selected');
			accountInfo.removeClass('selected');
		}
	});

	//on desktop - differentiate between a user trying to hover over a dropdown item vs trying to navigate into a submenu's contents
	sidebar.children('ul').menuAim({
		activate: function(row) {
			$(row).addClass('hover');
		},
		deactivate: function(row) {
			$(row).removeClass('hover');
		},
		exitMenu: function() {
			sidebar.find('.hover').removeClass('hover');
			return true;
		},
		submenuSelector: ".has-children",
	});

	function checkMQ() {
		//check if mobile or desktop device
		return window.getComputedStyle(document.querySelector('.cd-main-content'), '::before').getPropertyValue('content').replace(/'/g, "").replace(/"/g, "");
	}

	function moveNavigation(){
		var mq = checkMQ();
		if ( mq == 'mobile' && topNavigation.parents('.cd-side-nav').length == 0 ) {
			detachElements();
			topNavigation.appendTo(sidebar);
			searchForm.removeClass('is-hidden').prependTo(sidebar);
		} else if ( ( mq == 'tablet' || mq == 'desktop') &&  topNavigation.parents('.cd-side-nav').length > 0 ) {
			detachElements();
			searchForm.insertAfter(header.find('.cd-logo'));
			topNavigation.appendTo(header.find('.cd-nav'));
		}
		checkSelected(mq);
		resizing = false;
	}

	function detachElements() {
		topNavigation.detach();
		searchForm.detach();
	}

	function checkSelected(mq) {
		//on desktop, remove selected class from items selected on mobile/tablet version
		if( mq == 'desktop' ) $('.has-children.selected').removeClass('selected');
	}

	function checkScrollbarPosition() {
		var mq = checkMQ();

		if( mq != 'mobile' ) {
			var sidebarHeight = sidebar.outerHeight(),
				windowHeight = $(window).height(),
				mainContentHeight = mainContent.outerHeight(),
				scrollTop = $(window).scrollTop();

			( ( scrollTop + windowHeight > sidebarHeight ) && ( mainContentHeight - sidebarHeight != 0 ) ) ? sidebar.addClass('is-fixed').css('bottom', 0) : sidebar.removeClass('is-fixed').attr('style', '');
		}
		scrolling = false;
	}
});
//confirm delete - OLD
function ConfirmDelete(name, url) {
	confirm_delete = "<?php echo GetConfirmDelete();?>"
	confirm_delete = confirm_delete.replace("%s", name);
	if (confirm(confirm_delete))
		document.location = url;
}
//confirm delete
function Confirm(question, answer, action) {
	$.facebox('<div style="margin:18px; text-align:center;"><p>' + question + '</p><input type="button" value="' + answer + '" id="yes" /> &nbsp; <input type="button" value="No" onclick="$.facebox.close();" /></div>');
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
		case "ClamAV":
			var result = $('#ClamAVTestResult');
			result.html('');
			var host = $('#ClamAVHost').val();
			var port = $('#ClamAVPort').val();
			var url = "index.php?page=background_ajax_virustest&TestType=ClamAV&Hostname=" + host + "&Port=" + port;
			CallAjax(url,result);
			break;
		case "ClamWin":
			var result = $('#ClamWinTestResult');
			result.html('');
			var exe = $('#clamwinexecutable').val();
			var db = $('#clamwindbfolder').val();
			var url = "index.php?page=background_ajax_virustest&TestType=ClamWin&Executable=" + exe + "&DatabaseFolder=" + db;
			CallAjax(url,result);
			break;
		case "External":
			var result = $('#ExternalTestResult');
			result.html('');
			var exe = $('#customscannerexecutable').val();
			var val = $('#customscannerreturnvalue').val();
			var url = "index.php?page=background_ajax_virustest&TestType=External&Executable=" + exe + "&ReturnValue=" + val;
			CallAjax(url,result);
			break;
		case "SpamAssassin":
			var result = $('#SpamAssassinTestResult');
			result.html('');
			var host = $('#SpamAssassinHost').val();
			var port = $('#SpamAssassinPort').val();
			var url = "index.php?page=background_ajax_spamassassintest&Hostname=" + host + "&Port=" + port;
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