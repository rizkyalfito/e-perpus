<li class="active"><a href="#tgl-pinjam" data-toggle="tab">Formulir Peminjaman Buku</a></li>
                        <li><a href="#tgl-pengembalian" data-toggle="tab">Riwayat Peminjaman Buku</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- Font Awesome Icons -->
                        <div class="tab-pane active" id="tgl-pinjam">
                            <section id="new">
                                <?php
                                include "../../config/koneksi.php";

                                $fullname = $_SESSION['fullname'];
                                $sql = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE nama_anggota = '$fullname' AND tanggal_pengembalian = ''");
                                $hasil = mysqli_num_rows($sql);
                                ?>

                                <?php
                                if ($hasil > 0) {
                                    $sql3 = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE nama_anggota = '$fullname' AND tanggal_pengembalian = ''");
                                    $row = mysqli_num_rows($sql3);
                                    echo "
                                    <div class='alert alert-danger small'>
                                        Kamu saat ini telah meminjam sebanyak " . $hasil . " Buku
                                    </div>";
                                } else {
                                    //
                                }
                                ?>
                                <form action="pages/function/Peminjaman.php?aksi=pinjam" method="POST">
                                    <?php
                                    include "../../config/koneksi.php";
                                    $id = $_SESSION['id_user'];
                                    $query_fullname = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id'");
                                    $row1 = mysqli_fetch_array($query_fullname);
                                    ?>
                                    <div class="form-group">
                                        <label>Nama Anggota</label>
                                        <input type="text" class="form-control" name="namaAnggota" value="<?= $row1['fullname']; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Judul Buku</label>
                                        <select class="form-control" name="judulBuku">
                                            <option selected disabled> -- Silahkan pilih buku yang akan di pinjam -- </option>
                                            <?php
                                            include "../../config/koneksi.php";

                                            $sql = mysqli_query($koneksi, "SELECT * FROM buku");
                                            while ($data = mysqli_fetch_array($sql)) {
                                            ?>
                                                <option value="<?= $data['judul_buku']; ?>"> <?= $data['judul_buku']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Peminjaman</label>
                                        <input type="text" class="form-control" name="tanggalPeminjaman" value="<?= date('d-m-Y'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Kondisi Buku Saat Dipinjam</label>
                                        <select class="form-control" name="kondisiBukuSaatDipinjam">
                                            <option selected disabled>-- Silahkan pilih kondisi buku saat dipinjam --</option>
                                            <!-- -->
                                            <option value="Baik">Baik</option>
                                            <option value="Rusak">Rusak</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">Kirim</button>
                                    </div>
                                </form>
                            </section>
                        </div>
=======
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tgl-pengembalian" data-toggle="tab">Riwayat Peminjaman Buku</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- Tanggal Pengembalian -->
                        <div class="tab-pane active" id="tgl-pengembalian">
