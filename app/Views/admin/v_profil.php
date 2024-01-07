<?= $this->extend('layout/master_app'); ?>

<?= $this->section('content'); ?>
<div class="card">
  <div class="card-header">
    <span>Halaman Profil</span>
  </div>
  <div class="card-body" id="profil">
  </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
  $(document).ready(function() {
    $.ajax({
      url: "<?= base_url() ?>auth/getProfil",
      type: "post",
      dataType: "json",
      success: function(respon) {
        $("#profil").html(respon.status);
      },
    });
  });

  function Profil() {
    $.ajax({
      url: "<?= base_url() ?>auth/updateProfil",
      type: "post",
      data: $("#e_profil").serialize(),
      dataType: "json",
      beforeSend: function() {
        $('#btn_update').attr('disabled');
        $("#btn_update").html('<i class="fa fa-spin fa-spinner"></i> Loading...');
      },
      success: function(respon) {
        if (respon.status) {
          Swal.fire({
            icon: 'success',
            text: respon.msg,
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
        $('#btn_update').html('Update Profile');
      }
    });
  }
</script>
<?= $this->endSection(); ?>