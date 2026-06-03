/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `notifikasi` (
	`id` int (11),
	`user_id` int (11),
	`judul` varchar (600),
	`pesan` text ,
	`tipe` char (30),
	`is_read` tinyint (1),
	`created_at` timestamp 
); 
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('1','2','Waktunya Menyiram!','Tomat Cherry kamu perlu disiram hari ini jam 07:00.','penyiraman','1','2026-05-11 11:37:21');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('2','2','Perkiraan Panen Dekat','Kangkung kamu diperkirakan siap panen dalam 5 hari lagi!','panen','1','2026-05-11 11:37:21');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('3','1','Konsultasi Baru','Ada pertanyaan baru dari user: Cabai','konsultasi','1','2026-05-11 18:52:22');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('4','3','Tanaman Baru','jagung telah ditambahkan ke kebun digitalmu.','sistem','0','2026-05-11 19:16:05');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('5','1','Konsultasi Baru','Ada pertanyaan baru dari user: anjing','konsultasi','1','2026-05-19 14:40:51');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('6','3','Konsultasi Dijawab','Admin telah membalas konsultasi Anda: anjing','konsultasi','0','2026-05-19 14:44:05');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('7','1','Konsultasi Baru','Ada pertanyaan baru dari user: laprak modul3','konsultasi','1','2026-05-19 14:46:59');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('8','2','Konsultasi Dijawab','Admin telah membalas konsultasi Anda: laprak modul3','konsultasi','1','2026-05-19 14:47:34');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('9','2','Tanaman Baru','gandum telah ditambahkan ke kebun digitalmu.','sistem','1','2026-05-22 08:37:47');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('10','1','Konsultasi Baru','Ada pertanyaan baru dari user: gandum','konsultasi','1','2026-05-22 09:00:28');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('11','6','Selamat Datang! ?','Tanaman pertamamu \"padi jawa\" berhasil ditambahkan. Selamat berkebun!','sistem','0','2026-05-22 09:10:35');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('12','2','Konsultasi Dijawab','Admin telah membalas konsultasi Anda: gandum','konsultasi','1','2026-05-22 09:13:21');
insert into `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) values('13','2','Konsultasi Dijawab','Admin telah membalas konsultasi Anda: Cabai','konsultasi','1','2026-05-22 09:13:34');
