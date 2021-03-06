-- phpMyAdmin SQL Dump
-- version 2.9.0-rc1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 21, 2011 at 07:14 PM
-- Server version: 5.0.24
-- PHP Version: 5.1.6
-- 
-- Database: `lavaluvas`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `tblcentro`
-- 

CREATE TABLE `tblcentro` (
  `Id` int(11) NOT NULL auto_increment,
  `Descricao` text,
  `IdUsuario` int(11) default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- 
-- Dumping data for table `tblcentro`
-- 

INSERT INTO `tblcentro` (`Id`, `Descricao`, `IdUsuario`) VALUES 
(20, 'teste', NULL),
(21, 'teste 22', NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `tblfornecedor`
-- 

CREATE TABLE `tblfornecedor` (
  `id` int(11) NOT NULL auto_increment,
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `tblfornecedor`
-- 

INSERT INTO `tblfornecedor` (`id`, `codigo`, `nome`, `endereco`, `bairro`, `cidade`, `cep`, `uf`, `telefone1`, `telefone2`, `telefone3`, `cnpj`, `estadual`, `municipal`, `contato`, `email`, `obs`, `tipo`) VALUES 
(1, 'codigo1', 'nome1', 'endereco1', 'bairro1', 'cidade11', 'cep1', 'estado11', 'telefone1', 'celular1', 'faz1', 'cpnf1', 'estadual1', 'municipal1', 'contato1', 'email1', 'obs1', 'J');

-- --------------------------------------------------------

-- 
-- Table structure for table `tblrota`
-- 

CREATE TABLE `tblrota` (
  `id` int(11) NOT NULL auto_increment,
  `codigo` varchar(10) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `obs` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `tblrota`
-- 

INSERT INTO `tblrota` (`id`, `codigo`, `descricao`, `obs`) VALUES 
(1, 'codigo1', 'descricao1', 'obs1');

-- --------------------------------------------------------

-- 
-- Table structure for table `tblservicos`
-- 

CREATE TABLE `tblservicos` (
  `id` int(11) NOT NULL auto_increment,
  `codigo` varchar(50) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `unidade` varchar(10) NOT NULL,
  `precounitario` double NOT NULL,
  `precosn` varchar(3) NOT NULL,
  `rc` varchar(3) NOT NULL,
  `qtd` varchar(3) NOT NULL,
  `imposto` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `tblservicos`
-- 

INSERT INTO `tblservicos` (`id`, `codigo`, `descricao`, `unidade`, `precounitario`, `precosn`, `rc`, `qtd`, `imposto`) VALUES 
(1, '1', 'teste - teste', 'SIM', 15, 'SIM', 'SIM', 'SIM', 'SIM');

-- --------------------------------------------------------

-- 
-- Table structure for table `tblusuario`
-- 

CREATE TABLE `tblusuario` (
  `Id` int(11) NOT NULL auto_increment,
  `login` varchar(255) default NULL,
  `senha` varchar(255) default NULL,
  `Nome` varchar(50) default NULL,
  `IdUsuario` int(11) default NULL,
  `Email` varchar(50) default NULL,
  `Endereco` varchar(50) default NULL,
  `Bairro` varchar(50) default NULL,
  `Cidade` varchar(50) default NULL,
  `Cep` varchar(50) default NULL,
  `UF` varchar(50) default NULL,
  `Telefone` varchar(50) default NULL,
  `cota` varchar(200) default NULL,
  `tema` varchar(255) default NULL,
  `wallpapers` varchar(255) default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `tblusuario`
-- 

INSERT INTO `tblusuario` (`Id`, `login`, `senha`, `Nome`, `IdUsuario`, `Email`, `Endereco`, `Bairro`, `Cidade`, `Cep`, `UF`, `Telefone`, `cota`, `tema`, `wallpapers`) VALUES 
(1, 'admin', 'admin', 'Usuario Administrador', 0, '-', '-', '-', '-', '-', '-', '-', '-', 'blue', 'wallpapers/grande/000059.jpg');

CREATE TABLE `tblcliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) DEFAULT NULL,
  `nome` varchar(200) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
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
  `cep` int(11) DEFAULT NULL,
  `endereco1` varchar(100) DEFAULT NULL,
  `bairro1` varchar(20) DEFAULT NULL,
  `cidade1` varchar(20) DEFAULT NULL,
  `estado1` varchar(20) DEFAULT NULL,
  `cep1` varchar(20) DEFAULT NULL,
  `obs` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE `tblclienteservico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) DEFAULT NULL,
  `idservico` int(11) DEFAULT NULL,
  `valor` double(15,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;