<?= $this->extend("layout/master_app"); ?>

<?= $this->section("style"); ?>
<!-- DataTables -->
<link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
      <span>Jenis Kas</span>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create"><i class="fa fa-fw fa-plus"></i></button>
    </div>
  </div>
  <div class="card-body">
    <table id="t_jenis" class="table table-bordered" style="width: 100%;">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Jenis Kas</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Start: Modal create -->
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header btn-primary">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Jenis Arus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="c_jenis" method="post" action="Javascript:Create();">
          <?= csrf_field() ?>
          <div class="form-group">
            <label for="en_jenis">Jenis Arus :</label>
            <input type="text" class="form-control" id="en_jenis" name="en_jenis">
            <small class="text-danger err-jenis"></small>
          </div>
          <button type="submit" class="btn btn-primary" id="btn_create">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End: Modal create -->

<!-- Start: Modal update -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header btn-primary">
        <h5 class="modal-title" id="exampleModalLabel">Ubah Arus Kas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="e_jenis" method="post" action="Javascript:Update();">
          <?= csrf_field() ?>
          <input type="hidden" name="en_id" id="en_id">
          <div class="form-group">
            <label for="en_jenis">Jenis Arus Kas :</label>
            <input type="text" class="form-control" id="en_jenis" name="en_jenis">
          </div>
          <button type="submit" class="btn btn-primary" id="btn_update">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End: Modal update -->

<?= $this->endSection(); ?>

<?= $this->section("script") ?>
<!-- Required datatable js -->
<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#t_jenis').DataTable({
      "ajax": {
        "url": '<?= base_url("bismillah/getAllJenis") ?>',
        "type": "POST",
        "dataType": "json",
        async: "true",
      }
    });
  });

  function ModalTambah() {
    $('#create').modal('show');
  }

  function ModalEdit() {
    $('#edit').modal('show');
  }

  function Create() {
    $.ajax({
      url: "<?= base_url(); ?>bismillah/createJenis",
      type: "post",
      dataType: "json",
      data: $('#c_jenis').serialize(),
      beforeSend: function() {
        $('#btn_create').attr('disabled');
        $("#btn_create").html('<i class="fa fa-spin fa-spinner"></i> Loading...');
      },
      success: function(respon) {
        if (respon.error) {
          if (respon.error.en_jenis) {
            $('#en_jenis').addClass('is-invalid');
            $('.err-jenis').html(respon.error.en_jenis);
          } else {
            $('#en_jenis').removeClass('is-invalid');
            $('.err-jenis').html('');
          }

        } else {
          if (respon.status) {
            $('#create').modal('hide');
            Swal.fire({
              icon: 'success',
              text: respon.msg,
            }).then(function() {
              $('#t_jenis').DataTable().ajax.reload(null, false).draw(false);
              $('#c_jenis')[0].reset();
            })
          } else {
            Swal.fire({
              icon: 'warning',
              text: respon.msg,
            });
          }
        }
      },
      complete: function() {
        $('#btn_create').removeAttr('disable');
        $('#btn_create').html('Simpan');
      }
    });
  }

  function Edit(id) {
    $.ajax({
      url: "<?= base_url() ?>bismillah/getOneJenis",
      type: "post",
      data: {
        id: id
      },
      dataType: "json",
      success: function(respon) {
        ModalEdit();
        //insert data to form
        $("#e_jenis #en_id").val(respon.id_jenis);
        $("#e_jenis #en_jenis").val(respon.jenis);
      }
    });
  }

  function Update() {
    $.ajax({
      url: "<?= base_url() ?>bismillah/updateJenis",
      type: "post",
      data: $("#e_jenis").serialize(),
      dataType: "json",
      beforeSend: function() {
        $('#btn_update').attr('disabled');
        $("#btn_update").html('<i class="fa fa-spin fa-spinner"></i> Loading...');
      },
      success: function(respon) {
        if (respon.status) {
          $('#edit').modal('hide');
          Swal.fire({
            icon: 'success',
            text: respon.msg,
          }).then(function() {
            $('#t_jenis').DataTable().ajax.reload(null, false).draw(false);
            $('#e_jenis')[0].reset();
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
        $('#btn_update').html('Simpan');
      }
    });
  }

  function Delete(id) {
    $.ajax({
      url: "<?= base_url() ?>bismillah/deleteJenis",
      type: "post",
      data: {
        id: id
      },
      dataType: "json",
      success: function(respon) {
        if (respon.status) {
          Swal.fire({
            icon: 'success',
            text: respon.msg,
          }).then(function() {
            $('#t_jenis').DataTable().ajax.reload(null, false).draw(false);
          })
        } else {
          Swal.fire({
            icon: 'warning',
            text: respon.msg,
          });
        }
      }
    });
  }
</script>
<?= $this->endSection(); ?>