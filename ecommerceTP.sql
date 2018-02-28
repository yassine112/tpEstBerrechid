CREATE DATABASE  IF NOT EXISTS `ecommerce`;
USE `ecommerce`;

--
-- Table structure for table `_costomer`
--

DROP TABLE IF EXISTS `_costomer`;

CREATE TABLE `_costomer` (
  `id_costomer` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_costomer`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Table structure for table `_product`
--

DROP TABLE IF EXISTS `_product`;

CREATE TABLE `_product` (
  `id_prod` int(11) NOT NULL AUTO_INCREMENT,
  `name_prod` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `quantity_in_stock` int(11) DEFAULT NULL,
  `id_cat` int(11) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_prod`),
  UNIQUE KEY `name_prod` (`name_prod`),
  KEY `id_cat` (`id_cat`),
  CONSTRAINT `_product_ibfk_1` FOREIGN KEY (`id_cat`) REFERENCES `_category` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Table structure for table `_category`
--

DROP TABLE IF EXISTS `_category`;

CREATE TABLE `_category` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `name_cat` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_cat`),
  UNIQUE KEY `name_cat` (`name_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Table structure for table `_orderline`
--

DROP TABLE IF EXISTS `_orderline`;

CREATE TABLE `_orderline` (
  `idOrder` int(11) NOT NULL,
  `idProd` int(11) NOT NULL,
  `qut` int(11) DEFAULT NULL,
  `totale` float DEFAULT NULL,
  PRIMARY KEY (`idOrder`,`idProd`),
  KEY `_orderline_ibfk_2` (`idProd`),
  CONSTRAINT `_orderline_ibfk_1` FOREIGN KEY (`idOrder`) REFERENCES `_orderT` (`id_order`),
  CONSTRAINT `_orderline_ibfk_2` FOREIGN KEY (`idProd`) REFERENCES `_product` (`id_prod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `_orderT`
--

DROP TABLE IF EXISTS `_orderT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_orderT` (
  `id_order` int(11) NOT NULL AUTO_INCREMENT,
  `totale` float DEFAULT NULL,
  `date_order` datetime DEFAULT NULL,
  `id_costomer` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_order`),
  KEY `id_costomer` (`id_costomer`),
  CONSTRAINT `_ordert_ibfk_1` FOREIGN KEY (`id_costomer`) REFERENCES `_costomer` (`id_costomer`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8;

