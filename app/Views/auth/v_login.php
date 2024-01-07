<?= $this->extend("layout/master_auth"); ?>

<?= $this->section('content'); ?>
<div class="container py-5 h-100">
  <div class="row d-flex justify-content-center align-items-center h-100">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
      <div class="card shadow-2-strong" style="border-radius: 1rem;">
        <div class="card-body p-5">

          <h3 class="mb-3 text-center">Sign in</h3>

          <form id="c_login" method="post" action="Javascript:Login();">
            <div class="form-group">
              <input class="form-control" type="text" id="en_email" name="en_email" placeholder="Email">
              <small class="text-danger err-email"></small>
            </div>

            <div class="form-group">
              <input type="password" id="en_pass" name="en_pass" class="form-control" placeholder="Password">
              <small class="text-danger err-pass"></small>
            </div>

            <div class="form-group">
              <button class="btn btn-primary btn-block" type="submit">Login</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
  function Login() {
    $.ajax({
      url: "<?= base_url(); ?>auth/login",
      data: $('#c_login').serialize(),
      type: "POST",
      dataType: "JSON",
      beforeSend: function() {
        $('#btn_login').attr('disabled');
        $("#btn_login").html('<i class="fa fa-spin fa-spinner"></i> Loading...');
      },
      success: function(respon) {
        if (respon.error) {
          if (respon.error.en_email) {
            $('#en_email').addClass('is-invalid');
            $('.err-email').html(respon.error.en_email);
          } else {
            $('#en_email').removeClass('is-invalid');
            $('.err-email').html('');
          }

          if (respon.error.en_pass) {
            $('#en_pass').addClass('is-invalid');
            $('.err-pass').html(respon.error.en_pass);
          } else {
            $('#en_pass').removeClass('is-invalid');
            $('.err-pass').html('');
          }

        } else {
          if (respon.status) {
            Swal.fire({
              icon: 'success',
              text: respon.msg,
              showConfirmButton: false,
              timer: 1500
            });
            setTimeout(function() {
              window.location = respon.link
            }, 500);
          } else {
            Swal.fire({
              icon: 'warning',
              text: respon.msg,
            });
          }
        }
      },
      complete: function() {
        $('#btn_login').removeAttr('disable');
        $('#btn_login').html('Log In');
      }
    })
  }
</script>
<?= $this->endSection(); ?>