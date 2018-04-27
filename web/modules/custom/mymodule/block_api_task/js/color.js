(function ($, Drupal) {
    Drupal.behaviors.color = {
        attach: function attach(context) {
            var li = $('li', context);

            li.each(function () {
                var color = $(this).text();
                $(this).css('color', color);
            });
        }
    }
})(jQuery, Drupal);