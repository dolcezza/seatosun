/*
 * jQuery ThumbFx Overlayer Function
 * Usage : jQuery('[data-overlayer]').overlayer(options);
*/
(function (d) {
	var e = function () {};
	d.extend(e.prototype, {
		name: "overlayer",
		options: {
			effect: "fade",
			duration: 300,
			easing: "swing",
			cls: "overlayer",
			invert: false,
			overlaySelector: ".overlay",
			overlayDefault: "overlay-default"
		},
		initialize: function (c, a) {
			a = d.extend({}, this.options, a);
			c.attr("data-overlayer") && d.each(c.attr("data-overlayer").split(";"), function (b, c) {
				var d = c.match(/\s*([A-Z_]*?)\s*:\s*(.+)\s*/i);
				d && (a[d[1]] = d[2])
			});
			a.duration = Math.floor(a.duration);
			var b = c.children(a.overlaySelector).first();
			b.length || (b = d("<div>").addClass(a.overlayDefault).appendTo(c));
			b.css({
				position: "absolute",

				visibility: "hidden",
				display: "block"
			}).wrapInner("<div>");
			c.css({
				position: "relative",
				overflow: "hidden"
			}).addClass(a.cls);
			
			if(a.invert){
				d(window).load(function(){
					b.stop().css({
						visibility: "visible",
						width: c.width(),
						height: a.effect == "top" || a.effect == "bottom" ? "auto" : c.height()
					});
					switch(a.effect) {
					case "right":
						b.css({
							right: b.width() * -1,
							top: 0,
							bottom: 0
						}).animate({
							right: 0
						}, a.duration, a.easing);
						break;
					case "left":
						b.css({
							left: b.width() * -1,
							top: 0,
							bottom: 0
						}).animate({
							left: 0
						}, a.duration, a.easing);
						break;
					case "top":
						b.css({
							left: 0,
							top: b.height() * -1
						}).animate({
							top: 0
						}, a.duration, a.easing);
						break;
					case "bottom":
						b.css({
							left: 0,
							bottom: b.height() * -1
						}).animate({
							bottom: 0
						}, a.duration, a.easing);
						break;
					default:
						b.show().css({
							opacity: 0,
							top: 0,
							left: 0
						}).animate({
							opacity: 1
						}, a.duration, a.easing, function () {
							if(d.browser.msie) b.get(0).filter = "", b.attr("style", String(b.attr("style")).replace(/alpha\(opacity=([\d.]+)\)/i, ""))
						})
					}
				});
				c.bind({
					mouseleave: function () {
						b.stop().css({
							visibility: "visible",
							width: c.width(),
							height: a.effect == "top" || a.effect == "bottom" ? "auto" : c.height()
						});
						switch(a.effect) {
						case "right":
							b.css({
								right: b.width() * -1,
								top: 0,
								bottom: 0
							}).animate({
								right: 0
							}, a.duration, a.easing);
							break;
						case "left":
							b.css({
								left: b.width() * -1,
								top: 0,
								bottom: 0
							}).animate({
								left: 0
							}, a.duration, a.easing);
							break;
						case "top":
							b.css({
								left: 0,
								top: b.height() * -1
							}).animate({
								top: 0
							}, a.duration, a.easing);
							break;
						case "bottom":
							b.css({
								left: 0,
								bottom: b.height() * -1
							}).animate({
								bottom: 0
							}, a.duration, a.easing);
							break;
						default:
							b.show().css({
								opacity: 0,
								top: 0,
								left: 0
							}).animate({
								opacity: 1
							}, a.duration, a.easing, function () {
								if(d.browser.msie) b.get(0).filter = "", b.attr("style", String(b.attr("style")).replace(/alpha\(opacity=([\d.]+)\)/i, ""))
							})
						}
					},
					mouseenter: function () {
						b.stop();
						switch(a.effect) {
						case "right":
							b.animate({
								right: b.width() * -1
							}, a.duration, a.easing);
							break;
						case "left":
							b.animate({
								left: b.width() * -1
							}, a.duration, a.easing);
							break;
						case "top":
							b.animate({
								top: b.height() * -1
							}, a.duration, a.easing);
							break;
						case "bottom":
							b.animate({
								bottom: b.height() * -1
							}, a.duration, a.easing);
							break;
						default:
							b.animate({
								opacity: 0
							}, a.duration, a.easing, function () {
								b.hide()
							})
						}
					}
				});
			}
			else {
				c.bind({
					mouseenter: function () {
						b.stop().css({
							visibility: "visible",
							width: c.width(),
							height: a.effect == "top" || a.effect == "bottom" ? "auto" : c.height()
						});
						switch(a.effect) {
						case "right":
							b.css({
								right: b.width() * -1,
								top: 0,
								bottom: 0
							}).animate({
								right: 0
							}, a.duration, a.easing);
							break;
						case "left":
							b.css({
								left: b.width() * -1,
								top: 0,
								bottom: 0
							}).animate({
								left: 0
							}, a.duration, a.easing);
							break;
						case "top":
							b.css({
								left: 0,
								top: b.height() * -1
							}).animate({
								top: 0
							}, a.duration, a.easing);
							break;
						case "bottom":
							b.css({
								left: 0,
								bottom: b.height() * -1
							}).animate({
								bottom: 0
							}, a.duration, a.easing);
							break;
						default:
							b.show().css({
								opacity: 0,
								top: 0,
								left: 0
							}).animate({
								opacity: 1
							}, a.duration, a.easing, function () {
								if(d.browser.msie) b.get(0).filter = "", b.attr("style", String(b.attr("style")).replace(/alpha\(opacity=([\d.]+)\)/i, ""))
							})
						}
					},
					mouseleave: function () {
						b.stop();
						switch(a.effect) {
						case "right":
							b.animate({
								right: b.width() * -1
							}, a.duration, a.easing);
							break;
						case "left":
							b.animate({
								left: b.width() * -1
							}, a.duration, a.easing);
							break;
						case "top":
							b.animate({
								top: b.height() * -1
							}, a.duration, a.easing);
							break;
						case "bottom":
							b.animate({
								bottom: b.height() * -1
							}, a.duration, a.easing);
							break;
						default:
							b.animate({
								opacity: 0
							}, a.duration, a.easing, function () {
								b.hide()
							})
						}
					}
				})
			
			}
		}
	});
	d.fn[e.prototype.name] = function () {
		var c = arguments,
			a = c[0] ? c[0] : null;
		return this.each(function () {
			var b = d(this);
			if(e.prototype[a] && b.data(e.prototype.name) && a != "initialize") b.data(e.prototype.name)[a].apply(b.data(e.prototype.name), Array.prototype.slice.call(c, 1));
			else if(!a || d.isPlainObject(a)) {
				var f = new e;
				e.prototype.initialize && f.initialize.apply(f, d.merge([b], c));
				b.data(e.prototype.name, f)
			} else d.error("Method " + a + " does not exist on jQuery." + e.name)
		})
	}
})(jQuery);
