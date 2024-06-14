(function ($, Drupal) {
  Drupal.behaviors.ajmanCoreBehavior = {
    attach: function (context, settings) {
      // Function to show/hide field_elloborate based on the selected value and set it as required.
      function toggleFieldElloborate(serviceWrapper) {
        var selectedValue = serviceWrapper.find('select[name$="[field_service_adaptable]"]').val();
        var fieldElloborate = serviceWrapper.find('.field--name-field-elloborate');
        var fieldElloborateInput = fieldElloborate.find('input, textarea');

        if (selectedValue === 'yes') {
          fieldElloborate.show();
          fieldElloborateInput.prop('required', true);
        } else {
          fieldElloborate.hide();
          fieldElloborateInput.prop('required', false);
        }
      }

      // Iterate over each service paragraph.
      $('.paragraph-type--service', context).each(function () {
        var serviceWrapper = $(this);

        // Initially hide the field_elloborate field and remove required attribute.
        var fieldElloborate = serviceWrapper.find('.field--name-field-elloborate');
        var fieldElloborateInput = fieldElloborate.find('input, textarea');
        fieldElloborate.hide();
        fieldElloborateInput.prop('required', false);

        // Bind the change event to the field_service_adaptable select element.
        serviceWrapper.find('select[name$="[field_service_adaptable]"]').change(function () {
          toggleFieldElloborate(serviceWrapper);
        });

        // Initial call to set the correct visibility and required attribute on page load.
        toggleFieldElloborate(serviceWrapper);
      });
    }
  };
})(jQuery, Drupal);
