/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `balasan_konsultasi` (
	`konsultasi_id` int (11),
	`user_id` int (11),
	`pesan` text ,
	`created_at` timestamp 
); 
insert into `balasan_konsultasi` (`konsultasi_id`, `user_id`, `pesan`, `created_at`) values('2','1','bisa','2026-05-19 14:44:05');
insert into `balasan_konsultasi` (`konsultasi_id`, `user_id`, `pesan`, `created_at`) values('3','1','oohhh','2026-05-19 14:47:34');
insert into `balasan_konsultasi` (`konsultasi_id`, `user_id`, `pesan`, `created_at`) values('4','1','kenapa bapak?','2026-05-22 09:13:21');
insert into `balasan_konsultasi` (`konsultasi_id`, `user_id`, `pesan`, `created_at`) values('1','1','tidak tau bapak','2026-05-22 09:13:34');
