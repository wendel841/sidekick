$(document).ready(function() {
    $('.sentence').on('mouseenter', function() {
        $(this).find('[data-original-text]').each(function() {
            var originalText = $(this).data('originalText');
            var currentText = $(this).html();

            $(this).data('originalText', currentText);
            $(this)
                .html(originalText)
                .removeClass('new')
                .addClass('deleted');
        });
    }).on('mouseleave', function() {
        $(this).find('[data-original-text]').each(function() {
            var originalText = $(this).html();
            var currentText = $(this).data('originalText');

            $(this).data('originalText', originalText);
            $(this)
                .html(currentText)
                .removeClass('deleted')
                .addClass('new');
        });
    });
});
//# sourceMappingURL=all.js.map
