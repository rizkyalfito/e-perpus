<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Data Buku
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
            <li class="active">Data Buku</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Data Buku</h3>
                        <div class="form-group m-b-2 text-right" style="margin-top: -20px; margin-bottom: -5px;">
                            <button type="button" onclick="tambahBuku()" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Buku</button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- Ganti dari baris <div class="box-body table-responsive"> sampai </table> -->
<div class="box-body table-responsive">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th>Buku Baik</th>
                <th>Buku Rusak</th>
                <th>Jumlah Buku</th>
                <th>Barcode Units</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../../config/koneksi.php";

            $no = 1;
            $query = mysqli_query($koneksi, "SELECT * FROM buku");
            while ($row = mysqli_fetch_assoc($query)) {
                $unit_query = mysqli_query($koneksi, "SELECT barcode, kondisi, status FROM buku_unit WHERE id_buku = " . $row['id_buku']);
                $units = [];
                while ($unit = mysqli_fetch_assoc($unit_query)) {
                    $units[] = $unit;
                }
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['judul_buku']; ?></td>
                    <td><?= $row['pengarang']; ?></td>
                    <td><?= $row['penerbit_buku']; ?></td>
                    <td><?= $row['j_buku_baik']; ?></td>
                    <td><?= $row['j_buku_rusak']; ?></td>
                    <td><?php echo $row['j_buku_rusak'] + $row['j_buku_baik']; ?></td>
                    <td>
                        <?php if (count($units) > 0): ?>
                        <ul style="list-style-type:none; padding-left: 0; max-height: 150px; overflow-y: auto; margin: 0;">
                            <?php foreach ($units as $unit) : ?>
                                <li style="font-size: 12px; line-height: 1.2; margin-bottom: 2px;">
                                    <strong><?= htmlspecialchars($unit['barcode']); ?></strong> - 
                                    <?= ucfirst(htmlspecialchars($unit['kondisi'])); ?> - 
                                    <em><?= $unit['status'] == 'tersedia' ? '<span style="color:green;">Tersedia</span>' : '<span style="color:red;">Dipinjam</span>'; ?></em>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                            <span>Tidak ada unit buku</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="#" onclick="lihatBarcodeUnits(<?= $row['id_buku']; ?>, '<?= addslashes($row['judul_buku']); ?>', '<?= addslashes($row['pengarang']); ?>', '<?= $row['isbn']; ?>')" class="btn btn-success btn-sm" title="Lihat Barcode Units"><i class="fa fa-barcode"></i></a>
                        <a href="#" data-target="#modalEditBuku<?= $row['id_buku']; ?>" data-toggle="modal" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                        <a href="pages/function/Buku.php?act=hapus&id=<?= $row['id_buku']; ?>" class="btn btn-danger btn-sm btn-del"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>

                <!-- Modal Edit tetap di sini seperti semula -->
                <div class="modal fade" id="modalEditBuku<?= $row['id_buku']; ?>">
                    <!-- Modal content sama seperti sebelumnya -->
                </div>
            <?php } ?>
        </tbody>
    </table>
</div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="modalTambahBuku">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Tambah Buku</h4>
            </div>
            <form action="pages/function/Buku.php?act=tambah" enctype="multipart/form-data" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul Buku <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Masukkan judul buku" name="judulBuku" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori Buku <small style="color: red;">* Wajib diisi</small></label>
                        <select class="form-control" name="kategoriBuku" required>
                            <option value="" disabled selected>-- Pilih kategori buku --</option>
                            <?php
                            include "../../config/koneksi.php";
                            $sql = mysqli_query($koneksi, "SELECT * FROM kategori");
                            while ($data = mysqli_fetch_array($sql)) {
                            ?>
                                <option value="<?= $data['nama_kategori']; ?>"> <?= $data['nama_kategori']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Penerbit Buku <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Masukkan nama penerbit" name="penerbitBuku" required>
                        <small class="text-muted">Contoh: Gramedia Pustaka Utama, Mizan, Erlangga, dll.</small>
                    </div>
                    <div class="form-group">
                        <label>Pengarang <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Masukkan nama pengarang" name="pengarang" required>
                        <small class="text-muted">Contoh: Ahmad Tohari, Pramoedya Ananta Toer, dll.</small>
                    </div>
                    <div class="form-group">
                        <label>Tahun Terbit <small style="color: red;">* Wajib diisi</small></label>
                        <input type="number" min="1900" max="<?= date('Y') + 5; ?>" class="form-control" placeholder="Contoh: <?= date('Y'); ?>" name="tahunTerbit" required>
                    </div>
                    <div class="form-group">
                        <label>ISBN <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Contoh: 978-602-8519-93-9" name="iSbn" required>
                        <small class="text-muted">Masukkan nomor ISBN lengkap dengan tanda hubung</small>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Buku Baik <small style="color: red;">* Wajib diisi</small></label>
                        <input type="number" min="0" class="form-control" placeholder="Masukkan jumlah buku baik" name="jumlahBukuBaik" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Buku Rusak <small style="color: red;">* Wajib diisi</small></label>
                        <input type="number" min="0" class="form-control" placeholder="Masukkan jumlah buku rusak" name="jumlahBukuRusak" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Barcode Units -->
<div class="modal fade" id="modalBarcodeUnits" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
                    <i class="fa fa-barcode"></i> Barcode Units Buku
                </h4>
            </div>
            <div class="modal-body">
                <!-- Informasi Buku -->
                <div class="row">
                    <div class="col-md-12">
                        <div style="border: 2px solid #3c8dbc; border-radius: 10px; padding: 15px; background-color: #f9f9f9; margin-bottom: 20px;">
                            <h4 style="color: #3c8dbc; font-weight: bold; margin-bottom: 15px;">Informasi Buku</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Judul:</strong> <span id="bukuJudulUnits"></span></p>
                                    <p><strong>Pengarang:</strong> <span id="bukuPengarangUnits"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>ISBN:</strong> <span id="bukuISBNUnits"></span></p>
                                    <p><strong>Total Units:</strong> <span id="totalUnits"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Barcode Units -->
                <div class="row">
                    <div class="col-md-12">
                        <h4 style="color: #28a745; font-weight: bold; margin-bottom: 15px;">
                            <i class="fa fa-list"></i> Daftar Barcode Per Unit
                        </h4>
                        <div id="barcodeUnitsContainer" style="max-height: 400px; overflow-y: auto;">
                            <!-- Units akan di-generate via JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <div style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background-color: #fff;">
                            <h5 style="font-weight: bold; color: #333;">
                                <i class="fa fa-print"></i> Cetak Barcode
                            </h5>
                            <div class="form-group" style="margin-top: 10px;">
                                <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
                                    <button onclick="cetakSemuaBarcode()" class="btn btn-success btn-sm">
                                        <i class="fa fa-print"></i> Cetak Semua Barcode
                                    </button>
                                    <button onclick="cetakBarcodeSelected()" class="btn btn-primary btn-sm">
                                        <i class="fa fa-print"></i> Cetak Barcode Terpilih
                                    </button>
                                    <button onclick="downloadSemuaBarcode()" class="btn btn-info btn-sm">
                                        <i class="fa fa-download"></i> Download Semua
                                    </button>
                                </div>
                                <small class="text-muted">Pilih unit yang ingin dicetak dengan mencentang checkbox</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentBookUnits = [];

function tambahBuku() {
    $('#modalTambahBuku').modal('show');
}

function lihatBarcodeUnits(id_buku, judul, pengarang, isbn) {
    // Set informasi buku
    document.getElementById('bukuJudulUnits').textContent = judul;
    document.getElementById('bukuPengarangUnits').textContent = pengarang;
    document.getElementById('bukuISBNUnits').textContent = isbn;
    
    // Show loading
    document.getElementById('barcodeUnitsContainer').innerHTML = '<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat data units...</div>';
    $('#modalBarcodeUnits').modal('show');
    
    // Fetch units data via AJAX
    fetch(`pages/function/Buku.php?act=get_units&id_buku=${id_buku}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Units data received:', data);
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            currentBookUnits = data;
            document.getElementById('totalUnits').textContent = data.length;
            
            // Generate barcode units display
            generateBarcodeUnitsDisplay(data, judul);
        })
        .catch(error => {
            console.error('Error fetching units:', error);
            
            // Show error message dengan opsi migrasi
            document.getElementById('barcodeUnitsContainer').innerHTML = `
                <div class="alert alert-warning">
                    <h5><i class="fa fa-exclamation-triangle"></i> Data Units Tidak Ditemukan</h5>
                    <p>Buku ini belum memiliki data units barcode. Hal ini terjadi karena buku ditambahkan sebelum sistem barcode per unit diimplementasikan.</p>
                    <hr>
                    <p><strong>Solusi:</strong></p>
                    <ol>
                        <li>Jalankan script migrasi untuk membuat units dari data buku existing</li>
                        <li>Atau edit buku ini untuk regenerate units</li>
                    </ol>
                    <div style="margin-top: 15px;">
                        <a href="pages/function/migrate_existing_books.php" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fa fa-database"></i> Jalankan Migrasi
                        </a>
                        <button onclick="regenerateUnitsForBook(${id_buku})" class="btn btn-success btn-sm">
                            <i class="fa fa-refresh"></i> Regenerate Units Buku Ini
                        </button>
                    </div>
                </div>
            `;
            
            document.getElementById('totalUnits').textContent = '0';
            currentBookUnits = [];
        });
}

function generateBarcodeUnitsDisplay(units, judul) {
    const container = document.getElementById('barcodeUnitsContainer');
    container.innerHTML = '';
    
    if (units.length === 0) {
        container.innerHTML = '<p class="text-center">Tidak ada unit buku tersedia</p>';
        return;
    }
    
    units.forEach((unit, index) => {
        const unitDiv = document.createElement('div');
        unitDiv.className = 'barcode-unit-item';
        unitDiv.style.cssText = `
            border: 1px solid #ddd; 
            border-radius: 8px; 
            padding: 15px; 
            margin-bottom: 10px; 
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        `;
        
        const statusColor = unit.status === 'tersedia' ? '#28a745' : '#dc3545';
        const kondisiColor = unit.kondisi === 'baik' ? '#007bff' : '#ffc107';
        
        unitDiv.innerHTML = `
            <div style="display: flex; align-items: center;">
                <input type="checkbox" class="unit-checkbox" value="${unit.barcode}" style="margin-right: 15px;">
                <div>
                    <h6 style="margin: 0; font-weight: bold; color: #333;">${unit.barcode}</h6>
                    <div style="margin-top: 5px;">
                        <span style="background-color: ${kondisiColor}; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-right: 8px;">
                            ${unit.kondisi.toUpperCase()}
                        </span>
                        <span style="background-color: ${statusColor}; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px;">
                            ${unit.status.toUpperCase()}
                        </span>
                    </div>
                </div>
            </div>
            <div style="text-align: center;">
                <img src="https://barcode.tec-it.com/barcode.ashx?data=${unit.barcode}&code=Code128&translate-esc=true&width=120&height=30" 
                     alt="Barcode ${unit.barcode}" style="display: block; margin-bottom: 8px;">
                <div>
                    <button onclick="cetakSingleBarcode('${unit.barcode}', '${judul}')" class="btn btn-xs btn-success" style="margin-right: 5px;">
                        <i class="fa fa-print"></i>
                    </button>
                    <button onclick="downloadSingleBarcode('${unit.barcode}', '${judul}')" class="btn btn-xs btn-info">
                        <i class="fa fa-download"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(unitDiv);
    });
    
    // Add select all checkbox
    const selectAllDiv = document.createElement('div');
    selectAllDiv.style.cssText = 'margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px;';
    selectAllDiv.innerHTML = `
        <label style="margin: 0; font-weight: normal;">
            <input type="checkbox" id="selectAllUnits" onchange="toggleSelectAll()" style="margin-right: 8px;">
            Pilih Semua Units
        </label>
    `;
    container.insertBefore(selectAllDiv, container.firstChild);
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAllUnits');
    const checkboxes = document.querySelectorAll('.unit-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function cetakSingleBarcode(barcode, judul) {
    cetakBarcodeLabel([{barcode: barcode}], judul);
}

function cetakSemuaBarcode() {
    const judul = document.getElementById('bukuJudulUnits').textContent;
    cetakBarcodeLabel(currentBookUnits, judul);
}

function cetakBarcodeSelected() {
    const checkedBoxes = document.querySelectorAll('.unit-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Pilih minimal satu unit untuk dicetak');
        return;
    }
    
    const selectedUnits = [];
    checkedBoxes.forEach(checkbox => {
        const unit = currentBookUnits.find(u => u.barcode === checkbox.value);
        if (unit) selectedUnits.push(unit);
    });
    
    const judul = document.getElementById('bukuJudulUnits').textContent;
    cetakBarcodeLabel(selectedUnits, judul);
}

function cetakBarcodeLabel(units, judul) {
    const pengarang = document.getElementById('bukuPengarangUnits').textContent;
    
    let content = `
        <html>
        <head>
            <title>Label Barcode Units</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 10px;
                    background: white;
                }
                .label {
                    width: 2.5in;
                    height: 1in;
                    border: 1px solid #000;
                    padding: 5px;
                    margin: 5px;
                    display: inline-block;
                    text-align: center;
                    vertical-align: top;
                    page-break-inside: avoid;
                }
                .label h6 {
                    font-size: 9px;
                    margin: 2px 0;
                    font-weight: bold;
                }
                .label p {
                    font-size: 7px;
                    margin: 1px 0;
                }
                .label img {
                    width: 120px;
                    height: 25px;
                }
                .status-badge {
                    font-size: 6px;
                    padding: 1px 4px;
                    border-radius: 8px;
                    color: white;
                    margin: 0 2px;
                }
                .kondisi-baik { background-color: #007bff; }
                .kondisi-rusak { background-color: #ffc107; color: #000; }
                .status-tersedia { background-color: #28a745; }
                .status-dipinjam { background-color: #dc3545; }
                @media print {
                    body { margin: 0; }
                    .label { 
                        margin: 2px;
                        border: 1px solid #000;
                    }
                }
            </style>
        </head>
        <body>
    `;
    
    units.forEach(unit => {
        const barcodeImageUrl = `https://barcode.tec-it.com/barcode.ashx?data=${unit.barcode}&code=Code128&translate-esc=true&width=200&height=50&dpi=300`;
        
        content += `
            <div class="label">
                <h6>${judul && judul.length > 25 ? judul.substring(0, 25) + '...' : judul || 'Judul Tidak Tersedia'}</h6>
                <p>Pengarang: ${pengarang || 'Tidak Diketahui'}</p>
                <img src="${barcodeImageUrl}" alt="Barcode ${unit.barcode}" onerror="this.alt='Barcode: ${unit.barcode}';">
                <p><strong>${unit.barcode}</strong></p>
                <div>
                    <span class="status-badge kondisi-${unit.kondisi}">${unit.kondisi.toUpperCase()}</span>
                    <span class="status-badge status-${unit.status}">${unit.status.toUpperCase()}</span>
                </div>
            </div>
        `;
    });
    
    content += `
        </body>
        </html>
    `;
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    printWindow.document.write(content);
    printWindow.document.close();
    
    // Tunggu gambar barcode dimuat sebelum print
    printWindow.onload = function() {
        setTimeout(function() {
            printWindow.focus();
            printWindow.print();
        }, 1000);
    };
}

function downloadSingleBarcode(barcode, judul) {
    const barcodeUrl = `https://barcode.tec-it.com/barcode.ashx?data=${barcode}&code=Code128&translate-esc=true&width=400&height=100&format=png&download=true`;
    
    const link = document.createElement('a');
    link.href = barcodeUrl;
    link.download = `barcode_${judul.replace(/[^a-zA-Z0-9]/g, '_')}_${barcode}.png`;
    link.setAttribute('target', '_blank');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function downloadSemuaBarcode() {
    const judul = document.getElementById('bukuJudulUnits').textContent;
    
    currentBookUnits.forEach((unit, index) => {
        setTimeout(() => {
            downloadSingleBarcode(unit.barcode, judul);
        }, index * 500); // Delay 500ms between downloads
    });
}

function regenerateUnitsForBook(id_buku) {
    if (!confirm('Regenerate units untuk buku ini? Ini akan menghapus semua units existing (jika ada) dan membuat yang baru berdasarkan jumlah buku.')) {
        return;
    }
    
    // Show loading
    document.getElementById('barcodeUnitsContainer').innerHTML = '<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Regenerating units...</div>';
    
    fetch(`pages/function/Buku.php?act=regenerate_units&id_buku=${id_buku}`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Units berhasil di-regenerate!');
            // Reload units data
            lihatBarcodeUnits(id_buku, 
                document.getElementById('bukuJudulUnits').textContent,
                document.getElementById('bukuPengarangUnits').textContent,
                document.getElementById('bukuISBNUnits').textContent
            );
        } else {
            alert('Error: ' + (data.error || 'Gagal regenerate units'));
        }
    })
    .catch(error => {
        console.error('Error regenerating units:', error);
        alert('Gagal regenerate units');
    });
}

// Form validation untuk memastikan pengarang dan penerbit tidak kosong
document.addEventListener('DOMContentLoaded', function() {
    // Validasi untuk form tambah buku
    const formTambah = document.querySelector('#modalTambahBuku form');
    if (formTambah) {
        formTambah.addEventListener('submit', function(e) {
            const pengarang = this.querySelector('input[name="pengarang"]').value.trim();
            const penerbit = this.querySelector('input[name="penerbitBuku"]').value.trim();
            
            if (pengarang === '') {
                e.preventDefault();
                alert('Nama pengarang wajib diisi!');
                this.querySelector('input[name="pengarang"]').focus();
                return false;
            }
            
            if (penerbit === '') {
                e.preventDefault();
                alert('Nama penerbit wajib diisi!');
                this.querySelector('input[name="penerbitBuku"]').focus();
                return false;
            }
        });
    }
    
    // Validasi untuk semua form edit buku
    const formsEdit = document.querySelectorAll('form[action*="act=edit"]');
    formsEdit.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const pengarang = this.querySelector('input[name="pengarang"]').value.trim();
            const penerbit = this.querySelector('input[name="penerbitBuku"]').value.trim();
            
            if (pengarang === '') {
                e.preventDefault();
                alert('Nama pengarang wajib diisi!');
                this.querySelector('input[name="pengarang"]').focus();
                return false;
            }
            
            if (penerbit === '') {
                e.preventDefault();
                alert('Nama penerbit wajib diisi!');
                this.querySelector('input[name="penerbitBuku"]').focus();
                return false;
            }
        });
    });
});
</script>

<!-- jQuery 3 -->
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/dist/js/sweetalert.min.js"></script>

<!-- Pesan Berhasil Edit -->
<script>
    <?php
    if (isset($_SESSION['berhasil']) && $_SESSION['berhasil'] <> '') {
        echo "swal({
            icon: 'success',
            title: 'Berhasil',
            text: '$_SESSION[berhasil]'
        })";
    }
    $_SESSION['berhasil'] = '';
    ?>
</script>

<!-- Notif Gagal -->
<script>
    <?php
    if (isset($_SESSION['gagal']) && $_SESSION['gagal'] <> '') {
        echo "swal({
                icon: 'error',
                title: 'Gagal',
                text: '$_SESSION[gagal]'
              })";
    }
    $_SESSION['gagal'] = '';
    ?>
</script>

<!-- Swal Hapus Data -->
<script>
    $('.btn-del').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href')

        swal({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Apakah anda yakin ingin menghapus data buku ini ?',
                buttons: true,
                dangerMode: true,
                buttons: ['Tidak, Batalkan !', 'Iya, Hapus']
            })
            .then((willDelete) => {
                if (willDelete) {
                    document.location.href = href;
                } else {
                    swal({
                        icon: 'error',
                        title: 'Dibatalkan',
                        text: 'Data buku tersebut aman !'
                    })
                }
            });
    })
</script>