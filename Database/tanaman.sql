/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `tanaman` (
	`id` int (11),
	`user_id` int (11),
	`nama_tanaman` varchar (300),
	`jenis` varchar (300),
	`tanggal_tanam` date ,
	`estimasi_panen` int (11),
	`tanggal_prediksi_panen` date ,
	`suhu_ideal` float ,
	`ph_ideal` float ,
	`kelembaban_ideal` float ,
	`catatan_admin` text ,
	`suhu` int (11),
	`ph_tanah` Decimal (5),
	`kelembapan` int (11),
	`jenis_pupuk` varchar (300),
	`lokasi` varchar (450),
	`status` char (15),
	`ada_laporan_baru` tinyint (1),
	`foto` varchar (765),
	`catatan` text ,
	`created_at` timestamp 
); 
insert into `tanaman` (`id`, `user_id`, `nama_tanaman`, `jenis`, `tanggal_tanam`, `estimasi_panen`, `tanggal_prediksi_panen`, `suhu_ideal`, `ph_ideal`, `kelembaban_ideal`, `catatan_admin`, `suhu`, `ph_tanah`, `kelembapan`, `jenis_pupuk`, `lokasi`, `status`, `ada_laporan_baru`, `foto`, `catatan`, `created_at`) values('4','3','jagung','gatau','2026-05-06','2','2026-05-08','12','2','10',NULL,NULL,NULL,NULL,NULL,'depan kos','aktif','0',NULL,'hehe','2026-05-11 19:16:05');
insert into `tanaman` (`id`, `user_id`, `nama_tanaman`, `jenis`, `tanggal_tanam`, `estimasi_panen`, `tanggal_prediksi_panen`, `suhu_ideal`, `ph_ideal`, `kelembaban_ideal`, `catatan_admin`, `suhu`, `ph_tanah`, `kelembapan`, `jenis_pupuk`, `lokasi`, `status`, `ada_laporan_baru`, `foto`, `catatan`, `created_at`) values('6','6','padi jawa','tanaman','2026-05-22','30',NULL,NULL,NULL,NULL,NULL,'27','6.5','75','tayi sapi','sawah belakang','aktif','0',NULL,'my new journey','2026-05-22 09:10:35');
insert into `tanaman` (`id`, `user_id`, `nama_tanaman`, `jenis`, `tanggal_tanam`, `estimasi_panen`, `tanggal_prediksi_panen`, `suhu_ideal`, `ph_ideal`, `kelembaban_ideal`, `catatan_admin`, `suhu`, `ph_tanah`, `kelembapan`, `jenis_pupuk`, `lokasi`, `status`, `ada_laporan_baru`, `foto`, `catatan`, `created_at`) values('8','8','tomat','sayuran','2026-05-26','30','2026-06-25','21','6','65',NULL,NULL,NULL,NULL,NULL,'kebun','panen','0',NULL,'','2026-05-26 20:48:43');
insert into `tanaman` (`id`, `user_id`, `nama_tanaman`, `jenis`, `tanggal_tanam`, `estimasi_panen`, `tanggal_prediksi_panen`, `suhu_ideal`, `ph_ideal`, `kelembaban_ideal`, `catatan_admin`, `suhu`, `ph_tanah`, `kelembapan`, `jenis_pupuk`, `lokasi`, `status`, `ada_laporan_baru`, `foto`, `catatan`, `created_at`) values('10','2','Ganja','Narkoba','2026-05-26','40','2026-07-05','25','6','75',NULL,NULL,NULL,NULL,NULL,'Bromo','aktif','0',NULL,'','2026-05-26 21:52:15');
insert into `tanaman` (`id`, `user_id`, `nama_tanaman`, `jenis`, `tanggal_tanam`, `estimasi_panen`, `tanggal_prediksi_panen`, `suhu_ideal`, `ph_ideal`, `kelembaban_ideal`, `catatan_admin`, `suhu`, `ph_tanah`, `kelembapan`, `jenis_pupuk`, `lokasi`, `status`, `ada_laporan_baru`, `foto`, `catatan`, `created_at`) values('11','2','Jeruk','buah','2026-05-27','10','2026-06-06','20','7','100','penyiraman 2 hari sekali',NULL,NULL,NULL,NULL,'Kebun belakang','aktif','1',NULL,'3 hektar','2026-05-27 22:11:52');
insert into `tanaman` (`id`, `user_id`, `nama_tanaman`, `jenis`, `tanggal_tanam`, `estimasi_panen`, `tanggal_prediksi_panen`, `suhu_ideal`, `ph_ideal`, `kelembaban_ideal`, `catatan_admin`, `suhu`, `ph_tanah`, `kelembapan`, `jenis_pupuk`, `lokasi`, `status`, `ada_laporan_baru`, `foto`, `catatan`, `created_at`) values('12','2','Apel','buah','2026-05-29','15','2026-06-13','20','6','60',NULL,NULL,NULL,NULL,NULL,'depan kos','panen','0',NULL,'','2026-05-29 08:04:53');
insert into `tanaman` (`id`, `user_id`, `nama_tanaman`, `jenis`, `tanggal_tanam`, `estimasi_panen`, `tanggal_prediksi_panen`, `suhu_ideal`, `ph_ideal`, `kelembaban_ideal`, `catatan_admin`, `suhu`, `ph_tanah`, `kelembapan`, `jenis_pupuk`, `lokasi`, `status`, `ada_laporan_baru`, `foto`, `catatan`, `created_at`) values('13','9','Padi','Rempah','2026-05-29','60','2026-07-28','28','6','70','Penyiraman dan pemupukan setiap hari',NULL,NULL,NULL,NULL,'sawah','aktif','0',NULL,'','2026-05-29 08:30:56');
insert into `tanaman` (`id`, `user_id`, `nama_tanaman`, `jenis`, `tanggal_tanam`, `estimasi_panen`, `tanggal_prediksi_panen`, `suhu_ideal`, `ph_ideal`, `kelembaban_ideal`, `catatan_admin`, `suhu`, `ph_tanah`, `kelembapan`, `jenis_pupuk`, `lokasi`, `status`, `ada_laporan_baru`, `foto`, `catatan`, `created_at`) values('14','2','Mangga','buah','2026-05-29','12','2026-06-10','28','3','60',NULL,NULL,NULL,NULL,NULL,'pot','aktif','0',NULL,'','2026-05-29 09:15:43');
