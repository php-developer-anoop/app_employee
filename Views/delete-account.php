
 <title><?=$title?></title>
<link rel="icon" type="image/x-icon" href="<?=$favicon?>" />
<link rel="stylesheet" href="<?=base_url()?>/assets/frontend/css/bootstrap.min.css">
<link rel = "preload" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type ="text/css" as ="style" onload = "this.onload = null; this.rel = 'stylesheet';"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<style>
  *{ box-sizing:border-box; }
  body{ margin:0; padding:0; }
  .logoarea {
  background: #eeeeee;
  padding: 10px;
  text-align: center;
  }
  .form-control, select {
  height: 45px;
  }
  .logoarea img {
  width: 200px;
  height: 200px;
  }
  .deltform {
  padding: 50px;
  background: #eee;
  margin: 30px 0;
  border-radius: 10px;
  }
  .btncntr {
  text-align: center;
  }
  .deltform  button {
  max-width: 250px;
  }
  form input.otp {
  display:inline-block;
  width:25px;
  height:25px;
  text-align:center;
  margin:0 2px;
  border-width:0 0 1px 0;
  }
  .numbrotpfield {
  position: relative;
  }
  .otpwrpr {
  position: absolute;
  right: 20px; 
  gap: 10px;
  border-left: 1px solid;
  top: 40px;
  align-items: center;
  padding-left: 12px;
  z-index:99;
  }
  a.btn.customotpBtn {
  border-radius: 13px;
  background: #F6AF45;
  min-width: 95px !important;
  color: #fff;
  padding: 5px 0px;
  font-size: 12px;
  font-style: normal;
  font-weight: 400;
  }
  .share-crs-icn a i {
  font-size: 22px;
  }
</style>
<div class="logoarea">
  <img src="<?=base_url('assets/delete_account.png')?>">
</div>
<div class="container">
<div class="row">
  <div class="deltform">
    <h4> Account Delete Request</h4>
    <form>
      <div class="row">
        <div class="mb-3 col-md-6 position-relative">
          <label class="form-label" for="email">Select User Type</label>
          <div class="input-group input-group-merge">
            <select id="user_type" class="form-control">
              <option value="">Select User Type</option>
              <option value="employee">Employee</option>
              <option value="volunteer">Volunteer</option>
            </select>
          </div>
          <label class="form-label" for="email">Email</label>
          <div class="input-group input-group-merge">
            <input type="email" required id="email" class="form-control" autocomplete="off">
          </div>
        </div>
        <div class="form-group row mt-2">
          <div class="col-sm-12">
            <div class="custom-btn-group">
              <button type="button" onclick="return validate_form()" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </div>
    </form>
    </div>
  </div>
</div>
<script src="<?=base_url()?>/assets/frontend/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>

    function validate_form() {
    var email = $('#email').val();
    var user_type = $('#user_type').val();
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

    if (user_type.trim() == "") {
      alert('Please Select User Type');
      return false;
    } else if (email.trim() == "") {
      alert('Please Enter Email');
      return false;
    } else if (reg.test(email) === false) {
      alert("Invalid Email");
      return false;
    } else {
      // Ask for confirmation before proceeding
      var confirmDelete = confirm("Are you sure you want to delete the account?");
      
      if (confirmDelete) {
        $.ajax({
          url: '<?= base_url(ADMINPATH.'remove-account') ?>',
          type: 'POST',
          data: {
            'email': email,
            'user_type': user_type
          },
          cache: false,
          dataType: 'json',
          success: function(response) {
            // Use SweetAlert to display the message
            Swal.fire({
              icon: response.status ? 'success' : 'error',
              title: response.message,
            });
            if(response.status==true){
                window.location.reload();
            }
          },
          error: function(xhr, textStatus, errorThrown) {
            console.error(xhr, textStatus, errorThrown);
          }
        });
      } else {
        alert("Deletion canceled");
      }
    }
  }
</script>