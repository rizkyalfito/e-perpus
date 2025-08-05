<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Data Buku
            <small>
                <script>
                    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jum&#39;at','Sabtu'];
                    const date = new Date();
                    document.write(`${days[date.getDay()]}, ${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`);
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
                    <div class="box-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th><th>Sampul</th><th>Judul Buku</th><th>Klasifikasi</th><th>Pengarang</th>
                                    <th>Penerbit</th><th>Buku Baik</th><th>Buku Rusak</th><th>Jumlah Buku</th><th>Barcode Units</th><th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "../../config/koneksi.php";
                                $no = 1;
                                $query = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY judul_buku ASC");
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $unit_query = mysqli_query($koneksi, "SELECT barcode, kondisi, status FROM buku_unit WHERE id_buku = " . $row['id_buku']);
                                    $units = [];
                                    while ($unit = mysqli_fetch_assoc($unit_query)) {
                                        $units[] = $unit;
                                    }
                                    $foto_sampul = $row['foto_sampul'] ? $row['foto_sampul'] : 'default-cover.png';
                                    $path_foto = "assets/img/covers/" . $foto_sampul;
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td style="text-align: center;">
                                            <img src="<?= $path_foto; ?>" alt="Sampul <?= htmlspecialchars($row['judul_buku']); ?>" 
                                                 style="width: 60px; height: 80px; object-fit: cover; border-radius: 5px; cursor: pointer;"
                                                 onclick="previewCover('<?= $path_foto; ?>', '<?= htmlspecialchars($row['judul_buku']); ?>')"
                                                 onerror="this.src='assets/img/covers/default-cover.png'">
                                        </td>
                                        <td><?= htmlspecialchars($row['judul_buku']); ?></td>
                                        <td><?= !empty($row['klasifikasi_buku']) ? htmlspecialchars($row['klasifikasi_buku']) : '<em>Belum Diklasifikasi</em>'; ?></td>
                                        <td><?= htmlspecialchars($row['pengarang']); ?></td>
                                        <td><?= htmlspecialchars($row['penerbit_buku']); ?></td>
                                        <td><?= $row['j_buku_baik']; ?></td>
                                        <td><?= $row['j_buku_rusak']; ?></td>
                                        <td><?= $row['j_buku_rusak'] + $row['j_buku_baik']; ?></td>
                                        <td>
                                            <?php if (count($units) > 0): ?>
                                            <ul style="list-style-type:none; padding-left: 0; max-height: 150px; overflow-y: auto; margin: 0;">
                                                <?php foreach ($units as $unit) : ?>
                                                    <li style="font-size: 12px; line-height: 1.2; margin-bottom: 2px;">
                                                        <strong><?= htmlspecialchars($unit['barcode']); ?></strong> - 
                                                        <?= ucfirst(htmlspecialchars($unit['kondisi'])); ?> - 
                                                        <?= $unit['status'] == 'tersedia' ? '<span style="color:green;">Tersedia</span>' : '<span style="color:red;">Dipinjam</span>'; ?>
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

                                    <!-- Modal Edit Buku -->
                                    <div class="modal fade" id="modalEditBuku<?= $row['id_buku']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content" style="border-radius: 5px;">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Edit Buku</h4>
                                                </div>
                                                <form action="pages/function/Buku.php?act=edit" enctype="multipart/form-data" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_buku" value="<?= $row['id_buku']; ?>">
                                                        
                                                        <!-- Current Cover Preview -->
                                                        <div class="form-group">
                                                            <label>Foto Sampul Saat Ini</label>
                                                            <div style="text-align: center; margin-bottom: 10px;">
                                                                <img src="<?= $path_foto; ?>" alt="Current Cover" 
                                                                     style="max-width: 150px; max-height: 200px; object-fit: cover; border: 2px solid #ddd; border-radius: 8px;"
                                                                     onerror="this.src='assets/img/covers/default-cover.png'">
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- New Cover Upload -->
                                                        <div class="form-group">
                                                            <label>Ubah Foto Sampul</label>
                                                            <input type="file" class="form-control" name="fotoSampul" accept="image/*" onchange="previewNewCover(this, 'preview-edit-<?= $row['id_buku']; ?>')">
                                                            <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB. Kosongkan jika tidak ingin mengubah.</small>
                                                            <div style="text-align: center; margin-top: 10px;">
                                                                <img id="preview-edit-<?= $row['id_buku']; ?>" style="max-width: 150px; max-height: 200px; object-fit: cover; border: 2px solid #ddd; border-radius: 8px; display: none;">
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Form Fields -->
                                                        <?php
                                                        $fields = [
                                                            ['judulBuku', 'Judul Buku', 'text', $row['judul_buku'], true],
                                                            ['klasifikasiBuku', 'Klasifikasi Buku', 'text', $row['klasifikasi_buku'], false],
                                                            ['penerbitBuku', 'Penerbit Buku', 'text', $row['penerbit_buku'], true],
                                                            ['pengarang', 'Pengarang', 'text', $row['pengarang'], true],
                                                            ['tahunTerbit', 'Tahun Terbit', 'number', $row['tahun_terbit'], true],
                                                            ['iSbn', 'ISBN', 'text', $row['isbn'], true],
                                                            ['jumlahBukuBaik', 'Jumlah Buku Baik', 'number', $row['j_buku_baik'], true],
                                                            ['jumlahBukuRusak', 'Jumlah Buku Rusak', 'number', $row['j_buku_rusak'], true]
                                                        ];
                                                        
                                                        foreach ($fields as $field) {
                                                            $required = $field[4] ? 'required' : '';
                                                            $requiredText = $field[4] ? '<small style="color: red;">* Wajib diisi</small>' : '';
                                                            $value = htmlspecialchars($field[3]);
                                                            
                                                            if ($field[2] == 'number') {
                                                                $extra = $field[0] == 'tahunTerbit' ? 'min="1900" max="' . (date('Y') + 5) . '"' : 'min="0"';
                                                                echo "<div class='form-group'>
                                                                    <label>{$field[1]} {$requiredText}</label>
                                                                    <input type='number' class='form-control' name='{$field[0]}' value='{$value}' {$extra} {$required}>
                                                                </div>";
                                                            } else {
                                                                echo "<div class='form-group'>
                                                                    <label>{$field[1]} {$requiredText}</label>
                                                                    <input type='text' class='form-control' name='{$field[0]}' value='{$value}' {$required}>
                                                                </div>";
                                                            }
                                                        }
                                                        ?>
                                                        
                                                        <!-- Category Select -->
                                                        <div class="form-group">
                                                            <label>Kategori Buku <small style="color: red;">* Wajib diisi</small></label>
                                                            <select class="form-control" name="kategoriBuku" required>
                                                                <option value="">-- Pilih kategori buku --</option>
                                                                <?php
                                                                $sql_kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
                                                                while ($data_kategori = mysqli_fetch_array($sql_kategori)) {
                                                                    $selected = ($data_kategori['nama_kategori'] == $row['kategori_buku']) ? 'selected' : '';
                                                                    echo "<option value='{$data_kategori['nama_kategori']}' {$selected}>{$data_kategori['nama_kategori']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="modalTambahBuku">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Tambah Buku</h4>
            </div>
            <form action="pages/function/Buku.php?act=tambah" enctype="multipart/form-data" method="POST">
                <div class="modal-body">
                    <!-- Upload Cover -->
                    <div class="form-group">
                        <label>Foto Sampul Buku</label>
                        <input type="file" class="form-control" name="fotoSampul" accept="image/*" onchange="previewNewCover(this, 'preview-tambah')">
                        <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB. Opsional.</small>
                        <div style="text-align: center; margin-top: 10px;">
                            <img id="preview-tambah" style="max-width: 150px; max-height: 200px; object-fit: cover; border: 2px solid #ddd; border-radius: 8px; display: none;">
                        </div>
                    </div>
                    
                    <!-- Form Fields for Add -->
                    <?php
                    $addFields = [
                        ['judulBuku', 'Judul Buku', 'text', 'Masukkan judul buku', true],
                        ['klasifikasiBuku', 'Klasifikasi Buku', 'text', 'Masukkan klasifikasi buku (contoh: 001.1, 300.2, dll)', false],
                        ['penerbitBuku', 'Penerbit Buku', 'text', 'Masukkan nama penerbit', true],
                        ['pengarang', 'Pengarang', 'text', 'Masukkan nama pengarang', true],
                        ['tahunTerbit', 'Tahun Terbit', 'number', 'Contoh: ' . date('Y'), true],
                        ['iSbn', 'ISBN', 'text', 'Contoh: 978-602-8519-93-9', true],
                        ['jumlahBukuBaik', 'Jumlah Buku Baik', 'number', 'Masukkan jumlah buku baik', true],
                        ['jumlahBukuRusak', 'Jumlah Buku Rusak', 'number', 'Masukkan jumlah buku rusak', true]
                    ];
                    
                    foreach ($addFields as $field) {
                        $required = $field[4] ? 'required' : '';
                        $requiredText = $field[4] ? '<small style="color: red;">* Wajib diisi</small>' : '';
                        $placeholder = $field[3];
                        
                        if ($field[2] == 'number') {
                            $extra = $field[0] == 'tahunTerbit' ? 'min="1900" max="' . (date('Y') + 5) . '"' : 'min="0"';
                            echo "<div class='form-group'>
                                <label>{$field[1]} {$requiredText}</label>
                                <input type='number' class='form-control' name='{$field[0]}' placeholder='{$placeholder}' {$extra} {$required}>
                            </div>";
                        } else {
                            echo "<div class='form-group'>
                                <label>{$field[1]} {$requiredText}</label>
                                <input type='text' class='form-control' name='{$field[0]}' placeholder='{$placeholder}' {$required}>
                            </div>";
                        }
                    }
                    ?>
                    
                    <!-- Category Select for Add -->
                    <div class="form-group">
                        <label>Kategori Buku <small style="color: red;">* Wajib diisi</small></label>
                        <select class="form-control" name="kategoriBuku" required>
                            <option value="">-- Pilih kategori buku --</option>
                            <?php
                            include "../../config/koneksi.php";
                            $sql = mysqli_query($koneksi, "SELECT * FROM kategori");
                            while ($data = mysqli_fetch_array($sql)) {
                                echo "<option value='{$data['nama_kategori']}'>{$data['nama_kategori']}</option>";
                            }
                            ?>
                        </select>
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

<!-- Modal Preview Cover -->
<div class="modal fade" id="modalPreviewCover">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="previewCoverTitle">Preview Sampul Buku</h4>
            </div>
            <div class="modal-body" style="text-align: center;">
                <img id="previewCoverImage" style="max-width: 100%; max-height: 500px; object-fit: contain;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Barcode Units -->
<div class="modal fade" id="modalBarcodeUnits">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
                    <i class="fa fa-barcode"></i> Barcode Units Buku
                </h4>
            </div>
            <div class="modal-body">
                <!-- Book Info -->
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

                <!-- Barcode Units List -->
                <div class="row">
                    <div class="col-md-12">
                        <h4 style="color: #28a745; font-weight: bold; margin-bottom: 15px;">
                            <i class="fa fa-list"></i> Daftar Barcode Per Unit
                        </h4>
                        <div id="barcodeUnitsContainer" style="max-height: 400px; overflow-y: auto;">
                            <!-- Units will be generated via JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Print Actions -->
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <div style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background-color: #fff;">
                            <h5 style="font-weight: bold; color: #333;"><i class="fa fa-print"></i> Cetak Barcode</h5>
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

function previewNewCover(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

function previewCover(imageSrc, title) {
    document.getElementById('previewCoverImage').src = imageSrc;
    document.getElementById('previewCoverTitle').textContent = 'Sampul: ' + title;
    $('#modalPreviewCover').modal('show');
}

function lihatBarcodeUnits(id_buku, judul, pengarang, isbn) {
    document.getElementById('bukuJudulUnits').textContent = judul;
    document.getElementById('bukuPengarangUnits').textContent = pengarang;
    document.getElementById('bukuISBNUnits').textContent = isbn;
    
    document.getElementById('barcodeUnitsContainer').innerHTML = '<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat data units...</div>';
    $('#modalBarcodeUnits').modal('show');
    
    fetch(`pages/function/Buku.php?act=get_units&id_buku=${id_buku}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) throw new Error(data.error);
            currentBookUnits = data;
            document.getElementById('totalUnits').textContent = data.length;
            generateBarcodeUnitsDisplay(data, judul);
        })
        .catch(error => {
            console.error('Error fetching units:', error);
            document.getElementById('barcodeUnitsContainer').innerHTML = `
                <div class="alert alert-warning">
                    <h5><i class="fa fa-exclamation-triangle"></i> Data Units Tidak Ditemukan</h5>
                    <p>Buku ini belum memiliki data units barcode.</p>
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
    
    // Add select all checkbox first
    const selectAllDiv = document.createElement('div');
    selectAllDiv.style.cssText = 'margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px;';
    selectAllDiv.innerHTML = `
        <label style="margin: 0; font-weight: normal;">
            <input type="checkbox" id="selectAllUnits" onchange="toggleSelectAll()" style="margin-right: 8px;">
            Pilih Semua Units
        </label>
    `;
    container.appendChild(selectAllDiv);
    
    units.forEach((unit, index) => {
        const unitDiv = document.createElement('div');
        unitDiv.className = 'barcode-unit-item';
        unitDiv.style.cssText = `border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 10px; 
                                background-color: #fff; display: flex; align-items: center; justify-content: space-between;`;
        
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
                body { font-family: Arial, sans-serif; margin: 10px; background: white; }
                .label {
                    width: 2.5in; height: 1in; border: 1px solid #000; padding: 5px; margin: 5px;
                    display: inline-block; text-align: center; vertical-align: top; page-break-inside: avoid;
                }
                .label h6 { font-size: 9px; margin: 2px 0; font-weight: bold; }
                .label p { font-size: 7px; margin: 1px 0; }
                .label img { width: 120px; height: 25px; }
                .status-badge { font-size: 6px; padding: 1px 4px; border-radius: 8px; color: white; margin: 0 2px; }
                .kondisi-baik { background-color: #007bff; }
                .kondisi-rusak { background-color: #ffc107; color: #000; }
                .status-tersedia { background-color: #28a745; }
                .status-dipinjam { background-color: #dc3545; }
                @media print { body { margin: 0; } .label { margin: 2px; border: 1px solid #000; } }
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
    
    content += '</body></html>';
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    printWindow.document.write(content);
    printWindow.document.close();
    
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
        }, index * 500);
    });
}

function regenerateUnitsForBook(id_buku) {
    if (!confirm('Regenerate units untuk buku ini? Ini akan menghapus semua units existing (jika ada) dan membuat yang baru berdasarkan jumlah buku.')) {
        return;
    }
    
    document.getElementById('barcodeUnitsContainer').innerHTML = '<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Regenerating units...</div>';
    
    fetch(`pages/function/Buku.php?act=regenerate_units&id_buku=${id_buku}`, { method: 'POST' })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Units berhasil di-regenerate!');
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

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const validateForm = (form) => {
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
    };
    
    // Validate add form
    const formTambah = document.querySelector('#modalTambahBuku form');
    if (formTambah) validateForm(formTambah);
    
    // Validate all edit forms
    const formsEdit = document.querySelectorAll('form[action*="act=edit"]');
    formsEdit.forEach(validateForm);
});
</script>

<!-- jQuery and Scripts -->
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/dist/js/sweetalert.min.js"></script>

<!-- Success Message -->
<script>
    <?php
    if (isset($_SESSION['berhasil']) && $_SESSION['berhasil'] <> '') {
        echo "swal({ icon: 'success', title: 'Berhasil', text: '$_SESSION[berhasil]' })";
    }
    $_SESSION['berhasil'] = '';
    ?>
</script>

<!-- Error Message -->
<script>
    <?php
    if (isset($_SESSION['gagal']) && $_SESSION['gagal'] <> '') {
        echo "swal({ icon: 'error', title: 'Gagal', text: '$_SESSION[gagal]' })";
    }
    $_SESSION['gagal'] = '';
    ?>
</script>

<!-- Delete Confirmation -->
<script>
    $('.btn-del').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href')

        swal({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Apakah anda yakin ingin menghapus data buku ini ?',
            buttons: ['Tidak, Batalkan !', 'Iya, Hapus'],
            dangerMode: true
        })
        .then((willDelete) => {
            if (willDelete) {
                document.location.href = href;
            } else {
                swal({ icon: 'error', title: 'Dibatalkan', text: 'Data buku tersebut aman !' })
            }
        });
    })
</script>