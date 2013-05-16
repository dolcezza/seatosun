(function (d) {
    var e = function () {};
    d.extend(e.prototype, {
        name: "slides",
        options: {
            fx: 'fade',
            speed: 700,
            pager: false,
            nav: true,
            easing: 'swing',
            visibleClass: 'current-slide',
            activePagerClass: 'active'
        },
        initialize: function (c, a) {
            a = d.extend({}, this.options, a);
            c.attr("data-slides") && d.each(c.attr("data-slides").split(";"), function (b, c) {
                var d = c.match(/\s*([A-Z_]*?)\s*:\s*(.+)\s*/i);
                d && (a[d[1]] = d[2])
            });
            var slide = c.children();
            var length = slide.size();
            var fadeTime = parseInt(a.speed);
            var maxWidth = 0;
            var maxHeight = 0;
            var index = 0;
            var nav = '<div class="slide-nav"><a class="next"><span></span></a><a class="prev"><span></span></a></div>';
            var visibleClass = a.visibleClass;
            var activePagerClass = a.activePagerClass;
            var visible = {
                "position": "relative",
                "display": "block",
                "z-index": 1
            };
            var hidden = {
                "float": "none",
                "position": "absolute",
                "z-index": 0
            };
            var pagination = function () {
                var pager;
                var pagerLink;
                var pagerIndex = index;
                if (a.pager == "on") {
                    var pager = d('<div class="slide-pager"></div>');
                    c.append(pager);
                    for (var loop = 0; loop < length; loop++) {
                        pager.append('<a>' + (loop + 1) + '</a>');
                    }
                } else if (a.pager != "on" && a.pager != true) {
                    pager = d(a.pager);
                } else {
                    var pager = d('<div class="slide-pager"></div>');
                    c.append(pager);
                    for (var loop = 0; loop < length; loop++) {
                        pager.append('<a>' + (loop + 1) + '</a>');
                    }
                }
                a.pager = pager;
                pagerLink = pager.children();
                pagerLink.on('click', function () {
                    pagerIndex = d(this).index();
                    if (index === pagerIndex || c.find("." + visibleClass + ":animated").length) {
                        return false;
                    }
                    if (pagerIndex > length - 1 || pagerIndex < 0) alert('Pager out of bounds');
                    else {
                        pagerLink.removeClass(activePagerClass);
                        d(this).addClass(activePagerClass);
                        index = pagerIndex;
                        slideTo(index);
                        return false;
                    }
                });
            };
            var slideTo = function (idx) {
                switch (a.fx) {
                case 'fade':
                    slide.stop().fadeOut(fadeTime, a.easing, function () {
                        d(this).removeClass(visibleClass).css(hidden);
                    }).eq(idx).fadeIn(fadeTime, a.easing, function () {
                        d(this).addClass(visibleClass).css(visible);
                    });
                    break;
                case 'slide':
                    var slideVisibleIndex = c.find("." + visibleClass).index();
                    if (slideVisibleIndex === 0 && idx === length - 1) slideRight(slideVisibleIndex, idx);
                    else if (slideVisibleIndex === length - 1 && idx === 0) slideLeft(slideVisibleIndex, idx);
                    else if (slideVisibleIndex < idx) slideLeft(slideVisibleIndex, idx);
                    else if (slideVisibleIndex > idx) slideRight(slideVisibleIndex, idx);
                    break;
                }
            };
            var slideLeft = function (currentIndex, nextIndex) {
                var wrapW = c.width();
                slide.eq(currentIndex).animate({
                    'left': -wrapW
                }, a.speed, a.easing).removeClass(visibleClass).css(hidden);
                slide.eq(nextIndex).css({
                    'left': wrapW
                }).show().animate({
                    'left': "0px"
                }, a.speed, a.easing, function () {
                    slide.eq(currentIndex).hide()
                }).addClass(visibleClass).css(visible);
            };
            var slideRight = function (currentIndex, nextIndex) {
                var wrapW = c.width();
                slide.eq(currentIndex).animate({
                    'left': wrapW
                }, a.speed, a.easing).removeClass(visibleClass).css(hidden);
                slide.eq(nextIndex).css({
                    'left': -wrapW
                }).show().animate({
                    'left': "0px"
                }, a.speed, a.easing, function () {
                    slide.eq(currentIndex).hide()
                }).addClass(visibleClass).css(visible);
            };
            if (length > 1) {
                if (a.nav && a.nav != "off" && a.nav != "false") c.append(nav).addClass('slides clearfix');
                else c.addClass('slides clearfix');
                slide.hide().eq(0).addClass(visibleClass).css(visible).show();
                if (a.pager) pagination();
                if (a.pager) d(a.pager).children().eq(index).addClass(activePagerClass);
            }
            c.find('.next').on('click', function () {
                if (c.find("." + visibleClass + ":animated").length) {
                    return false;
                }
                if (index >= length - 1) index = 0;
                else index++;
                if (a.pager) d(a.pager).children().removeClass(activePagerClass).eq(index).addClass(activePagerClass);
                slideTo(index);
                return false;
            });
            c.find('.prev').on('click', function () {
                if (c.find("." + visibleClass + ":animated").length) {
                    return false;
                }
                if (index > 0) index--;
                else index = length - 1;
                if (a.pager) d(a.pager).children().removeClass(activePagerClass).eq(index).addClass(activePagerClass);
                slideTo(index);
                return false;
            });
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