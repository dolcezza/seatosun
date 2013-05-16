jQuery(document).ready(function ($) {
    function htmlEscape(str) {
        var stringval = "";
        $.each(str, function (i, element) {
            stringval += element.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        });
        return String(stringval);
    }

    $('#sc-generator-select').on('change', function () {
        var queried_shortcode = $('#sc-generator-select').find(':selected').val();
        $('#sc-generator-settings').addClass('sc-loading-animation');
        $('#sc-generator-settings').load($('#sc-generator-url').val() + '/generator.php?shortcode=' + queried_shortcode, function () {
            $('#sc-generator-settings').removeClass('sc-loading-animation');
            $('input.color').each(function (index, element) {
                $(this).miniColors();
            });
        })
    });
    $('.shortcode-trigger').live('click', function () {
        $('#sc-wrap').show();
        $.topbox.show($('#sc-wrap'), {
            'title': 'Insert Shortcode',
            'closeOnEsc': true,
            'theme': 'default',
            'height': 'auto',
            'width': 'auto',
            'speed': 500,
            'easing': 'swing',
            'buttons': {
                Close: function () {
                    this.close()
                },
                Insert: function () {
                    var queried_shortcode = $('#sc-generator-select').find(':selected').val();
                    $('#sc-generator-result').val('[' + queried_shortcode);
                    $('#sc-generator-settings .sc-generator-attr').each(function () {
                        if ($(this).val() !== '') {
                            $('#sc-generator-result').val($('#sc-generator-result').val() + ' ' + $(this).attr('name') + '="' + htmlEscape($(this).val()) + '"')
                        }
                    });
                    $('#sc-generator-result').val($('#sc-generator-result').val() + ']');
                    if ($('#sc-generator-content').val() != 'false') {
                        $('#sc-generator-result').val($('#sc-generator-result').val() + $('#sc-generator-content').val() + '[/' + queried_shortcode + ']')
                    }
                    window.send_to_editor($('#sc-generator-result').val());
                    this.close()
                }
            },
            'onClose': function () {
                $('#sc-wrap').hide()
            }
        })
    })
});