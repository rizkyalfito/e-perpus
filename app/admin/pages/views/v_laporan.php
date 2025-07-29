<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Laporan Perpustakaan
            <small>
                <script type='text/javascript'>
                    var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
                    var date = new Date();
                    var day = date.getDate();
                    var month = date.getMonth();
                    var thisDay = date.getDay(),
                        thisDay = myDays[thisDay];
                    var yy = date.getYear();
                    var year = (yy < 1000) ? yy + 1900 : yy;
                    document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
                </script>
            </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Laporan Perpustakaan</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#rentang-tanggal" data-toggle="tab"><i class="fa fa-calendar-o"></i> Rentang Tanggal</a></li>
                        <li><a href="#nama-anggota" data-toggle="tab"><i class="fa fa-user"></i> Per Anggota</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- Rentang Tanggal -->
                        <div class="tab-pane active" id="rentang-tanggal">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-calendar-o"></i> Laporan Berdasarkan Rentang Tanggal</h3>
                                </div>
                                <div class="box-body">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> 
                                        <strong>Info:</strong> Laporan ini akan menampilkan semua data peminjaman dan pengembalian dalam rentang tanggal yang dipilih.
                                    </div>
                                    
                                    <form action="pages/function/Laporan.php?aksi=rentang_tanggal" method="POST" target="_blank">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Mulai <span class="text-red">*</span></label>
                                                    <div class="input-group date">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="date" class="form-control" name="tanggal_mulai" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Selesai <span class="text-red">*</span></label>
                                                    <div class="input-group date">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="date" class="form-control" name="tanggal_selesai" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Jenis Laporan</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="jenis_laporan" value="gabungan" checked>
                                                    <strong>Gabungan</strong> - Peminjaman dan Pengembalian dalam satu tabel
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="jenis_laporan" value="terpisah">
                                                    <strong>Terpisah</strong> - Peminjaman dan Pengembalian dalam tabel terpisah
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <small class="text-muted">Pilih rentang tanggal untuk melihat aktivitas peminjaman dan pengembalian</small>
                                        
                                        <div class="form-group" style="margin-top: 15px;">
                                            <button type="submit" class="btn btn-primary btn-flat btn-lg">
                                                <i class="fa fa-print"></i> Tampilkan & Cetak Laporan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Anggota -->
                        <div class="tab-pane" id="nama-anggota">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-user"></i> Laporan Berdasarkan Nama Anggota</h3>
                                </div>
                                <div class="box-body">
                                    <form action="pages/function/Laporan.php?aksi=nama_anggota" method="POST" target="_blank">
                                        <div class="form-group">
                                            <label>Nama Anggota <span class="text-red">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" class="form-control" name="nama_anggota" placeholder="Ketik nama lengkap anggota/siswa" required id="namaAnggotaInput">
                                            </div>
                                            <small class="text-muted">Masukkan nama lengkap anggota untuk melihat riwayat peminjaman</small>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-info btn-flat">
                                                <i class="fa fa-print"></i> Tampilkan & Cetak Laporan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- jQuery dan Scripts -->
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/dist/js/sweetalert.min.js"></script>

<!-- Select2 -->
<link rel="stylesheet" href="../../assets/bower_components/select2/dist/css/select2.min.css">
<script src="../../assets/bower_components/select2/dist/js/select2.full.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: "Pilih anggota...",
        allowClear: true
    });
    
    // Auto-fill nama anggota ketika dipilih dari dropdown
    $('#selectAnggota').on('change', function() {
        var selectedName = $(this).val();
        $('#namaAnggotaInput').val(selectedName);
    });
    
    // Clear dropdown ketika user mengetik manual
    $('#namaAnggotaInput').on('input', function() {
        if ($(this).val() === '') {
            $('#selectAnggota').val('').trigger('change');
        }
    });
    
    // Validasi form sebelum submit
    $('form').on('submit', function(e) {
        var form = $(this);
        var requiredFields = form.find('[required]');
        var isValid = true;
        
        // Validasi khusus untuk rentang tanggal
        if (form.find('input[name="tanggal_mulai"]').length > 0) {
            var tanggalMulai = new Date(form.find('input[name="tanggal_mulai"]').val());
            var tanggalSelesai = new Date(form.find('input[name="tanggal_selesai"]').val());
            
            if (tanggalMulai > tanggalSelesai) {
                isValid = false;
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai!');
                return false;
            }
        }
        
        requiredFields.each(function() {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass('has-error');
            } else {
                $(this).removeClass('has-error');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi!');
        }
    });
});
</script>

<!-- Custom CSS dengan Bootstrap Integration -->
<style>
/* Form Styles */
.has-error {
    border-color: #dd4b39 !important;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 6px #ce8483;
}

.form-group label {
    font-weight: 600;
    color: #333;
}

.text-red {
    color: #dd4b39;
}

.btn-flat {
    border-radius: 0;
}

/* AdminLTE Box Styles */
.small-box .inner h3 {
    font-size: 28px;
    font-weight: bold;
}

.box-header.with-border {
    border-bottom: 1px solid #f4f4f4;
}

.nav-tabs-custom > .nav-tabs > li.active {
    border-top-color: #3c8dbc;
}

/* Radio button styling */
.radio {
    margin-bottom: 10px;
}

.radio label {
    font-weight: normal;
    cursor: pointer;
}

.radio input[type="radio"] {
    margin-right: 8px;
}

/* Print Styles - Optimized for Bootstrap */
@media print {
    .no-print, .btn, .form-control, .input-group, .alert, .nav-tabs, .box-header {
        display: none !important;
    }
    
    body {
        font-size: 12px;
        line-height: 1.4;
        color: #000 !important;
        background: white !important;
    }
    
    .print-header {
        position: relative;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #000;
        page-break-inside: avoid;
    }
    
    .print-logo-left {
        position: absolute;
        left: 0;
        top: 0;
        height: 80px;
        width: 80px;
    }
    
    .print-logo-right {
        position: absolute;
        right: 0;
        top: 0;
        height: 80px;
        width: 80px;
    }
    
    .print-header-text {
        margin: 0 100px;
        text-align: center;
        padding-top: 10px;
    }
    
    .print-header-text h3 {
        margin: 0 0 10px 0;
        font-size: 18px;
        font-weight: bold;
        color: #000;
    }
    
    .print-header-text p {
        margin: 3px 0;
        font-size: 11px;
        color: #000;
    }
    
    .print-title {
        text-align: center;
        margin: 20px 0;
        font-size: 14px;
        font-weight: bold;
        color: #000;
    }
    
    .print-filter {
        text-align: center;
        margin: 10px 0;
        font-size: 12px;
        font-style: italic;
        color: #000;
    }
    
    .table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-top: 15px;
    }
    
    .table th,
    .table td {
        border: 1px solid #000 !important;
        padding: 6px 4px !important;
        font-size: 10px !important;
        line-height: 1.2;
        color: #000 !important;
        background: white !important;
    }
    
    .table th {
        background-color: #f0f0f0 !important;
        font-weight: bold !important;
        text-align: center !important;
    }
    
    .table .text-right {
        text-align: right !important;
    }
    
    .table .text-center {
        text-align: center !important;
    }
    
    .print-summary {
        margin-top: 20px;
        font-size: 11px;
        font-weight: bold;
        color: #000;
    }
    
    .print-footer {
        margin-top: 40px;
        page-break-inside: avoid;
    }
    
    .print-signature {
        float: right;
        width: 200px;
        text-align: center;
        font-size: 11px;
        color: #000;
    }
    
    .print-signature p {
        margin: 5px 0;
    }
    
    .print-signature .signature-line {
        margin-top: 50px;
        border-top: 1px solid #000;
        padding-top: 5px;
    }
    
    /* Status Colors for Print */
    .status-belum {
        color: #000 !important;
        font-weight: bold;
    }
    
    .status-sudah {
        color: #000 !important;
        font-weight: bold;
    }
    
    /* Clear floats */
    .clearfix::after {
        content: "";
        display: table;
        clear: both;
    }
}

/* Screen-only styles */
@media screen {
    .status-belum {
        color: #d9534f;
        font-weight: bold;
    }
    
    .status-sudah {
        color: #5cb85c;
        font-weight: bold;
    }
    
    .denda-amount {
        font-weight: bold;
        color: #f0ad4e;
    }
    
    .input-group-addon {
        background-color: #f8f9fa;
        border-color: #ced4da;
    }
    
    .select2-container--default .select2-selection--single {
        height: 34px;
        line-height: 34px;
        border-color: #ced4da;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .small-box .inner h3 {
        font-size: 22px;
    }
    
    .nav-tabs-custom > .nav-tabs > li {
        margin-bottom: 0;
    }
    
    .nav-tabs-custom > .nav-tabs > li > a {
        font-size: 12px;
        padding: 8px 12px;
    }
}

/* Utility classes for consistency with Bootstrap */
.font-weight-bold {
    font-weight: 700 !important;
}

.text-muted {
    color: #6c757d !important;
}

.border-0 {
    border: 0 !important;
}

.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
}
</style>