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
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Sampul</th>
                                    <th>Judul Buku</th>
                                    <th>Klasifikasi</th>
                                    <th>Pengarang</th>
                                    <th>Penerbit</th>
                                    <th>Buku Baik</th>
                                    <th>Buku Rusak</th>
                                    <th>Total Buku</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "../../config/koneksi.php";

                                $no = 1;
                                $query = mysqli_query($koneksi, "SELECT * FROM buku");
                                while ($row = mysqli_fetch_assoc($query)) {
                                    // Ambil unit buku dan barcode-nya
                                    $unit_query = mysqli_query($koneksi, "SELECT barcode, kondisi, status FROM buku_unit WHERE id_buku = " . $row['id_buku']);
                                    $units = [];
                                    while ($unit = mysqli_fetch_assoc($unit_query)) {
                                        $units[] = $unit;
                                    }
                                    
                                    // Handle foto sampul - tidak pakai default
                                    $foto_sampul = null;
                                    $path_foto = null;
                                    if (!empty($row['foto_sampul'])) {
                                        $foto_sampul = $row['foto_sampul'];
                                        $path_foto = "assets/img/covers/" . $foto_sampul;
                                    }
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        
                                        <!-- Kolom Sampul -->
                                        <td style="text-align: center;">
                                            <?php if ($path_foto && !empty($foto_sampul)): ?>
                                                <div style="width: 60px; height: 80px; display: inline-block; position: relative;">
                                                    <img src="<?= $path_foto; ?>" 
                                                         alt="Sampul <?= htmlspecialchars($row['judul_buku']); ?>" 
                                                         style="width: 60px; height: 80px; object-fit: cover; border-radius: 5px; cursor: pointer;"
                                                         onclick="previewCover('<?= $path_foto; ?>', '<?= htmlspecialchars($row['judul_buku']); ?>')"
                                                         onerror="handleImageError(this, '<?= htmlspecialchars($row['judul_buku']); ?>')">
                                                </div>
                                            <?php else: ?>
                                                <div style="width: 60px; height: 80px; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; border: 1px dashed #ccc; border-radius: 5px; color: #999; font-size: 10px; text-align: center;">
                                                    Tidak ada<br>sampul
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td><?= htmlspecialchars($row['judul_buku']); ?></td>
                                        <td><?= !empty($row['klasifikasi_buku']) ? htmlspecialchars($row['klasifikasi_buku']) : '<em>Belum Diklasifikasi</em>'; ?></td>
                                        <td><?= htmlspecialchars($row['pengarang']); ?></td>
                                        <td><?= htmlspecialchars($row['penerbit_buku']); ?></td>
                                        <td><?= $row['j_buku_baik']; ?></td>
                                        <td><?= $row['j_buku_rusak']; ?></td>
                                        <td><?= $row['j_buku_baik'] + $row['j_buku_rusak']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
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

<!-- Modal Preview Cover -->
<div class="modal fade" id="modalPreviewCover" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
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

<script>
// Function untuk preview cover dalam modal
function previewCover(imageSrc, title) {
    if (!imageSrc || imageSrc === '') {
        alert('Tidak ada sampul untuk ditampilkan');
        return;
    }
    
    const previewImage = document.getElementById('previewCoverImage');
    const previewTitle = document.getElementById('previewCoverTitle');
    
    previewImage.src = imageSrc;
    previewTitle.textContent = 'Sampul: ' + title;
    
    // Handle image load error
    previewImage.onerror = function() {
        previewTitle.textContent = 'Sampul tidak dapat dimuat: ' + title;
        this.alt = 'Gambar tidak dapat dimuat';
    };
    
    $('#modalPreviewCover').modal('show');
}

// Function untuk handle error gambar
function handleImageError(img, title) {
    // Hide the image and replace with placeholder
    img.style.display = 'none';
    
    // Create placeholder div
    const placeholder = document.createElement('div');
    placeholder.style.cssText = 'width: 60px; height: 80px; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; border: 1px dashed #ccc; border-radius: 5px; color: #999; font-size: 10px; text-align: center;';
    placeholder.innerHTML = 'Sampul<br>hilang';
    placeholder.title = 'Sampul untuk "' + title + '" tidak dapat dimuat';
    
    // Replace the image with placeholder
    img.parentNode.replaceChild(placeholder, img);
}
</script>