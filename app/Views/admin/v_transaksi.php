<?= $this->extend("layout/master_app"); ?>

<?= $this->section("style"); ?>
<!-- DataTables -->
<link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<!-- Start: table section -->
<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
      <span>Tabel Transaksi</span>
      <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#create"><i class="fas fa-fw fa-plus"></i></button>
    </div>
  </div>
  <div class="card-body">
    <table id="tabel_transaksi" class="table table-bordered" style="width: 100%;">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Tanggal</th>
          <th scope="col">Kategori</th>
          <th scope="col">Keterangan</th>
          <th scope="col">Kas Masuk</th>
          <th scope="col">Kas Keluar</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>
<!-- End: table section -->

<!-- Start: Modal create -->
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header btn-primary">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Transaksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="c_transaksi" method="post" action="Javascript:Create();">
          <?= csrf_field() ?>
          <div class="form-group">
            <label for="en_sumber">Jenis Arus Kas</label>
            <select class="form-control" id="en_jenis" name="en_jenis" onchange="fetchData(this.value)">
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
          <div class="form-group">
            <label for="en_sumber">Kategori Kas</label>
            <select class="form-control" id="en_kat" name="en_kat">
            </select>
          </div>
          <small class="text-danger err-jenis"></small>
          <div class="form-group">
            <label for="en_jml">Nominal :</label>
            <input type="number" class="form-control" id="en_jml" name="en_jml">
            <small class="text-danger err-jml"></small>
          </div>
          <div class="form-group">
            <label for="en_nama">Keterangan :</label>
            <input type="text" class="form-control" id="en_ket" name="en_ket">
            <small class="text-danger err-ket"></small>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary" id="btn_create">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End: Modal create -->

<?= $this->endSection(); ?>

<?= $this->section("script") ?>
<!-- Required datatable js -->
<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#tabel_transaksi').DataTable({
      "ajax": {
        "url": '<?= base_url("bismillah/getAllTransaksi") ?>',
        "type": "POST",
        "dataType": "json",
        async: "true"
      }
    });
  });

  function ModalTambah() {
    $('#create').modal('show');
  }

  function fetchData(id) {
    // alert(id);
    $.ajax({
      url: "<?= base_url(); ?>bismillah/selectKategori",
      method: "post",

      data: {
        id_jenis: id
      },
      success: function(respon) {
        // console.log(respon);
        $('#en_kat').html(respon.status);
      },
    });
  }

  function Create() {
    $.ajax({
      url: "<?= base_url(); ?>bismillah/createTransaksi",
      type: "post",
      dataType: "json",
      data: $('#c_transaksi').serialize(),
      beforeSend: function() {
        $('#btn_create').attr('disabled');
        $("#btn_create").html('<i class="fa fa-spin fa-spinner"></i> Loading...');
      },
      success: function(respon) {
        if (respon.error) {
          if (respon.error.en_jml) {
            $('#en_jml').addClass('is-invalid');
            $('.err-jml').html(respon.error.en_jml);
          } else {
            $('#en_jml').removeClass('is-invalid');
            $('.err-jml').html('');
          }

        } else {
          if (respon.status) {
            $('#create').modal('hide');
            Swal.fire({
              icon: 'success',
              text: respon.msg,
            }).then(function() {
              $('#tabel_transaksi').DataTable().ajax.reload(null, false).draw(false);
              $('#c_transaksi')[0].reset();
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
</script>
<?= $this->endSection(); ?>