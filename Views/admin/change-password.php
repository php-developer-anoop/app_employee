<div class="content-wrapper">
  <div class="container-fluid">
    <div class="content h100">
      <div class="d-flex justify-content-center align-items-center  mt10">
        <div class="login-box">
          <div class="card card-outline card-primary">
            <div class="card-header text-center">
             <h1>Change Password</h1>
            </div>
            <div class="card-body">
              <form onsubmit="return false" id="change_password_form">
                <div class="input-group mb-3">
                  <input type="password" class="form-control" placeholder="Password" id="password">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <input type="password" class="form-control" placeholder="Confirm Password" id="cpassword">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <button onclick="return validatepassword()" class="btn btn-primary btn-block">Change password</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    function validatepassword(){
        var password=$('#password').val();
        var cpassword=$('#cpassword').val();
        if(password==""){
            toastr.error("Please Enter Password");
            return false;
        }else if(cpassword==""){
            toastr.error("Please Enter Confirm Password");
            return false;
        }else if(password !== cpassword){
            toastr.error("Both Password Must be Same");
            return false; 
        }else{
            $.ajax({
      url: '<?= base_url(ADMINPATH.'changePassword') ?>',
      type: "POST",
      data: {'password': password},
      cache: false,
        success:function(response) {   
            
            if(response=="success"){
                toastr.success("Password Changes Successfully ,Also Sent To The Email Id");
                $('#change_password_form')[0].reset();
            }else{
                toastr.error("Something Went Wrong!!");
            }
        }
      });
        }
    }
</script>