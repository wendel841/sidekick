$(document).ready(function() {
    $('[data-original-text]').on('mouseenter', function() {
            var originalText = $(this).data('originalText');
            var currentText = $(this).html();

            $(this).data('originalText', currentText);
            $(this).html(originalText);
    }).on('mouseleave', function() {
            var originalText = $(this).html();
            var currentText = $(this).data('originalText');

            $(this).data('originalText', originalText);
            $(this).html(currentText);
    });
});