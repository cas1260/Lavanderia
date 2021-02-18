# SQL Manager 2007 Lite for MySQL 4.4.2.1
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : lavaluvas


#
# Structure for the `tblcentro` table : 
#

CREATE TABLE `tblcentro` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Descricao` text,
  `IdUsuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

#
# Structure for the `tblcliente` table : 
#

CREATE TABLE `tblcliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) DEFAULT NULL,
  `nome` varchar(200) DEFAULT NULL,
  `tipo` varchar(1) DEFAULT NULL,
  `doc` varchar(20) DEFAULT NULL,
  `estadual` varchar(20) DEFAULT NULL,
  `municipal` varchar(20) DEFAULT NULL,
  `idrota` int(11) DEFAULT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Structure for the `tblclienteservico` table : 
#

CREATE TABLE `tblclienteservico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) DEFAULT NULL,
  `idservico` int(11) DEFAULT NULL,
  `valor` double(15,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Structure for the `tblfornecedor` table : 
#

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
# Structure for the `tblmercadoria` table : 
#

CREATE TABLE `tblmercadoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `valor` double(15,3) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL,
  `qtdminimo` int(11) DEFAULT NULL,
  `obs` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

#
# Structure for the `tblmotorista` table : 
#

CREATE TABLE `tblmotorista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `obs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

#
# Structure for the `tblrota` table : 
#

CREATE TABLE `tblrota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `obs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Structure for the `tblservicos` table : 
#

CREATE TABLE `tblservicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `unidade` varchar(10) NOT NULL,
  `precounitario` double NOT NULL,
  `precosn` varchar(3) NOT NULL,
  `rc` varchar(3) NOT NULL,
  `qtd` varchar(3) NOT NULL,
  `imposto` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

#
# Structure for the `tblusuario` table : 
#

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
# Data for the `tblcliente` table  (LIMIT 0,500)
#

INSERT INTO `tblcliente` (`id`, `codigo`, `nome`, `tipo`, `doc`, `estadual`, `municipal`, `idrota`, `contato`, `email`, `telefone`, `fax`, `celular`, `inicio`, `extra`, `extra2`, `extra3`, `endereco`, `bairro`, `cidade`, `estado`, `cep`, `endereco1`, `bairro1`, `cidade1`, `estado1`, `cep1`, `obs`) VALUES 
  (1,'codigo','cliente','J','111','222','3333',1,'444','5556','6666','888','777','2011-07-08T00:00:00','99','999','99','E1','E2','D3','F55','D4','F1','F2','GG3','GHH5','GGG4','HHHHHH5');
COMMIT;

#
# Data for the `tblfornecedor` table  (LIMIT 0,500)
#

INSERT INTO `tblfornecedor` (`id`, `codigo`, `nome`, `endereco`, `bairro`, `cidade`, `cep`, `uf`, `telefone1`, `telefone2`, `telefone3`, `cnpj`, `estadual`, `municipal`, `contato`, `email`, `obs`, `tipo`) VALUES 
  (1,'codigo1','nome1','endereco1','bairro1','cidade11','cep1','estado11','telefone1','celular1','faz1','cpnf1','estadual1','municipal1','contato1','email1','obs1','J');
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
  (1,'codigo1','descricao1','obs1');
COMMIT;

#
# Data for the `tblservicos` table  (LIMIT 0,500)
#

INSERT INTO `tblservicos` (`id`, `codigo`, `descricao`, `unidade`, `precounitario`, `precosn`, `rc`, `qtd`, `imposto`) VALUES 
  (1,'1','teste - teste','SIM',15,'SIM','SIM','SIM','SIM'),
  (2,'2','dois','SIM',10,'SIM','SIM','SIM','SIM');
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


alter table tblcliente add numero nvarchar(10);
alter table tblcliente add  numero1 nvarchar(10)
alter table tblcliente add reajuste date()