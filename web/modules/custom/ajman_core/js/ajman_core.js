(function (Drupal, $) {
    Drupal.behaviors.ajmanCore = {
      attach: function (context, settings) {
        var $serviceAdaptableField = $('select[name="field_service_adaptable[0][value]"]', context);
        var $elloborateFieldWrapper = $('.field-elloborate-wrapper', context);
  
        // Function to toggle the visibility of the elloborate field.
        function toggleElloborateField() {
          if ($serviceAdaptableField.val() === 'yes') {
            $elloborateFieldWrapper.show();
          } else {
            $elloborateFieldWrapper.hide();
          }
        }
  
        // Initialize the visibility on page load.
        toggleElloborateField();
  
        // Add event listener to toggle the field on change.
        $serviceAdaptableField.on('change', toggleElloborateField);
      }
    };
  })(Drupal, jQuery);
  