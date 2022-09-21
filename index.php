<?php
require('config.php');
include('header.php');
?>
<div class="container">
  <main>
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="assets/images/logo.png" alt="">
      <h2>Conference Registration form</h2>
      
    </div>

    <div style="text-align: center;" id="kktable">

    <table class="table align-middle mb-0 table-bordered">
        <thead class="bg-dark text-white">
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th colspan="2">INDAM Registered Members</th>
        </thead>

        <tbody>

          <tr>
            <th scope="row">Nationality</th>
            <th scope="row">Category</th>
            <th scope="row">Registration Fee (Early Bird)</th>
            <th scope="row">Registration Fee (Late Registration)</th>
            <th scope="row">Registration Fee (Early Bird)</th>
            <th scope="row">Registration Fee (Late Registration)</th>
          </tr>

          <tr>
            <th scope="row">Delegates from India and other SAARC countries</th>
            <td>Academician/Industry Professional/Others</td>
            <td>INR 6000</td>
            <td>INR 7000</td>
            <td>INR 4800</td>
            <td>INR 5600</td>
          </tr>

          <tr>
            <th scope="row">Delegates from India and other SAARC countries </th>
            <td>Full Time Research Scholar</td>
            <td>INR 3000</td>
            <td>INR 4000</td>
            <td>INR 2400</td>
            <td>INR 3200</td>
          </tr>

          <tr>
            <th scope="row">Delegates from other countries</th>
            <td>Academician/Industry Professional/Others</td>
            <td>USD 300</td>
            <td>USD 350</td>
            <td>USD 240</td>
            <td>USD 280</td>
          </tr>

          <tr>
            <th scope="row">Delegates from other countries</th>
            <td>Full Time Research Scholar</td>
            <td>USD 100</td>
            <td>USD 150</td>
            <td>USD 80</td>
            <td>USD 120</td>
          </tr>

        </tbody>
    </table>
      
    </div>
    <div class="clearfix"><br /></div>

    <div class="col-md-12 col-lg-12">

      <?php 
       if(isset($_SESSION['error'])) : 
          echo "<div class='alert alert-danger'>";
          foreach($_SESSION['error'] as $error)  :
            echo "<p>".$error."</p>".PHP_EOL;
          endforeach;
          unset($_SESSION['error']);
          echo "</div>";
        endif;

        session_destroy();  // remove all the sessions from the page.
      ?>


      <form class="needs-validation" method="post" id="registration-form" name="registration-form" action="payment-confirm.php" novalidate>

      <div class="col-lg-12 mb-4">
            <label for="name" class="form-label fw-bold">Name</label>
            <input type="name"  minlength="8" maxlength="20"  class="form-control" id="name" name="name" placeholder="Enter your full name" required>
            <div class="invalid-feedback">
              Please enter your name.
            </div>
      </div>

      <div class="row g-3">
          <div class="col-md-6 form-group">
           <label for="email" class="form-label fw-bold">Email</label>
            <input type="email"  minlength="8" maxlength="50" class="form-control" id="email" name="email" placeholder="you@example.com" required>
            <div class="invalid-feedback">
              Please enter a valid email address for registration purpose.
            </div>
          </div>

          <div class="col-md-6 form-group">
            <label for="mm" class="form-label fw-bold">Mobile number.</label>
              <input type="hidden" id="country" name="country"/>
              <input type="hidden" id="dial_code" name="dial_code"/>
              <input type="tel" class="form-control" name="phone_number" id="phone_number" placeholder="Your mobile number" required>
              <div class="invalid-feedback">
                Please enter a valid mobile number.
              </div>
      </div>

      <label class="form-label fw-bold mt-5">Type of Registration</label>
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
            <label for="present_designation" class="form-label fw-bold">Present Designation</label>
            <input type="present_designation" class="form-control" id="present_designation" name="present_designation" placeholder="Your present designation" required>
            <div class="invalid-feedback">
                Please enter a valid present designation.
            </div>
          </div>

          <div class="col-sm-6">
            <label for="affiliation" class="form-label fw-bold">Affiliation (Institute / Organization)</label>
            <input type="affiliation" class="form-control" id="affiliation" name="affiliation" placeholder="Affiliation (Institute / Organization)" required>
            <div class="invalid-feedback">
                Please enter a valid affiliation.
            </div>
          </div>

        <label class="form-label fw-bold mt-5">Are you from India / SAARC countries?</label>
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

        <label class="form-label fw-bold">Are you an INDAM registered member?</label>
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
            <label for="member_id" class=" sr-only form-label fw-bold" >Please enter your INDAM member id</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">INDAM</div>
              </div>
              <div class="col-xs-2">
              <input type="text" minlength="4" maxlength="4"  id="mem_1" name="indam_member_id[]" class="form-control" id="inlineFormInputGroup" placeholder="4 digit">
              </div>
              <div class="input-group-prepend">
                <div class="input-group-text">-</div>
              </div>
              <div class="col-xs-1">
              <input type="text"  minlength="1" maxlength="1"  id="mem_2" class="form-control col-sm-2" name="indam_member_id[]" id="inlineFormInputGroup" placeholder="Character">
              </div>
              <div class="input-group-prepend">
                <div class="input-group-text">-</div>
              </div>
              <div class="col-xs-2">
              <input type="text"  minlength="4" maxlength="4" id="mem_3" class="form-control" name="indam_member_id[]" id="inlineFormInputGroup" placeholder="Last 4 digit">
              </div>
            </div>
            <small id="ndamHelpBlock" class="form-text text-muted">
                You can find your INDAM member id by logging into your INDAM account. It looks like i.e INDAM-2022-S-0000
            </small>
            <div class="invalid-feedback">
              Please enter your INDAM member id.
            </div>
          </div> 
        </div>

        <hr class="my-4">
        <b> Note :  Exact price will be shown on the next page. </b>
        <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to payment</button>
      </form>
    </div>
</div>
</main>

<?php include('footer.php') ?>
