-- Database Schema for LifeQuest
-- Can be imported into phpMyAdmin or InfinityFree MySQL

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `xp` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tugas` (
  `id_tugas` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `id_kategori` INT(11) NOT NULL,
  `judul` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT DEFAULT NULL,
  `deadline` DATE DEFAULT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'Belum Selesai',
  PRIMARY KEY (`id_tugas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `quest` (
  `id_quest` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_quest` VARCHAR(100) NOT NULL,
  `poin` INT(11) NOT NULL DEFAULT 0,
  `status` VARCHAR(20) NOT NULL DEFAULT 'Belum',
  PRIMARY KEY (`id_quest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Default Data

INSERT INTO `users` (`id_user`, `username`, `xp`) VALUES
(1, 'Chaesaar', 0)
ON DUPLICATE KEY UPDATE `username`='Chaesaar';

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Pekerjaan'),
(2, 'Pribadi'),
(3, 'Kesehatan'),
(4, 'Belajar')
ON DUPLICATE KEY UPDATE `id_kategori`=`id_kategori`;

INSERT INTO `quest` (`id_quest`, `nama_quest`, `poin`, `status`) VALUES
(1, 'Minum air putih 2 liter', 15, 'Belum'),
(2, 'Membaca buku selama 15 menit', 20, 'Belum'),
(3, 'Olahraga ringan / stretching pagi', 25, 'Belum'),
(4, 'Menyelesaikan 1 tugas penting', 30, 'Belum'),
(5, 'Tidur sebelum jam 11 malam', 15, 'Belum')
ON DUPLICATE KEY UPDATE `id_quest`=`id_quest`;
