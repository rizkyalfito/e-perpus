-- Migration script to add buku_unit table for unique book units with barcode

CREATE TABLE IF NOT EXISTS buku_unit (
  id_buku_unit INT(11) NOT NULL AUTO_INCREMENT,
  id_buku INT(11) NOT NULL,
  barcode VARCHAR(50) NOT NULL UNIQUE,
  kondisi ENUM('baik', 'rusak') NOT NULL DEFAULT 'baik',
  status ENUM('tersedia', 'dipinjam') NOT NULL DEFAULT 'tersedia',
  PRIMARY KEY (id_buku_unit),
  FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
