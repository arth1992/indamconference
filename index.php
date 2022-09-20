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


      <form class="needs-validation" method="post" action="payment-confirm.php" novalidate>

      <div class="col-lg-12 mb-4">
            <label for="name" class="form-label fw-bold">Name</label>
            <input type="name" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
            <div class="invalid-feedback">
              Please enter your name.
            </div>
      </div>

      <div class="row g-3">
          <div class="col-md-6 form-group">
           <label for="email" class="form-label fw-bold">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
            <div class="invalid-feedback">
              Please enter a valid email address for registration purpose.
            </div>
          </div>

          <div class="col-md-6 form-group">
            <label for="mm" class="form-label fw-bold">Mobile no.</label>
              <input type="tel" class="form-control" id="phone" name="phone" placeholder="Your mobile number" required>
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
            <label for="member_id" class="form-label fw-bold" >If you are an INDAM member Please enter your INDAM registration id</label>
            <input type="text" class="form-control" id="member_id" name="member_id" placeholder="Member ID">
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
