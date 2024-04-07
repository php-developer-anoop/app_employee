<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $meta_title ?></title>
    <?=link_tag('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback')."\n";?>
    <?=link_tag(base_url('assets/plugins/fontawesome-free/css/all.min.css'))."\n";?>
    <?=link_tag(base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'))."\n";?>
    <?=link_tag(base_url('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'))."\n";?>
    <?=link_tag(base_url('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'))."\n";?>
    <?=link_tag(base_url('assets/plugins/toastr/toastr.min.css'))."\n";?>
    <?=link_tag(base_url('assets/dist/css/adminlte.min.css') )."\n";?>
    <style>
      .swal2-popup.swal2-toast .swal2-title {
      font-size: 15px;
      margin: 10px;
      color: #6c757d;
      }
    </style>
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <a href="javascript:void(0)" class="h1">Forgot Password</a>
        </div>
        <div class="card-body">
          <p class="login-box-msg">Forgot your password? Here you can easily retrieve a new password.</p>
          <form onsubmit="return false;">
            <div class="input-group mb-3">
              <input type="email" class="form-control" id="email" placeholder="Enter Registered Email" autocomplete="off">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button  onclick="return validate()" class="btn btn-primary btn-block">Request New Password</button>
              </div>
            </div>
          </form>
          <p class="mt-3 mb-1 text-center">
            <a href="<?=base_url(HOSPITALPATH . 'login')?>">Login</a>
          </p>
        </div>
      </div>
    </div>
    <?=script_tag(base_url('assets/plugins/jquery/jquery.min.js'))?>
    <?=script_tag(base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'))?>
    <?=script_tag(base_url('assets/plugins/sweetalert2/sweetalert2.min.js'))?>
    <?=script_tag(base_url('assets/plugins/toastr/toastr.min.js'))?>
    <?=script_tag(base_url('assets/dist/js/adminlte.min.js'))?>
    <script>
     var Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 1000
});

function validate() {
  var email = $('#email').val();
  if (email == "") {
    Toast.fire({
      icon: 'warning',
      title: 'Please Enter Email'
    })
    return false;
  } else if (email != "") {
    $.ajax({
      url: '<?=base_url(HOSPITALPATH.'sendNewPassword')?>',
      type: "POST",
      data: {
        'email': email
      },
      cache: false,
      success: function(response) {
        if (response == "success") {
          toastr.success("New Password Has Been Sent On Entered Email Id");
          window.location.href = "<?=base_url(HOSPITALPATH.'login')?>"
        } else {
          toastr.error(response);
        }
      }
    });
  }
}
      
      $(function () {
        <?php if (session()->getFlashdata('success')) { ?>
          toastr.success("<?php echo session()->getFlashdata('success'); ?>")
        <?php } ?>
        <?php if (session()->getFlashdata('failed')) { ?>
          toastr.error('<?php echo session()->getFlashdata('failed'); ?>')
        <?php } ?>
      });
    </script>
  </body>
</html>