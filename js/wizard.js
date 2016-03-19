$(document).ready(function () {
    $(document).on('focus click', '.wizard', function () {
        hidePopup();
        showPopup($(this).closest('.form-item'));
    });
    $(document).on('click', '.popup', function () {
        hidePopup();
    });

    $(document).on('blur','input')

    showPopup = function (element) {
        var position = element.attr('data-wizard-position');
        var wizard = element.attr('data-wizard');
        var inputWidth = element.find('input').outerWidth();
        var selectWidth = element.find('select').outerWidth();
        var offset = selectWidth > inputWidth ? selectWidth : inputWidth;
        element.append('<div class="popup popup-' + position + '">' + wizard + '<img src="../images/help_point.png"></div>');
        if (position == 'right') {
            $('.popup').css('left', offset + 20);
        }
    }

    hidePopup = function () {
        $('.popup').remove();
    }

    showError = function (element) {
        var position = element.attr('data-wizard-position');
        var inputWidth = element.find('input').outerWidth();
        var selectWidth = element.find('select').outerWidth();
        var offset = selectWidth > inputWidth ? selectWidth : inputWidth;
        element.append('<div class="error error-' + position + '"></div>');
        $('.error').css('left', offset + 20);
    }
    hideError = function () {
        $('.error').remove();
    }
});
var showPopup;
var hidePopup;

var showError;
var hideError;
