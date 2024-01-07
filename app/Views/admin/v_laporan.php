<?= $this->extend("layout/master_app"); ?>

<?= $this->section("style"); ?>
<!-- DataTables -->
<link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<!-- Start: filter section -->
<div class="card">
  <div class="card-header">
    <span>Filter Laporan</span>
  </div>
  <div class="card-body">
    <form id="c_filter" method="post" action="Javascript:Create();">
      <div class="row">
        <div class="col-lg-4">
          <div class="form-group">
            <label for="en_mulai">Tanggal Mulai</label>
            <input type="date" class="form-control" id="en_mulai" name="en_mulai">
          </div>
        </div>
        <div class="col-lg-4">
          <div class="form-group">
            <label for="en_sampai">Sampai Tanggal</label>
            <input type="date" class="form-control" id="en_sampai" name="en_sampai">
          </div>
        </div>
        <div class="col-lg-4">
          <div class="form-group">
            <label for="en_kat">Kategori</label>
            <select class="form-control" name="en_kat" id="en_kat">
              <option value="Semua">-Semua Kategori-</option>
              <?php
              foreach ($kategori as $value) {
              ?>
                <option value="<?= $value['kategori'] ?>"><?= $value['kategori'] ?></option>
              <?php
              }
              ?>
            </select>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <button type="submit" class="btn btn-primary" id="btn_create">Tampilkan</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- End: filter section -->

<div class="mb-4"></div>

<!-- Start: table section -->
<div class="card">
  <div class="card-header">
    <span>Laporan Pemasukan dan Pengeluaran</span>
  </div>
  <div class="card-body" id="t_laporan">
  </div>
</div>
<!-- End: table section -->

<?= $this->endSection(); ?>

<?= $this->section("script") ?>
<!-- Required datatable js -->
<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {

  });

  function ModalTambah() {
    $('#create').modal('show');
  }

  function Create() {
    $.ajax({
      url: "<?= base_url(); ?>bismillah/filterLaporan",
      type: "post",
      dataType: "json",
      data: $('#c_filter').serialize(),
      success: function(respon) {
        $("#t_laporan").html(respon.status);
      },
    });
  }
</script>
<?= $this->endSection(); ?>