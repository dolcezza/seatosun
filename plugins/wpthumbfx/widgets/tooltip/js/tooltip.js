(function (d) {
    var e = function () {};
    d.extend(e.prototype, {
        name: "tooltip",
        options: {
            activation: "hover",
            maxwidth: 220,
            gutter: 5,
            sticky: false,
            position: "top",
            delay: 0,
            fadeIn: 300,
            fadeOut: 300,
            tipSelector: ".tip-content"
        },
        initialize: function (c, a) {
            a = d.extend({}, this.options, a);
            c.attr("data-tooltip") && d.each(c.attr("data-tooltip").split(";"), function (b, c) {
                var d = c.match(/\s*([A-Z_]*?)\s*:\s*(.+)\s*/i);
                d && (a[d[1]] = d[2])
            });
            a.maxwidth = parseInt(a.maxwidth);
            a.gutter = parseInt(a.gutter);
            a.delay = parseInt(a.delay);
            a.fadeIn = parseInt(a.fadeIn);
            a.fadeOut = parseInt(a.fadeOut);
            var b = c.children(a.tipSelector).first();
            var u = b.length ? b.html() : c.attr('title');
            var flag = b.length ? true : false;
            var v = false;
            if (!flag) c.removeAttr('title');
            if (d("#tip_holder").length <= 0) {
                var w = d('<div>', {
                    id: 'tip_holder'
                });
                var x = d('<div>', {
                    id: 'tip_content',
                    Class: 'clearfix'
                });
                var y = d('<div>', {
                    id: 'tip_arrow'
                });
                var sp = d('<span>', {
                    html: '',
                    id: 'close-tip'
                });
                d("body").append(w.html(x).prepend(sp).prepend(y.html('<div id="tip_arrow_inner"></div>')))
            } else {
                var w = d("#tip_holder");
                var x = d("#tip_content");
                var sp = d('#close-tip');
                var y = d("#tip_arrow")
            }
            var z = function () {
                x.empty().html(u);
                if (a.sticky) d(sp).hide().show();
                else d(sp).hide();
                w.css({
                    'max-width': a.maxwidth + 'px'
                });
            };
            var A = function () {
                z();
                w.hide().removeAttr("class").css("margin", "0");
                y.removeAttr("style");
                var b = parseInt(c.offset()["top"]);
                var e = parseInt(c.offset()["left"]);
                var f = parseInt(c.outerWidth());
                var g = parseInt(c.outerHeight());
                var h = w.outerWidth();
                var i = w.outerHeight();
                var j = Math.round((f - h) / 2);
                var k = Math.round((g - i) / 2);
                var l = Math.round(e + j);
                var m = Math.round(b + g + a.gutter);
                var n = "";
                var o = "";
                var p = Math.round(h - 12) / 2;
                if (a.position == "bottom") {
                    n = "_bottom"
                } else {
                    if (a.position == "top") {
                        n = "_top"
                    } else {
                        if (a.position == "left") {
                            n = "_left"
                        } else {
                            if (a.position == "right") {
                                n = "_right"
                            }
                        }
                    }
                }
                var q = (j + e) < parseInt(d(window).scrollLeft());
                var r = (h + e) > parseInt(d(window).width());
                if ((q && j < 0) || (n == "_right" && !r) || (n == "_left" && e < (h + a.gutter + 5))) {
                    n = "_right";
                    o = Math.round(i - 13) / 2;
                    p = -12;
                    l = Math.round(e + f + a.gutter);
                    m = Math.round(b + k)
                } else {
                    if ((r && j < 0) || (n == "_left" && !q)) {
                        n = "_left";
                        o = Math.round(i - 13) / 2;
                        p = Math.round(h);
                        l = Math.round(e - (h + a.gutter + 5));
                        m = Math.round(b + k)
                    }
                }
                var s = (b + g + a.gutter + i + 8) > parseInt(d(window).height() + d(window).scrollTop());
                var t = ((b + g) - (a.gutter + i + 8)) < 0;
                if (s || (n == "_bottom" && s) || (n == "_top" && !t)) {
                    if (n == "_top" || n == "_bottom") {
                        n = "_top"
                    } else {
                        n = n + "_top"
                    }
                    o = i;
                    m = Math.round(b - (i + 5 + a.gutter))
                } else if (t | (n == "_top" && t) || (n == "_bottom" && !s)) {
                    if (n == "_top" || n == "_bottom") {
                        n = "_bottom"
                    } else {
                        n = n + "_bottom"
                    }
                    o = -12;
                    m = Math.round(b + g + a.gutter)
                }
                if (n == "_right_top" || n == "_left_top") {
                    m = m + 5
                } else {
                    if (n == "_right_bottom" || n == "_left_bottom") {
                        m = m - 5
                    }
                }
                if (n == "_left_top" || n == "_left_bottom") {
                    l = l + 5
                }
                y.css({
                    "margin-left": p + "px",
                    "margin-top": o + "px"
                });
                w.css({
                    "margin-left": l + "px",
                    "margin-top": m + "px"
                }).attr("class", "tip" + n);
                if (v) {
                    clearTimeout(v)
                }
                v = setTimeout(function () {
                    w.stop(true, true).fadeIn(a.fadeIn)
                }, a.delay)
            };
            var B = function () {
                if (v) {
                    clearTimeout(v)
                }
                w.fadeOut(a.fadeOut);
                sp.hide();
            };
            sp.on('click', function () {
                B();
            });
            d(window).resize(function () {
                B();
            });
            if (a.activation == "hover") {
                c.hover(function () {
                    A()
                }, function () {
                    if (!a.sticky) {
                        B()
                    }
                });
            } else {
                if (a.activation == "focus") {
                    c.focus(function () {
                        A()
                    }).blur(function () {
                        B()
                    })
                } else {
                    if (a.activation == "click") {
                        c.click(function () {
                            A();
                            return false
                        }).hover(function () {}, function () {
                            if (!a.sticky) {
                                B()
                            }
                        });
                    }
                }
            }
        }
    });
    d.fn[e.prototype.name] = function () {
        var c = arguments,
            a = c[0] ? c[0] : null;
        return this.each(function () {
            var b = d(this);
            if (e.prototype[a] && b.data(e.prototype.name) && a != "initialize") b.data(e.prototype.name)[a].apply(b.data(e.prototype.name), Array.prototype.slice.call(c, 1));
            else if (!a || d.isPlainObject(a)) {
                var f = new e;
                e.prototype.initialize && f.initialize.apply(f, d.merge([b], c));
                b.data(e.prototype.name, f)
            } else d.error("Method " + a + " does not exist on jQuery." + e.name)
        })
    }
})(jQuery);