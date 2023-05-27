/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 10.1.38-MariaDB : Database - accounting_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`accounting_db` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `accounting_db`;

/*Table structure for table `chart_of_accounts` */

DROP TABLE IF EXISTS `chart_of_accounts`;

CREATE TABLE `chart_of_accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int(11) NOT NULL,
  `head` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` int(11) DEFAULT '0',
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_sub_category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sub_category_id` int(11) DEFAULT NULL,
  `sub_sub_category_id` int(11) DEFAULT NULL,
  `system_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reference` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `head` (`head`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `chart_of_accounts` */

insert  into `chart_of_accounts`(`id`,`type`,`type_id`,`head`,`group_id`,`group_name`,`category`,`sub_category`,`sub_sub_category`,`category_id`,`sub_category_id`,`sub_sub_category_id`,`system_code`,`status`,`created_at`,`updated_at`,`reference`) values (1,'Asset',2,'testing head',0,NULL,'test sdfsdf','tset','test',NULL,NULL,NULL,'A2','2','2021-09-08 08:26:02','2021-09-08 21:03:45',1),(2,'Asset',2,'testing head',0,NULL,'test','tset','test',NULL,NULL,NULL,'A1','1','2021-09-08 08:26:38','2021-09-08 08:26:38',1),(3,'Liabilities',16,'testing head',0,NULL,'test','tset','test',NULL,NULL,NULL,'L1','1','2021-09-08 08:58:02','2021-09-08 08:58:02',1),(4,'Asset',2,'sdfsdf',0,NULL,'test','df','sdfsdf',NULL,NULL,NULL,'A3','1','2021-09-08 08:58:34','2021-09-08 08:58:34',NULL),(5,'Asset',2,'test',0,NULL,'cat1','sub-cat-1','sub-sub-cat',27,29,31,'A4','1','2021-09-24 22:40:22','2021-09-24 22:40:22',0),(6,'Asset',2,'fsdf',0,NULL,'Assestive Devices','Motor Cycle Purchase A/C','ts',12,17,18,'','1','2021-09-26 21:43:51','2021-09-26 21:43:51',0),(7,'Non-Current Assets',2,'testing head',0,NULL,'Assestive Devices','Motor Cycle Purchase A/C',NULL,12,17,0,'','1','2021-09-26 21:45:58','2021-09-26 21:45:58',0),(8,'Asset',2,'testing head236',11,'Non-Current Assets','Assestive Devices','Motor Cycle Purchase A/C','ts',12,17,18,'','1','2021-09-26 21:48:50','2021-09-26 22:02:07',0),(9,'Income',20,'head1',23,'Direct Income','Revenue','Service Charge Fine',NULL,25,27,0,'I1','1','2021-10-03 19:34:21','2021-10-03 19:34:21',0),(10,'Income',20,'head2',23,'Direct Income','Revenue','Food Court Service Charge Fine',NULL,25,28,0,'I2','1','2021-10-03 19:34:44','2021-10-03 19:34:44',0),(11,'Income',20,'head3',23,'Direct Income','Revenue','Electricity Fine',NULL,25,29,0,'I3','1','2021-10-03 19:35:11','2021-10-03 19:35:11',0);

/*Table structure for table `customer_logs` */

DROP TABLE IF EXISTS `customer_logs`;

CREATE TABLE `customer_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `shop_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shop_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_contact` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_nid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_lincese_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incorporation_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_date` date DEFAULT NULL,
  `contact_s_date` date DEFAULT NULL,
  `renewal_date` date DEFAULT NULL,
  `contact_closure_date` date DEFAULT NULL,
  `advance_deposit` double DEFAULT NULL,
  `security_deposit` double DEFAULT NULL,
  `adj_adv_deposit` double DEFAULT NULL,
  `adj_effective_from` date DEFAULT NULL,
  `adj_closure_date` date DEFAULT NULL,
  `monthly_rent` double DEFAULT NULL,
  `renewal_rent` double DEFAULT NULL,
  `service_charge` double DEFAULT NULL,
  `billing_system` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_period` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_owner_name` int(11) DEFAULT NULL,
  `password_visible` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `shop_no` (`shop_no`),
  KEY `shop_name` (`shop_name`),
  KEY `owner_nid` (`owner_nid`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `customer_logs` */

insert  into `customer_logs`(`id`,`customer_id`,`shop_no`,`shop_name`,`owner_name`,`owner_contact`,`owner_nid`,`email`,`owner_address`,`trade_lincese_no`,`incorporation_no`,`bin`,`etin`,`contact_person_name`,`contact_person_phone`,`region`,`contact_no`,`contact_date`,`contact_s_date`,`renewal_date`,`contact_closure_date`,`advance_deposit`,`security_deposit`,`adj_adv_deposit`,`adj_effective_from`,`adj_closure_date`,`monthly_rent`,`renewal_rent`,`service_charge`,`billing_system`,`credit_period`,`contact_owner_name`,`password_visible`,`status`,`created_by`,`updated_by`,`created_at`,`updated_at`) values (1,6,'ert345ef','ert43534','wert','ert','wert','a@t.com','pabna','ertwet','er','ertw','ertwe','fazlu niloy','4245654646','6','ewrt','2021-09-09','2021-09-29','2021-09-20','2021-09-16',55345,45345,345,'2021-09-14','2021-09-14',435345,34534,5555,'rt','ert',3,NULL,1,NULL,8,'2021-09-14T13:47:38.000000Z','0000-00-00 00:00:00'),(2,6,'ert345ef','ert43534','wert','ert','wert','a@t.com','pabna','ertwet','er','ertw','ertwe','fazlu niloy','4245654646','6','ewrt','2021-09-09','2021-09-29','2021-09-20','2021-09-16',55345,45345,345,'2021-09-14','2021-09-14',435345,34534,5555,'rt','ert',3,NULL,1,NULL,8,'2021-09-14T13:47:38.000000Z','0000-00-00 00:00:00'),(3,5,'sfsd','sdfa','sdf','safd','as',NULL,'sdf',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,1,NULL,8,'2021-09-13T15:47:06.000000Z','2021-09-14 21:37:57'),(4,6,'ert345ef','ert43534','wert','ert','wert','a@t.com','pabna','ertwet','er','ertw','ertwe','fazlu niloy','4245654646','6','ewrt','2021-09-09','2021-09-29','2021-09-20','2021-09-16',55345,45345,345,'2021-09-14','2021-09-14',435345,34534,5555,'rt','ert',3,NULL,1,NULL,8,'2021-09-14T13:47:38.000000Z','2021-09-14 21:38:13');

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shop_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_contact` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_nid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etin` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_lincese_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incorporation_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` int(11) DEFAULT NULL,
  `contact_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_date` date DEFAULT '0000-00-00',
  `contact_s_date` date DEFAULT '0000-00-00',
  `renewal_date` date DEFAULT '0000-00-00',
  `contact_closure_date` date DEFAULT '0000-00-00',
  `advance_deposit` double(20,2) DEFAULT '0.00',
  `security_deposit` double(20,2) DEFAULT '0.00',
  `adj_adv_deposit` double(20,2) DEFAULT '0.00',
  `adj_effective_from` date DEFAULT '0000-00-00',
  `adj_closure_date` date DEFAULT '0000-00-00',
  `monthly_rent` double(20,2) DEFAULT '0.00',
  `renewal_rent` double(20,2) DEFAULT '0.00',
  `service_charge` double(20,2) DEFAULT '0.00',
  `billing_system` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_period` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_owner_name` int(11) DEFAULT NULL,
  `password_visible` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_owner_contact_unique` (`owner_contact`),
  UNIQUE KEY `customers_email_unique` (`email`),
  KEY `shop_no` (`shop_no`),
  KEY `shop_name` (`shop_name`),
  KEY `renewal_date` (`renewal_date`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `customers` */

insert  into `customers`(`id`,`shop_no`,`shop_name`,`owner_name`,`owner_contact`,`owner_nid`,`etin`,`email`,`owner_address`,`trade_lincese_no`,`incorporation_no`,`bin`,`contact_person_name`,`contact_person_phone`,`region`,`contact_no`,`contact_date`,`contact_s_date`,`renewal_date`,`contact_closure_date`,`advance_deposit`,`security_deposit`,`adj_adv_deposit`,`adj_effective_from`,`adj_closure_date`,`monthly_rent`,`renewal_rent`,`service_charge`,`billing_system`,`credit_period`,`contact_owner_name`,`password_visible`,`status`,`created_by`,`updated_by`,`created_at`,`updated_at`) values (1,'01','shop one','sdf','shop one','shop one','shop one',NULL,'shop one','shop one','shop one','shop one','shop one','shop one',NULL,'shop one','2021-10-15','2021-10-16','2021-10-14','2021-10-14',333.00,444.00,444.00,'2021-10-11','2021-10-24',435345.00,34534.00,4324.00,'d','10',1,NULL,1,8,NULL,'2021-10-03 19:22:46','2021-10-03 19:22:46'),(4,'02','shop two','asdfsdf','shop sdfsdf',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,34444.00,6000.00,333.00,'ddd','20',3,NULL,1,8,NULL,'2021-10-03 19:24:07','2021-10-03 19:24:07');

/*Table structure for table `districts` */

DROP TABLE IF EXISTS `districts`;

CREATE TABLE `districts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `districts` */

/*Table structure for table `emails` */

DROP TABLE IF EXISTS `emails`;

CREATE TABLE `emails` (
  `id` bigint(20) unsigned NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'System Generated',
  `mail_purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `emails` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `godowns` */

DROP TABLE IF EXISTS `godowns`;

CREATE TABLE `godowns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `godowns` */

insert  into `godowns`(`id`,`name`,`address`,`contact_person_name`,`contact_number`,`email`,`status`,`created_by`,`updated_by`,`created_at`,`updated_at`) values (1,'tssdfa','pabna','fazlu niloy','4245654646','a@t.com','2',8,NULL,'2021-09-27 22:29:27','2021-09-27 22:51:45'),(2,'ts','pabna','fazlu niloy','4245654646','a@t.com','1',8,NULL,'2021-09-27 22:33:16','2021-09-27 22:33:16');

/*Table structure for table `group_accounts` */

DROP TABLE IF EXISTS `group_accounts`;

CREATE TABLE `group_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `status` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `group_accounts` */

insert  into `group_accounts`(`id`,`name`,`status`,`created_by`,`created_at`,`updated_by`,`updated_at`) values (1,'Production Unit',0,1,'2021-09-07 19:52:05',NULL,'2021-09-07 19:52:05'),(2,'Service Unit',0,1,'2021-09-07 19:59:41',NULL,'2021-09-07 19:59:41'),(3,'Construction Unit',0,1,'2021-09-07 19:59:49',NULL,'2021-09-07 19:59:49'),(4,'IT Services',0,1,'2021-09-07 19:59:54',NULL,'2021-09-07 19:59:54'),(5,'Market Unit-1',0,1,'2021-09-07 20:00:00',NULL,'2021-09-07 20:00:00'),(6,'Market Unit-2',0,1,'2021-09-07 20:00:08',NULL,'2021-09-07 20:00:08'),(7,'tests',1,8,'2021-09-08 21:11:42',NULL,'2021-09-08 21:11:42'),(8,'tests',1,8,'2021-09-08 21:12:13',NULL,'2021-09-08 21:12:13'),(9,'test',1,8,'2021-09-08 21:21:23',NULL,'2021-09-08 21:21:23'),(10,'Pant dfsdf',1,8,'2021-09-08 21:54:05',NULL,'2021-09-08 22:00:45');

/*Table structure for table `income_details` */

DROP TABLE IF EXISTS `income_details`;

CREATE TABLE `income_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `income_id` int(11) NOT NULL,
  `income_head` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `income_head_id` int(11) NOT NULL,
  `month` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double DEFAULT '0',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `vat` int(11) DEFAULT '0',
  `vat_amount` double(18,2) DEFAULT '0.00',
  `total` double(18,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `income_details` */

insert  into `income_details`(`id`,`income_id`,`income_head`,`income_head_id`,`month`,`amount`,`remarks`,`vat`,`vat_amount`,`total`,`created_at`,`updated_at`,`effective_date`) values (1,0,'Food Court Service Charge Fine',10,'2021-10-20',6000,NULL,NULL,NULL,NULL,'2021-10-03 22:25:45','2021-10-03 22:25:45',NULL),(2,2,'Food Court Service Charge Fine',10,'2021-10-28',300.3,NULL,NULL,NULL,NULL,'2021-10-03 22:33:52','2021-10-03 22:33:52',NULL),(3,4,'Service Charge Fine',9,'2021-10-21',620,NULL,NULL,NULL,NULL,'2021-10-03 22:35:31','2021-10-03 22:35:31',NULL),(4,4,'Food Court Service Charge Fine',10,'2021-10-21',60000,NULL,NULL,NULL,NULL,'2021-10-03 22:35:31','2021-10-03 22:35:31',NULL),(5,5,'Service Charge Fine',9,'2021-10-21',620,NULL,NULL,NULL,NULL,'2021-10-03 22:36:00','2021-10-03 22:36:00',NULL),(6,5,'Food Court Service Charge Fine',10,'2021-10-21',60000,NULL,NULL,NULL,NULL,'2021-10-03 22:36:00','2021-10-03 22:36:00',NULL),(7,6,'Service Charge Fine',9,'2021-10-27',300.3,NULL,NULL,NULL,NULL,'2021-10-03 22:36:50','2021-10-03 22:36:50',NULL),(8,6,'Food Court Service Charge Fine',10,'2021-10-27',5000,NULL,NULL,NULL,NULL,'2021-10-03 22:36:50','2021-10-03 22:36:50',NULL),(9,6,'Electricity Fine',11,'2021-10-27',600,NULL,NULL,NULL,NULL,'2021-10-03 22:36:50','2021-10-03 22:36:50',NULL),(10,7,'Service Charge Fine',9,'2021-10-20',300.3,NULL,NULL,NULL,NULL,'2021-10-03 22:47:30','2021-10-03 22:47:30',NULL),(11,7,'Food Court Service Charge Fine',10,'2021-10-20',900.3,NULL,NULL,NULL,NULL,'2021-10-03 22:47:30','2021-10-03 22:47:30',NULL),(12,8,'Service Charge Fine',9,'2021-10-20',300.3,NULL,NULL,NULL,NULL,'2021-10-03 22:48:23','2021-10-03 22:48:23',NULL),(17,9,'Service Charge Fine',9,'2021-10-20',300.3,NULL,NULL,NULL,NULL,'2021-10-05 19:44:59','2021-10-05 19:44:59',NULL),(18,9,'Food Court Service Charge Fine',10,'2021-10-13',300.3,NULL,NULL,NULL,NULL,'2021-10-05 19:44:59','2021-10-05 19:44:59',NULL),(19,9,'Electricity Fine',11,'2021-10-13',300.3,NULL,NULL,NULL,NULL,'2021-10-05 19:44:59','2021-10-05 19:44:59',NULL),(20,10,'Service Charge Fine',9,'2021-10-05',5000,NULL,NULL,NULL,NULL,'2021-10-05 22:00:46','2021-10-05 22:00:46',NULL),(21,10,'Food Court Service Charge Fine',10,'2021-09-30',300.3,NULL,NULL,NULL,NULL,'2021-10-05 22:00:46','2021-10-05 22:00:46',NULL),(22,10,'Electricity Fine',11,'2021-09-30',300.3,NULL,NULL,NULL,NULL,'2021-10-05 22:00:46','2021-10-05 22:00:46',NULL),(23,11,'Service Charge Fine',9,'2021-10-20',5000,NULL,NULL,NULL,NULL,'2021-10-05 22:57:13','2021-10-05 22:57:13',NULL),(24,13,'Service Charge Fine',9,'Apr 2021',5000,'test one',NULL,NULL,NULL,'2021-10-06 20:59:32','2021-10-06 20:59:32',NULL),(25,13,'Food Court Service Charge Fine',10,'Apr 2021',1000,'ok goods ',NULL,NULL,NULL,'2021-10-06 20:59:32','2021-10-06 20:59:32',NULL),(26,14,'Food Court Service Charge Fine',10,'Mar 2021',200,'sfsdfs',NULL,NULL,NULL,'2021-10-06 21:01:52','2021-10-06 21:01:52',NULL),(27,14,'Service Charge Fine',9,'Mar 2021',50000,'erewrw',NULL,NULL,NULL,'2021-10-06 21:01:52','2021-10-06 21:01:52',NULL),(28,15,'Service Charge Fine',9,'Mar 2021',50000,'testing',NULL,NULL,NULL,'2021-10-06 21:51:48','2021-10-06 21:51:48',NULL),(29,16,'Service Charge Fine',9,'Feb 2021',30000,'testing ok',NULL,NULL,NULL,'2021-10-06 21:55:26','2021-10-06 21:55:26',NULL),(30,16,'Food Court Service Charge Fine',10,'Feb 2021',5600,'ok no problem',NULL,NULL,NULL,'2021-10-06 21:55:26','2021-10-06 21:55:26',NULL),(31,16,'Electricity Fine',11,'Feb 2021',3000,'ok no problem the year',NULL,NULL,NULL,'2021-10-06 21:55:26','2021-10-06 21:55:26',NULL),(32,17,'Food Court Service Charge Fine',10,'May 2021',50000,'sesaerer',NULL,NULL,NULL,'2021-10-06 21:58:34','2021-10-06 21:58:34',NULL),(33,18,'Food Court Service Charge Fine',10,'May 2021',50000,'sesaerer',NULL,NULL,NULL,'2021-10-06 21:58:54','2021-10-06 21:58:54',NULL),(34,19,'Food Court Service Charge Fine',10,'Apr 2021',435435345,'sdfsdfas',NULL,NULL,NULL,'2021-10-06 21:59:29','2021-10-06 21:59:29',NULL),(35,20,'head2',10,'Apr 2021',300.3,'698325741',NULL,NULL,NULL,'2021-10-09 21:20:53','2021-10-09 21:20:53',NULL),(36,20,'head1',9,'Apr 2021',3000,'testing',NULL,NULL,NULL,'2021-10-09 21:20:53','2021-10-09 21:20:53',NULL),(37,20,'head3',11,'May 2021',30000,'30000',NULL,NULL,NULL,'2021-10-09 21:20:53','2021-10-09 21:20:53',NULL),(38,22,'head2',10,'Apr 2021',300.3,'',NULL,NULL,NULL,'2021-10-09 21:44:54','2021-10-09 21:44:54','2021-04-01'),(39,22,'head3',11,'Apr 2021',300.3,'',NULL,NULL,NULL,'2021-10-09 21:44:54','2021-10-09 21:44:54','2021-04-01'),(40,23,'head1',9,'Mar 2021',33,'fsdf',NULL,NULL,NULL,'2021-10-09 21:49:31','2021-10-09 21:49:31','2021-03-01'),(41,23,'head3',11,'Mar 2021',333,'s',NULL,NULL,NULL,'2021-10-09 21:49:31','2021-10-09 21:49:31','2021-03-01'),(42,27,'head1',9,'Jan 2021',5000,'test',5,250.00,5250.00,'2021-10-09 23:09:40','2021-10-09 23:09:40','2021-01-01'),(43,27,'head2',10,'Jan 2021',3000,'ddddd',15,450.00,3450.00,'2021-10-09 23:09:40','2021-10-09 23:09:40','2021-01-01'),(44,28,'head1',9,'Apr 2021',5000,'test',5,250.00,5250.00,'2021-10-09 23:11:51','2021-10-09 23:11:51','2021-04-01'),(45,28,'head3',11,'Apr 2021',2000,'st',10,200.00,2200.00,'2021-10-09 23:11:51','2021-10-09 23:11:51','2021-04-01'),(46,29,'head2',10,'May 2021',500,'sdfsdf',5,25.00,525.00,'2021-10-09 23:31:54','2021-10-09 23:31:54','2021-05-01'),(47,29,'head3',11,'May 2021',3000,'assf',10,300.00,3300.00,'2021-10-09 23:31:54','2021-10-09 23:31:54','2021-05-01'),(48,30,'head2',10,'May 2021',300.3,'sfsdf',555,1666.66,1966.96,'2021-10-09 23:39:04','2021-10-09 23:39:04','2021-05-01'),(49,30,'head3',11,'May 2021',5000,'f',150,7500.00,12500.00,'2021-10-09 23:39:04','2021-10-09 23:39:04','2021-05-01');

/*Table structure for table `incomes` */

DROP TABLE IF EXISTS `incomes`;

CREATE TABLE `incomes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `invoice_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `person_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `vat` double(18,2) DEFAULT '0.00',
  `vat_amount` double(18,2) DEFAULT '0.00',
  `total` double(18,2) DEFAULT '0.00',
  `grand_total` double(18,2) DEFAULT '0.00',
  `created_by` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `remarks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `credit_period` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gl_no` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_no` (`invoice_no`),
  KEY `shop_no` (`shop_no`),
  KEY `shop_name` (`shop_name`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `incomes` */

insert  into `incomes`(`id`,`shop_no`,`shop_name`,`customer_id`,`invoice_no`,`person_id`,`vat`,`vat_amount`,`total`,`grand_total`,`created_by`,`updated_by`,`remarks`,`issue_date`,`due_date`,`credit_period`,`created_at`,`updated_at`,`gl_no`,`post_date`) values (1,'02','shop two',4,NULL,'0',2.00,12.01,600.60,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-03 22:33:24','2021-10-03 22:33:24',NULL,NULL),(2,'02','shop two',4,NULL,'0',2.00,12.01,600.60,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-03 22:33:52','2021-10-03 22:33:52',NULL,NULL),(3,'02','shop two',4,NULL,'0',15.00,9093.00,60620.00,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-03 22:34:46','2021-10-03 22:34:46',NULL,NULL),(4,'02','shop two',4,NULL,'0',15.00,9093.00,60620.00,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-03 22:35:31','2021-10-03 22:35:31',NULL,NULL),(5,'02','shop two',4,NULL,'0',15.00,9093.00,60620.00,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-03 22:36:00','2021-10-03 22:36:00',NULL,NULL),(6,'01','shop one',1,NULL,'0',2.00,118.01,5900.30,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-03 22:36:50','2021-10-03 22:36:50',NULL,NULL),(7,'02','shop two',4,'1020212021-020000500006','0',12.00,144.07,1200.60,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-03 22:47:30','2021-10-03 22:47:30',NULL,NULL),(8,'02','shop two',4,'1021-020000600007','0',15.00,45.05,300.30,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-03 22:48:23','2021-10-03 22:48:23',NULL,NULL),(9,'02','shop two',4,'1021-02-00007-00008','0',2.00,18.02,900.90,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-03 22:49:39','2021-10-05 19:44:59',NULL,NULL),(10,'01','shop one',1,'1021-01-00001-00009','0',15.00,840.09,5600.60,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-05 22:00:46','2021-10-05 22:00:46',NULL,NULL),(11,'01','shop one',1,'1021-01-00002-00010','0',15.00,750.00,5000.00,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-05 22:57:13','2021-10-05 22:57:13',NULL,NULL),(12,'01','shop one',1,'1021-01-00003-00011','0',15.00,900.00,6000.00,0.00,8,0,NULL,NULL,NULL,NULL,'2021-10-06 20:56:53','2021-10-06 20:56:53',NULL,NULL),(13,'01','shop one',1,'1021-01-00004-00012','0',15.00,6900.00,6000.00,NULL,8,0,NULL,'2021-10-06','2021-10-16',10,'2021-10-06 20:59:32','2021-10-06 20:59:32',NULL,NULL),(14,'02','shop two',4,'1021-02-00008-00013','0',15.00,7530.00,50200.00,57730.00,8,0,NULL,'2021-10-20','2021-11-09',20,'2021-10-06 21:01:52','2021-10-06 21:01:52',NULL,NULL),(15,'01','shop one',1,'1021-01-00005-00014','0',15.00,7500.00,50000.00,57500.00,8,0,NULL,'2021-10-15','2021-10-25',10,'2021-10-06 21:51:48','2021-10-06 21:51:48',NULL,NULL),(16,'02','shop two',4,'1021-02-00009-00015','0',15.00,5790.00,38600.00,44390.00,8,0,NULL,'2021-10-30','2021-11-19',20,'2021-10-06 21:55:26','2021-10-06 21:55:26',NULL,NULL),(17,'02','shop two',4,'1021-02-00010-00016','0',15.00,7500.00,50000.00,57500.00,8,0,NULL,'2021-10-13','2021-11-02',20,'2021-10-06 21:58:34','2021-10-06 21:58:34',NULL,NULL),(18,'02','shop two',4,'1021-02-00011-00017','0',15.00,7500.00,50000.00,57500.00,8,0,NULL,'2021-10-13','2021-11-02',20,'2021-10-06 21:58:54','2021-10-06 21:58:54',NULL,NULL),(19,'02','shop two',4,'1021-02-00012-00018','0',0.00,0.00,435435345.00,435435345.00,8,0,NULL,'2021-10-06','2021-10-26',20,'2021-10-06 21:59:29','2021-10-06 21:59:29',NULL,NULL),(20,'02','shop two',4,'1021-02-00013-00019','0',15.00,4995.05,33300.30,38295.35,8,0,NULL,'2021-10-09','2021-10-29',20,'2021-10-09 21:20:53','2021-10-09 21:20:53',NULL,NULL),(21,'01','shop one',1,'1021-01-00006-00020','0',15.00,90.09,600.60,690.69,8,0,NULL,'2021-10-09','2021-10-19',10,'2021-10-09 21:44:23','2021-10-09 21:44:23',NULL,'2021-10-09'),(22,'01','shop one',1,'1021-01-00007-00021','0',15.00,90.09,600.60,690.69,8,0,NULL,'2021-10-09','2021-10-19',10,'2021-10-09 21:44:54','2021-10-09 21:44:54',NULL,'2021-10-09'),(23,'01','shop one',1,'1021-01-00008-00022','0',12.00,43.92,366.00,409.92,8,0,NULL,'2021-10-06','2021-10-16',10,'2021-10-09 21:49:31','2021-10-09 21:49:31',NULL,'2021-10-09'),(24,'02','shop two',4,'1021-02-00014-00023','0',0.00,900.00,8000.00,NULL,8,0,NULL,'2021-10-09','2021-10-29',20,'2021-10-09 23:06:51','2021-10-09 23:06:51',NULL,'2021-10-09'),(25,'02','shop two',4,'1021-02-00015-00024','0',0.00,NULL,8000.00,NULL,8,0,NULL,'2021-10-09','2021-10-29',20,'2021-10-09 23:08:17','2021-10-09 23:08:17',NULL,'2021-10-09'),(26,'02','shop two',4,'1021-02-00016-00025','0',0.00,700.00,8000.00,NULL,8,0,NULL,'2021-10-09','2021-10-29',20,'2021-10-09 23:08:45','2021-10-09 23:08:45',NULL,'2021-10-09'),(27,'02','shop two',4,'1021-02-00017-00026','0',0.00,700.00,8000.00,NULL,8,0,NULL,'2021-10-09','2021-10-29',20,'2021-10-09 23:09:40','2021-10-09 23:09:40',NULL,'2021-10-09'),(28,'02','shop two',4,'1021-02-00018-00027','0',0.00,450.00,7000.00,NULL,8,0,NULL,'2021-10-15','2021-11-04',20,'2021-10-09 23:11:51','2021-10-09 23:11:51',NULL,'2021-10-09'),(29,'01','shop one',1,'1021-01-00009-00028','0',0.00,325.00,3500.00,3825.00,8,0,NULL,'2021-10-14','2021-10-24',10,'2021-10-09 23:31:54','2021-10-09 23:31:54',NULL,'2021-10-09'),(30,'02','shop two',4,'1021-02-00019-00029','0',0.00,9166.66,5300.30,14466.96,8,0,NULL,'2021-10-16','2021-11-05',20,'2021-10-09 23:39:04','2021-10-09 23:39:04',NULL,'2021-10-09');

/*Table structure for table `journals` */

DROP TABLE IF EXISTS `journals`;

CREATE TABLE `journals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ref_id` bigint(20) DEFAULT NULL,
  `transaction_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `ledger_head` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `debit` double(18,2) DEFAULT '0.00',
  `credit` double(18,2) DEFAULT '0.00',
  `post_date` date DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `voucher_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_module` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ref_id` (`ref_id`),
  KEY `invoice_no` (`invoice_no`),
  KEY `voucher_no` (`voucher_no`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `journals` */

insert  into `journals`(`id`,`ref_id`,`transaction_type`,`invoice_no`,`customer_name`,`remarks`,`ledger_head`,`date`,`debit`,`credit`,`post_date`,`effective_date`,`voucher_no`,`ref_module`,`created_by`,`updated_by`,`created_at`,`updated_at`) values (1,15,'Income','1021-01-00005-00014','shop one','','Accounts Receivable','2021-10-15',50000.00,0.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:57:49','2021-10-06 21:57:49'),(2,15,'Income','1021-01-00005-00014','shop one','','Accounts Receivable (VAT)','2021-10-15',7500.00,0.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:57:49','2021-10-06 21:57:49'),(3,15,'Income','1021-01-00005-00014','shop one','testing','Service Charge Fine','2021-10-15',0.00,50000.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:57:49','2021-10-06 21:57:49'),(4,16,'Income','1021-02-00009-00015','shop two','','Accounts Receivable','2021-10-30',38600.00,0.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:57:49','2021-10-06 21:57:49'),(5,16,'Income','1021-02-00009-00015','shop two','','Accounts Receivable (VAT)','2021-10-30',5790.00,0.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:57:49','2021-10-06 21:57:49'),(6,16,'Income','1021-02-00009-00015','shop two','testing ok','Service Charge Fine','2021-10-30',0.00,30000.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:57:49','2021-10-06 21:57:49'),(7,16,'Income','1021-02-00009-00015','shop two','ok no problem','Food Court Service Charge Fine','2021-10-30',0.00,5600.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:57:49','2021-10-06 21:57:49'),(8,16,'Income','1021-02-00009-00015','shop two','ok no problem the year','Electricity Fine','2021-10-30',0.00,3000.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:57:49','2021-10-06 21:57:49'),(9,18,'Income','1021-02-00011-00017','shop two','','Accounts Receivable','2021-10-13',50000.00,0.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:58:54','2021-10-06 21:58:54'),(10,18,'Income','1021-02-00011-00017','shop two','','Accounts Receivable (VAT)','2021-10-13',7500.00,0.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:58:54','2021-10-06 21:58:54'),(11,18,'Income','1021-02-00011-00017','shop two','sesaerer','Food Court Service Charge Fine','2021-10-13',0.00,50000.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:58:54','2021-10-06 21:58:54'),(12,19,'Income','1021-02-00012-00018','shop two','','Accounts Receivable','2021-10-06',435435345.00,0.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:59:29','2021-10-06 21:59:29'),(13,19,'Income','1021-02-00012-00018','shop two','','Accounts Receivable (VAT)','2021-10-06',0.00,0.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:59:29','2021-10-06 21:59:29'),(14,19,'Income','1021-02-00012-00018','shop two','sdfsdfas','Food Court Service Charge Fine','2021-10-06',0.00,435435345.00,NULL,NULL,'','Income',8,0,'2021-10-06 21:59:29','2021-10-06 21:59:29'),(15,20,'Income','1021-02-00013-00019','shop two','','Accounts Receivable','2021-10-09',33300.30,0.00,NULL,NULL,'','Income',8,0,'2021-10-09 21:20:53','2021-10-09 21:20:53'),(16,20,'Income','1021-02-00013-00019','shop two','','Accounts Receivable (VAT)','2021-10-09',4995.05,0.00,NULL,NULL,'','Income',8,0,'2021-10-09 21:20:53','2021-10-09 21:20:53'),(17,20,'Income','1021-02-00013-00019','shop two','','Sales VAT Payable A/C','2021-10-09',0.00,4995.05,NULL,NULL,'','Income',8,0,'2021-10-09 21:20:53','2021-10-09 21:20:53'),(18,22,'Income','1021-01-00007-00021','shop one','','Sales VAT Payable A/C','2021-10-09',0.00,90.09,NULL,NULL,'','Income',8,0,'2021-10-09 21:44:54','2021-10-09 21:44:54'),(19,22,'Income','1021-01-00007-00021','shop one','','Accounts Receivable (VAT)','2021-10-09',90.09,0.00,NULL,NULL,'','Income',8,0,'2021-10-09 21:44:54','2021-10-09 21:44:54'),(20,22,'Income','1021-01-00007-00021','shop one','','Accounts Receivable','2021-10-09',600.60,0.00,NULL,NULL,'','Income',8,0,'2021-10-09 21:44:54','2021-10-09 21:44:54'),(21,23,'Income','1021-01-00008-00022','shop one','','Sales VAT Payable A/C','2021-10-06',0.00,43.92,NULL,'2021-03-01','','Income',8,0,'2021-10-09 21:49:31','2021-10-09 21:49:31'),(22,23,'Income','1021-01-00008-00022','shop one','','Accounts Receivable (VAT)','2021-10-06',43.92,0.00,NULL,'2021-03-01','','Income',8,0,'2021-10-09 21:49:31','2021-10-09 21:49:31'),(23,23,'Income','1021-01-00008-00022','shop one','','Accounts Receivable','2021-10-06',366.00,0.00,NULL,'2021-03-01','','Income',8,0,'2021-10-09 21:49:31','2021-10-09 21:49:31'),(24,27,'Income','1021-02-00017-00026','shop two','','Sales VAT Payable A/C','2021-10-09',0.00,700.00,NULL,'2021-01-01','','Income',8,0,'2021-10-09 23:09:40','2021-10-09 23:09:40'),(25,27,'Income','1021-02-00017-00026','shop two','','Accounts Receivable (VAT)','2021-10-09',700.00,0.00,NULL,'2021-01-01','','Income',8,0,'2021-10-09 23:09:40','2021-10-09 23:09:40'),(26,27,'Income','1021-02-00017-00026','shop two','','Accounts Receivable','2021-10-09',8000.00,0.00,NULL,'2021-01-01','','Income',8,0,'2021-10-09 23:09:40','2021-10-09 23:09:40'),(27,28,'Income','1021-02-00018-00027','shop two','','Sales VAT Payable A/C','2021-10-15',0.00,450.00,NULL,'2021-04-01','','Income',8,0,'2021-10-09 23:11:51','2021-10-09 23:11:51'),(28,28,'Income','1021-02-00018-00027','shop two','','Accounts Receivable (VAT)','2021-10-15',450.00,0.00,NULL,'2021-04-01','','Income',8,0,'2021-10-09 23:11:51','2021-10-09 23:11:51'),(29,28,'Income','1021-02-00018-00027','shop two','','Accounts Receivable','2021-10-15',7000.00,0.00,NULL,'2021-04-01','','Income',8,0,'2021-10-09 23:11:51','2021-10-09 23:11:51'),(30,29,'Income','1021-01-00009-00028','shop one','','Sales VAT Payable A/C','2021-10-14',0.00,325.00,NULL,'2021-05-01','','Income',8,0,'2021-10-09 23:31:54','2021-10-09 23:31:54'),(31,29,'Income','1021-01-00009-00028','shop one','','Accounts Receivable (VAT)','2021-10-14',325.00,0.00,NULL,'2021-05-01','','Income',8,0,'2021-10-09 23:31:54','2021-10-09 23:31:54'),(32,29,'Income','1021-01-00009-00028','shop one','','Accounts Receivable','2021-10-14',3500.00,0.00,NULL,'2021-05-01','','Income',8,0,'2021-10-09 23:31:54','2021-10-09 23:31:54'),(33,30,'Income','1021-02-00019-00029','shop two','sfsdf','head2','2021-10-16',0.00,300.30,NULL,'2021-05-01','','Income',8,0,'2021-10-09 23:39:04','2021-10-09 23:39:04'),(34,30,'Income','1021-02-00019-00029','shop two','f','head3','2021-10-16',0.00,5000.00,NULL,'2021-05-01','','Income',8,0,'2021-10-09 23:39:04','2021-10-09 23:39:04'),(35,30,'Income','1021-02-00019-00029','shop two','','Sales VAT Payable A/C','2021-10-16',0.00,9166.66,NULL,'2021-05-01','','Income',8,0,'2021-10-09 23:39:04','2021-10-09 23:39:04'),(36,30,'Income','1021-02-00019-00029','shop two','','Accounts Receivable (VAT)','2021-10-16',9166.66,0.00,NULL,'2021-05-01','','Income',8,0,'2021-10-09 23:39:04','2021-10-09 23:39:04'),(37,30,'Income','1021-02-00019-00029','shop two','','Accounts Receivable','2021-10-16',5300.30,0.00,NULL,'2021-05-01','','Income',8,0,'2021-10-09 23:39:04','2021-10-09 23:39:04');

/*Table structure for table `locations` */

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `english_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `locations` */

/*Table structure for table `logs` */

DROP TABLE IF EXISTS `logs`;

CREATE TABLE `logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `action_id` int(11) unsigned DEFAULT NULL,
  `module` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=333 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `logs` */

insert  into `logs`(`id`,`description`,`action`,`type`,`created_by`,`created_at`,`updated_at`,`action_id`,`module`) values (1,'Rony Mondal has Added New chart of account testing head','Add Chart of account','success',8,'2021-09-08 08:26:38','2021-09-08 08:26:38',NULL,NULL),(2,'Rony Mondal has Added New chart of account testing head','Add Chart of account','success',8,'2021-09-08 08:58:03','2021-09-08 08:58:03',NULL,NULL),(3,'Rony Mondal has Added New chart of account sdfsdf','Add Chart of account','success',8,'2021-09-08 08:58:34','2021-09-08 08:58:34',NULL,NULL),(4,'Developer has been assigned permission Read Coa.','Assign Permission to Role','success',8,'2021-09-08 20:10:55','2021-09-08 20:10:55',NULL,NULL),(5,'Read Coa Permission has been added successfully.','Add Permission','success',8,'2021-09-08 20:10:55','2021-09-08 20:10:55',NULL,NULL),(6,'Developer has been assigned permission Create Coa.','Assign Permission to Role','success',8,'2021-09-08 20:11:23','2021-09-08 20:11:23',NULL,NULL),(7,'Create Coa Permission has been added successfully.','Add Permission','success',8,'2021-09-08 20:11:23','2021-09-08 20:11:23',NULL,NULL),(8,'Super Admin has been assigned permission Edit Coa.','Assign Permission to Role','success',8,'2021-09-08 20:11:47','2021-09-08 20:11:47',NULL,NULL),(9,'Developer has been assigned permission Edit Coa.','Assign Permission to Role','success',8,'2021-09-08 20:11:47','2021-09-08 20:11:47',NULL,NULL),(10,'Edit Coa Permission has been added successfully.','Add Permission','success',8,'2021-09-08 20:11:47','2021-09-08 20:11:47',NULL,NULL),(11,'Rony Mondal has updated  chart of account testing head','Add Chart of account','success',8,'2021-09-08 20:58:02','2021-09-08 20:58:02',1,'Chart of account'),(12,'Rony Mondal has updated  chart of account testing head','Add Chart of account','success',8,'2021-09-08 21:03:45','2021-09-08 21:03:45',1,'Chart of account'),(13,'Rony Mondal Group account has been created successfull ','Add Chart of account','success',8,'2021-09-08 21:12:14','2021-09-08 21:12:14',8,'Group Accounts'),(14,'Super Admin has been assigned permission Edit Group Account.','Assign Permission to Role','success',8,'2021-09-08 21:21:05','2021-09-08 21:21:05',0,''),(15,'Developer has been assigned permission Edit Group Account.','Assign Permission to Role','success',8,'2021-09-08 21:21:05','2021-09-08 21:21:05',0,''),(16,'Edit Group Account Permission has been added successfully.','Add Permission','success',8,'2021-09-08 21:21:05','2021-09-08 21:21:05',0,''),(17,'Rony Mondal Group account has been created successfull ','Add','success',8,'2021-09-08 21:21:23','2021-09-08 21:21:23',9,'Group Accounts'),(18,'Rony Mondal Group account has been created successfull ','Add','success',8,'2021-09-08 21:54:05','2021-09-08 21:54:05',10,'Group Accounts'),(19,'Rony Mondal Group account has been updated','Edit','success',8,'2021-09-08 22:00:45','2021-09-08 22:00:45',10,'Group Accounts'),(20,'Super Admin has been assigned permission Read Owner.','Assign Permission to Role','success',8,'2021-09-11 20:19:51','2021-09-11 20:19:51',0,''),(21,'Admin has been assigned permission Read Owner.','Assign Permission to Role','success',8,'2021-09-11 20:19:51','2021-09-11 20:19:51',0,''),(22,'Developer has been assigned permission Read Owner.','Assign Permission to Role','success',8,'2021-09-11 20:19:51','2021-09-11 20:19:51',0,''),(23,'Read Owner Permission has been added successfully.','Add Permission','success',8,'2021-09-11 20:19:52','2021-09-11 20:19:52',0,''),(24,'Super Admin has been assigned permission Create Owner.','Assign Permission to Role','success',8,'2021-09-11 20:20:20','2021-09-11 20:20:20',0,''),(25,'Admin has been assigned permission Create Owner.','Assign Permission to Role','success',8,'2021-09-11 20:20:20','2021-09-11 20:20:20',0,''),(26,'Developer has been assigned permission Create Owner.','Assign Permission to Role','success',8,'2021-09-11 20:20:20','2021-09-11 20:20:20',0,''),(27,'Create Owner Permission has been added successfully.','Add Permission','success',8,'2021-09-11 20:20:20','2021-09-11 20:20:20',0,''),(28,'Rony Mondal New owner has been created successfull ','Add','success',8,'2021-09-11 20:35:37','2021-09-11 20:35:37',3,'Owner Info'),(29,'Super Admin has been assigned permission Delete Owner.','Assign Permission to Role','success',8,'2021-09-11 20:52:56','2021-09-11 20:52:56',0,''),(30,'Admin has been assigned permission Delete Owner.','Assign Permission to Role','success',8,'2021-09-11 20:52:56','2021-09-11 20:52:56',0,''),(31,'Developer has been assigned permission Delete Owner.','Assign Permission to Role','success',8,'2021-09-11 20:52:56','2021-09-11 20:52:56',0,''),(32,'Delete Owner Permission has been added successfully.','Add Permission','success',8,'2021-09-11 20:52:56','2021-09-11 20:52:56',0,''),(33,'Super Admin has been assigned permission Edit Owner.','Assign Permission to Role','success',8,'2021-09-11 20:53:26','2021-09-11 20:53:26',0,''),(34,'Admin has been assigned permission Edit Owner.','Assign Permission to Role','success',8,'2021-09-11 20:53:26','2021-09-11 20:53:26',0,''),(35,'Developer has been assigned permission Edit Owner.','Assign Permission to Role','success',8,'2021-09-11 20:53:26','2021-09-11 20:53:26',0,''),(36,'Edit Owner Permission has been added successfully.','Add Permission','success',8,'2021-09-11 20:53:26','2021-09-11 20:53:26',0,''),(37,'Rony Mondal  owner has been In-active successfull ','delete','success',8,'2021-09-11 22:28:53','2021-09-11 22:28:53',3,'Owner Info'),(38,'Rony Mondal  owner has been In-active successfull ','delete','success',8,'2021-09-11 22:29:18','2021-09-11 22:29:18',3,'Owner Info'),(39,'Super Admin has been assigned permission Update Owner.','Assign Permission to Role','success',8,'2021-09-11 22:36:30','2021-09-11 22:36:30',0,''),(40,'Admin has been assigned permission Update Owner.','Assign Permission to Role','success',8,'2021-09-11 22:36:30','2021-09-11 22:36:30',0,''),(41,'Developer has been assigned permission Update Owner.','Assign Permission to Role','success',8,'2021-09-11 22:36:30','2021-09-11 22:36:30',0,''),(42,'Update Owner Permission has been added successfully.','Add Permission','success',8,'2021-09-11 22:36:30','2021-09-11 22:36:30',0,''),(43,'Rony Mondal  owner has been In-active successfull ','delete','success',8,'2021-09-11 22:36:43','2021-09-11 22:36:43',3,'Owner Info'),(44,'Rony Mondal  owner has been In-active successfull ','delete','success',8,'2021-09-11 22:37:03','2021-09-11 22:37:03',3,'Owner Info'),(45,'Rony Mondal Edit owner has been created successfull ','Edit','success',8,'2021-09-11 22:41:23','2021-09-11 22:41:23',3,'Owner Info'),(46,'Rony Mondal New owner has been created successfull ','Add','success',8,'2021-09-11 23:19:48','2021-09-11 23:19:48',4,'Owner Info'),(47,'Rony Mondal Edit owner has been created successfull ','Edit','success',8,'2021-09-11 23:22:36','2021-09-11 23:22:36',4,'Owner Info'),(48,'Rony Mondal Edit owner has been created successfull ','Edit','success',8,'2021-09-11 23:22:45','2021-09-11 23:22:45',3,'Owner Info'),(49,'Rony Mondal Edit owner has been created successfull ','Edit','success',8,'2021-09-11 23:24:24','2021-09-11 23:24:24',1,'Owner Info'),(50,'Super Admin has been assigned permission Read Unit.','Assign Permission to Role','success',8,'2021-09-12 20:57:30','2021-09-12 20:57:30',0,''),(51,'Admin has been assigned permission Read Unit.','Assign Permission to Role','success',8,'2021-09-12 20:57:30','2021-09-12 20:57:30',0,''),(52,'Developer has been assigned permission Read Unit.','Assign Permission to Role','success',8,'2021-09-12 20:57:31','2021-09-12 20:57:31',0,''),(53,'Read Unit Permission has been added successfully.','Add Permission','success',8,'2021-09-12 20:57:31','2021-09-12 20:57:31',0,''),(54,'Super Admin has been assigned permission Create Unit.','Assign Permission to Role','success',8,'2021-09-12 20:58:10','2021-09-12 20:58:10',0,''),(55,'Admin has been assigned permission Create Unit.','Assign Permission to Role','success',8,'2021-09-12 20:58:10','2021-09-12 20:58:10',0,''),(56,'Developer has been assigned permission Create Unit.','Assign Permission to Role','success',8,'2021-09-12 20:58:10','2021-09-12 20:58:10',0,''),(57,'Create Unit Permission has been added successfully.','Add Permission','success',8,'2021-09-12 20:58:10','2021-09-12 20:58:10',0,''),(58,'Super Admin has been assigned permission Edit Unit.','Assign Permission to Role','success',8,'2021-09-12 20:58:28','2021-09-12 20:58:28',0,''),(59,'Admin has been assigned permission Edit Unit.','Assign Permission to Role','success',8,'2021-09-12 20:58:28','2021-09-12 20:58:28',0,''),(60,'Developer has been assigned permission Edit Unit.','Assign Permission to Role','success',8,'2021-09-12 20:58:28','2021-09-12 20:58:28',0,''),(61,'Edit Unit Permission has been added successfully.','Add Permission','success',8,'2021-09-12 20:58:28','2021-09-12 20:58:28',0,''),(62,'Super Admin has been assigned permission Delete Unit.','Assign Permission to Role','success',8,'2021-09-12 20:58:55','2021-09-12 20:58:55',0,''),(63,'Developer has been assigned permission Delete Unit.','Assign Permission to Role','success',8,'2021-09-12 20:58:55','2021-09-12 20:58:55',0,''),(64,'Delete Unit Permission has been added successfully.','Add Permission','success',8,'2021-09-12 20:58:55','2021-09-12 20:58:55',0,''),(65,'Rony Mondal Measuring Unit has been created successfull ','Add','success',8,'2021-09-12 21:25:33','2021-09-12 21:25:33',NULL,'Measuring Unit'),(66,'Rony Mondal Measuring Unit has been created successfull ','Add','success',8,'2021-09-12 21:28:29','2021-09-12 21:28:29',1,'Measuring Unit'),(67,'Super Admin has been assigned permission Update Unit.','Assign Permission to Role','success',8,'2021-09-12 21:35:42','2021-09-12 21:35:42',0,''),(68,'Admin has been assigned permission Update Unit.','Assign Permission to Role','success',8,'2021-09-12 21:35:42','2021-09-12 21:35:42',0,''),(69,'Developer has been assigned permission Update Unit.','Assign Permission to Role','success',8,'2021-09-12 21:35:42','2021-09-12 21:35:42',0,''),(70,'Update Unit Permission has been added successfully.','Add Permission','success',8,'2021-09-12 21:35:42','2021-09-12 21:35:42',0,''),(71,'Rony Mondal Measuring Unit has been Updated successfull ','Edit','success',8,'2021-09-12 21:36:41','2021-09-12 21:36:41',1,'Measuring Unit'),(72,'Rony Mondal Measuring Unit has been Updated successfull ','Edit','success',8,'2021-09-12 21:39:31','2021-09-12 21:39:31',1,'Measuring Unit'),(73,'Rony Mondal Measuring Unit has been Deleted successfull ','Delete','success',8,'2021-09-12 21:48:36','2021-09-12 21:48:36',1,'Measuring Unit'),(74,'Rony Mondal Measuring Unit has been created successfull ','Add','success',8,'2021-09-12 21:48:50','2021-09-12 21:48:50',2,'Measuring Unit'),(75,'Rony Mondal Measuring Unit has been Deleted successfull ','Delete','success',8,'2021-09-12 21:48:55','2021-09-12 21:48:55',2,'Measuring Unit'),(76,'Super Admin has been assigned permission Create Customer.','Assign Permission to Role','success',8,'2021-09-13 20:04:40','2021-09-13 20:04:40',0,''),(77,'Admin has been assigned permission Create Customer.','Assign Permission to Role','success',8,'2021-09-13 20:04:40','2021-09-13 20:04:40',0,''),(78,'Developer has been assigned permission Create Customer.','Assign Permission to Role','success',8,'2021-09-13 20:04:41','2021-09-13 20:04:41',0,''),(79,'Create Customer Permission has been added successfully.','Add Permission','success',8,'2021-09-13 20:04:41','2021-09-13 20:04:41',0,''),(80,'Super Admin has been assigned permission Edit Customer.','Assign Permission to Role','success',8,'2021-09-13 20:05:06','2021-09-13 20:05:06',0,''),(81,'Admin has been assigned permission Edit Customer.','Assign Permission to Role','success',8,'2021-09-13 20:05:06','2021-09-13 20:05:06',0,''),(82,'Developer has been assigned permission Edit Customer.','Assign Permission to Role','success',8,'2021-09-13 20:05:06','2021-09-13 20:05:06',0,''),(83,'Edit Customer Permission has been added successfully.','Add Permission','success',8,'2021-09-13 20:05:06','2021-09-13 20:05:06',0,''),(84,'Super Admin has been assigned permission Read Customer.','Assign Permission to Role','success',8,'2021-09-13 20:05:50','2021-09-13 20:05:50',0,''),(85,'Admin has been assigned permission Read Customer.','Assign Permission to Role','success',8,'2021-09-13 20:05:50','2021-09-13 20:05:50',0,''),(86,'Developer has been assigned permission Read Customer.','Assign Permission to Role','success',8,'2021-09-13 20:05:50','2021-09-13 20:05:50',0,''),(87,'Read Customer Permission has been added successfully.','Add Permission','success',8,'2021-09-13 20:05:50','2021-09-13 20:05:50',0,''),(88,'Super Admin has been assigned permission Update Customer.','Assign Permission to Role','success',8,'2021-09-13 20:06:31','2021-09-13 20:06:31',0,''),(89,'Temporary has been assigned permission Update Customer.','Assign Permission to Role','success',8,'2021-09-13 20:06:31','2021-09-13 20:06:31',0,''),(90,'Admin has been assigned permission Update Customer.','Assign Permission to Role','success',8,'2021-09-13 20:06:31','2021-09-13 20:06:31',0,''),(91,'Developer has been assigned permission Update Customer.','Assign Permission to Role','success',8,'2021-09-13 20:06:31','2021-09-13 20:06:31',0,''),(92,'Update Customer Permission has been added successfully.','Add Permission','success',8,'2021-09-13 20:06:31','2021-09-13 20:06:31',0,''),(93,'Super Admin has been assigned permission Delete Customer.','Assign Permission to Role','success',8,'2021-09-13 20:07:09','2021-09-13 20:07:09',0,''),(94,'Admin has been assigned permission Delete Customer.','Assign Permission to Role','success',8,'2021-09-13 20:07:09','2021-09-13 20:07:09',0,''),(95,'Developer has been assigned permission Delete Customer.','Assign Permission to Role','success',8,'2021-09-13 20:07:09','2021-09-13 20:07:09',0,''),(96,'Delete Customer Permission has been added successfully.','Add Permission','success',8,'2021-09-13 20:07:09','2021-09-13 20:07:09',0,''),(97,'Rony Mondal -  Customer has been Deleted successfull ','Delete','success',8,'2021-09-13 22:21:33','2021-09-13 22:21:33',1,'Customer Info'),(98,'Rony Mondal - sf Customer has been Deleted successfull ','Delete','success',8,'2021-09-13 22:22:51','2021-09-13 22:22:51',4,'Customer Info'),(99,'Rony Mondal has Added New Lookup system module','Add Lookup','success',8,'2021-09-13 22:24:14','2021-09-13 22:24:14',0,''),(100,'Rony Mondal has Added New Lookup group account','Add Lookup','success',8,'2021-09-13 22:24:53','2021-09-13 22:24:53',0,''),(101,'Rony Mondal has Added New Lookup Pant','Add Lookup','success',8,'2021-09-13 22:26:06','2021-09-13 22:26:06',0,''),(102,'Rony Mondal has Added New Lookup gdfgdfg','Add Lookup','success',8,'2021-09-13 22:26:24','2021-09-13 22:26:24',0,''),(103,'Rony Mondal has Deleted Lookup gdfgdfg','Delete Lookup','danger',8,'2021-09-13 22:27:07','2021-09-13 22:27:07',0,''),(104,'Rony Mondal has Added New Lookup Divison','Add Lookup','success',8,'2021-09-13 23:25:36','2021-09-13 23:25:36',0,''),(105,'Rony Mondal has Added New Lookup Dhaka','Add Lookup','success',8,'2021-09-13 23:26:23','2021-09-13 23:26:23',0,''),(106,'Rony Mondal has Added New Lookup Khulna','Add Lookup','success',8,'2021-09-13 23:27:02','2021-09-13 23:27:02',0,''),(107,'Rony Mondal has Added New Lookup Rajshahi','Add Lookup','success',8,'2021-09-13 23:27:24','2021-09-13 23:27:24',0,''),(108,'Rony Mondal New Customer has been created successfull ','Add','success',8,'2021-09-14 19:47:39','2021-09-14 19:47:39',6,'Customer Info'),(109,'Rony Mondal Customer has been Updated successfull ','Update','success',8,'2021-09-14 20:04:08','2021-09-14 20:04:08',6,'Customer Info'),(110,'Rony Mondal Customer has been Updated successfull ','Update','success',8,'2021-09-14 20:28:08','2021-09-14 20:28:08',NULL,'Customer Info'),(111,'Rony Mondal Customer has been Updated successfull ','Update','success',8,'2021-09-14 20:41:38','2021-09-14 20:41:38',6,'Customer Info'),(112,'Rony Mondal Customer has been Updated successfull ','Update','success',8,'2021-09-14 20:41:45','2021-09-14 20:41:45',6,'Customer Info'),(113,'Rony Mondal Customer has been Updated successfull ','Update','success',8,'2021-09-14 21:12:21','2021-09-14 21:12:21',NULL,'Customer Info'),(114,'Rony Mondal Customer has been Updated successfull ','Update','success',8,'2021-09-14 21:28:49','2021-09-14 21:28:49',NULL,'Customer Info'),(115,'Rony Mondal Customer has been Updated successfull ','Update','success',8,'2021-09-14 21:34:11','2021-09-14 21:34:11',NULL,'Customer Info'),(116,'Rony Mondal Customer has been Updated successfull ','Update','success',8,'2021-09-14 21:34:37','2021-09-14 21:34:37',NULL,'Customer Info'),(117,'Rony Mondal Customer has been Updated successfull ','Update','success',8,'2021-09-14 21:37:57','2021-09-14 21:37:57',NULL,'Customer Info'),(118,'Rony Mondal Customer has been Updated successfull ','Update','success',8,'2021-09-14 21:38:13','2021-09-14 21:38:13',NULL,'Customer Info'),(119,'Rony Mondal - sdfa Customer has been Deleted successfull ','Delete','success',8,'2021-09-14 23:12:36','2021-09-14 23:12:36',5,'Customer Info'),(120,'Super Admin has been assigned permission Read Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:50:00','2021-09-16 20:50:00',0,''),(121,'Admin has been assigned permission Read Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:50:00','2021-09-16 20:50:00',0,''),(122,'Developer has been assigned permission Read Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:50:00','2021-09-16 20:50:00',0,''),(123,'Read Vendor Permission has been added successfully.','Add Permission','success',8,'2021-09-16 20:50:00','2021-09-16 20:50:00',0,''),(124,'Super Admin has been assigned permission Create Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:50:31','2021-09-16 20:50:31',0,''),(125,'Admin has been assigned permission Create Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:50:31','2021-09-16 20:50:31',0,''),(126,'Developer has been assigned permission Create Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:50:31','2021-09-16 20:50:31',0,''),(127,'Create Vendor Permission has been added successfully.','Add Permission','success',8,'2021-09-16 20:50:31','2021-09-16 20:50:31',0,''),(128,'Super Admin has been assigned permission Edit Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:50:57','2021-09-16 20:50:57',0,''),(129,'Admin has been assigned permission Edit Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:50:57','2021-09-16 20:50:57',0,''),(130,'Developer has been assigned permission Edit Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:50:57','2021-09-16 20:50:57',0,''),(131,'Edit Vendor Permission has been added successfully.','Add Permission','success',8,'2021-09-16 20:50:57','2021-09-16 20:50:57',0,''),(132,'Super Admin has been assigned permission Delete Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:51:18','2021-09-16 20:51:18',0,''),(133,'Admin has been assigned permission Delete Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:51:18','2021-09-16 20:51:18',0,''),(134,'Developer has been assigned permission Delete Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:51:18','2021-09-16 20:51:18',0,''),(135,'Delete Vendor Permission has been added successfully.','Add Permission','success',8,'2021-09-16 20:51:18','2021-09-16 20:51:18',0,''),(136,'Super Admin has been assigned permission Update Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:51:45','2021-09-16 20:51:45',0,''),(137,'Admin has been assigned permission Update Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:51:45','2021-09-16 20:51:45',0,''),(138,'Developer has been assigned permission Update Vendor.','Assign Permission to Role','success',8,'2021-09-16 20:51:45','2021-09-16 20:51:45',0,''),(139,'Update Vendor Permission has been added successfully.','Add Permission','success',8,'2021-09-16 20:51:45','2021-09-16 20:51:45',0,''),(140,'Rony Mondal New Vendor has been created successfull ','Add','success',8,'2021-09-16 21:13:04','2021-09-16 21:13:04',1,'Vendor Info'),(141,'Rony Mondal Vendor has been Updated successfull ','Update','success',8,'2021-09-16 21:38:57','2021-09-16 21:38:57',1,'Vendor Info'),(142,'Rony Mondal - ert43534 Customer has been Deleted successfull ','Delete','success',8,'2021-09-18 19:37:41','2021-09-18 19:37:41',6,'Customer Info'),(143,'Rony Mondal Vendor has been Updated successfull ','Update','success',8,'2021-09-18 19:39:54','2021-09-18 19:39:54',1,'Vendor Info'),(144,'Rony Mondal Vendor has been Updated successfull ','Update','success',8,'2021-09-18 21:06:50','2021-09-18 21:06:50',1,'Vendor Info'),(145,'Rony Mondal Measuring Unit has been created successfull ','Add','success',8,'2021-09-19 21:39:58','2021-09-19 21:39:58',1,'Measuring Unit'),(146,'Rony Mondal has Added New Lookup Products','Add Lookup','success',8,'2021-09-21 22:32:27','2021-09-21 22:32:27',0,''),(147,'Rony Mondal has Added New Lookup Suger','Add Lookup','success',8,'2021-09-21 22:33:19','2021-09-21 22:33:19',0,''),(148,'Rony Mondal has Added New Lookup Sub Category','Add Lookup','success',8,'2021-09-21 22:34:03','2021-09-21 22:34:03',0,''),(149,'Rony Mondal has Added New Lookup Coa Category','Add Lookup','success',8,'2021-09-22 21:08:46','2021-09-22 21:08:46',0,''),(150,'Rony Mondal has Edited Lookup Coa Sub Category','Edit Lookup','info',8,'2021-09-22 21:09:22','2021-09-22 21:09:22',0,''),(151,'Rony Mondal has Edited Lookup Coa Sub Category','Edit Lookup','info',8,'2021-09-22 21:09:28','2021-09-22 21:09:28',0,''),(152,'Rony Mondal has Added New Lookup test one','Add Lookup','success',8,'2021-09-22 21:10:39','2021-09-22 21:10:39',0,''),(153,'Rony Mondal has Edited Lookup test one','Edit Lookup','info',8,'2021-09-22 21:10:50','2021-09-22 21:10:50',0,''),(154,'Rony Mondal has Added New Lookup test two','Add Lookup','success',8,'2021-09-22 21:16:14','2021-09-22 21:16:14',0,''),(155,'Rony Mondal has Added New Lookup test two1','Add Lookup','success',8,'2021-09-22 21:18:05','2021-09-22 21:18:05',0,''),(156,'Rony Mondal has Deleted Lookup test two','Delete Lookup','danger',8,'2021-09-22 22:19:17','2021-09-22 22:19:17',0,''),(157,'Rony Mondal has Deleted Lookup test two1','Delete Lookup','danger',8,'2021-09-22 22:19:24','2021-09-22 22:19:24',0,''),(158,'Rony Mondal has Added New Lookup test one','Add Lookup','success',8,'2021-09-22 22:21:53','2021-09-22 22:21:53',0,''),(159,'Rony Mondal has Added New Lookup a','Add Lookup','success',8,'2021-09-22 22:22:15','2021-09-22 22:22:15',0,''),(160,'Rony Mondal has Added New Lookup b','Add Lookup','success',8,'2021-09-22 22:23:02','2021-09-22 22:23:02',0,''),(161,'Rony Mondal has Added New Lookup a1','Add Lookup','success',8,'2021-09-22 22:25:41','2021-09-22 22:25:41',0,''),(162,'Rony Mondal has Added New Lookup a2','Add Lookup','success',8,'2021-09-22 22:32:52','2021-09-22 22:32:52',0,''),(163,'Rony Mondal has Added New Lookup a12','Add Lookup','success',8,'2021-09-22 22:34:30','2021-09-22 22:34:30',0,''),(164,'Rony Mondal has Added New Lookup a13','Add Lookup','success',8,'2021-09-22 22:36:01','2021-09-22 22:36:01',0,''),(165,'Rony Mondal has Added New Lookup check-category','Add Lookup','success',8,'2021-09-22 22:42:14','2021-09-22 22:42:14',0,''),(166,'Rony MondalNew Vendor has been created successfull ','Add','success',8,'2021-09-22 23:11:14','2021-09-22 23:11:14',2,'Vendor Info'),(167,'Rony Mondal has Added New Lookup test one','Add Lookup','success',8,'2021-09-23 22:16:53','2021-09-23 22:16:53',0,''),(168,'Rony Mondal has Added New Lookup 12233','Add Lookup','success',8,'2021-09-23 22:19:52','2021-09-23 22:19:52',0,''),(169,'Rony Mondal Vendor has been Updated successfull ','Update','success',8,'2021-09-23 23:17:45','2021-09-23 23:17:45',1,'Vendor Info'),(170,'Rony Mondal Vendor has been Updated successfull ','Update','success',8,'2021-09-23 23:18:52','2021-09-23 23:18:52',2,'Vendor Info'),(171,'Rony Mondal has Added New Lookup two','Add Lookup','success',8,'2021-09-23 23:54:54','2021-09-23 23:54:54',0,''),(172,'Rony Mondal has Added New Lookup cat1','Add Lookup','success',8,'2021-09-24 00:06:58','2021-09-24 00:06:58',0,''),(173,'Rony Mondal has Added New Lookup cat2','Add Lookup','success',8,'2021-09-24 00:10:37','2021-09-24 00:10:37',0,''),(174,'Rony Mondal has Added New Lookup sub-cat-1','Add Lookup','success',8,'2021-09-24 00:15:31','2021-09-24 00:15:31',0,''),(175,'Rony Mondal has Added New Lookup sub-cat-2','Add Lookup','success',8,'2021-09-24 00:16:00','2021-09-24 00:16:00',0,''),(176,'Rony Mondal has Added New Lookup sub-sub-cat','Add Lookup','success',8,'2021-09-24 00:16:37','2021-09-24 00:16:37',0,''),(177,'Rony Mondal has Added New Lookup sub-cat-3','Add Lookup','success',8,'2021-09-24 00:18:07','2021-09-24 00:18:07',0,''),(178,'Rony Mondal has Added New Lookup sub-sub-cat-4','Add Lookup','success',8,'2021-09-24 00:18:38','2021-09-24 00:18:38',0,''),(179,'Rony Mondal has Added New chart of account test','Add Chart of account','success',8,'2021-09-24 22:40:22','2021-09-24 22:40:22',0,''),(180,'Rony MondalNew Vendor has been created successfull ','Add','success',8,'2021-09-25 08:27:33','2021-09-25 08:27:33',3,'Vendor Info'),(181,'Rony MondalNew Vendor has been created successfull ','Add','success',8,'2021-09-25 08:38:00','2021-09-25 08:38:00',4,'Vendor Info'),(182,'Rony Mondal has Added New Lookup Asset','Add Lookup','success',8,'2021-09-25 20:27:33','2021-09-25 20:27:33',0,''),(183,'Rony Mondal has Added New Lookup Current Assets','Add Lookup','success',8,'2021-09-25 20:28:39','2021-09-25 20:28:39',0,''),(184,'Rony Mondal has Added New Lookup Testing one 236541','Add Lookup','success',8,'2021-09-25 20:29:18','2021-09-25 20:29:18',0,''),(185,'Rony Mondal has Added New Lookup Division','Add Lookup','success',8,'2021-09-25 21:05:50','2021-09-25 21:05:50',0,''),(186,'Rony Mondal has Added New Lookup Asset','Add Lookup','success',8,'2021-09-25 21:06:32','2021-09-25 21:06:32',0,''),(187,'Rony Mondal has Added New Lookup Non-Current Assets','Add Lookup','success',8,'2021-09-25 21:07:22','2021-09-25 21:07:22',0,''),(188,'Rony Mondal has Added New Lookup Current Assets','Add Lookup','success',8,'2021-09-25 21:33:56','2021-09-25 21:33:56',0,''),(189,'Rony Mondal has Added New Lookup Pant','Add Lookup','success',8,'2021-09-25 21:37:31','2021-09-25 21:37:31',0,''),(190,'Rony Mondal has Added New Lookup Current Assets','Add Lookup','success',8,'2021-09-25 21:42:32','2021-09-25 21:42:32',0,''),(191,'Rony Mondal has Added New Lookup Non-Current Assets','Add Lookup','success',8,'2021-09-25 21:43:00','2021-09-25 21:43:00',0,''),(192,'Rony Mondal has Added New Lookup Pant','Add Lookup','success',8,'2021-09-25 21:46:54','2021-09-25 21:46:54',0,''),(193,'Rony Mondal has Added New Lookup Non-Current Assets','Add Lookup','success',8,'2021-09-25 21:47:58','2021-09-25 21:47:58',0,''),(194,'Rony Mondal has Added New Lookup Non-Current Assets','Add Lookup','success',8,'2021-09-25 21:51:29','2021-09-25 21:51:29',0,''),(195,'Rony Mondal has Added New Lookup Non-Current Assets','Add Lookup','success',8,'2021-09-25 21:53:03','2021-09-25 21:53:03',0,''),(196,'Rony Mondal has Added New Lookup Assestive Devices','Add Lookup','success',8,'2021-09-25 21:59:20','2021-09-25 21:59:20',0,''),(197,'Rony Mondal has Added New Lookup Dhaka','Add Lookup','success',8,'2021-09-25 22:23:35','2021-09-25 22:23:35',0,''),(198,'Rony Mondal has Added New Lookup Fixed Asset','Add Lookup','success',8,'2021-09-25 22:31:24','2021-09-25 22:31:24',0,''),(199,'Rony Mondal has Added New Lookup Motor Vehicle','Add Lookup','success',8,'2021-09-25 22:32:12','2021-09-25 22:32:12',0,''),(200,'Rony Mondal has Added New Lookup Liability','Add Lookup','success',8,'2021-09-25 22:33:57','2021-09-25 22:33:57',0,''),(201,'Rony Mondal has Added New Lookup Motor Cycle Purchase A/C','Add Lookup','success',8,'2021-09-25 22:52:12','2021-09-25 22:52:12',0,''),(202,'Rony Mondal has Added New Lookup ts','Add Lookup','success',8,'2021-09-25 23:05:47','2021-09-25 23:05:47',0,''),(203,'Rony Mondal has Added New Lookup tsdsf','Add Lookup','success',8,'2021-09-25 23:11:07','2021-09-25 23:11:07',0,''),(204,'Rony Mondal has Added New Lookup Income','Add Lookup','success',8,'2021-09-26 20:19:02','2021-09-26 20:19:02',0,''),(205,'Rony Mondal has Added New Lookup Expense','Add Lookup','success',8,'2021-09-26 20:19:40','2021-09-26 20:19:40',0,''),(206,'Rony Mondal has Added New chart of account fsdf','Add Chart of account','success',8,'2021-09-26 21:43:51','2021-09-26 21:43:51',0,''),(207,'Rony Mondal has Added New chart of account testing head','Add Chart of account','success',8,'2021-09-26 21:45:58','2021-09-26 21:45:58',0,''),(208,'Rony Mondal has Added New chart of account testing head','Add Chart of account','success',8,'2021-09-26 21:48:50','2021-09-26 21:48:50',0,''),(209,'Rony Mondal has updated  chart of account testing head236','Add Chart of account','success',8,'2021-09-26 22:02:07','2021-09-26 22:02:07',8,'Chart of account'),(210,'Super Admin has been assigned permission Create Godown.','Assign Permission to Role','success',8,'2021-09-27 22:25:44','2021-09-27 22:25:44',0,''),(211,'Admin has been assigned permission Create Godown.','Assign Permission to Role','success',8,'2021-09-27 22:25:45','2021-09-27 22:25:45',0,''),(212,'Developer has been assigned permission Create Godown.','Assign Permission to Role','success',8,'2021-09-27 22:25:45','2021-09-27 22:25:45',0,''),(213,'Create Godown Permission has been added successfully.','Add Permission','success',8,'2021-09-27 22:25:45','2021-09-27 22:25:45',0,''),(214,'Super Admin has been assigned permission Read Godown.','Assign Permission to Role','success',8,'2021-09-27 22:26:08','2021-09-27 22:26:08',0,''),(215,'Admin has been assigned permission Read Godown.','Assign Permission to Role','success',8,'2021-09-27 22:26:08','2021-09-27 22:26:08',0,''),(216,'Developer has been assigned permission Read Godown.','Assign Permission to Role','success',8,'2021-09-27 22:26:08','2021-09-27 22:26:08',0,''),(217,'Read Godown Permission has been added successfully.','Add Permission','success',8,'2021-09-27 22:26:08','2021-09-27 22:26:08',0,''),(218,'Super Admin has been assigned permission Edit Godown.','Assign Permission to Role','success',8,'2021-09-27 22:26:31','2021-09-27 22:26:31',0,''),(219,'Admin has been assigned permission Edit Godown.','Assign Permission to Role','success',8,'2021-09-27 22:26:31','2021-09-27 22:26:31',0,''),(220,'Developer has been assigned permission Edit Godown.','Assign Permission to Role','success',8,'2021-09-27 22:26:31','2021-09-27 22:26:31',0,''),(221,'Edit Godown Permission has been added successfully.','Add Permission','success',8,'2021-09-27 22:26:31','2021-09-27 22:26:31',0,''),(222,'Super Admin has been assigned permission Delete Godown.','Assign Permission to Role','success',8,'2021-09-27 22:26:47','2021-09-27 22:26:47',0,''),(223,'Admin has been assigned permission Delete Godown.','Assign Permission to Role','success',8,'2021-09-27 22:26:47','2021-09-27 22:26:47',0,''),(224,'Developer has been assigned permission Delete Godown.','Assign Permission to Role','success',8,'2021-09-27 22:26:48','2021-09-27 22:26:48',0,''),(225,'Delete Godown Permission has been added successfully.','Add Permission','success',8,'2021-09-27 22:26:48','2021-09-27 22:26:48',0,''),(226,'Super Admin has been assigned permission Update Godown.','Assign Permission to Role','success',8,'2021-09-27 22:27:08','2021-09-27 22:27:08',0,''),(227,'Admin has been assigned permission Update Godown.','Assign Permission to Role','success',8,'2021-09-27 22:27:08','2021-09-27 22:27:08',0,''),(228,'Developer has been assigned permission Update Godown.','Assign Permission to Role','success',8,'2021-09-27 22:27:08','2021-09-27 22:27:08',0,''),(229,'Update Godown Permission has been added successfully.','Add Permission','success',8,'2021-09-27 22:27:08','2021-09-27 22:27:08',0,''),(230,'Rony MondalNew Store has been created successfull ','Add','success',8,'2021-09-27 22:33:16','2021-09-27 22:33:16',2,'Store Info'),(231,'Rony MondalNew Store has been created successfull ','Add','success',8,'2021-09-27 22:51:45','2021-09-27 22:51:45',1,'Store Info'),(232,'Super Admin has been assigned permission Create Product.','Assign Permission to Role','success',8,'2021-09-28 21:18:49','2021-09-28 21:18:49',0,''),(233,'Admin has been assigned permission Create Product.','Assign Permission to Role','success',8,'2021-09-28 21:18:49','2021-09-28 21:18:49',0,''),(234,'Developer has been assigned permission Create Product.','Assign Permission to Role','success',8,'2021-09-28 21:18:49','2021-09-28 21:18:49',0,''),(235,'Create Product Permission has been added successfully.','Add Permission','success',8,'2021-09-28 21:18:49','2021-09-28 21:18:49',0,''),(236,'Super Admin has been assigned permission Update Product.','Assign Permission to Role','success',8,'2021-09-28 21:19:10','2021-09-28 21:19:10',0,''),(237,'Developer has been assigned permission Update Product.','Assign Permission to Role','success',8,'2021-09-28 21:19:11','2021-09-28 21:19:11',0,''),(238,'Update Product Permission has been added successfully.','Add Permission','success',8,'2021-09-28 21:19:11','2021-09-28 21:19:11',0,''),(239,'Super Admin has been assigned permission Read Product.','Assign Permission to Role','success',8,'2021-09-28 21:19:33','2021-09-28 21:19:33',0,''),(240,'Developer has been assigned permission Read Product.','Assign Permission to Role','success',8,'2021-09-28 21:19:33','2021-09-28 21:19:33',0,''),(241,'Read Product Permission has been added successfully.','Add Permission','success',8,'2021-09-28 21:19:33','2021-09-28 21:19:33',0,''),(242,'Super Admin has been assigned permission Delete Product.','Assign Permission to Role','success',8,'2021-09-28 21:20:56','2021-09-28 21:20:56',0,''),(243,'Admin has been assigned permission Delete Product.','Assign Permission to Role','success',8,'2021-09-28 21:20:56','2021-09-28 21:20:56',0,''),(244,'Developer has been assigned permission Delete Product.','Assign Permission to Role','success',8,'2021-09-28 21:20:57','2021-09-28 21:20:57',0,''),(245,'Delete Product Permission has been added successfully.','Add Permission','success',8,'2021-09-28 21:20:57','2021-09-28 21:20:57',0,''),(246,'Super Admin has been assigned permission Edit Product.','Assign Permission to Role','success',8,'2021-09-28 21:21:27','2021-09-28 21:21:27',0,''),(247,'Admin has been assigned permission Edit Product.','Assign Permission to Role','success',8,'2021-09-28 21:21:27','2021-09-28 21:21:27',0,''),(248,'Developer has been assigned permission Edit Product.','Assign Permission to Role','success',8,'2021-09-28 21:21:27','2021-09-28 21:21:27',0,''),(249,'Edit Product Permission has been added successfully.','Add Permission','success',8,'2021-09-28 21:21:27','2021-09-28 21:21:27',0,''),(250,'Rony Mondal New Product has been created successfull ','Add','success',8,'2021-09-29 23:09:19','2021-09-29 23:09:19',1,'Product Info'),(251,'Rony Mondal New Product has been created successfull ','Add','success',8,'2021-09-30 21:52:44','2021-09-30 21:52:44',2,'Product Info'),(252,'Rony Mondal New Product has been created successfull ','Add','success',8,'2021-09-30 22:44:05','2021-09-30 22:44:05',3,'Product Info'),(253,'Rony Mondal   Product has been Updated successfull ','Update','success',8,'2021-09-30 23:01:18','2021-09-30 23:01:18',1,'Product Info'),(254,'Rony Mondal   Product has been Updated successfull ','Update','success',8,'2021-09-30 23:03:43','2021-09-30 23:03:43',3,'Product Info'),(255,'Rony Mondal   Product has been Updated successfull ','Update','success',8,'2021-09-30 23:28:52','2021-09-30 23:28:52',3,'Product Info'),(256,'Rony Mondal   Product has been Updated successfull ','Update','success',8,'2021-09-30 23:30:42','2021-09-30 23:30:42',3,'Product Info'),(257,'Rony Mondal - sdfsd Product has been Deleted successfull ','Delete','success',8,'2021-09-30 23:33:04','2021-09-30 23:33:04',3,'Product Info'),(258,'Rony Mondal New Product has been created successfull ','Add','success',8,'2021-09-30 23:52:57','2021-09-30 23:52:57',4,'Product Info'),(259,'Super Admin has been assigned permission Read Tax.','Assign Permission to Role','success',8,'2021-10-02 21:50:41','2021-10-02 21:50:41',0,''),(260,'Developer has been assigned permission Read Tax.','Assign Permission to Role','success',8,'2021-10-02 21:50:42','2021-10-02 21:50:42',0,''),(261,'Read Tax Permission has been added successfully.','Add Permission','success',8,'2021-10-02 21:50:42','2021-10-02 21:50:42',0,''),(262,'Super Admin has been assigned permission Update Tax.','Assign Permission to Role','success',8,'2021-10-02 21:54:01','2021-10-02 21:54:01',0,''),(263,'Developer has been assigned permission Update Tax.','Assign Permission to Role','success',8,'2021-10-02 21:54:01','2021-10-02 21:54:01',0,''),(264,'Update Tax Permission has been added successfully.','Add Permission','success',8,'2021-10-02 21:54:01','2021-10-02 21:54:01',0,''),(265,'Super Admin has been assigned permission Edit Tax.','Assign Permission to Role','success',8,'2021-10-02 21:54:20','2021-10-02 21:54:20',0,''),(266,'Developer has been assigned permission Edit Tax.','Assign Permission to Role','success',8,'2021-10-02 21:54:20','2021-10-02 21:54:20',0,''),(267,'Edit Tax Permission has been added successfully.','Add Permission','success',8,'2021-10-02 21:54:20','2021-10-02 21:54:20',0,''),(268,'Super Admin has been assigned permission Delete Tax.','Assign Permission to Role','success',8,'2021-10-02 21:54:37','2021-10-02 21:54:37',0,''),(269,'Developer has been assigned permission Delete Tax.','Assign Permission to Role','success',8,'2021-10-02 21:54:37','2021-10-02 21:54:37',0,''),(270,'Delete Tax Permission has been added successfully.','Add Permission','success',8,'2021-10-02 21:54:37','2021-10-02 21:54:37',0,''),(271,'Super Admin has been assigned permission Create Tax.','Assign Permission to Role','success',8,'2021-10-02 21:54:58','2021-10-02 21:54:58',0,''),(272,'Developer has been assigned permission Create Tax.','Assign Permission to Role','success',8,'2021-10-02 21:54:58','2021-10-02 21:54:58',0,''),(273,'Create Tax Permission has been added successfully.','Add Permission','success',8,'2021-10-02 21:54:58','2021-10-02 21:54:58',0,''),(274,'Rony MondalNew Tax has been created successfull ','Add','success',8,'2021-10-02 22:33:53','2021-10-02 22:33:53',1,'Tax Info'),(275,'Rony MondalTax has been updated successfull ','Update','success',8,'2021-10-02 22:36:32','2021-10-02 22:36:32',1,'Tax Info'),(276,'Rony MondalTax has been updated successfull ','Update','success',8,'2021-10-02 22:38:32','2021-10-02 22:38:32',1,'Tax Info'),(277,'Super Admin has been assigned permission Create Income.','Assign Permission to Role','success',8,'2021-10-03 00:40:12','2021-10-03 00:40:12',0,''),(278,'Developer has been assigned permission Create Income.','Assign Permission to Role','success',8,'2021-10-03 00:40:12','2021-10-03 00:40:12',0,''),(279,'Create Income Permission has been added successfully.','Add Permission','success',8,'2021-10-03 00:40:12','2021-10-03 00:40:12',0,''),(280,'Super Admin has been assigned permission Read Income.','Assign Permission to Role','success',8,'2021-10-03 00:40:34','2021-10-03 00:40:34',0,''),(281,'Developer has been assigned permission Read Income.','Assign Permission to Role','success',8,'2021-10-03 00:40:34','2021-10-03 00:40:34',0,''),(282,'Read Income Permission has been added successfully.','Add Permission','success',8,'2021-10-03 00:40:34','2021-10-03 00:40:34',0,''),(283,'Super Admin has been assigned permission Edit Income.','Assign Permission to Role','success',8,'2021-10-03 00:41:01','2021-10-03 00:41:01',0,''),(284,'Developer has been assigned permission Edit Income.','Assign Permission to Role','success',8,'2021-10-03 00:41:01','2021-10-03 00:41:01',0,''),(285,'Edit Income Permission has been added successfully.','Add Permission','success',8,'2021-10-03 00:41:01','2021-10-03 00:41:01',0,''),(286,'Super Admin has been assigned permission Update Income.','Assign Permission to Role','success',8,'2021-10-03 00:41:24','2021-10-03 00:41:24',0,''),(287,'Developer has been assigned permission Update Income.','Assign Permission to Role','success',8,'2021-10-03 00:41:25','2021-10-03 00:41:25',0,''),(288,'Update Income Permission has been added successfully.','Add Permission','success',8,'2021-10-03 00:41:25','2021-10-03 00:41:25',0,''),(289,'Super Admin has been assigned permission Delete Income.','Assign Permission to Role','success',8,'2021-10-03 00:41:42','2021-10-03 00:41:42',0,''),(290,'Developer has been assigned permission Delete Income.','Assign Permission to Role','success',8,'2021-10-03 00:41:42','2021-10-03 00:41:42',0,''),(291,'Delete Income Permission has been added successfully.','Add Permission','success',8,'2021-10-03 00:41:42','2021-10-03 00:41:42',0,''),(292,'Rony Mondal New Customer has been created successfull ','Add','success',8,'2021-10-03 19:22:47','2021-10-03 19:22:47',1,'Customer Info'),(293,'Rony Mondal New Customer has been created successfull ','Add','success',8,'2021-10-03 19:24:08','2021-10-03 19:24:08',4,'Customer Info'),(294,'Rony Mondal has Added New Lookup Income','Add Lookup','success',8,'2021-10-03 19:26:12','2021-10-03 19:26:12',0,''),(295,'Rony Mondal has Added New Lookup Direct Income','Add Lookup','success',8,'2021-10-03 19:27:26','2021-10-03 19:27:26',0,''),(296,'Rony Mondal has Added New Lookup Indirect Income','Add Lookup','success',8,'2021-10-03 19:27:43','2021-10-03 19:27:43',0,''),(297,'Rony Mondal has Added New Lookup Revenue','Add Lookup','success',8,'2021-10-03 19:28:23','2021-10-03 19:28:23',0,''),(298,'Rony Mondal has Added New Lookup Other Income','Add Lookup','success',8,'2021-10-03 19:28:43','2021-10-03 19:28:43',0,''),(299,'Rony Mondal has Added New Lookup Service Charge Fine','Add Lookup','success',8,'2021-10-03 19:30:05','2021-10-03 19:30:05',0,''),(300,'Rony Mondal has Added New Lookup Food Court Service Charge Fine','Add Lookup','success',8,'2021-10-03 19:30:55','2021-10-03 19:30:55',0,''),(301,'Rony Mondal has Added New Lookup Electricity Fine','Add Lookup','success',8,'2021-10-03 19:31:25','2021-10-03 19:31:25',0,''),(302,'Rony Mondal has Added New Lookup Electricity Bill Collection Office','Add Lookup','success',8,'2021-10-03 19:31:59','2021-10-03 19:31:59',0,''),(303,'Rony Mondal has Added New chart of account head1','Add Chart of account','success',8,'2021-10-03 19:34:21','2021-10-03 19:34:21',0,''),(304,'Rony Mondal has Added New chart of account head2','Add Chart of account','success',8,'2021-10-03 19:34:44','2021-10-03 19:34:44',0,''),(305,'Rony Mondal has Added New chart of account head3','Add Chart of account','success',8,'2021-10-03 19:35:11','2021-10-03 19:35:11',0,''),(306,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-03 22:25:45','2021-10-03 22:25:45',NULL,'Income'),(307,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-03 22:33:52','2021-10-03 22:33:52',2,'Income'),(308,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-03 22:36:00','2021-10-03 22:36:00',5,'Income'),(309,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-03 22:36:50','2021-10-03 22:36:50',6,'Income'),(310,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-03 22:47:30','2021-10-03 22:47:30',7,'Income'),(311,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-03 22:48:23','2021-10-03 22:48:23',8,'Income'),(312,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-03 22:49:39','2021-10-03 22:49:39',9,'Income'),(313,'Rony MondalNew Income has been updated successfull ','Add','success',8,'2021-10-05 19:44:17','2021-10-05 19:44:17',9,'Income'),(314,'Rony MondalNew Income has been updated successfull ','Add','success',8,'2021-10-05 19:44:59','2021-10-05 19:44:59',9,'Income'),(315,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-05 22:00:46','2021-10-05 22:00:46',10,'Income'),(316,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-05 22:57:13','2021-10-05 22:57:13',11,'Income'),(317,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-06 20:59:32','2021-10-06 20:59:32',13,'Income'),(318,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-06 21:01:52','2021-10-06 21:01:52',14,'Income'),(319,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-06 21:51:48','2021-10-06 21:51:48',15,'Income'),(320,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-06 21:55:26','2021-10-06 21:55:26',16,'Income'),(321,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-06 21:58:54','2021-10-06 21:58:54',18,'Income'),(322,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-06 21:59:29','2021-10-06 21:59:29',19,'Income'),(323,'Super Admin has been assigned permission Read Journal.','Assign Permission to Role','success',8,'2021-10-07 19:28:41','2021-10-07 19:28:41',0,''),(324,'Developer has been assigned permission Read Journal.','Assign Permission to Role','success',8,'2021-10-07 19:28:41','2021-10-07 19:28:41',0,''),(325,'Read Journal Permission has been added successfully.','Add Permission','success',8,'2021-10-07 19:28:41','2021-10-07 19:28:41',0,''),(326,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-09 21:20:53','2021-10-09 21:20:53',20,'Income'),(327,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-09 21:44:54','2021-10-09 21:44:54',22,'Income'),(328,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-09 21:49:31','2021-10-09 21:49:31',23,'Income'),(329,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-09 23:09:40','2021-10-09 23:09:40',27,'Income'),(330,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-09 23:11:51','2021-10-09 23:11:51',28,'Income'),(331,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-09 23:31:54','2021-10-09 23:31:54',29,'Income'),(332,'Rony MondalNew Income has been created successfull ','Add','success',8,'2021-10-09 23:39:04','2021-10-09 23:39:04',30,'Income');

/*Table structure for table `lookups` */

DROP TABLE IF EXISTS `lookups`;

CREATE TABLE `lookups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `group_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `child_id` int(11) DEFAULT '0',
  `child_id_2` int(11) DEFAULT '0',
  `priority` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '1',
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lookups` */

insert  into `lookups`(`id`,`parent_id`,`name`,`description`,`group_id`,`category_id`,`child_id`,`child_id_2`,`priority`,`status`,`updated_by`,`created_at`,`updated_at`) values (1,0,'Division',NULL,NULL,NULL,0,0,1,1,8,'2021-09-25 21:05:50','2021-09-25 21:05:50'),(2,0,'Asset',NULL,NULL,NULL,0,0,1,1,8,'2021-09-25 21:06:32','2021-09-25 21:06:32'),(6,2,'Current Assets','Current Assets',0,NULL,0,0,2,1,8,'2021-09-25 21:42:32','2021-09-25 21:42:32'),(13,1,'Dhaka','Dhaka',0,0,0,0,1,1,8,'2021-09-25 22:23:35','2021-09-25 22:23:35'),(14,2,'Fixed Asset',NULL,0,0,0,0,1,1,8,'2021-09-25 22:31:23','2021-09-25 22:31:23'),(16,0,'Liability','Liability',0,0,0,0,1,1,8,'2021-09-25 22:33:57','2021-09-25 22:33:57'),(20,0,'Income','Income',0,0,0,0,1,1,8,'2021-09-26 20:19:02','2021-09-26 20:19:02'),(21,0,'Expense','Expense',0,0,0,0,1,1,8,'2021-09-26 20:19:40','2021-09-26 20:19:40'),(23,20,'Direct Income','Direct Income',0,0,0,0,2,1,8,'2021-10-03 19:27:26','2021-10-03 19:27:26'),(24,20,'Indirect Income','Indirect Income',0,0,0,0,2,1,8,'2021-10-03 19:27:43','2021-10-03 19:27:43'),(25,20,'Revenue','Revenue',23,0,0,0,3,1,8,'2021-10-03 19:28:23','2021-10-03 19:28:23'),(26,20,'Other Income','Other Income',24,0,0,0,3,1,8,'2021-10-03 19:28:43','2021-10-03 19:28:43'),(27,20,'Service Charge Fine',NULL,23,25,0,0,4,1,8,'2021-10-03 19:30:05','2021-10-03 19:30:05'),(28,20,'Food Court Service Charge Fine','Food Court Service Charge Fine',23,25,0,0,4,1,8,'2021-10-03 19:30:55','2021-10-03 19:30:55'),(29,20,'Electricity Fine','Electricity Fine',23,25,0,0,4,1,8,'2021-10-03 19:31:25','2021-10-03 19:31:25'),(30,20,'Electricity Bill Collection Office','Electricity Bill Collection Office',23,25,0,0,4,1,8,'2021-10-03 19:31:59','2021-10-03 19:31:59');

/*Table structure for table `measurement_units` */

DROP TABLE IF EXISTS `measurement_units`;

CREATE TABLE `measurement_units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `measurement_units` */

insert  into `measurement_units`(`id`,`name`,`short_name`,`created_by`,`updated_by`,`created_at`,`updated_at`) values (1,'Pant','t',8,NULL,'2021-09-19 21:39:58','2021-09-19 21:39:58');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_06_01_000001_create_oauth_auth_codes_table',1),(4,'2016_06_01_000002_create_oauth_access_tokens_table',1),(5,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),(6,'2016_06_01_000004_create_oauth_clients_table',1),(7,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),(8,'2019_08_19_000000_create_failed_jobs_table',1),(9,'2020_04_16_081558_create_permission_tables',1),(10,'2020_04_16_195157_create_user_details_table',2),(11,'2019_08_02_214654_create_logs_table',3),(12,'2019_08_04_001852_create_notifications_table',3),(13,'2019_08_06_204306_create_lookups_table',3),(14,'2020_02_04_232002_create_locations_table',4),(16,'2020_05_14_195130_create_user_tables_combinations_table',5),(53,'2021_06_13_152211_create_settings_table',16),(56,'2021_07_29_171527_create_s_m_s_table',17),(57,'2021_08_06_180303_create_emails_table',18);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_roles` */

insert  into `model_has_roles`(`role_id`,`model_type`,`model_id`) values (1,'App\\User',9),(1,'App\\User',10),(4,'App\\User',1),(6,'App\\Customer',1),(6,'App\\Customer',2),(6,'App\\Customer',3),(6,'App\\Customer',4),(4,'App\\User',8);

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `send_other_devices` int(11) NOT NULL,
  `created_for` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `notifications` */

/*Table structure for table `oauth_access_tokens` */

DROP TABLE IF EXISTS `oauth_access_tokens`;

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `oauth_access_tokens` */

insert  into `oauth_access_tokens`(`id`,`user_id`,`client_id`,`name`,`scopes`,`revoked`,`created_at`,`updated_at`,`expires_at`) values ('003ae20e255601a6cb9297be8a6b238d467f4755afdaf652233f1f8f9f397c09a7732f1eb5df5a6a',1,1,'authToken','[]',0,'2020-05-08 06:37:12','2020-05-08 06:37:12','2021-05-08 06:37:12'),('0df013390b9ba2ba7eb46064ce9445cb8752656ea06532ff0eea9b8d511053b416966ec204835046',1,1,'authToken','[]',0,'2020-04-26 03:59:10','2020-04-26 03:59:10','2021-04-26 03:59:10'),('105c57502a543826448469c9705029dcda50808e315b6f460876c0acb970917aa445133e4289f5f6',2,1,'authToken','[]',0,'2020-04-27 07:27:37','2020-04-27 07:27:37','2021-04-27 07:27:37'),('13de4ae3e6c4c35abb7e48b3cdd93a897ad3cfbae9672c5657f8b9fc62edc9908136f027c097455f',1,1,'authToken','[]',0,'2020-04-26 03:23:13','2020-04-26 03:23:13','2021-04-26 03:23:13'),('180be91b04978019e82d6979da5166b12657e3cb25fdc347e69193cae9c84456bbbefa09ab24778b',1,1,'authToken','[]',0,'2020-04-19 00:08:56','2020-04-19 00:08:56','2021-04-19 00:08:56'),('26d24e1f8a96a3be8a0b87bf9086bf68680e6f33ed6d2d3a205bbdeea634394445f5f3fed1176849',1,1,'authToken','[]',0,'2020-04-30 11:45:46','2020-04-30 11:45:46','2021-04-30 11:45:46'),('3808744a59392b99a2ec1801f75ebd3e9bd2d368ac430201bea8c5a263436582d351d4ccc87ec56a',1,1,'authToken','[]',0,'2020-05-09 03:24:21','2020-05-09 03:24:21','2021-05-09 03:24:21'),('39e8629d6d7020bf2a333eddf5a54438112f23d80cb728104db85bf47695f091132d6b03fb4aea6b',2,1,'authToken','[]',0,'2020-04-28 00:43:45','2020-04-28 00:43:45','2021-04-28 00:43:45'),('41220c0b76116a06ae5451e3d770180b3a2c575013f4f99a7dadc208aea3f7dbe4c459a19a062a4a',1,1,'authToken','[]',0,'2020-04-19 12:14:41','2020-04-19 12:14:41','2021-04-19 12:14:41'),('553e452a7ba11147e3e62cc98b57bab15fcaed1cd11f26454e634726cf29e17b353d7a0f60e89d19',2,1,'authToken','[]',0,'2020-04-27 06:22:33','2020-04-27 06:22:33','2021-04-27 06:22:33'),('596b9cb1d50549b40a7405d42fd89d4ce4e5dcbc8241246160592340301155f82538d46b4b6b5a21',3,1,'authToken','[]',0,'2020-05-05 08:57:53','2020-05-05 08:57:53','2021-05-05 08:57:53'),('5b0e5c780005380083eb2fa0c72046e9e75bfac080f6f57ddf5a00117b32a895f1e05aac40876e27',1,1,'authToken','[]',0,'2020-04-18 01:31:21','2020-04-18 01:31:21','2021-04-18 01:31:21'),('5e20a6bf75d214c8b3cf2d3b2048d22a63bb8d9fddf33bc2683296b8aef89323e00db07dadb3dcce',1,1,'authToken','[]',0,'2020-05-01 06:41:55','2020-05-01 06:41:55','2021-05-01 06:41:55'),('64fe62d9efc7738746492a5059b926f351049ee750b6f0c165a195a31d37967e6c77710ad0a129ad',1,1,'authToken','[]',0,'2020-05-01 20:58:08','2020-05-01 20:58:08','2021-05-01 20:58:08'),('66849a7470d85c307e85a7094d913e28d7737ed21a4722f25f983ab8d4c6150d1a5d6f56bd2f9923',1,1,'authToken','[]',0,'2020-04-27 06:52:29','2020-04-27 06:52:29','2021-04-27 06:52:29'),('66d6abb242e694420bc16810e734b19e57e6b1c40ef33b97adcfd9f5dfb06b57f966b7bf1a299687',1,1,'authToken','[]',0,'2020-04-18 17:10:04','2020-04-18 17:10:04','2021-04-18 17:10:04'),('7a1e08fcda1aa591939ab58e658ebb8d7bfdba380d2fbe8a617846fcc79b86ceca16f99fe4359548',1,1,'authToken','[]',0,'2020-05-13 05:40:39','2020-05-13 05:40:39','2021-05-13 05:40:39'),('862714a951a25744eaa38645213cd1f008d06807b5a16b49a09c77e63d5ed1efb874e3cf602c7ee1',1,1,'authToken','[]',0,'2020-04-18 02:17:01','2020-04-18 02:17:01','2021-04-18 02:17:01'),('867c1f1519e35ed62c64ffd1efd1778ceec9333f4b65c32920a876937058c69bf09c888ff275430a',1,1,'authToken','[]',0,'2020-04-18 01:44:04','2020-04-18 01:44:04','2021-04-18 01:44:04'),('86d128204ed79f729c28605ab477570f39ed02035c5c59ea1f3dda8dc2a0bb3d6fb0dd638aebe7ef',1,1,'authToken','[]',0,'2020-05-05 07:29:59','2020-05-05 07:29:59','2021-05-05 07:29:59'),('8a611990ae73977aba60796aa82c44d6aa2c4df5d8786bbe5b150d571777eae79cacc3125246e8a5',1,1,'authToken','[]',0,'2020-04-20 04:08:19','2020-04-20 04:08:19','2021-04-20 04:08:19'),('932fd755cdc0c66ad24de82fd2961ff23e4c3f47826509052a19bb7760d26d9d3b0ddec142587781',1,1,'authToken','[]',0,'2020-04-18 13:28:06','2020-04-18 13:28:06','2021-04-18 13:28:06'),('93597ceb17bb02f5fe5a3696e9a29b2fbdda78a463b0a5c09758e16c36edee9bc2f6f831f8a52c25',1,1,'authToken','[]',0,'2020-05-07 20:37:24','2020-05-07 20:37:24','2021-05-07 20:37:24'),('9988377fbce0da2e3908b448dffef4a1a28df93f317978f8f324ca09425f09fec2e8790f841762e8',1,1,'authToken','[]',0,'2020-04-18 11:25:45','2020-04-18 11:25:45','2021-04-18 11:25:45'),('9becbe3b9b8b4c39df2c9ddd66f5429c5a100bc2100180c67cdce22e692b7b7760a7f5f8e06ca7f6',1,1,'authToken','[]',0,'2020-05-05 08:29:37','2020-05-05 08:29:37','2021-05-05 08:29:37'),('9bf1f60296f66205fb56c91fb0fa7fe0c044a93044cc6b4737724cb357305114fdc6c8a28115fb79',1,1,'authToken','[]',0,'2020-04-28 01:25:48','2020-04-28 01:25:48','2021-04-28 01:25:48'),('a20f34f9ca701d3693348d0137c369421f82f562d2d3806b4027f4c480dd447d6dbc171f27cca70d',1,1,'authToken','[]',0,'2020-04-29 08:35:49','2020-04-29 08:35:49','2021-04-29 08:35:49'),('ad0dafb730e6c0b91aa907470d8ab93a5ec1ea0190fd18988d9f580777155503cb2226b0a1f2f608',1,1,'authToken','[]',0,'2020-04-18 01:42:40','2020-04-18 01:42:40','2021-04-18 01:42:40'),('b58ad4b492e1edf0687e58a63a3c90775585d65214bf2f493e2a741e57923c7f5e92a6d4460f83c3',1,1,'authToken','[]',0,'2020-04-30 20:47:00','2020-04-30 20:47:00','2021-04-30 20:47:00'),('b6125bc39cb972d30ff0e73cd651f58e925f3634b0162e8322221075635197ebc48f2dfe199f1b4c',1,1,'authToken','[]',0,'2020-04-27 07:38:23','2020-04-27 07:38:23','2021-04-27 07:38:23'),('b909dd80e32180bcb2d3b01d816034ec9dde7a8f75ca988e93986fe8799802be5c4b5580bf29deb2',7,1,'authToken','[]',0,'2020-04-20 04:08:01','2020-04-20 04:08:01','2021-04-20 04:08:01'),('c3c748e71d44bdccaab6ccbec661db15a538cbf417f848e946df5b234e6131026cd34c5ab54f453f',1,1,'authToken','[]',0,'2020-05-08 06:40:49','2020-05-08 06:40:49','2021-05-08 06:40:49'),('c6e6b865bb3145db803af91d43f3492858d7736024f708c96f7dbe2c6255fffa006e7d5480035ad8',1,1,'authToken','[]',0,'2020-04-27 18:32:02','2020-04-27 18:32:02','2021-04-27 18:32:02'),('ca2eaf0b9b53f50142ecd8a44067d0236d4f2e1919b4a35d34f56c69f56bb686e66478a27b51cc04',1,1,'authToken','[]',0,'2020-04-20 14:00:24','2020-04-20 14:00:24','2021-04-20 14:00:24'),('cfb8dad2539dbcc308966d28d3cf1ea20fe30852cbd36ab5dac89bd6cc21db5d9375c0067a5a72d4',1,1,'authToken','[]',0,'2020-05-01 06:34:43','2020-05-01 06:34:43','2021-05-01 06:34:43'),('d0bb9792c831261101a18d536b3acf64e18070aeb034956872e10f48f49f10a573f1b4d4f2e26daf',3,1,'authToken','[]',0,'2020-04-28 01:22:59','2020-04-28 01:22:59','2021-04-28 01:22:59'),('d3e6aa8e1256e03902ecca161e7f4ba48f8db77f37cb4451b654abd85b45bc3a8f3679dd0668cf9d',1,1,'authToken','[]',0,'2020-05-09 03:56:52','2020-05-09 03:56:52','2021-05-09 03:56:52'),('d50049a4f4a690810be232177b496ab4dfb212e372e586100902e8fcece7d04d6eca914d36c3d71d',1,1,'authToken','[]',0,'2020-04-19 16:38:26','2020-04-19 16:38:26','2021-04-19 16:38:26'),('d783adcbd0d07de17013e19de418187a54b9e1f1858cd62380ea6e09263b08214e4ed63bb4e1ede7',3,1,'authToken','[]',0,'2020-04-28 01:24:34','2020-04-28 01:24:34','2021-04-28 01:24:34'),('dcc3ec4dd67ff8c10a396ebfede3a435555f1a0056261248f352edfa8b2c9a6e0fcf4fa101d944c7',3,1,'authToken','[]',0,'2020-05-09 03:56:19','2020-05-09 03:56:19','2021-05-09 03:56:19'),('e18d9f6d8e52bbe9c542452d56e6cdc2da19a3dcd06f6f16e6f375c9fa81f43c00a08b7f55ecf68d',1,1,'authToken','[]',0,'2020-04-29 04:51:39','2020-04-29 04:51:39','2021-04-29 04:51:39'),('e8f0b897cd1bc695536d2722e28699820d07bf2643a5442f078ac79ece0b2355a0da9fd8ed405425',1,1,'authToken','[]',0,'2020-04-27 03:06:01','2020-04-27 03:06:01','2021-04-27 03:06:01'),('ece93b63c5090d2b8c42f6d5162cf49583b478f45c20f6c14a46d0f8637c1dc06898235631b6dff1',1,1,'authToken','[]',0,'2020-04-28 01:08:07','2020-04-28 01:08:07','2021-04-28 01:08:07'),('fe084bf5fe66049a566800759d080845f8ea7e7ed42fbd9c2e3070c402a30dc4c2af3572a5c18b20',1,1,'authToken','[]',0,'2020-05-01 05:05:55','2020-05-01 05:05:55','2021-05-01 05:05:55'),('fff2c3b174b6f66447a075538280b7da93692aaf12688ecd6969b6dcdd0f1ab6debb5de7a8a109bc',1,1,'authToken','[]',0,'2020-04-26 19:02:09','2020-04-26 19:02:09','2021-04-26 19:02:09');

/*Table structure for table `oauth_auth_codes` */

DROP TABLE IF EXISTS `oauth_auth_codes`;

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `oauth_auth_codes` */

/*Table structure for table `oauth_clients` */

DROP TABLE IF EXISTS `oauth_clients`;

CREATE TABLE `oauth_clients` (
  `id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `oauth_clients` */

insert  into `oauth_clients`(`id`,`user_id`,`name`,`secret`,`redirect`,`personal_access_client`,`password_client`,`revoked`,`created_at`,`updated_at`) values (1,NULL,'pigeon','up2aJJiu29CCcorFAWUf8CjxqObxLEw0UTqNlHW1','http://localhost',1,0,0,'2020-04-18 01:27:45','2020-04-18 01:27:45');

/*Table structure for table `oauth_personal_access_clients` */

DROP TABLE IF EXISTS `oauth_personal_access_clients`;

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `oauth_personal_access_clients` */

insert  into `oauth_personal_access_clients`(`id`,`client_id`,`created_at`,`updated_at`) values (1,1,'2020-04-18 01:27:45','2020-04-18 01:27:45');

/*Table structure for table `oauth_refresh_tokens` */

DROP TABLE IF EXISTS `oauth_refresh_tokens`;

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `oauth_refresh_tokens` */

/*Table structure for table `owners` */

DROP TABLE IF EXISTS `owners`;

CREATE TABLE `owners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`(191)),
  KEY `name` (`name`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `owners` */

insert  into `owners`(`id`,`type`,`name`,`address`,`phone`,`email`,`contact_person_name`,`contact_person_phone`,`status`,`created_at`,`updated_at`,`created_by`,`updated_by`) values (1,'Owner Info','fazlu niloy','pabna','4245654646','a@t.com','sdf','sdf','2','2021-09-11 20:32:11','2021-09-11 23:24:24',8,8),(2,'1','fdsfsd','asdf',NULL,NULL,NULL,NULL,'1','2021-09-11 20:33:56','2021-09-11 20:33:56',8,NULL),(3,'Owner Info','test man','test','4245654646','a@t.com','sfsf','4245654646','1','2021-09-11 20:35:37','2021-09-11 23:22:45',8,8),(4,'Sister Company Info','fazlu niloy','pabna','4245654646','a@t.com','fazlu niloy','4245654646','1','2021-09-11 23:19:48','2021-09-11 23:22:36',8,8);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `id` (`id`),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values (1,'read-dashboard','web','2020-04-16 20:10:56','2020-04-16 20:10:56'),(2,'read-user','web','2020-04-17 17:59:20','2020-04-17 17:59:20'),(3,'edit-role','web','2020-04-19 12:22:41','2020-04-19 12:22:41'),(4,'edit-profile','web','2020-04-19 19:20:04','2020-04-19 19:20:04'),(5,'create-permission','web','2020-04-26 03:34:37','2020-04-26 03:34:37'),(6,'read-permission','web','2020-04-26 04:08:01','2020-04-26 04:08:01'),(7,'edit-permission','web','2020-04-26 04:11:25','2020-04-26 04:11:25'),(8,'delete-permission','web','2020-04-26 04:11:38','2020-04-26 04:11:38'),(9,'create-role','web','2020-04-26 04:12:11','2020-04-26 04:12:11'),(10,'read-role','web','2020-04-26 04:12:24','2020-04-26 04:12:24'),(11,'delete-role','web','2020-04-26 04:12:46','2020-04-26 04:12:46'),(12,'edit-user-role','web','2020-04-26 04:14:12','2020-04-26 04:14:12'),(13,'assign-user-permission','web','2020-04-26 04:25:36','2020-04-26 04:25:36'),(14,'delete-user-permission','web','2020-04-26 04:27:29','2020-04-26 04:27:29'),(15,'assign-role-permission','web','2020-04-26 04:28:59','2020-04-26 04:28:59'),(16,'delete-role-permission','web','2020-04-26 04:29:47','2020-04-26 04:29:47'),(17,'read-role-permission','web','2020-04-26 04:31:54','2020-04-26 04:31:54'),(18,'read-user-permission','web','2020-04-26 04:32:06','2020-04-26 04:32:06'),(19,'register-user','web','2020-04-27 05:29:09','2020-04-27 05:29:09'),(20,'read-log','web','2020-04-29 08:49:08','2020-04-29 08:49:08'),(21,'read-all-user-log','web','2020-04-29 08:51:47','2020-04-29 08:51:47'),(22,'read-user-tables-combination','web','2020-05-15 04:28:49','2020-05-15 04:28:49'),(23,'read-lookup','web','2020-10-02 18:49:02','2020-10-02 18:49:02'),(24,'create-lookup','web','2020-10-02 18:49:40','2020-10-02 18:49:40'),(25,'edit-lookup','web','2020-10-02 18:50:00','2020-10-02 18:50:00'),(26,'delete-lookup','web','2020-10-02 18:53:05','2020-10-02 18:53:05'),(27,'change-password','web','2020-10-02 23:47:43','2020-10-02 23:47:43'),(43,'backup','web','2020-11-16 20:06:33','2020-11-16 20:06:33'),(44,'read-location','web','2020-11-18 23:10:11','2020-11-18 23:10:11'),(45,'create-location','web','2020-11-18 23:10:33','2020-11-18 23:10:33'),(46,'edit-location','web','2020-11-18 23:10:57','2020-11-18 23:10:57'),(47,'delete-location','web','2020-11-18 23:11:21','2020-11-18 23:11:21'),(48,'create-group-account','web','2021-09-07 19:30:22','2021-09-07 19:30:22'),(49,'read-group-account','web','2021-09-07 19:30:41','2021-09-07 19:30:41'),(50,'read-coa','web','2021-09-08 20:10:55','2021-09-08 20:10:55'),(51,'create-coa','web','2021-09-08 20:11:23','2021-09-08 20:11:23'),(52,'edit-coa','web','2021-09-08 20:11:47','2021-09-08 20:11:47'),(53,'edit-group-account','web','2021-09-08 21:21:05','2021-09-08 21:21:05'),(54,'read-owner','web','2021-09-11 20:19:51','2021-09-11 20:19:51'),(55,'create-owner','web','2021-09-11 20:20:20','2021-09-11 20:20:20'),(56,'delete-owner','web','2021-09-11 20:52:56','2021-09-11 20:52:56'),(57,'edit-owner','web','2021-09-11 20:53:26','2021-09-11 20:53:26'),(58,'update-owner','web','2021-09-11 22:36:29','2021-09-11 22:36:29'),(59,'read-unit','web','2021-09-12 20:57:30','2021-09-12 20:57:30'),(60,'create-unit','web','2021-09-12 20:58:10','2021-09-12 20:58:10'),(61,'edit-unit','web','2021-09-12 20:58:28','2021-09-12 20:58:28'),(62,'delete-unit','web','2021-09-12 20:58:55','2021-09-12 20:58:55'),(63,'update-unit','web','2021-09-12 21:35:42','2021-09-12 21:35:42'),(64,'create-customer','web','2021-09-13 20:04:40','2021-09-13 20:04:40'),(65,'edit-customer','web','2021-09-13 20:05:06','2021-09-13 20:05:06'),(66,'read-customer','web','2021-09-13 20:05:50','2021-09-13 20:05:50'),(67,'update-customer','web','2021-09-13 20:06:31','2021-09-13 20:06:31'),(68,'delete-customer','web','2021-09-13 20:07:09','2021-09-13 20:07:09'),(69,'read-vendor','web','2021-09-16 20:50:00','2021-09-16 20:50:00'),(70,'create-vendor','web','2021-09-16 20:50:31','2021-09-16 20:50:31'),(71,'edit-vendor','web','2021-09-16 20:50:57','2021-09-16 20:50:57'),(72,'delete-vendor','web','2021-09-16 20:51:18','2021-09-16 20:51:18'),(73,'update-vendor','web','2021-09-16 20:51:45','2021-09-16 20:51:45'),(74,'create-godown','web','2021-09-27 22:25:44','2021-09-27 22:25:44'),(75,'read-godown','web','2021-09-27 22:26:07','2021-09-27 22:26:07'),(76,'edit-godown','web','2021-09-27 22:26:31','2021-09-27 22:26:31'),(77,'delete-godown','web','2021-09-27 22:26:47','2021-09-27 22:26:47'),(78,'update-godown','web','2021-09-27 22:27:08','2021-09-27 22:27:08'),(79,'create-product','web','2021-09-28 21:18:49','2021-09-28 21:18:49'),(80,'update-product','web','2021-09-28 21:19:10','2021-09-28 21:19:10'),(81,'read-product','web','2021-09-28 21:19:33','2021-09-28 21:19:33'),(82,'delete-product','web','2021-09-28 21:20:56','2021-09-28 21:20:56'),(83,'edit-product','web','2021-09-28 21:21:27','2021-09-28 21:21:27'),(84,'read-tax','web','2021-10-02 21:50:41','2021-10-02 21:50:41'),(85,'update-tax','web','2021-10-02 21:54:00','2021-10-02 21:54:00'),(86,'edit-tax','web','2021-10-02 21:54:20','2021-10-02 21:54:20'),(87,'delete-tax','web','2021-10-02 21:54:36','2021-10-02 21:54:36'),(88,'create-tax','web','2021-10-02 21:54:58','2021-10-02 21:54:58'),(89,'create-income','web','2021-10-03 00:40:12','2021-10-03 00:40:12'),(90,'read-income','web','2021-10-03 00:40:34','2021-10-03 00:40:34'),(91,'edit-income','web','2021-10-03 00:41:01','2021-10-03 00:41:01'),(92,'update-income','web','2021-10-03 00:41:24','2021-10-03 00:41:24'),(93,'delete-income','web','2021-10-03 00:41:42','2021-10-03 00:41:42'),(94,'read-journal','web','2021-10-07 19:28:40','2021-10-07 19:28:40');

/*Table structure for table `product_logs` */

DROP TABLE IF EXISTS `product_logs`;

CREATE TABLE `product_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) DEFAULT NULL,
  `service_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `vendor_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT '0',
  `regular_price` double DEFAULT '0',
  `discounted_price` double DEFAULT '0',
  `rate_effective_date` date DEFAULT NULL,
  `vds_section` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vds_rate` double DEFAULT '0',
  `tds_section` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tds_rate` double DEFAULT '0',
  `is_best_sell` int(11) DEFAULT '0',
  `is_new` int(11) DEFAULT '0',
  `tds_head` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vds_head` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_ledger` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` double DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `effective_date_from` date DEFAULT NULL,
  `effective_date_to` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `product_logs` */

insert  into `product_logs`(`id`,`product_id`,`service_type`,`product_name`,`vendor_id`,`vendor_name`,`brand_name`,`size`,`unit_id`,`quantity`,`regular_price`,`discounted_price`,`rate_effective_date`,`vds_section`,`vds_rate`,`tds_section`,`tds_rate`,`is_best_sell`,`is_new`,`tds_head`,`vds_head`,`assigned_ledger`,`opening_balance`,`description`,`created_by`,`updated_by`,`effective_date_from`,`effective_date_to`,`created_at`,`updated_at`,`status`) values (1,3,'Service','cdfsdf',1,'sdfsd',NULL,NULL,NULL,0,NULL,NULL,NULL,'test',2,'d',4,0,0,NULL,NULL,NULL,333,NULL,8,8,'2021-09-30','2021-09-30','2021-09-30 23:29:31','2021-09-30 23:29:31',1),(2,3,'Service','cdfsdf',1,'sdfsd',NULL,NULL,NULL,0,NULL,NULL,NULL,'test',2,'d',4,0,0,NULL,NULL,NULL,333,NULL,8,8,'2021-09-30','2021-09-30','2021-09-30 23:29:31','2021-09-30 23:29:31',1),(3,3,'Service','cdfsdf',1,'sdfsd',NULL,NULL,NULL,0,NULL,NULL,NULL,'test',2,'d',4,0,0,NULL,NULL,NULL,333,NULL,8,8,'2021-09-30','2021-09-30','2021-09-30 23:29:31','2021-09-30 23:29:31',1),(4,3,'Service','cdfsdf',1,'sdfsd',NULL,NULL,NULL,0,NULL,NULL,NULL,'test',2,'d',4,0,0,NULL,NULL,NULL,333,NULL,8,8,'2021-09-30','2021-09-30','2021-09-30 23:29:31','2021-09-30 23:29:31',1),(5,3,'Service','cdfsdf',1,'sdfsd',NULL,NULL,NULL,0,NULL,NULL,NULL,'test',2,'d',4,0,0,NULL,NULL,NULL,333,NULL,8,8,'2021-09-30','2021-09-30','2021-09-30 23:30:42','2021-09-30 23:30:42',1);

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `vendor_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT '0',
  `regular_price` double DEFAULT '0',
  `discounted_price` double DEFAULT '0',
  `rate_effective_date` date DEFAULT NULL,
  `vds_section` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vds_rate` double DEFAULT '0',
  `tds_section` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tds_rate` double DEFAULT '0',
  `is_best_sell` int(11) DEFAULT '0',
  `is_new` int(11) DEFAULT '0',
  `tds_head` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vds_head` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_ledger` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` double DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `effective_date_from` date DEFAULT NULL,
  `effective_date_to` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `products` */

insert  into `products`(`id`,`service_type`,`product_name`,`vendor_id`,`vendor_name`,`brand_name`,`size`,`unit_id`,`quantity`,`regular_price`,`discounted_price`,`rate_effective_date`,`vds_section`,`vds_rate`,`tds_section`,`tds_rate`,`is_best_sell`,`is_new`,`tds_head`,`vds_head`,`assigned_ledger`,`opening_balance`,`description`,`created_by`,`updated_by`,`effective_date_from`,`effective_date_to`,`created_at`,`updated_at`,`status`) values (1,'Product','dsdd',1,'sdfsd',NULL,'sdfsdf',1,0,34343,2,'2021-09-09',NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,534,NULL,8,8,'2021-09-29','2021-09-29','2021-09-29 23:09:19','2021-09-30 23:01:17',1),(2,'Product','ssss',1,'sdf','0','s',1,0,34343,2,'2021-09-30',NULL,NULL,NULL,NULL,0,0,'2','2',NULL,11111,NULL,8,0,'2021-09-30','2021-09-30','2021-09-30 21:52:44','2021-09-30 21:52:44',1),(4,'Product','testing',1,'sdfsd','1','s,m,l',1,0,NULL,44,'2021-09-30','test',3,NULL,3,0,0,NULL,NULL,'1',3333,NULL,8,0,'2021-09-30','2021-09-30','2021-09-30 23:52:57','2021-09-30 23:52:57',2);

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_has_permissions` */

insert  into `role_has_permissions`(`permission_id`,`role_id`) values (1,1),(1,3),(1,4),(1,5),(2,1),(2,3),(2,4),(3,1),(3,4),(4,1),(4,3),(4,4),(4,5),(5,4),(6,1),(6,3),(6,4),(7,1),(7,4),(8,4),(9,1),(9,4),(10,1),(10,3),(10,4),(11,4),(12,1),(12,3),(12,4),(13,1),(13,3),(13,4),(14,1),(14,3),(14,4),(15,1),(15,4),(16,1),(16,4),(17,1),(17,3),(17,4),(18,1),(18,3),(18,4),(19,1),(19,3),(19,4),(20,1),(20,3),(20,4),(21,1),(21,4),(21,5),(22,1),(22,3),(22,4),(22,5),(23,1),(23,4),(24,4),(25,4),(26,4),(27,1),(27,2),(27,3),(27,4),(43,1),(43,4),(44,1),(44,4),(45,1),(45,4),(46,1),(46,4),(47,1),(47,4),(48,4),(49,4),(50,4),(51,4),(52,1),(52,4),(53,1),(53,4),(54,1),(54,3),(54,4),(55,1),(55,3),(55,4),(56,1),(56,3),(56,4),(57,1),(57,3),(57,4),(58,1),(58,3),(58,4),(59,1),(59,3),(59,4),(60,1),(60,3),(60,4),(61,1),(61,3),(61,4),(62,1),(62,4),(63,1),(63,3),(63,4),(64,1),(64,3),(64,4),(65,1),(65,3),(65,4),(66,1),(66,3),(66,4),(67,1),(67,2),(67,3),(67,4),(68,1),(68,3),(68,4),(69,1),(69,3),(69,4),(70,1),(70,3),(70,4),(71,1),(71,3),(71,4),(72,1),(72,3),(72,4),(73,1),(73,3),(73,4),(74,1),(74,3),(74,4),(75,1),(75,3),(75,4),(76,1),(76,3),(76,4),(77,1),(77,3),(77,4),(78,1),(78,3),(78,4),(79,1),(79,3),(79,4),(80,1),(80,4),(81,1),(81,4),(82,1),(82,3),(82,4),(83,1),(83,3),(83,4),(84,1),(84,4),(85,1),(85,4),(86,1),(86,4),(87,1),(87,4),(88,1),(88,4),(89,1),(89,4),(90,1),(90,4),(91,1),(91,4),(92,1),(92,4),(93,1),(93,4),(94,1),(94,4);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`guard_name`,`level`,`created_at`,`updated_at`) values (1,'super-admin','web',1,'2020-04-16 20:10:56','2020-04-16 20:10:56'),(2,'temporary','web',5000,'2020-04-16 20:12:36','2020-04-16 20:12:36'),(3,'admin','web',2,'2020-04-27 06:21:36','2020-04-27 06:21:36'),(4,'developer','web',1,'2020-04-28 01:13:02','2020-04-28 01:15:38'),(5,'member','web',4,'2020-11-13 17:26:11','2021-04-20 01:01:20'),(6,'customer','customer',2000,'2021-04-15 22:53:21','2021-04-20 00:58:22');

/*Table structure for table `s_m_s` */

DROP TABLE IF EXISTS `s_m_s`;

CREATE TABLE `s_m_s` (
  `id` bigint(20) unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `sender_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bulk_company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_per_sms` double NOT NULL DEFAULT '0',
  `sms_counted` double NOT NULL DEFAULT '0',
  `total_cost` double NOT NULL DEFAULT '0',
  `sms_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'System Generated',
  `sms_purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `s_m_s` */

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL,
  `delivery_charge` double DEFAULT NULL,
  `copyright` text COLLATE utf8mb4_unicode_ci,
  `small_about` text COLLATE utf8mb4_unicode_ci,
  `company_address` text COLLATE utf8mb4_unicode_ci,
  `company_email_address` text COLLATE utf8mb4_unicode_ci,
  `company_contact_no` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `settings` */

insert  into `settings`(`id`,`delivery_charge`,`copyright`,`small_about`,`company_address`,`company_email_address`,`company_contact_no`) values (1,60,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `taxes` */

DROP TABLE IF EXISTS `taxes`;

CREATE TABLE `taxes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tax_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_head` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lower_limit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `upper_limit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `compulsory_vds` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `basis` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_by` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `taxes` */

insert  into `taxes`(`id`,`tax_type`,`account_head`,`section`,`lower_limit`,`upper_limit`,`compulsory_vds`,`basis`,`year`,`rate`,`created_by`,`updated_by`,`created_at`,`updated_at`) values (1,'TDS','test','dfgdfg','4','4',NULL,'4','2021-2022','4',8,8,'2021-10-02 22:33:53','2021-10-02 22:38:32');

/*Table structure for table `user_details` */

DROP TABLE IF EXISTS `user_details`;

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `dob` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` int(11) NOT NULL DEFAULT '0' COMMENT '1=male, 2=Female, 3=others',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `picture` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `user_details` */

insert  into `user_details`(`id`,`dob`,`gender`,`phone`,`address`,`picture`,`created_by`,`updated_by`,`created_at`,`updated_at`) values (1,'1993-12-18',1,'01754479709','Santahar, Bogra, Rajshahi, Bangladesh','pigeon_1622027496.jpg',NULL,1,'2020-04-16 20:25:09','2021-05-26 23:11:36'),(8,NULL,0,NULL,NULL,NULL,1,1,'2021-06-13 14:59:14','2021-06-13 14:59:14');

/*Table structure for table `user_tables_combinations` */

DROP TABLE IF EXISTS `user_tables_combinations`;

CREATE TABLE `user_tables_combinations` (
  `id` bigint(20) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `combination` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `user_tables_combinations` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decrypted_password` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `id` (`id`),
  KEY `email` (`email`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`username`,`email`,`email_verified_at`,`password`,`decrypted_password`,`remember_token`,`created_at`,`updated_at`) values (1,'Pigeon','pigeon','toukir.ahamed.pigeon@gmail.com',NULL,'$2y$10$kdDzDqa8PoIggGSc3liauOTEFFxSWOuCfUKkn8s5Pjh2w6YrR8P6y','12345678','ulyyyDmh3xE4GDPElpKCtjBIuNlRhVxYsZWJLE8dMuUhWGV31a2KfBBKuRp5','2020-04-16 20:25:09','2020-10-03 00:10:37'),(8,'Rony Mondal','rony','ronymondal@gmail.com',NULL,'$2y$10$RtvMFzpcRye/xt6s4.ipkuGu8pu4GCo7bSK14bSLVQPjlwVWLYThq','12345678',NULL,'2021-06-13 14:59:14','2021-06-13 14:59:14');

/*Table structure for table `vendors` */

DROP TABLE IF EXISTS `vendors`;

CREATE TABLE `vendors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_contact` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_nid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_lincese_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incorporation_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etin` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `bank_account_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `routing_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validity` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `tax_exemption` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tds_section` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_reg` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_rate` double(10,2) DEFAULT NULL,
  `tds_rate` double(10,2) DEFAULT NULL,
  `service_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_name_1` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_name_2` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_name_3` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_name_4` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_name_5` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_name_6` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendors_owner_contact_unique` (`owner_contact`),
  UNIQUE KEY `vendors_email_unique` (`email`),
  KEY `vendor_name` (`vendor_name`),
  KEY `region_id` (`region_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `vendors` */

insert  into `vendors`(`id`,`vendor_name`,`owner_name`,`owner_contact`,`owner_nid`,`email`,`owner_address`,`trade_lincese_no`,`incorporation_no`,`bin`,`etin`,`contact_person_name`,`contact_person_phone`,`supplier_type`,`payment_method`,`region_id`,`bank_account_title`,`bank_name`,`branch_name`,`account_no`,`routing_number`,`validity`,`status`,`tax_exemption`,`tds_section`,`vat_reg`,`service_code`,`vat_rate`,`tds_rate`,`service_type`,`service_name`,`brand_name_1`,`brand_name_2`,`brand_name_3`,`brand_name_4`,`brand_name_5`,`brand_name_6`,`created_by`,`updated_by`,`created_at`,`updated_at`) values (1,'sdfsd','sdfasdf','safd','asdf',NULL,NULL,'sdf','asdf','asdf','sadf','test','4234','food supplier','EFT',NULL,'sdf','sdfsdfsdfasdf','sdfsdfsf df','sdfsf','sdff',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'Product',NULL,'fresh','sugar','solt',NULL,NULL,NULL,0,8,'2021-09-16 21:13:03','2021-09-23 23:17:45'),(2,'test','sdf','sfdsdf',NULL,'a@t.com','pabna',NULL,NULL,NULL,NULL,'fazlu niloy','4245654646','food supplier','Cash',8,'t012544','ab','pabna','3333333','r',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'Product',NULL,'fresh','sugar','solt',NULL,NULL,NULL,8,8,'2021-09-22 23:11:14','2021-09-23 23:18:52'),(3,'s','s',NULL,NULL,NULL,'s',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'Product',NULL,'s','d','d','sd','d',NULL,8,0,'2021-09-25 08:27:33','2021-09-25 08:27:33'),(4,'sdf','sdf','d','d','sdfsdfs@t.com','sdfsdf','d','d',NULL,'d','vv sdf','3453535','food supplier','Cheque',8,'d',NULL,'d','ds','d','2021-09-25',1,'d','d','d','d',6.00,4.00,'Product',NULL,'d','d','d','d','d',NULL,8,0,'2021-09-25 08:37:59','2021-09-25 08:37:59');

/* Trigger structure for table `customers` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `acc_log` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `acc_log` BEFORE UPDATE ON `customers` FOR EACH ROW BEGIN
    END */$$


DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
