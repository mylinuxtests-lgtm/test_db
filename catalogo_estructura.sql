/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 10.3.39-MariaDB-0+deb10u2 : Database - administracion_oficios
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`administracion_oficios` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci */;

USE `administracion_oficios`;

/*Table structure for table `cat_estructura` */

DROP TABLE IF EXISTS `cat_estructura`;

CREATE TABLE `cat_estructura` (
  `id_estructura` int(11) NOT NULL AUTO_INCREMENT,
  `dsc_estructura` varchar(255) NOT NULL,
  `id_padre` int(11) DEFAULT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_estructura`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `cat_estructura` */

insert  into `cat_estructura`(`id_estructura`,`dsc_estructura`,`id_padre`,`visible`) values (1,'Direccion General de Planeacion',NULL,1),(2,'Direccion de Tecnologias de la Informacion y Comunicaciones',1,1),(3,'Departamento de Soporte Tecnico y Comunicaciones',2,1),(4,'Departamento de Desarrollo de Sistemas Web',2,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
