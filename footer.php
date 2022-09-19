<footer class="my-5 pt-5 text-muted text-center text-small">
  <p class="mb-1">&copy; <?= date('Y') ?> INDAM</p>
</footer>
</div>


<script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/form-validation.js"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="assets/js/intlTelInput.min.js"></script>
<script>

  $('input[type=radio][name=is_member]').change(function() {
    console.log(this.value); 
    if (this.value == 'yes') {
      $("#member_id_div").show();
      $("#member_id").attr("required","required");
    }else{
      $("#member_id_div").hide();
      $("#member_id").removeAttr("required");
    }
    
  });


  var input = document.querySelector("#phone");
  window.intlTelInput(input, {

  });

 
</script>
</body>

</html>