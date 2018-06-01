/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 10.1.24-MariaDB : Database - ims_1
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ims_1` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `ims_1`;

/*Table structure for table `commands` */

DROP TABLE IF EXISTS `commands`;

CREATE TABLE `commands` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `command` varchar(50) DEFAULT NULL,
  `messages` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `commands` */

insert  into `commands`(`id`,`command`,`messages`) values (1,'start','Selamat Datang, Fitur Yang Disediakan Adalah \r\npembayaran air dan cek tagihan air'),(2,'aku mau bayar air ni','silahkan masukan kode daerah no pdam anda\r\nseperti:\r\n111 156790'),(3,'aku mau lihat tagihan','silahkan masukan no pdam anda dan bulan\r\nseperti :\r\n150450544 Desember'),(4,'transfer','Pembayaran Anda Masih Menunggu Konfirmasi'),(5,'trf','Pembayaran Anda Masih Menunggu Konfirmasi'),(6,'register','id chat mu adalah ');

/*Table structure for table `inboxes` */

DROP TABLE IF EXISTS `inboxes`;

CREATE TABLE `inboxes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) DEFAULT NULL,
  `update_id` int(11) DEFAULT NULL,
  `messages` text,
  `picture_id` text,
  `pdam_number` text,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` text,
  `flag` enum('received','processed','done') DEFAULT 'received',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=708 DEFAULT CHARSET=latin1;

/*Data for the table `inboxes` */

insert  into `inboxes`(`id`,`chat_id`,`update_id`,`messages`,`picture_id`,`pdam_number`,`bulan`,`tahun`,`flag`) values (703,255444684,563824593,'trf','AgADBQADSqgxG8veiFSP82BJ3hxE0D4_1jIABCOimVpFGLSiEvEAAgI','11123456',12,'2019','done'),(704,255444684,563824594,'trf','AgADBQADSqgxG8veiFSP82BJ3hxE0D4_1jIABCOimVpFGLSiEvEAAgI','11123456',12,'2019','done'),(705,255444684,563824595,'trf','AgADBQADSqgxG8veiFSP82BJ3hxE0D4_1jIABCOimVpFGLSiEvEAAgI','11123456',2,'2019','done'),(706,255444684,563824596,'trf','AgADBQADKKgxG8vegFRYgSJcS5egHr821TIABLmej_aqaV1E5_YAAgI','11123456',2,'2019','done'),(707,255444684,563824597,'trf','AgADBQADS6gxG8veiFRDv6HMeh_imCJH1jIABGrfVcFn3eVe8O0AAgI','11123456',2,'2019','done');

/*Table structure for table `outboxes` */

DROP TABLE IF EXISTS `outboxes`;

CREATE TABLE `outboxes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inbox_id` int(10) unsigned NOT NULL,
  `chat_id` int(10) unsigned NOT NULL,
  `message` text,
  `flag` enum('just_arrived','processed','sent') DEFAULT 'just_arrived',
  `sent_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=latin1;

/*Data for the table `outboxes` */

insert  into `outboxes`(`id`,`inbox_id`,`chat_id`,`message`,`flag`,`sent_time`) values (108,703,255444684,'transaksi dengan nomor rekening anda atau tahun dan bulan tidak sesuai dan tidak ada','sent','2018-06-01 16:55:46'),(109,704,255444684,'transaksi dengan nomor rekening anda atau tahun dan bulan tidak sesuai dan tidak ada','sent','2018-06-01 16:56:27'),(110,705,255444684,'Pembayaran Anda Masih Menunggu Konfirmasi','sent','2018-06-01 16:58:04'),(111,706,255444684,'Pembayaran Anda Masih Menunggu Konfirmasi','sent','2018-06-01 17:02:56'),(112,707,255444684,'Pembayaran Anda Masih Menunggu Konfirmasi','sent','2018-06-01 17:07:03');

/*Table structure for table `transaksi` */

DROP TABLE IF EXISTS `transaksi`;

CREATE TABLE `transaksi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_pdam` bigint(20) DEFAULT NULL,
  `no_pdam` text,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `tanggal_bayar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bukti_pembayaran` text,
  `chat_id` text,
  `jumlah_tagihan` int(11) DEFAULT NULL,
  `flag` enum('lunas','belum lunas','batal','proces') DEFAULT 'belum lunas',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

/*Data for the table `transaksi` */

insert  into `transaksi`(`id`,`transaksi_pdam`,`no_pdam`,`bulan`,`tahun`,`tanggal_bayar`,`bukti_pembayaran`,`chat_id`,`jumlah_tagihan`,`flag`) values (30,3,'11123456',10,2018,'2018-06-01 17:07:09','f1de68dad5324f0b88c02e09f60576b3','123456',100000,'proces'),(31,4,'11123456',1,2018,'2018-06-01 17:07:09','f1de68dad5324f0b88c02e09f60576b3','123456',100000,'proces'),(32,5,'11123456',2,2019,'2018-06-01 17:07:09','fd9e7851e70b4e1a85721eb5332401af','255444684',1990000,'proces');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `username` varchar(25) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
