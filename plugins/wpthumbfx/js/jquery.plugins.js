(function (a) {
    a.browser.msie && 9 > parseInt(a.browser.version) && (a(document).ready(function () {
        a("body").addClass("apress-ie apress-ie" + parseInt(a.browser.version))
    }), a.each("abbr article aside audio canvas details figcaption figure footer header hgroup mark meter nav output progress section summary time video".split(" "), function () {
        document.createElement(this)
    }))
})(jQuery);
(function (a) {
    a.easing.jswing = a.easing.swing;
    a.extend(a.easing, {
        def: "easeOutQuad",
        swing: function (b, c, d, e, f) {
            return a.easing[a.easing.def](b, c, d, e, f)
        },
        easeInQuad: function (a, b, c, d, e) {
            return d * (b /= e) * b + c
        },
        easeOutQuad: function (a, b, c, d, e) {
            return -d * (b /= e) * (b - 2) + c
        },
        easeInOutQuad: function (a, b, c, d, e) {
            return 1 > (b /= e / 2) ? d / 2 * b * b + c : -d / 2 * (--b * (b - 2) - 1) + c
        },
        easeInCubic: function (a, b, c, d, e) {
            return d * (b /= e) * b * b + c
        },
        easeOutCubic: function (a, b, c, d, e) {
            return d * ((b = b / e - 1) * b * b + 1) + c
        },
        easeInOutCubic: function (a, b, c, d, e) {
            return 1 > (b /= e / 2) ? d / 2 * b * b * b + c : d / 2 * ((b -= 2) * b * b + 2) + c
        },
        easeInQuart: function (a, b, c, d, e) {
            return d * (b /= e) * b * b * b + c
        },
        easeOutQuart: function (a, b, c, d, e) {
            return -d * ((b = b / e - 1) * b * b * b - 1) + c
        },
        easeInOutQuart: function (a, b, c, d, e) {
            return 1 > (b /= e / 2) ? d / 2 * b * b * b * b + c : -d / 2 * ((b -= 2) * b * b * b - 2) + c
        },
        easeInQuint: function (a, b, c, d, e) {
            return d * (b /= e) * b * b * b * b + c
        },
        easeOutQuint: function (a, b, c, d, e) {
            return d * ((b = b / e - 1) * b * b * b * b + 1) + c
        },
        easeInOutQuint: function (a, b, c, d, e) {
            return 1 > (b /= e / 2) ? d / 2 * b * b * b * b * b + c : d / 2 * ((b -= 2) * b * b * b * b + 2) + c
        },
        easeInSine: function (a, b, c, d, e) {
            return -d * Math.cos(b / e * (Math.PI / 2)) + d + c
        },
        easeOutSine: function (a, b, c, d, e) {
            return d * Math.sin(b / e * (Math.PI / 2)) + c
        },
        easeInOutSine: function (a, b, c, d, e) {
            return -d / 2 * (Math.cos(Math.PI * b / e) - 1) + c
        },
        easeInExpo: function (a, b, c, d, e) {
            return 0 == b ? c : d * Math.pow(2, 10 * (b / e - 1)) + c
        },
        easeOutExpo: function (a, b, c, d, e) {
            return b == e ? c + d : d * (-Math.pow(2, - 10 * b / e) + 1) + c
        },
        easeInOutExpo: function (a, b, c, d, e) {
            return 0 == b ? c : b == e ? c + d : 1 > (b /= e / 2) ? d / 2 * Math.pow(2, 10 * (b - 1)) + c : d / 2 * (-Math.pow(2, - 10 * --b) + 2) + c
        },
        easeInCirc: function (a, b, c, d, e) {
            return -d * (Math.sqrt(1 - (b /= e) * b) - 1) + c
        },
        easeOutCirc: function (a, b, c, d, e) {
            return d * Math.sqrt(1 - (b = b / e - 1) * b) + c
        },
        easeInOutCirc: function (a, b, c, d, e) {
            return 1 > (b /= e / 2) ? -d / 2 * (Math.sqrt(1 - b * b) - 1) + c : d / 2 * (Math.sqrt(1 - (b -= 2) * b) + 1) + c
        },
        easeInElastic: function (a, b, c, d, e) {
            var a = 1.70158,
                f = 0,
                g = d;
            if (0 == b) return c;
            if (1 == (b /= e)) return c + d;
            f || (f = .3 * e);
            g < Math.abs(d) ? (g = d, a = f / 4) : a = f / (2 * Math.PI) * Math.asin(d / g);
            return -(g * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - a) * 2 * Math.PI / f)) + c
        },
        easeOutElastic: function (a, b, c, d, e) {
            var a = 1.70158,
                f = 0,
                g = d;
            if (0 == b) return c;
            if (1 == (b /= e)) return c + d;
            f || (f = .3 * e);
            g < Math.abs(d) ? (g = d, a = f / 4) : a = f / (2 * Math.PI) * Math.asin(d / g);
            return g * Math.pow(2, - 10 * b) * Math.sin((b * e - a) * 2 * Math.PI / f) + d + c
        },
        easeInOutElastic: function (a, b, c, d, e) {
            var a = 1.70158,
                f = 0,
                g = d;
            if (0 == b) return c;
            if (2 == (b /= e / 2)) return c + d;
            f || (f = e * .3 * 1.5);
            g < Math.abs(d) ? (g = d, a = f / 4) : a = f / (2 * Math.PI) * Math.asin(d / g);
            return 1 > b ? -.5 * g * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - a) * 2 * Math.PI / f) + c : .5 * g * Math.pow(2, - 10 * (b -= 1)) * Math.sin((b * e - a) * 2 * Math.PI / f) + d + c
        },
        easeInBack: function (a, b, c, d, e, f) {
            void 0 == f && (f = 1.70158);
            return d * (b /= e) * b * ((f + 1) * b - f) + c
        },
        easeOutBack: function (a, b, c, d, e, f) {
            void 0 == f && (f = 1.70158);
            return d * ((b = b / e - 1) * b * ((f + 1) * b + f) + 1) + c
        },
        easeInOutBack: function (a, b, c, d, e, f) {
            void 0 == f && (f = 1.70158);
            return 1 > (b /= e / 2) ? d / 2 * b * b * (((f *= 1.525) + 1) * b - f) + c : d / 2 * ((b -= 2) * b * (((f *= 1.525) + 1) * b + f) + 2) + c
        },
        easeInBounce: function (b, c, d, e, f) {
            return e - a.easing.easeOutBounce(b, f - c, 0, e, f) + d
        },
        easeOutBounce: function (a, b, c, d, e) {
            return (b /= e) < 1 / 2.75 ? d * 7.5625 * b * b + c : b < 2 / 2.75 ? d * (7.5625 * (b -= 1.5 / 2.75) * b + .75) + c : b < 2.5 / 2.75 ? d * (7.5625 * (b -= 2.25 / 2.75) * b + .9375) + c : d * (7.5625 * (b -= 2.625 / 2.75) * b + .984375) + c
        },
        easeInOutBounce: function (b, c, d, e, f) {
            return c < f / 2 ? .5 * a.easing.easeInBounce(b, 2 * c, 0, e, f) + d : .5 * a.easing.easeOutBounce(b, 2 * c - f, 0, e, f) + .5 * e + d
        }
    })
})(jQuery);
(function (a) {
    function b(b) {
        var c = b || window.event,
            d = [].slice.call(arguments, 1),
            e = 0,
            f = 0,
            h = 0,
            b = a.event.fix(c);
        b.type = "mousewheel";
        b.wheelDelta && (e = b.wheelDelta / 120);
        b.detail && (e = -b.detail / 3);
        h = e;
        void 0 !== c.axis && c.axis === c.HORIZONTAL_AXIS && (h = 0, f = -1 * e);
        void 0 !== c.wheelDeltaY && (h = c.wheelDeltaY / 120);
        void 0 !== c.wheelDeltaX && (f = -1 * c.wheelDeltaX / 120);
        d.unshift(b, e, f, h);
        return a.event.handle.apply(this, d)
    }
    var c = ["DOMMouseScroll", "mousewheel"];
    a.event.special.mousewheel = {
        setup: function () {
            if (this.addEventListener) for (var a = c.length; a;) this.addEventListener(c[--a], b, !1);
            else this.onmousewheel = b
        },
        teardown: function () {
            if (this.removeEventListener) for (var a = c.length; a;) this.removeEventListener(c[--a], b, !1);
            else this.onmousewheel = null
        }
    };
    a.fn.extend({
        mousewheel: function (a) {
            return a ? this.bind("mousewheel", a) : this.trigger("mousewheel")
        },
        unmousewheel: function (a) {
            return this.unbind("mousewheel", a)
        }
    })
})(jQuery);