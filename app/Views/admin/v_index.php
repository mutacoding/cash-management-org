<?= $this->extend("layout/master_app"); ?>

<?= $this->section("style"); ?>
<!-- DataTables -->
<link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="row">

  <!-- Pemasukan Hari Ini -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
              Pemasukan Hari ini</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= "Rp " . number_format($pmsHariIni['pmsHariIni'], 0, ",", "."); ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pemasukan Bulan Ini -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
              Pemasukan Bulan ini</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= "Rp " . number_format($pmsBulanIni['pmsBulanIni'], 0, ",", "."); ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Pemasukan -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
              Total Pemasukan</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= "Rp " . number_format($totalPemasukan['totalPemasukan'], 0, ",", "."); ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total -->
  <?php
  $total = $totalPemasukan['totalPemasukan'] - $totalPengeluaran['totalPengeluaran'];
  ?>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
              Total Keseluruhan</div>
            <div class="h5 mb-0 font-weight-bold text-dark"><?= "Rp " . number_format($total, 0, ",", "."); ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pengeluaran Hari Ini -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-danger shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
              Pengeluaran Hari Ini</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= "Rp " . number_format($pngHariIni['pngHariIni'], 0, ",", "."); ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pengeluaran Bulan Ini -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-danger shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
              Pengeluaran Bulan Ini</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= "Rp " . number_format($pngBulanIni['pngBulanIni'], 0, ",", "."); ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Pengeluaran -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-danger shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
              Total Pengeluaran</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= "Rp " . number_format($totalPengeluaran['totalPengeluaran'], 0, ",", "."); ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Start -->

</div>
<?= $this->endSection(); ?>

<?= $this->section('script') ?>

<!-- Required datatable js -->
<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
  $(document).ready(function() {
    var table = $('#tabel_pesan').DataTable({
      "ajax": {
        "url": '<?= base_url("contact/getAll") ?>',
        "type": "POST",
        "dataType": "json",
        async: "true",
      }
    });
  });
</script>
<?= $this->endSection('script') ?>