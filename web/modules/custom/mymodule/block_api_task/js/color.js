(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.color = {
        attach: function attach(context) {
            var colors = drupalSettings.block_api_task.colors;
            var classname = drupalSettings.block_api_task.classname;

            $('.' + classname + ' li').each(function (index, item) {
                $(this).css('color', colors[index]);
            });
        }
    }
})(jQuery, Drupal, drupalSettings);