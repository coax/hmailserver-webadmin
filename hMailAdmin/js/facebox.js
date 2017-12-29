//github.com/defunkt/facebox 1.3 *heavily modified*
(function($) {
	$.facebox = function(data, klass) {
		$.facebox.loading(data.settings || []);
		if (data.ajax) fillFaceboxFromAjax(data.ajax, klass);
		else if (data.image) fillFaceboxFromImage(data.image, klass);
		else if (data.div) fillFaceboxFromHref(data.div, klass);
		else if ($.isFunction(data)) data.call($);
		else $.facebox.reveal(data, klass);
	};
	$.extend($.facebox, {
		settings: {
			opacity: 0.5,
			overlay: true,
			loadingImage: 'css/loading.gif',
			closeImage: 'css/close.png',
			imageTypes: ['png', 'jpg', 'jpeg', 'gif'],
			faceboxHtml: '<div id="facebox"><div class="popup"><div class="content"></div><a href="#" class="close"></a></div></div>'
		},
		loading: function() {
			init();
			if ($('#facebox .loading').length == 1) return true;
			showOverlay();
			$('#facebox .content').html('<div class="loading"><img src="' + $.facebox.settings.loadingImage + '"/></div>');
			$('#facebox').show().css({
				top: $(window).scrollTop() + ($(window).height() / 10),
				left: $(window).width() / 2 - ($('#facebox .popup').outerWidth() / 2)
			});
			$(document).bind('keydown.facebox', function(e) {
				if (e.keyCode == 27) $.facebox.close();
				return true;
			});
			$(document).trigger('loading.facebox');
		},
		reveal: function(data, klass) {
			$(document).trigger('beforeReveal.facebox');
			if (klass) $('#facebox .content').addClass(klass);
			$('#facebox .content').empty().append(data).hide();
			$('#facebox .loading').remove();
			$('#facebox .popup').children().fadeIn(250);
			$(document).trigger('reveal.facebox').trigger('afterReveal.facebox');
			// Added (re)positioning
			reposition()
			$(window).resize(function(){
				reposition()
			});
			var originalPosition = $('#facebox').offset().top, startPosition = $(window).scrollTop();
			$(window).on('scroll', function() {
				var endPosition = $(window).scrollTop();
				if ($('#facebox').height() < $(window).height()) {
					$('#facebox').css({
						top: originalPosition + (endPosition - startPosition)
					});
				}
			});
		},
		close: function() {
			$(document).trigger('close.facebox');
			return false;
		}
	});
	// Reposition modal
	function reposition() {
		$('#facebox').css({
			left: $(window).width() / 2 - ($('#facebox .popup').outerWidth() / 2)
		})
	}
	$.fn.facebox = function(settings) {
		if ($(this).length === 0) return;
		init(settings);
		function clickHandler() {
			$.facebox.loading(true);
			var klass = this.rel.match(/facebox\[?\.(\w+)\]?/);
			if (klass) klass = klass[1];
			fillFaceboxFromHref(this.href, klass);
			return false;
		}
		return this.bind('click.facebox', clickHandler);
	};
	function init(settings) {
		if ($.facebox.settings.inited) return true;
		else $.facebox.settings.inited = true;
		$(document).trigger('init.facebox');
		makeCompatible();
		var imageTypes = $.facebox.settings.imageTypes.join('|');
		$.facebox.settings.imageTypesRegexp = new RegExp('\\.(' + imageTypes + ')(\\?.*)?$', 'i');
		if (settings) $.extend($.facebox.settings, settings);
		$('body').append($.facebox.settings.faceboxHtml);
		var preload = [new Image(), new Image()];
		preload[0].src = $.facebox.settings.closeImage;
		preload[1].src = $.facebox.settings.loadingImage;
		$('#facebox').find('.b:first, .bl').each(function() {
			preload.push(new Image());
			preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1');
		});
		$('#facebox .close').click($.facebox.close).append('<img src="' + $.facebox.settings.closeImage + '">');
	}
	function makeCompatible() {
		var $s = $.facebox.settings;
		$s.loadingImage = $s.loading_image || $s.loadingImage;
		$s.closeImage = $s.close_image || $s.closeImage;
		$s.imageTypes = $s.image_types || $s.imageTypes;
		$s.faceboxHtml = $s.facebox_html || $s.faceboxHtml;
	}
	function fillFaceboxFromHref(href, klass) {
		// Div
		if (href.match(/#/)) {
			var url = window.location.href.split('#')[0];
			var target = href.replace(url, '');
			if (target == '#') return;
			$.facebox.reveal($(target).show().replaceWith("<div id='facebox_moved'></div>"), klass);
		// Image
		} else if (href.match($.facebox.settings.imageTypesRegexp)) {
			fillFaceboxFromImage(href, klass);
		// Ajax
		} else {
			fillFaceboxFromAjax(href, klass);
		}
	}
	function fillFaceboxFromImage(href, klass) {
		var image = new Image();
		image.onload = function() {
			$.facebox.reveal('<div class="image"><img src="' + image.src + '"></div>', klass);
		};
		image.src = href;
	}
	function fillFaceboxFromAjax(href, klass) {
		$.facebox.jqxhr = $.get(href, function(data) {
			$.facebox.reveal(data, klass);
		});
	}
	function skipOverlay() {
		return $.facebox.settings.overlay === false || $.facebox.settings.opacity === null;
	}
	function showOverlay() {
		if (skipOverlay()) return;
		if ($('#facebox_overlay').length === 0) $("body").append('<div id="facebox_overlay" class="facebox_hide"></div>');
		$('#facebox_overlay').hide().addClass("facebox_overlayBG").css('opacity', $.facebox.settings.opacity).click(function() {
			//$(document).trigger('close.facebox')
		}).fadeIn(200);
		return false;
	}
	function hideOverlay() {
		if (skipOverlay()) return;
		$('#facebox_overlay').fadeOut(200, function() {
			$("#facebox_overlay").removeClass("facebox_overlayBG");
			$("#facebox_overlay").addClass("facebox_hide");
			$("#facebox_overlay").remove();
		});
		return false;
	}
	$(document).bind('close.facebox', function() {
		if ($.facebox.jqxhr) {
			$.facebox.jqxhr.abort();
			$.facebox.jqxhr = null;
		}
		$(document).unbind('keydown.facebox');
		$('#facebox').fadeOut(function() {
			if ($('#facebox_moved').length === 0) $('#facebox .content').removeClass().addClass('content');
			else $('#facebox_moved').replaceWith($('#facebox .content').children().hide());
			$('#facebox .loading').remove();
			$('#facebox .content').empty();
			$(document).trigger('afterClose.facebox');
		});
		hideOverlay();
	});
})(jQuery);