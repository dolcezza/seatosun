;(function($) {
    $(function() {
        // Add keyboard navigation support to nav menu
        var currentMenuLink;
        var retainOrRelease = 0;
        
        $('.sub-menu-container > a, .sub-menu a').focusin(function(event) {
            retainOrRelease++;
            
            if (retainOrRelease == 1) {
                currentMenuLink = $(event.target).closest('.sub-menu-container');
                currentMenuLink.addClass('focus');
            }
        }).focusout(function(event) {
            retainOrRelease--;
            if (retainOrRelease == 0) {
                currentMenuLink.removeClass('focus');
            }
        });
        
        // Add placeholder support for non-HTML5 browsers
        if (!Modernizr.placeholder) {
            $('input[placeholder], textarea[placeholder]').placeholder();
        }
        
        // Load TypeKit fonts
        if (window.Typekit) {
            try {
                window.Typekit.load();
            } catch(e) {
                
            }
        }
        
        // Initialize ColorBox script
        if ($.colorbox) {
            $('.colorbox, .lightbox').each(function() {
                var self = $(this);
                var options = self.data('options') || {};
                
                self.colorbox(options);
            });
        }
    });
})(jQuery);


// Custom Modernizr Tests
if (Modernizr) {
    Modernizr.addTest('placeholder', !!("placeholder" in document.createElement("input")));
    Modernizr.addTest('lastchild', function () {
        var hasLastChild,
            rules = ['#modernizr-last-child li{display:block;width:100px;height:100px;}','#modernizr-last-child li:last-child{width:200px;}'],
            head = document.getElementsByTagName('head')[0] || (function () {
                return document.documentElement.appendChild(document.createElement('head'));
            }()),
            root = document.body || (function () {
                return document.documentElement.appendChild(document.createElement('body'));
            }()),
            list = document.createElement('ul'),
            firstChild = document.createElement('li'),
            lastChild = document.createElement('li'),
            style = document.createElement('style');
            
        style.type = "text/css";
        if(style.styleSheet){ style.styleSheet.cssText = rules.join(''); } 
        else {style.appendChild(document.createTextNode(rules.join(''))); }
        head.appendChild(style);
        
        list.id = "modernizr-last-child";
        list.appendChild(firstChild);
        list.appendChild(lastChild);
        root.appendChild(list);
        hasLastChild = lastChild.offsetWidth > firstChild.offsetWidth;
        
        head.removeChild(style);
        root.removeChild(list);
        
        return hasLastChild;
    });
    Modernizr.addTest('firstchild', function () {
        var hasFirstChild,
            rules = ['#modernizr-first-child li{display:block;width:100px;height:100px;}','#modernizr-first-child li:first-child{width:200px;}'],
            head = document.getElementsByTagName('head')[0] || (function () {
                return document.documentElement.appendChild(document.createElement('head'));
            }()),
            root = document.body || (function () {
                return document.documentElement.appendChild(document.createElement('body'));
            }()),
            list = document.createElement('ul'),
            firstChild = document.createElement('li'),
            lastChild = document.createElement('li'),
            style = document.createElement('style');
            
        style.type = "text/css";
        if(style.styleSheet){ style.styleSheet.cssText = rules.join(''); } 
        else {style.appendChild(document.createTextNode(rules.join(''))); }
        head.appendChild(style);
        
        list.id = "modernizr-first-child";
        list.appendChild(firstChild);
        list.appendChild(lastChild);
        root.appendChild(list);
        hasFirstChild = firstChild.offsetWidth > lastChild.offsetWidth;
        
        head.removeChild(style);
        root.removeChild(list);
        
        return hasFirstChild;
    });
}