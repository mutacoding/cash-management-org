<?= $this->extend("layout/master_app"); ?>

<?= $this->section("style"); ?>
<!-- DataTables -->
<link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
      <span>Kategori Arus Kas</span>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create"><i class="fas fa-fw fa-plus"></i></button>
    </div>
  </div>
  <div class="card-body">
    <table id="t_kategori" class="table table-bordered" style="width: 100%;">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Kategori Arus Kas</th>
          <th scope="col">Jenis Arus Kas</th>
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
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-fw fa-plus"></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="c_kategori" method="post" action="Javascript:Create();">
          <?= csrf_field() ?>
          <div class="form-group">
            <label for="en_kat">Kategori Arus Kas :</label>
            <input type="text" class="form-control" id="en_kat" name="en_kat">
            <small class="text-danger err-kategori"></small>
          </div>
          <div class="form-group">
            <label for="en_jenis">Jenis Arus Kas :</label>
            <select class="form-control" id="en_jenis" name="en_jenis">
              <option>Pilih Jenis Kas</option>
              <?php
              foreach ($jenis as $value) {
              ?>
                <option value="<?= $value['id_jenis'] ?>"><?= $value['jenis'] ?></option>
              <?php
              }
              ?>
            </select>
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
        <h5 class="modal-title" id="exampleModalLabel">Ubah Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="e_kategori" method="post" action="Javascript:Update();">
          <?= csrf_field() ?>
          <input type="hidden" name="en_id" id="en_id">
          <div class="form-group">
            <label for="en_kat">Kategori Arus Kas :</label>
            <input type="text" class="form-control" id="en_kat" name="en_kat">
          </div>
          <div class="form-group">
            <label for="en_kategori">Jenis Arus Kas :</label>
            <select class="form-control" id="en_jenis" name="en_jenis">
              <option>Pilih Jenis Kas</option>
              <?php
              foreach ($jenis as $value) {
              ?>
                <option value="<?= $value['id_jenis'] ?>"><?= $value['jenis'] ?></option>
              <?php
              }
              ?>
            </select>
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
    var table = $('#t_kategori').DataTable({
      "ajax": {
        "url": '<?= base_url("bismillah/getAllKategori") ?>',
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
      url: "<?= base_url(); ?>bismillah/createKategori",
      type: "post",
      dataType: "json",
      data: $('#c_kategori').serialize(),
      beforeSend: function() {
        $('#btn_create').attr('disabled');
        $("#btn_create").html('<i class="fa fa-spin fa-spinner"></i> Loading...');
      },
      success: function(respon) {
        if (respon.error) {
          if (respon.error.en_kategori) {
            $('#en_kategori').addClass('is-invalid');
            $('.err-kategori').html(respon.error.en_kategori);
          } else {
            $('#en_kategori').removeClass('is-invalid');
            $('.err-kategori').html('');
          }
        } else {
          if (respon.status) {
            $('#create').modal('hide');
            Swal.fire({
              icon: 'success',
              text: respon.msg,
            }).then(function() {
              $('#t_kategori').DataTable().ajax.reload(null, false).draw(false);
              $('#c_kategori')[0].reset();
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
    console.log(id);
    $.ajax({
      url: "<?= base_url() ?>bismillah/getOneKategori",
      type: "post",
      data: {
        id: id
      },
      dataType: "json",
      success: function(respon) {
        ModalEdit();
        //insert data to form
        $("#e_kategori #en_id").val(respon.id_kategori);
        $("#e_kategori #en_kat").val(respon.kategori);
        $("#e_kategori #en_jenis").val(respon.jenis_id);
      }
    });
  }

  function Update() {
    $.ajax({
      url: "<?= base_url() ?>bismillah/updateKategori",
      type: "post",
      data: $("#e_kategori").serialize(),
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
            $('#t_kategori').DataTable().ajax.reload(null, false).draw(false);
            $('#e_kategori')[0].reset();
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
      url: "<?= base_url() ?>bismillah/deleteKategori",
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
            $('#t_kategori').DataTable().ajax.reload(null, false).draw(false);
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