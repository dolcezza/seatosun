jQuery(function (a) {
    var b = a("#slides form");
    a('input[type="submit"]', b).bind("click", function (c) {
        c.preventDefault();
        var d = a(this);
        d.attr("disabled", !0).parent().addClass("saving");
        a.post(b.attr("action"), b.serialize(), function () {
            d.attr("disabled", !1).parent().removeClass("saving")
        })
    });
    var c = a("#slides div.howtouse").hide();
    a("#slides a.howtouse").bind("click", function () {
        c.slideToggle()
    })
});