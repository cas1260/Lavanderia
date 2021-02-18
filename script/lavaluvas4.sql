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

USE `lavaluvas`;

#
# Structure for the `tblcentro` table : 
#

DROP TABLE IF EXISTS `tblcentro`;

CREATE TABLE `tblcentro` (
  `Id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `Descricao` TEXT,
  `IdUsuario` INTEGER(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)

)TYPE=MyISAM COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0 ROW_FORMAT=DYNAMIC;

#
# Structure for the `tblcliente` table : 
#

DROP TABLE IF EXISTS `tblcliente`;

CREATE TABLE `tblcliente` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(20) DEFAULT NULL,
  `nome` VARCHAR(200) DEFAULT NULL,
  `tipo` VARCHAR(1) DEFAULT NULL,
  `doc` VARCHAR(20) DEFAULT NULL,
  `estadual` VARCHAR(20) DEFAULT NULL,
  `municipal` VARCHAR(20) DEFAULT NULL,
  `contato` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(50) DEFAULT NULL,
  `telefone` VARCHAR(20) DEFAULT NULL,
  `fax` VARCHAR(20) DEFAULT NULL,
  `celular` VARCHAR(20) DEFAULT NULL,
  `inicio` VARCHAR(20) DEFAULT NULL,
  `extra` TEXT,
  `extra2` TEXT,
  `extra3` TEXT,
  `endereco` VARCHAR(100) DEFAULT NULL,
  `bairro` VARCHAR(20) DEFAULT NULL,
  `cidade` VARCHAR(20) DEFAULT NULL,
  `estado` VARCHAR(10) DEFAULT NULL,
  `cep` VARCHAR(20) DEFAULT NULL,
  `endereco1` VARCHAR(100) DEFAULT NULL,
  `bairro1` VARCHAR(20) DEFAULT NULL,
  `cidade1` VARCHAR(20) DEFAULT NULL,
  `estado1` VARCHAR(20) DEFAULT NULL,
  `cep1` VARCHAR(20) DEFAULT NULL,
  `obs` TEXT,
  `numero` VARCHAR(10) DEFAULT NULL,
  `numero1` VARCHAR(10) DEFAULT NULL,
  `reajuste` DATE DEFAULT NULL,
  PRIMARY KEY (`id`)

)TYPE=InnoDB COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0;

#
# Structure for the `tblclienteservico` table : 
#

DROP TABLE IF EXISTS `tblclienteservico`;

CREATE TABLE `tblclienteservico` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `idcliente` INTEGER(11) DEFAULT NULL,
  `idservico` INTEGER(11) DEFAULT NULL,
  `valor` DOUBLE(15,3) DEFAULT NULL,
  PRIMARY KEY (`id`)

)TYPE=InnoDB COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0;

#
# Structure for the `tblfornecedor` table : 
#

DROP TABLE IF EXISTS `tblfornecedor`;

CREATE TABLE `tblfornecedor` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(50) NOT NULL,
  `nome` VARCHAR(200) NOT NULL,
  `endereco` VARCHAR(200) NOT NULL,
  `bairro` VARCHAR(100) NOT NULL,
  `cidade` VARCHAR(100) NOT NULL,
  `cep` VARCHAR(10) NOT NULL,
  `uf` VARCHAR(10) NOT NULL,
  `telefone1` VARCHAR(15) NOT NULL,
  `telefone2` VARCHAR(15) NOT NULL,
  `telefone3` VARCHAR(15) NOT NULL,
  `cnpj` VARCHAR(20) NOT NULL,
  `estadual` VARCHAR(20) NOT NULL,
  `municipal` VARCHAR(20) NOT NULL,
  `contato` VARCHAR(20) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `obs` TEXT NOT NULL,
  `tipo` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id`)

)TYPE=MyISAM COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0 ROW_FORMAT=DYNAMIC;

#
# Structure for the `tblfornecedormercadoria` table : 
#

DROP TABLE IF EXISTS `tblfornecedormercadoria`;

CREATE TABLE `tblfornecedormercadoria` (
  `Id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `idmercadoria` INTEGER(11) DEFAULT NULL,
  `idfornecedor` INTEGER(11) DEFAULT NULL,
  `checked` INTEGER(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)

)TYPE=InnoDB COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0;

#
# Structure for the `tblmercadoria` table : 
#

DROP TABLE IF EXISTS `tblmercadoria`;

CREATE TABLE `tblmercadoria` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(20) DEFAULT NULL,
  `descricao` VARCHAR(100) DEFAULT NULL,
  `valor` DOUBLE(15,3) DEFAULT NULL,
  `qtd` INTEGER(11) DEFAULT NULL,
  `qtdminimo` INTEGER(11) DEFAULT NULL,
  `obs` TEXT,
  PRIMARY KEY (`id`)

)TYPE=InnoDB COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0;

#
# Structure for the `tblmotorista` table : 
#

DROP TABLE IF EXISTS `tblmotorista`;

CREATE TABLE `tblmotorista` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(10) NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `obs` TEXT NOT NULL,
  PRIMARY KEY (`id`)

)TYPE=MyISAM COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0 ROW_FORMAT=DYNAMIC;

#
# Structure for the `tblrota` table : 
#

DROP TABLE IF EXISTS `tblrota`;

CREATE TABLE `tblrota` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(10) NOT NULL,
  `descricao` VARCHAR(100) NOT NULL,
  `obs` TEXT NOT NULL,
  PRIMARY KEY (`id`)

)TYPE=MyISAM COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0 ROW_FORMAT=DYNAMIC;

#
# Structure for the `tblrotacliente` table : 
#

DROP TABLE IF EXISTS `tblrotacliente`;

CREATE TABLE `tblrotacliente` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `idcliente` INTEGER(11) DEFAULT NULL,
  `idrota` INTEGER(11) DEFAULT NULL,
  `checked` INTEGER(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)

)TYPE=InnoDB COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0;

#
# Structure for the `tblservicos` table : 
#

DROP TABLE IF EXISTS `tblservicos`;

CREATE TABLE `tblservicos` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(50) NOT NULL,
  `descricao` VARCHAR(200) NOT NULL,
  `unidade` VARCHAR(10) DEFAULT NULL,
  `precounitario` DOUBLE NOT NULL,
  `precosn` VARCHAR(3) DEFAULT NULL,
  `rc` VARCHAR(3) DEFAULT NULL,
  `qtd` VARCHAR(3) DEFAULT NULL,
  `imposto` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (`id`)

)TYPE=MyISAM COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0 ROW_FORMAT=DYNAMIC;

#
# Structure for the `tblusuario` table : 
#

DROP TABLE IF EXISTS `tblusuario`;

CREATE TABLE `tblusuario` (
  `Id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(255) DEFAULT NULL,
  `senha` VARCHAR(255) DEFAULT NULL,
  `Nome` VARCHAR(50) DEFAULT NULL,
  `IdUsuario` INTEGER(11) DEFAULT NULL,
  `Email` VARCHAR(50) DEFAULT NULL,
  `Endereco` VARCHAR(50) DEFAULT NULL,
  `Bairro` VARCHAR(50) DEFAULT NULL,
  `Cidade` VARCHAR(50) DEFAULT NULL,
  `Cep` VARCHAR(50) DEFAULT NULL,
  `UF` VARCHAR(50) DEFAULT NULL,
  `Telefone` VARCHAR(50) DEFAULT NULL,
  `cota` VARCHAR(200) DEFAULT NULL,
  `tema` VARCHAR(255) DEFAULT NULL,
  `wallpapers` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)

)TYPE=MyISAM COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0 ROW_FORMAT=DYNAMIC;

#
# Structure for the `tblvendedor` table : 
#

DROP TABLE IF EXISTS `tblvendedor`;

CREATE TABLE `tblvendedor` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(10) NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `obs` TEXT NOT NULL,
  PRIMARY KEY (`id`)

)TYPE=MyISAM COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0 PACK_KEYS=0 MIN_ROWS=0 MAX_ROWS=0 ROW_FORMAT=DYNAMIC;

#
# Data for the `tblcentro` table  (LIMIT 0,500)
#

INSERT INTO `tblcentro` (`Id`, `Descricao`, `IdUsuario`) VALUES 
  (20,'teste',NULL),
  (21,'teste 22',NULL);
COMMIT;

#
# Data for the `tblcliente` table  (LIMIT 0,500)
#

INSERT INTO `tblcliente` (`id`, `codigo`, `nome`, `tipo`, `doc`, `estadual`, `municipal`, `contato`, `email`, `telefone`, `fax`, `celular`, `inicio`, `extra`, `extra2`, `extra3`, `endereco`, `bairro`, `cidade`, `estado`, `cep`, `endereco1`, `bairro1`, `cidade1`, `estado1`, `cep1`, `obs`, `numero`, `numero1`, `reajuste`) VALUES 
  (1,'166','cliente','F','01212345646','222','3333','444','5556','6666','888','777','2011-07-08T00:00:00','99','999','99','E1','E2','D3','F55','D4','F1','F2','GG3','GHH5','GGG4','HHHHHH5','12','1','2000-12-31');
COMMIT;

#
# Data for the `tblclienteservico` table  (LIMIT 0,500)
#

INSERT INTO `tblclienteservico` (`id`, `idcliente`, `idservico`, `valor`) VALUES 
  (1,1,3,15.500);
COMMIT;

#
# Data for the `tblfornecedor` table  (LIMIT 0,500)
#

INSERT INTO `tblfornecedor` (`id`, `codigo`, `nome`, `endereco`, `bairro`, `cidade`, `cep`, `uf`, `telefone1`, `telefone2`, `telefone3`, `cnpj`, `estadual`, `municipal`, `contato`, `email`, `obs`, `tipo`) VALUES 
  (1,'0002','nome1','endereco1','bairro1','cidade11','cep1','estado11','telefone1','celular1','faz1','1','estadual1','municipal1','contato1','email1','obs1','J');
COMMIT;

#
# Data for the `tblfornecedormercadoria` table  (LIMIT 0,500)
#

INSERT INTO `tblfornecedormercadoria` (`Id`, `idmercadoria`, `idfornecedor`, `checked`) VALUES 
  (1,2,1,1);
COMMIT;

#
# Data for the `tblmercadoria` table  (LIMIT 0,500)
#

INSERT INTO `tblmercadoria` (`id`, `codigo`, `descricao`, `valor`, `qtd`, `qtdminimo`, `obs`) VALUES 
  (2,'2','teste3',2.000,3,3,'2342');
COMMIT;

#
# Data for the `tblmotorista` table  (LIMIT 0,500)
#

INSERT INTO `tblmotorista` (`id`, `codigo`, `nome`, `obs`) VALUES 
  (2,'1','teste de motorista','teste obs');
COMMIT;

#
# Data for the `tblrota` table  (LIMIT 0,500)
#

INSERT INTO `tblrota` (`id`, `codigo`, `descricao`, `obs`) VALUES 
  (1,'codigo1','descricao1','obs1'),
  (2,'1','Rota 2','teste'),
  (3,'3','Rota 2','Rota 2');
COMMIT;

#
# Data for the `tblrotacliente` table  (LIMIT 0,500)
#

INSERT INTO `tblrotacliente` (`id`, `idcliente`, `idrota`, `checked`) VALUES 
  (2,1,2,1);
COMMIT;

#
# Data for the `tblservicos` table  (LIMIT 0,500)
#

INSERT INTO `tblservicos` (`id`, `codigo`, `descricao`, `unidade`, `precounitario`, `precosn`, `rc`, `qtd`, `imposto`) VALUES 
  (1,'1','teste - teste','SIM',15,'SIM','SIM','SIM','SIM'),
  (2,'2','dois','SIM',10,'SIM','SIM','SIM','SIM'),
  (3,'000003','teste de servico',NULL,12.5,NULL,NULL,NULL,NULL);
COMMIT;

#
# Data for the `tblusuario` table  (LIMIT 0,500)
#

INSERT INTO `tblusuario` (`Id`, `login`, `senha`, `Nome`, `IdUsuario`, `Email`, `Endereco`, `Bairro`, `Cidade`, `Cep`, `UF`, `Telefone`, `cota`, `tema`, `wallpapers`) VALUES 
  (1,'admin','admin','Usuario Administrador',0,'-','-','-','-','-','-','-','-','blue','wallpapers/grande/000059.jpg');
COMMIT;

#
# Data for the `tblvendedor` table  (LIMIT 0,500)
#

INSERT INTO `tblvendedor` (`id`, `codigo`, `nome`, `obs`) VALUES 
  (3,'1','Vendedor teste ffdff','obh de vendedor');
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;