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

// Fetching pin details
document.getElementById("pincode").addEventListener("blur", function () {
  const pin = this.value.trim();
  if (pin.length === 6 && /^\d+$/.test(pin)) {
    fetch("https://api.postalpincode.in/pincode/" + pin)
      .then(res => res.json())
      .then(data => {
        if (data[0].Status === "Success") {
          const po = data[0].PostOffice[0];
          document.getElementById("district").value = po.District || "N/A";
          document.getElementById("tehsil").value = po.Block || "N/A";
          document.getElementById("state").value = po.State || "N/A";
          console.log(data);
        } else {
          alert("Invalid PIN code entered.");
          clearFields();
        }
      })
      .catch(err => {
        console.error("Error fetching location data:", err);
        alert("Could not fetch location details.");
        clearFields();
      });
  } else {
    alert("Please enter a valid 6-digit PIN code.");
    clearFields();
  }
});

function clearFields() {
  document.getElementById("district").value = "";
  document.getElementById("tehsil").value = "";
  document.getElementById("state").value = "";
}