/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `jadwal` (
	`id` int (11),
	`tanaman_id` int (11),
	`user_id` int (11),
	`jenis_perawatan` char (51),
	`tanggal_jadwal` date ,
	`waktu` time ,
	`status` char (24),
	`catatan` text ,
	`created_at` timestamp 
); 
insert into `jadwal` (`id`, `tanaman_id`, `user_id`, `jenis_perawatan`, `tanggal_jadwal`, `waktu`, `status`, `catatan`, `created_at`) values('1','1','2','penyiraman','2026-05-11','07:00:00','terlewat',NULL,'2026-05-11 11:37:14');
insert into `jadwal` (`id`, `tanaman_id`, `user_id`, `jenis_perawatan`, `tanggal_jadwal`, `waktu`, `status`, `catatan`, `created_at`) values('3','2','2','penyiraman','2026-05-11','07:00:00','terlewat',NULL,'2026-05-11 11:37:14');
insert into `jadwal` (`id`, `tanaman_id`, `user_id`, `jenis_perawatan`, `tanggal_jadwal`, `waktu`, `status`, `catatan`, `created_at`) values('5','4','3','penyiraman','2026-05-11','07:00:00','pending',NULL,'2026-05-11 19:16:05');
insert into `jadwal` (`id`, `tanaman_id`, `user_id`, `jenis_perawatan`, `tanggal_jadwal`, `waktu`, `status`, `catatan`, `created_at`) values('6','5','2','penyiraman','2026-05-22','07:00:00','selesai',NULL,'2026-05-22 08:37:47');
insert into `jadwal` (`id`, `tanaman_id`, `user_id`, `jenis_perawatan`, `tanggal_jadwal`, `waktu`, `status`, `catatan`, `created_at`) values('7','6','6','penyiraman','2026-05-22','07:00:00','pending',NULL,'2026-05-22 09:10:35');
