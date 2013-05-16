jQuery(function (a) {
    a("#tabs").tabs();
});

(function (a) {
    var b = {
        get: function (a) {
            return window.sessionStorage ? sessionStorage.getItem(a) : null
        },
        set: function (a, b) {
            window.sessionStorage && sessionStorage.setItem(a, b)
        }
    };
    a.fn.tabs = function () {
        return this.each(function () {
            var g = a(this).addClass("content").wrap('<div class="tabs-box" />').before('<ul class="nav" />'),
                e = a(this).prev("ul.nav");
            g.children("li").each(function () {
                e.append("<li class='" + a(this).attr('id') + "'><a>" + a(this).hide().attr("data-name") + "</a></li>")
            });
            e.children("li").bind("click", function (c) {
                c.preventDefault();
                var c = a("li", e).removeClass("active").index(a(this).addClass("active").get(0)),
                    h = g.children("li").hide();
                a(h[c]).show();
                b.set("atomicpress-tab", c)
            });
            var f = parseInt(b.get("atomicpress-tab"));
            a(!isNaN(f) ? e.children("li").get(f) : e.children("li:first")).trigger("click")
        })
    }
})(jQuery);