<footer class="my-5 pt-5 text-muted text-center text-small">
  <p class="mb-1">&copy; <?= date('Y') ?> INDAM</p>
</footer>
</div>


<script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/form-validation.js"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="assets/js/intlTelInput-jquery.min.js"></script>
<script src="assets/js/intlTelInput.min.js"></script>
<script src="assets/js/utils.js"></script>

<script>

  $('input[type=radio][name=is_member]').change(function() {
    console.log(this.value); 
    if (this.value == 'yes') {
      $("#member_id_div").show();
      $("#mem_1").attr("required","required");
      $("#mem_2").attr("required","required");
      $("#mem_3").attr("required","required");
    }else{
      $("#member_id_div").hide();
      $("#mem_1").removeAttr("required");
      $("#mem_2").removeAttr("required");
      $("#mem_3").removeAttr("required");
     
    }
    
  });

  var input = $("#phone_number").intlTelInput({
            separateDialCode: true,
            initialCountry: "in"
        });
  var country = $('#country');
  
  // listen to the telephone input for changes
  input.on('keyup', function(e) {
    // change the hidden input value to the selected country code
    country.val(($("#phone_number").intlTelInput("getSelectedCountryData").name));
    $("#dial_code").val(($("#phone_number").intlTelInput("getSelectedCountryData").dialCode));
  });
</script>
</body>

</html>