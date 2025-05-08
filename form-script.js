 // Document toggle logic
 $('#document-type').on('change', function () {
    if (this.value === 'Aadhar') {
      $('#aadhar-number').show().attr('required', true);
      $('#license-number').hide().removeAttr('required');
    } else if (this.value === 'Driving-License') {
      $('#license-number').show().attr('required', true);
      $('#aadhar-number').hide().removeAttr('required');
    } else {
      $('#license-number, #aadhar-number').hide().removeAttr('required');
    }
  }).trigger('change');