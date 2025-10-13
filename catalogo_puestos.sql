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

/*Table structure for table `cat_puestos` */

DROP TABLE IF EXISTS `cat_puestos`;

CREATE TABLE `cat_puestos` (
  `id_puesto` int(11) NOT NULL AUTO_INCREMENT,
  `dsc_puesto` varchar(60) NOT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_puesto`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `cat_puestos` */

insert  into `cat_puestos`(`id_puesto`,`dsc_puesto`,`visible`) values (1,'Directora General de Planeacion',1),(2,'Director de Tecnologias de la Informacion y Comunicaciones',1),(3,'Coordinador de Proyectos Informaticos',1),(4,'Jefa de Departamento de Soporte Tecnico y Comunicaciones',1),(5,'Jefe de Departamento de Desarrollo de Sistemas Web',1),(6,'Supervisor de Redes y Telecomunaciones',1),(7,'Enlace de Tramites de Validaciones Tecnicas DGTIC',1),(8,'Gestora de Cuentas de Correo Electronico',1),(9,'Soporte Tecnico Informatico',1),(10,'Coordinadora de Implementacion de Sistemas',1),(11,'Desarrollador Web',1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
