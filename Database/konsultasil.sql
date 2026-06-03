/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `konsultasi` (
	`id` int (11),
	`user_id` int (11),
	`judul` varchar (600),
	`pesan` text ,
	`status` char (24),
	`created_at` timestamp 
); 
insert into `konsultasi` (`id`, `user_id`, `judul`, `pesan`, `status`, `created_at`) values('1','2','Cabai','Kenapa cabe cabean murah?','dijawab','2026-05-11 18:52:22');
insert into `konsultasi` (`id`, `user_id`, `judul`, `pesan`, `status`, `created_at`) values('2','3','anjing','Apakah bisa pelihara anjing?','dijawab','2026-05-19 14:40:51');
insert into `konsultasi` (`id`, `user_id`, `judul`, `pesan`, `status`, `created_at`) values('3','2','laprak modul3','aku belom ngerjain','dijawab','2026-05-19 14:46:59');
insert into `konsultasi` (`id`, `user_id`, `judul`, `pesan`, `status`, `created_at`) values('4','2','gandum','aduh','dijawab','2026-05-22 09:00:28');
