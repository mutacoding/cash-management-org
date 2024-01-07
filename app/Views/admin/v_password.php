<?= $this->extend('layout/master_app'); ?>

<?= $this->section('content'); ?>
<div class="card">
  <div class="card-header">
    <span>Ganti Password</span>
  </div>
  <div class="card-body" id="password">
  </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
  $(document).ready(function() {
    $.ajax({
      url: "<?= base_url() ?>auth/getPassword",
      type: "post",
      dataType: "json",
      success: function(respon) {
        $("#password").html(respon.status);
      },
    });
  });

  function Password() {
    $.ajax({
      url: "<?= base_url() ?>auth/updatePassword",
      type: "post",
      data: $("#e_pass").serialize(),
      dataType: "json",
      beforeSend: function() {
        $('#btn_update').attr('disabled');
        $("#btn_update").html('<i class="fa fa-spin fa-spinner"></i> Loading...');
      },
      success: function(respon) {
        if (respon.error) {
          if (respon.error.new_pass) {
            $('#new_pass').addClass('is-invalid');
            $('.err-new').html(respon.error.new_pass);
          } else {
            $('#new_pass').removeClass('is-invalid');
            $('.err-new').html('');
          }

          if (respon.error.cnew_pass) {
            $('#cnew_pass').addClass('is-invalid');
            $('.err-cnew').html(respon.error.cnew_pass);
          } else {
            $('#cnew_pass').removeClass('is-invalid');
            $('.err-cnew').html('');
          }
        } else if (respon.status) {
          Swal.fire({
            icon: 'success',
            text: respon.msg,
          }).then(function() {
            $('#e_pass')[0].reset();
          })
        } else if (respon.status) {
          Swal.fire({
            icon: 'warning',
            text: respon.msg,
          });
        }
      },
      complete: function() {
        $('#btn_update').removeAttr('disable');
        $('#btn_update').html('Update Password');
      }
    });
  }
</script>
<?= $this->endSection(); ?>