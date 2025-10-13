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

/*Table structure for table `empleados` */

DROP TABLE IF EXISTS `empleados`;

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL AUTO_INCREMENT,
  `id_puesto_fk` int(11) NOT NULL,
  `id_estructura_fk` int(11) NOT NULL,
  `id_tipo_contrato_fk` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `primer_apellido` varchar(120) NOT NULL,
  `segundo_apellido` varchar(120) DEFAULT NULL,
  `curp` varchar(20) DEFAULT NULL,
  `rfc` varchar(20) DEFAULT NULL,
  `correo` varchar(120) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `id_usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualiza` datetime DEFAULT NULL,
  `id_usuario_actualiza` int(11) DEFAULT NULL,
  `activo` tinyint(4) DEFAULT 1,
  `visible` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`id_empleado`),
  KEY `fk_empleados_cat_puestos_idx` (`id_puesto_fk`),
  KEY `fk_empleados_cat_estructura1_idx` (`id_estructura_fk`),
  KEY `fk_empleados_cat_tipo_contrato1_idx` (`id_tipo_contrato_fk`),
  CONSTRAINT `fk_empleados_cat_estructura1` FOREIGN KEY (`id_estructura_fk`) REFERENCES `cat_estructura` (`id_estructura`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleados_cat_puestos` FOREIGN KEY (`id_puesto_fk`) REFERENCES `cat_puestos` (`id_puesto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleados_cat_tipo_contrato1` FOREIGN KEY (`id_tipo_contrato_fk`) REFERENCES `cat_tipo_contrato` (`id_tipo_contrato`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `empleados` */

insert  into `empleados`(`id_empleado`,`id_puesto_fk`,`id_estructura_fk`,`id_tipo_contrato_fk`,`nombre`,`primer_apellido`,`segundo_apellido`,`curp`,`rfc`,`correo`,`telefono`,`fecha_registro`,`id_usuario_registro`,`fecha_actualiza`,`id_usuario_actualiza`,`activo`,`visible`) values (1,1,1,1,'Giovanna Leticia','Cabrera','Contreras',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(2,2,2,1,'Oscar Ulises','Esparza','Ochoa',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(3,3,2,1,'Ernesto','Sobrevilla','Aguirre',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(4,4,2,1,'Alma Erendira','Garcia','Ruiz',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(5,5,2,1,'Juan Pablo','Barrientos','Martinez',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(6,6,3,1,'Jorge Abraham','Guerra','Avalos',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(7,7,3,1,'Fernando','Espinosa','Capitan',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(8,8,3,1,'Diana Cristel','Murrieta','Murrieta',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(9,9,3,1,'Juan Gabriel','Carrillo','Ayala',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(10,9,3,1,'Jose de Jesus','Jimenez','Venegas',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(11,10,4,1,'Gladys','Segura','Alvarado',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(12,11,4,1,'Juan David','Martinez','Calzada',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(13,11,4,1,'Juan Mario','Hernandez','Yebra',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(14,11,4,1,'Jonathan David','Mendez','Zuñiga',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(15,11,4,1,'Jonathan Humberto','Rodriguez','Salazar',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(16,11,4,1,'Arlette Liliana','Arauza','Jimenez',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(17,11,4,1,'Citlaly','Herrera','Mendez',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
