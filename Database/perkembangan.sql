/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `perkembangan` (
	`id` int (11),
	`tanaman_id` int (11),
	`tanggal` date ,
	`tinggi_cm` Decimal (8),
	`kondisi` char (33),
	`catatan` text ,
	`foto` varchar (765),
	`created_at` timestamp 
); 
insert into `perkembangan` (`id`, `tanaman_id`, `tanggal`, `tinggi_cm`, `kondisi`, `catatan`, `foto`, `created_at`) values('1','1','2026-05-11','-0.10','baik','',NULL,'2026-05-11 18:56:16');
