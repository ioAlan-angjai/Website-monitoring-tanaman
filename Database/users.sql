/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `users` (
	`id` int (11),
	`nama` varchar (300),
	`email` varchar (450),
	`password` varchar (765),
	`role` char (15),
	`foto` varchar (765),
	`created_at` timestamp 
); 
insert into `users` (`id`, `nama`, `email`, `password`, `role`, `foto`, `created_at`) values('1','Administrator','admin@gmail.com','$2y$10$QjUvXEv/E5XmHwDq6S9vH.v7nNfE6p1GfM1gWvO6BfFjV6kY1nQmG','admin',NULL,'2026-05-11 11:36:48');
insert into `users` (`id`, `nama`, `email`, `password`, `role`, `foto`, `created_at`) values('2','Budi Santoso','budi@gmail.com','$2y$10$gz1OxXvFcgmkUiX6gQcEneVQzhnmBoFw22wC4c8iCLckvqkAC0.wS','user','user_2_1778501097.jpg','2026-05-11 11:36:48');
insert into `users` (`id`, `nama`, `email`, `password`, `role`, `foto`, `created_at`) values('3','Tio','kurniawantio@gmail.com','$2y$10$SZRuq4nlCDXhxluAaoTBy./q1t/RUNc0Ud/QIxWo375ReXX3vWB.C','user',NULL,'2026-05-11 16:01:05');
insert into `users` (`id`, `nama`, `email`, `password`, `role`, `foto`, `created_at`) values('4','Tio alan','ayunieti4@gmail.com','$2y$10$iv4gNWxFL40eulk6UqAsXuVP.43uPqHdD72kIuCtojBuKa9wNeGm6','user',NULL,'2026-05-11 19:07:27');
insert into `users` (`id`, `nama`, `email`, `password`, `role`, `foto`, `created_at`) values('5','Fanya','fanya@gmail.com','$2y$10$Box2BEdKb.JHIIHhct/Sjevm7Sk3EfjlhL0cCa0RjijgPDoATV8a2','user',NULL,'2026-05-22 08:55:48');
insert into `users` (`id`, `nama`, `email`, `password`, `role`, `foto`, `created_at`) values('6','ichallista','ichakk@gmail.com','$2y$10$D/6JZpQ4qA1QE2MYhlUCJOMmKkijdRKUkGRZEm4IZiLZkDt4/RHvG','user',NULL,'2026-05-22 09:09:30');
insert into `users` (`id`, `nama`, `email`, `password`, `role`, `foto`, `created_at`) values('7','admin','admin1@gmail.com','$2y$10$QjUvXEv/E5XmHwDq6S9vH.v7nNfE6p1GfM1gWvO6BfFjV6kY1nQmG','admin',NULL,'2026-05-26 19:24:48');
insert into `users` (`id`, `nama`, `email`, `password`, `role`, `foto`, `created_at`) values('8','Admin Web','admin2@gmail.com','$2y$10$rlPWfdXg0hk3hzuhlJdSJ.h7/HwZk0diQzvGdz6CcFIiqG2DOz2W2','admin',NULL,'2026-05-26 19:34:15');
