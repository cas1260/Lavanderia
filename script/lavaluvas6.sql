-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Jun 13, 2011 as 03:07 PM
-- Versão do Servidor: 5.5.8
-- Versão do PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `lavaluvas`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblcentro`
--

CREATE TABLE IF NOT EXISTS `tblcentro` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Descricao` text,
  `IdUsuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Extraindo dados da tabela `tblcentro`
--

INSERT INTO `tblcentro` (`Id`, `Descricao`, `IdUsuario`) VALUES
(22, 'PRODUÇÃO', NULL),
(23, 'ADMINISTRAÇÃO', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblcliente`
--

CREATE TABLE IF NOT EXISTS `tblcliente` (
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
  `fantasia` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `tblcliente`
--

INSERT INTO `tblcliente` (`id`, `codigo`, `nome`, `tipo`, `doc`, `estadual`, `municipal`, `contato`, `email`, `telefone`, `fax`, `celular`, `inicio`, `extra`, `extra2`, `extra3`, `endereco`, `bairro`, `cidade`, `estado`, `cep`, `endereco1`, `bairro1`, `cidade1`, `estado1`, `cep1`, `obs`, `numero`, `numero1`, `reajuste`, `fantasia`) VALUES
(4, '1', 'LAVA LUVAS', 'J', '03023775000', '186.015.421-0075', NULL, 'RONAN', 'lavaluvas@yahoo.com.br', NULL, NULL, NULL, '2011-06-13T00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2012-06-13', 'CARLOS');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblclienteservico`
--

CREATE TABLE IF NOT EXISTS `tblclienteservico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) DEFAULT NULL,
  `idservico` int(11) DEFAULT NULL,
  `valor` double(15,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `tblclienteservico`
--

INSERT INTO `tblclienteservico` (`id`, `idcliente`, `idservico`, `valor`) VALUES
(2, 1, 1, 1550.400),
(3, 1, 2, 10.000),
(4, 2, 1, 2.290);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblforma`
--

CREATE TABLE IF NOT EXISTS `tblforma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) DEFAULT NULL,
  `tipo` varchar(360) DEFAULT NULL,
  `dias` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `tblforma`
--

INSERT INTO `tblforma` (`id`, `descricao`, `tipo`, `dias`) VALUES
(1, 'A vista', '0', NULL),
(2, '30 dias', '30', NULL),
(3, '30 / 60 dias', '30 / 60', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblfornecedor`
--

CREATE TABLE IF NOT EXISTS `tblfornecedor` (
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
  `fantasia` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `tblfornecedor`
--

INSERT INTO `tblfornecedor` (`id`, `codigo`, `nome`, `endereco`, `bairro`, `cidade`, `cep`, `uf`, `telefone1`, `telefone2`, `telefone3`, `cnpj`, `estadual`, `municipal`, `contato`, `email`, `obs`, `tipo`, `fantasia`) VALUES
(1, '1', 'AGRO QUÍMICA LTDA', 'RUA RIO A', 'INDUSTRIAL', 'MONTRELA', '32000-000', 'SP', '(11) 3396 - 001', '', '', '01234567000', '186.015.411-0025', '', 'RODRIGO', 'DIATOM@DIATOM.COM.BR', '', 'J', 'Fantasia');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblfornecedormercadoria`
--

CREATE TABLE IF NOT EXISTS `tblfornecedormercadoria` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idmercadoria` int(11) DEFAULT NULL,
  `idfornecedor` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `tblfornecedormercadoria`
--

INSERT INTO `tblfornecedormercadoria` (`Id`, `idmercadoria`, `idfornecedor`, `checked`) VALUES
(6, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblitemromanei`
--

CREATE TABLE IF NOT EXISTS `tblitemromanei` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idservico` int(11) DEFAULT NULL,
  `obra` varchar(20) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL,
  `valor` double(15,3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Extraindo dados da tabela `tblitemromanei`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `tblmercadoria`
--

CREATE TABLE IF NOT EXISTS `tblmercadoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `valor` double(15,3) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL,
  `qtdminimo` int(11) DEFAULT NULL,
  `obs` text,
  `unidade` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `tblmercadoria`
--

INSERT INTO `tblmercadoria` (`id`, `codigo`, `descricao`, `valor`, `qtd`, `qtdminimo`, `obs`, `unidade`) VALUES
(1, '000001', 'BOBINA PICOTADA ', 8.370, 20, 12, '30X40, 35X50, 40X60\r\n', ''),
(2, '2', 'SACO PARA LIXO', 8.540, 5, 3, 'MAIS FINO - EXPESSURA 0,10', 'PC');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblmotorista`
--

CREATE TABLE IF NOT EXISTS `tblmotorista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `obs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `tblmotorista`
--

INSERT INTO `tblmotorista` (`id`, `codigo`, `nome`, `obs`) VALUES
(2, '1', 'CARLOS HAMILTON', 'teste obs'),
(3, '2', 'EDSON', ''),
(4, '3', 'EDUARDO LUCIANO', ''),
(5, '4', 'RONAN BRUZZI', ''),
(6, '5', 'THALES HENRIQUE', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblromanei`
--

CREATE TABLE IF NOT EXISTS `tblromanei` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `tblromanei`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `tblrota`
--

CREATE TABLE IF NOT EXISTS `tblrota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `obs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `tblrota`
--

INSERT INTO `tblrota` (`id`, `codigo`, `descricao`, `obs`) VALUES
(1, '000001', 'DIÁRIO', 'Atendimento todos os dias'),
(2, '000002', '2ª', '2ª-feira'),
(3, '000003', '3ª', '3ª-feira'),
(4, '000004', '4ª', '4ª-feira'),
(5, '000005', '5ª', '5ª-feira'),
(6, '000006', '6ª', '6ª-feira');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblrotacliente`
--

CREATE TABLE IF NOT EXISTS `tblrotacliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) DEFAULT NULL,
  `idrota` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Extraindo dados da tabela `tblrotacliente`
--

INSERT INTO `tblrotacliente` (`id`, `idcliente`, `idrota`, `checked`) VALUES
(19, 1, 2, 1),
(20, 1, 4, 1),
(21, 2, 2, 1),
(22, 2, 4, 1),
(23, 3, 3, 1),
(24, 3, 6, 1),
(27, 4, 4, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblservicos`
--

CREATE TABLE IF NOT EXISTS `tblservicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `unidade` varchar(10) DEFAULT NULL,
  `precounitario` double NOT NULL,
  `precosn` varchar(3) DEFAULT NULL,
  `rc` varchar(3) DEFAULT NULL,
  `qtd` varchar(3) DEFAULT NULL,
  `imposto` varchar(10) DEFAULT NULL,
  `desconto` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `tblservicos`
--

INSERT INTO `tblservicos` (`id`, `codigo`, `descricao`, `unidade`, `precounitario`, `precosn`, `rc`, `qtd`, `imposto`, `desconto`) VALUES
(1, '29', 'RECUP. CALÇA', 'UN', 2.29, NULL, '0,3', NULL, NULL, '1.00'),
(2, '36', 'RECUP. CAMISA', 'UN', 2.29, NULL, NULL, NULL, NULL, ''),
(3, '28', 'RECUP. BOTINA', 'PAR', 5.49, '0,2', NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblusuario`
--

CREATE TABLE IF NOT EXISTS `tblusuario` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `tblusuario`
--

INSERT INTO `tblusuario` (`Id`, `login`, `senha`, `Nome`, `IdUsuario`, `Email`, `Endereco`, `Bairro`, `Cidade`, `Cep`, `UF`, `Telefone`, `cota`, `tema`, `wallpapers`) VALUES
(1, 'admin', 'admin', 'Usuario Administrador', 0, '-', '-', '-', '-', '-', '-', '-', '-', 'blue', 'wallpapers/grande/000059.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblvendedor`
--

CREATE TABLE IF NOT EXISTS `tblvendedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `obs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `tblvendedor`
--

INSERT INTO `tblvendedor` (`id`, `codigo`, `nome`, `obs`) VALUES
(1, '1', 'CARLOS HAMILTON', ''),
(2, '2', 'EDSON', ''),
(3, '3', 'EDUARDO LUCIANO', ''),
(4, '4', 'MARIA DO CARMO', ''),
(5, '5', 'RONAN BRUZZI', ''),
(6, '6', 'THALES HENRIQUE', '');
