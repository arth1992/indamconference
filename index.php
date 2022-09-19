<?php
require('config.php');
include('header.php');
?>
<div class="container">
  <main>
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="assets/images/logo.png" alt="">
      <h2>Registration form</h2>
      
    </div>

    <div style="text-align: center;" id="kktable">


      <table class="table align-middle mb-0 bg-white">
        <thead class="bg-light">
          <tr>
            <th>
              <strong>Nationality</strong>
            </th>
            <th><strong>
                Category</strong>
            </th>
            <th>
              <strong>&nbsp;Registration Fee &nbsp;<br> (Early Bird)</strong>
            </th>
            <th>
              <strong>&nbsp;Registration Fee <br>&nbsp; (Late Registration)&nbsp;</strong>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              &nbsp;Delegates from India and other SAARC countries&nbsp;
            </td>
            <td>
              Academician/Researcher
            </td>
            <td>
              INR 6000
            </td>
            <td>
              INR 7000
            </td>
          </tr>
          <tr>
            <td>
              &nbsp;Delegates from India and other SAARC countries&nbsp;
            </td>
            <td>
              &nbsp;Full-time Research Scholar&nbsp;
            </td>
            <td>
              INR 3000
            </td>
            <td>
              INR 4000
            </td>
          </tr>
          <tr>
            <td>
              &nbsp;Delegates from other countries
            </td>
            <td>
              Academician/Researcher
            </td>
            <td>
              USD 300
            </td>
            <td>
              USD 350
            </td>
          </tr>
          <tr>
            <td>
              Delegates from other countries
            </td>
            <td>
              Full-time Research Scholar&nbsp;
            </td>
            <td>
              USD 100
            </td>
            <td>
              USD 150
            </td>
          </tr>
        </tbody>
      </table>

    </div>
    <div class="clearfix"><br /></div>

    <div class="col-md-12 col-lg-12">

      <?php print_r($_SESSION); 
       if(isset($_SESSION['error'])) : 
          echo "<div class='alert alert-danger'>";
          foreach($_SESSION['error'] as $error)  :
            echo "<p>".$error."</p>".PHP_EOL;
          endforeach;
          unset($_SESSION['error']);
          echo "</div>";
        endif;
        ?>


      <form class="needs-validation" method="post" action="payment-confirm.php" novalidate>

      <div class="col-lg-12 mb-4">
            <label for="name" class="form-label">Name</label>
            <input type="name" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
            <div class="invalid-feedback">
              Please enter your name.
            </div>
          </div>

      <label class="form-label">Type of Registration</label>
      <div class="my-3">
          <div class="form-check">
            <input id="credit" name="registrationType" type="radio" class="form-check-input" value="academician" checked required>
            <label class="form-check-label" for="academician">Academician</label>
          </div>
          <div class="form-check">
            <input id="debit" name="registrationType" type="radio" class="form-check-input" value="student" required>
            <label class="form-check-label" for="student">Student</label>
          </div>
          <div class="form-check">
            <input id="debit" name="registrationType" type="radio" class="form-check-input" value="other" required>
            <label class="form-check-label" for="other">Other</label>
          </div>
        </div>


        <div class="row g-3">
          <div class="col-sm-6">
            <label for="present_designation" class="form-label">Present Designation</label>
            <input type="present_designation" class="form-control" id="present_designation" name="present_designation" placeholder="Your present designation" required>
            <div class="invalid-feedback">
                Please enter a valid present designation.
            </div>
          </div>

          <div class="col-sm-6">
            <label for="affiliation" class="form-label">Affiliation (Institute / Organization)</label>
            <input type="affiliation" class="form-control" id="affiliation" name="affiliation" placeholder="Affiliation (Institute / Organization)" required>
            <div class="invalid-feedback">
                Please enter a valid affiliation.
            </div>
          </div>

        <label class="form-label">Are you from India / SAARC countries?</label>
        <div class="my-3">
          <div class="form-check">
            <input id="credit" name="nationality" type="radio" class="form-check-input" value="saarc" checked required>
            <label class="form-check-label" for="yes">Yes</label>
          </div>
          <div class="form-check">
            <input id="debit" name="nationality" type="radio" class="form-check-input" value="other" required>
            <label class="form-check-label" for="no">No</label>
          </div>
        </div>

        <label class="form-label">Are you an INDAM registered member?</label>
        <div class="my-3">
          <div class="form-check">
            <input id="credit" name="is_member" type="radio" class="form-check-input" value="yes" required>
            <label class="form-check-label" for="yes">Yes</label>
          </div>
          <div class="form-check">
            <input id="debit" name="is_member" type="radio" class="form-check-input" value="no" required>
            <label class="form-check-label" for="no">No</label>
          </div>
        </div>

        <div class="col-lg-12" id="member_id_div" style="display: none;">
            <label for="member_id" class="form-label">If you are an INDAM member Please enter your INDAM registration id</label>
            <input type="text" class="form-control" id="member_id" name="member_id" placeholder="Member ID">
            <div class="invalid-feedback">
              Please enter your INDAM member id.
            </div>
          </div>


        <div class="row g-3">
          <div class="col-md-6 form-group">
           <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
            <div class="invalid-feedback">
              Please enter a valid email address for registration purpose.
            </div>
          </div>

          <div class="col-md-6 form-group">
            <label for="mm" class="form-label">Mobile no.</label>
              <input type="tel" class="form-control" id="phone" name="phone" placeholder="Your mobile number" required>
              <div class="invalid-feedback">
                Please enter a valid mobile number.
              </div>
          </div>

         
        </div>

        <hr class="my-4">
        <b> Note :  Price will be calculated on the next page. </b>
        <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
      </form>
    </div>
</div>
</main>

<?php include('footer.php') ?>
