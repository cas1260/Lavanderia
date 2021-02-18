# SQL Manager 2007 Lite for MySQL 4.4.2.1
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : lavaluvas


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;

SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE `lavaluvas`
    CHARACTER SET 'latin1'
    COLLATE 'latin1_swedish_ci';

USE `lavaluvas`;

#
# Structure for the `tblcentro` table : 
#

DROP TABLE IF EXISTS `tblcentro`;

CREATE TABLE `tblcentro` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Descricao` text,
  `IdUsuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

#
# Structure for the `tblcliente` table : 
#

DROP TABLE IF EXISTS `tblcliente`;

CREATE TABLE `tblcliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) DEFAULT NULL,
  `nome` varchar(200) DEFAULT NULL,
  `tipo` varchar(1) DEFAULT NULL,
  `doc` varchar(20) DEFAULT NULL,
  `estadual` varchar(20) DEFAULT NULL,
  `municipal` varchar(20) DEFAULT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `inicio` varchar(20) DEFAULT NULL,
  `extra` text,
  `extra2` text,
  `extra3` text,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(20) DEFAULT NULL,
  `cidade` varchar(20) DEFAULT NULL,
  `estado` varchar(10) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `endereco1` varchar(100) DEFAULT NULL,
  `bairro1` varchar(20) DEFAULT NULL,
  `cidade1` varchar(20) DEFAULT NULL,
  `estado1` varchar(20) DEFAULT NULL,
  `cep1` varchar(20) DEFAULT NULL,
  `obs` text,
  `numero` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `numero1` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `reajuste` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Structure for the `tblclienteservico` table : 
#

DROP TABLE IF EXISTS `tblclienteservico`;

CREATE TABLE `tblclienteservico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) DEFAULT NULL,
  `idservico` int(11) DEFAULT NULL,
  `valor` double(15,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

#
# Structure for the `tblforma` table : 
#

DROP TABLE IF EXISTS `tblforma`;

CREATE TABLE `tblforma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `dias` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

#
# Structure for the `tblfornecedor` table : 
#

DROP TABLE IF EXISTS `tblfornecedor`;

CREATE TABLE `tblfornecedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `endereco` varchar(200) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `cep` varchar(10) NOT NULL,
  `uf` varchar(10) NOT NULL,
  `telefone1` varchar(15) NOT NULL,
  `telefone2` varchar(15) NOT NULL,
  `telefone3` varchar(15) NOT NULL,
  `cnpj` varchar(20) NOT NULL,
  `estadual` varchar(20) NOT NULL,
  `municipal` varchar(20) NOT NULL,
  `contato` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `obs` text NOT NULL,
  `tipo` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Structure for the `tblfornecedormercadoria` table : 
#

DROP TABLE IF EXISTS `tblfornecedormercadoria`;

CREATE TABLE `tblfornecedormercadoria` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idmercadoria` int(11) DEFAULT NULL,
  `idfornecedor` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Structure for the `tblitemromanei` table : 
#

DROP TABLE IF EXISTS `tblitemromanei`;

CREATE TABLE `tblitemromanei` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idservico` int(11) DEFAULT NULL,
  `obra` varchar(20) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL,
  `valor` double(15,3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Structure for the `tblmercadoria` table : 
#

DROP TABLE IF EXISTS `tblmercadoria`;

CREATE TABLE `tblmercadoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `valor` double(15,3) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL,
  `qtdminimo` int(11) DEFAULT NULL,
  `obs` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Structure for the `tblmotorista` table : 
#

DROP TABLE IF EXISTS `tblmotorista`;

CREATE TABLE `tblmotorista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `obs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

#
# Structure for the `tblromanei` table : 
#

DROP TABLE IF EXISTS `tblromanei`;

CREATE TABLE `tblromanei` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `os` int(11) DEFAULT NULL,
  `pedido` int(11) DEFAULT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `solicitante` varchar(100) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `liberacao` date DEFAULT NULL,
  `retirada` date DEFAULT NULL,
  `idvendedor` int(11) DEFAULT NULL,
  `idforma` int(11) DEFAULT NULL,
  `desconto` double(15,3) DEFAULT NULL,
  `total` double(15,3) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Structure for the `tblrota` table : 
#

DROP TABLE IF EXISTS `tblrota`;

CREATE TABLE `tblrota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `obs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

#
# Structure for the `tblrotacliente` table : 
#

DROP TABLE IF EXISTS `tblrotacliente`;

CREATE TABLE `tblrotacliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) DEFAULT NULL,
  `idrota` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

#
# Structure for the `tblservicos` table : 
#

DROP TABLE IF EXISTS `tblservicos`;

CREATE TABLE `tblservicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `unidade` varchar(10) DEFAULT NULL,
  `precounitario` double NOT NULL,
  `precosn` varchar(3) DEFAULT NULL,
  `rc` varchar(3) DEFAULT NULL,
  `qtd` varchar(3) DEFAULT NULL,
  `imposto` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

#
# Structure for the `tblusuario` table : 
#

DROP TABLE IF EXISTS `tblusuario`;

CREATE TABLE `tblusuario` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `Nome` varchar(50) DEFAULT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Endereco` varchar(50) DEFAULT NULL,
  `Bairro` varchar(50) DEFAULT NULL,
  `Cidade` varchar(50) DEFAULT NULL,
  `Cep` varchar(50) DEFAULT NULL,
  `UF` varchar(50) DEFAULT NULL,
  `Telefone` varchar(50) DEFAULT NULL,
  `cota` varchar(200) DEFAULT NULL,
  `tema` varchar(255) DEFAULT NULL,
  `wallpapers` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

#
# Structure for the `tblvendedor` table : 
#

DROP TABLE IF EXISTS `tblvendedor`;

CREATE TABLE `tblvendedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `obs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

#
# Data for the `tblcentro` table  (LIMIT 0,500)
#

INSERT INTO `tblcentro` (`Id`, `Descricao`, `IdUsuario`) VALUES 
  (20,'teste',NULL),
  (21,'teste 22',NULL);
COMMIT;

#
# Data for the `tblclienteservico` table  (LIMIT 0,500)
#

INSERT INTO `tblclienteservico` (`id`, `idcliente`, `idservico`, `valor`) VALUES 
  (2,1,1,1550.400),
  (3,1,2,10.000);
COMMIT;

#
# Data for the `tblforma` table  (LIMIT 0,500)
#

INSERT INTO `tblforma` (`id`, `descricao`, `tipo`, `dias`) VALUES 
  (1,'A vista',0,NULL),
  (2,'30 dias',2,NULL);
COMMIT;

#
# Data for the `tblfornecedormercadoria` table  (LIMIT 0,500)
#

INSERT INTO `tblfornecedormercadoria` (`Id`, `idmercadoria`, `idfornecedor`, `checked`) VALUES 
  (1,2,1,1);
COMMIT;

#
# Data for the `tblmotorista` table  (LIMIT 0,500)
#

INSERT INTO `tblmotorista` (`id`, `codigo`, `nome`, `obs`) VALUES 
  (2,'1','teste de motorista','teste obs');
COMMIT;

#
# Data for the `tblromanei` table  (LIMIT 0,500)
#

INSERT INTO `tblromanei` (`id`, `os`, `pedido`, `idcliente`, `solicitante`, `data`, `liberacao`, `retirada`, `idvendedor`, `idforma`, `desconto`, `total`, `status`) VALUES 
  (1,1,123,0,'Cleber','2010-01-10','2015-05-21','2012-01-20',0,0,10.000,15.000,1);
COMMIT;

#
# Data for the `tblrotacliente` table  (LIMIT 0,500)
#

INSERT INTO `tblrotacliente` (`id`, `idcliente`, `idrota`, `checked`) VALUES 
  (7,1,2,1);
COMMIT;

#
# Data for the `tblusuario` table  (LIMIT 0,500)
#

INSERT INTO `tblusuario` (`Id`, `login`, `senha`, `Nome`, `IdUsuario`, `Email`, `Endereco`, `Bairro`, `Cidade`, `Cep`, `UF`, `Telefone`, `cota`, `tema`, `wallpapers`) VALUES 
  (1,'admin','admin','Usuario Administrador',0,'-','-','-','-','-','-','-','-','blue','wallpapers/grande/000059.jpg');
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;