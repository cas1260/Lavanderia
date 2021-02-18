-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Jun 30, 2011 as 05:15 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Extraindo dados da tabela `tblcentro`
--

INSERT INTO `tblcentro` (`Id`, `Descricao`, `IdUsuario`) VALUES
(22, 'PRODUÇÃO', NULL),
(23, 'ADMINISTRAÇÃO', NULL),
(24, 'MEIO AMBIENTE', NULL);

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
  `status` int(11) NOT NULL,
  `idforma` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `tblcliente`
--

INSERT INTO `tblcliente` (`id`, `codigo`, `nome`, `tipo`, `doc`, `estadual`, `municipal`, `contato`, `email`, `telefone`, `fax`, `celular`, `inicio`, `extra`, `extra2`, `extra3`, `endereco`, `bairro`, `cidade`, `estado`, `cep`, `endereco1`, `bairro1`, `cidade1`, `estado1`, `cep1`, `obs`, `numero`, `numero1`, `reajuste`, `fantasia`, `status`, `idforma`) VALUES
(5, '2', 'PROEMA AUTOMOTIVA S/A', 'J', '04450767000', '001.011.838-0095', NULL, 'HEVERTON / CELSO', 'lourdes.amaral@proema.com', '(31) 2102 - 9700', NULL, NULL, '2007-11-27T00:00:00', 'Atendimento fixo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AV. ENGENHEIRO GE', 'PAULO CAMILO', 'BETIM', 'MG', NULL, NULL, NULL, 'S/N', '2011-12-31', 'PROEMA', 0, 0),
(6, '3', 'CEVA LOGISTICS LTDA', 'J', '43854116004', '067.729.316-0185', NULL, 'OSVALDINA / GISELLE', 'giselle.montoya@cevalogistics.com', '(31) 2123 - 3431', NULL, '(31) 8465 - 0586', '2010-10-08T00:00:00', 'Horário de atendimento:Preferencialmente na parte da manhã.', '(31) 8465 - 0586 Gisele Montoya (31) 8491-8416 Eduardo Domingos', 'Portaria 03 - galpão 21 - bloco B', NULL, NULL, NULL, NULL, NULL, 'ROD. FERNÃO DIAS', 'JARDIM TERESOPOLIS', 'BETIM', 'MG', '32.500-630', NULL, NULL, 'KM 429', '2011-10-07', 'CEVA FIAT', 3, 1),
(7, '4', 'TECNOMETAL ENG. E CONST. MECANICA LTDA', 'J', '38625489000', '712.666.748-0085', NULL, 'LUCIANO / MAERCIO', 'edilaura.goncalves@tecnometal.com.br', '(31) 2122 - 2427', '(31) 2122 - 2441', NULL, '2011-06-01T00:00:00', 'CADASTRADO DESDE 27/11/2007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AV. DAS NACOES', 'DISTRITO INDUSTRIAL', 'VESPASIANO', 'MG', '33.200-000', NULL, NULL, '3801', '2012-05-31', 'TECNOMETAL', 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblclienteservico`
--

CREATE TABLE IF NOT EXISTS `tblclienteservico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) DEFAULT NULL,
  `idservico` int(11) DEFAULT NULL,
  `valor` double(15,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `tblclienteservico`
--

INSERT INTO `tblclienteservico` (`id`, `idcliente`, `idservico`, `valor`) VALUES
(2, 1, 1, 1550.40),
(3, 1, 2, 10.00),
(4, 2, 1, 2.29),
(5, 7, 6, 1.50);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblforma`
--

CREATE TABLE IF NOT EXISTS `tblforma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) DEFAULT NULL,
  `tipo` varchar(360) DEFAULT NULL,
  `dias` int(11) DEFAULT NULL,
  `obs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `tblforma`
--

INSERT INTO `tblforma` (`id`, `descricao`, `tipo`, `dias`, `obs`) VALUES
(1, 'À VISTA', 'À VISTA', NULL, 'Desconto de 5%'),
(2, '30', '30 DIAS', NULL, ''),
(3, '30/60', '30 / 60 DIAS', NULL, ''),
(4, '28/42', '28 / 42 DIAS', NULL, ''),
(5, '28', '28 DIAS', NULL, ''),
(6, '15', '15 DIAS', NULL, ''),
(7, '05', '05 DIAS', NULL, ''),
(8, '07', '07 DIAS', NULL, '');

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
  `telefone1` varchar(20) NOT NULL,
  `telefone2` varchar(20) NOT NULL,
  `telefone3` varchar(20) NOT NULL,
  `cnpj` varchar(20) NOT NULL,
  `estadual` varchar(20) NOT NULL,
  `municipal` varchar(20) NOT NULL,
  `contato` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `obs` text NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `fantasia` varchar(100) NOT NULL,
  `idforma` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `tblfornecedor`
--

INSERT INTO `tblfornecedor` (`id`, `codigo`, `nome`, `endereco`, `bairro`, `cidade`, `cep`, `uf`, `telefone1`, `telefone2`, `telefone3`, `cnpj`, `estadual`, `municipal`, `contato`, `email`, `obs`, `tipo`, `fantasia`, `idforma`) VALUES
(2, '1', 'FCP COMERCIAL DE PLÁSTICOS SINTÉTICO LTDA', 'AV. OLEGÁRIO MACIEL, 486', 'CENTRO', 'BELO HORIZONTE', '32000-000', 'MG', '(31) 3238 - 1234', '(12) 3456 - 7890', '', '07544253000', '', '', '', '', '(31) 3238 - 0063Inscrição Estadual - Isento\n(00) 00000 00000 teste\n00000\n000\n00\n0', 'F', 'FCP', 1),
(3, '2', 'MAXXI QUÍMICA LTDA', 'AV. OLAVO DOS SANTOS, 480', 'DISTRITO INDUSTRIAL', 'PARÁ DE MINAS', '35660-251', 'MG', '(37) 3236 - 272', '(31) 9979 - 026', '', '02006487000', '471.708.721-0064', '', 'JOANA / DAYSE', 'vendasmaxxi@nwm.com.br', '(37) 3236 - 2723(31) 9979 - 0267', 'J', 'MAXXI QUÍMICA', 0),
(5, '3', 'MANCHESTER CHEMICAL PRODUTOS QUÍMICOS LTDA', 'RUA AMADEU, 645', 'VILA GUILHERME', 'SÃO PAULO', '02064-050', 'SP', '(31) 3355 - 971', '', '(11) 6905 - 152', '50555254000111', '011.007.584-9117', '', 'VILMA / JÔ', 'manchester@manchesterchemical.com.br', '31 3355-9711\n11 6905-1522\n\njosilva@manchesterchemical.com.br', 'J', 'MANCHESTER', 0),
(6, '4', 'Teste de Fornecedor', 'Endereco', 'Bairro', 'Cidade', '32001-400', 'MG', '(31) 8864 - 5049', '(31) 8806 - 5049', '', '06380498656', '', '', 'cleber', 'neobh.com.br@gmail.com', 'Pagamento avista desconto de 5%', 'F', 'Teste de Fantasia', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=96 ;

--
-- Extraindo dados da tabela `tblfornecedormercadoria`
--

INSERT INTO `tblfornecedormercadoria` (`Id`, `idmercadoria`, `idfornecedor`, `checked`) VALUES
(6, 1, 1, 1),
(40, 3, 6, 1),
(44, 10, 3, 1),
(87, 3, 2, 1),
(88, 4, 2, 1),
(89, 5, 2, 1),
(90, 6, 2, 1),
(91, 7, 2, 1),
(92, 8, 2, 1),
(93, 9, 2, 1),
(94, 3, 6, 1),
(95, 10, 6, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblitemromanei`
--

CREATE TABLE IF NOT EXISTS `tblitemromanei` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idservico` int(11) DEFAULT NULL,
  `obra` varchar(20) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL,
  `valor` double(15,3) DEFAULT NULL,
  `subtotal` double(15,3) DEFAULT NULL,
  `idromanei` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

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
  `valor` double(15,2) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL,
  `qtdminimo` int(11) DEFAULT NULL,
  `obs` text,
  `unidade` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Extraindo dados da tabela `tblmercadoria`
--

INSERT INTO `tblmercadoria` (`id`, `codigo`, `descricao`, `valor`, `qtd`, `qtdminimo`, `obs`, `unidade`) VALUES
(3, '1', 'LINHA 40 - COR BEGE', 4.90, 8, 5, 'ROLO DE LINHA PARA COSTURA / RECUPERAÇÃO', 'UN'),
(4, '2', 'LINHA 60 - COR CINZA CLARO', 4.90, 5, 3, 'ROLO DE LINHA PARA COSTURA / RECUPERAÇÃO', 'UN'),
(5, '3', 'LINHA 60 - COR CINZA ESCURO3', 4.90, 5, 5, 'ROLO DE LINHA PARA COSTURA / RECUPERAÇÃO', 'UN'),
(6, '4', 'VELCRO 50 PRETO', 0.93, 100, 50, 'VELCRO IMPORTADO - METRO', 'MT'),
(7, '5', 'VELCRO 50 BRANCO', 0.93, 50, 25, 'VELCRO IMPORTADO - METRO', 'MT'),
(8, '6', 'POLIESTER PVC 600 - PRETO', 3.50, 5, 3, 'LONA PARA ?', 'MT'),
(9, '7', 'LINHA 60 - COR BRANCA', 4.90, 15, 8, 'ROLO DE LINHA PARA COSTURA / RECUPERAÇÃO', 'UN'),
(10, '8', 'RENEX 95', 9.95, 50, 10, 'PRODUTO COM + 5% DO VALOR REFERENTE IPI - IMPOSTO PRODUTO INDUSTRIALIZADO.', 'KG');

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
  `data` date DEFAULT NULL,
  `idvendedor` int(11) DEFAULT NULL,
  `pedido` varchar(20) DEFAULT NULL,
  `volume` varchar(20) DEFAULT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `solicitante` varchar(150) DEFAULT NULL,
  `idmotorista` int(11) DEFAULT NULL,
  `entrada` date DEFAULT NULL,
  `desconto` double(11,0) DEFAULT NULL,
  `valortotal` double(11,0) DEFAULT NULL,
  `idforma` int(11) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `tblrota`
--

INSERT INTO `tblrota` (`id`, `codigo`, `descricao`, `obs`) VALUES
(1, '1', 'DIÁRIO', 'ATENDIMENTO TODOS OS DIAS DA SEMANA'),
(2, '2', '2ª', '2ª-feira'),
(3, '3', '3ª', '3ª-feira'),
(4, '4', '4ª', '4ª-feira'),
(5, '5', '5ª', '5ª-feira'),
(6, '6', '6ª', '6ª-feira'),
(7, '7', 'CLIENTE', 'CLIENTE RESPONSÁVEL PELA RETIRADA DO MATERIAL'),
(8, '8', 'TRANSPORTADORA', 'SOLICITAR COLETA PELA TRANSPORTADORA QUE ATENDE O CLIENTE'),
(9, '9', 'INDETERMINADO', 'CLIENTE LOCALIZADO FORA DOS ROTEIROS - REGIÃO ISOLADA');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

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
(27, 4, 4, 1),
(43, 5, 3, 1),
(44, 5, 6, 1),
(54, 6, 4, 1),
(55, 6, 6, 1),
(56, 7, 6, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblservicos`
--

CREATE TABLE IF NOT EXISTS `tblservicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `unidade` varchar(10) DEFAULT NULL,
  `precounitario` double(15,2) NOT NULL,
  `precosn` varchar(3) DEFAULT NULL,
  `rc` varchar(3) DEFAULT NULL,
  `qtd` varchar(3) DEFAULT NULL,
  `imposto` varchar(10) DEFAULT NULL,
  `desconto` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=165 ;

--
-- Extraindo dados da tabela `tblservicos`
--

INSERT INTO `tblservicos` (`id`, `codigo`, `descricao`, `unidade`, `precounitario`, `precosn`, `rc`, `qtd`, `imposto`, `desconto`) VALUES
(2, '36', 'RECUP. CAMISA', 'UN', 2.42, NULL, NULL, NULL, NULL, ''),
(4, '3', 'LOCAÇÃO DE TOALHA INDUSTRIAL', 'UN', 0.61, NULL, NULL, NULL, NULL, ''),
(5, '28', 'RECUP. BOTA/SAPATO', 'PAR', 6.35, NULL, NULL, NULL, NULL, ''),
(6, '29', 'RECUP. CALÇA', 'UN', 2.42, NULL, NULL, NULL, NULL, ''),
(7, '4', 'RECUP. ABAFADOR DE RUÍDO', 'UN', 2.32, NULL, NULL, NULL, NULL, ''),
(8, '5', 'RECUP. AVENTAL DE BRIM', 'UN', 2.89, NULL, NULL, NULL, NULL, ''),
(9, '6', 'RECUP. AVENTAL DE LONA', 'UN', 2.89, NULL, NULL, NULL, NULL, ''),
(10, '7', 'RECUP. AVENTAL DE PVC', 'UN', 2.89, NULL, NULL, NULL, NULL, ''),
(11, '8', 'RECUP. AVENTAL DE RASPA C/ MANGA', 'UN', 9.31, NULL, NULL, NULL, NULL, ''),
(12, '9', 'RECUP. AVENTAL DE RASPA S/ MANGA', 'UN', 8.22, NULL, NULL, NULL, NULL, ''),
(13, '10', 'RECUP. BANDEIRA', 'UN', 6.76, NULL, NULL, NULL, NULL, ''),
(14, '11', 'RECUP. BERMUDA', 'UN', 2.39, NULL, NULL, NULL, NULL, ''),
(15, '12', 'RECUP. BLAYSER', 'UN', 2.69, NULL, NULL, NULL, NULL, ''),
(16, '30', 'RECUP. CAMISA DE MALHA', 'UN', 2.29, NULL, NULL, NULL, NULL, ''),
(17, '13', 'RECUP. BLUSÃO ACOLCHOADO', 'UN', 10.58, NULL, NULL, NULL, NULL, ''),
(18, '14', 'RECUP. BLUSÃO DE LONA', 'UN', 9.20, NULL, NULL, NULL, NULL, ''),
(19, '20', 'RECUP. BLUSÃO DE RASPA', 'UN', 11.11, NULL, NULL, NULL, NULL, ''),
(21, '15', 'RECUP. BLUSÃO FRIGORÍFICO', 'UN', 10.58, NULL, NULL, NULL, NULL, ''),
(22, '16', 'RECUP. BOLSA', 'UN', 1.70, NULL, NULL, NULL, NULL, ''),
(23, '17', 'RECUP. BONÉ', 'UN', 1.30, NULL, NULL, NULL, NULL, ''),
(24, '18', 'RECUP. BOTA DE VIRÍLIA', 'PAR', 7.56, NULL, NULL, NULL, NULL, ''),
(25, '27', 'RECUP. BOTA PVC', 'PAR', 6.35, NULL, NULL, NULL, NULL, ''),
(26, '31', 'RECUP. CALÇA DE PVC', 'UN', 2.48, NULL, NULL, NULL, NULL, ''),
(27, '32', 'RECUP. CALÇA DE RASPA', 'UN', 11.11, NULL, NULL, NULL, NULL, ''),
(28, '19', 'RECUP. CALÇA FRIGORÍFICO', 'UN', 5.49, NULL, NULL, NULL, NULL, ''),
(29, '33', 'RECUP. CALÇA NOMEX', 'UN', 3.42, NULL, NULL, NULL, NULL, ''),
(30, '34', 'RECUP. CAMISA NOMEX', 'UN', 3.42, NULL, NULL, NULL, NULL, ''),
(31, '35', 'RECUP. CALÇA OP. MOTOSSERRA', 'UN', 5.79, NULL, NULL, NULL, NULL, ''),
(32, '37', 'RECUP. CAMISA CONDUTIVA', 'UN', 3.29, NULL, NULL, NULL, NULL, ''),
(33, '40', 'RECUP. CAPA DE CHUVA', 'UN', 2.48, NULL, NULL, NULL, NULL, ''),
(34, '41', 'RECUP. CAPA P/ BANCO DE CARRO', 'UN', 5.43, NULL, NULL, NULL, NULL, ''),
(35, '43', 'RECUP. CAPACETE', 'UN', 3.59, NULL, NULL, NULL, NULL, ''),
(36, '42', 'RECUP. CAPACETE JATO', 'UN', 6.90, NULL, NULL, NULL, NULL, ''),
(37, '46', 'RECUP. CAPUZ', 'UN', 1.30, NULL, NULL, NULL, NULL, ''),
(38, '47', 'RECUP. CARNEIRA', 'UN', 0.97, NULL, NULL, NULL, NULL, ''),
(39, '48', 'RECUP. CINTO DE CARRO', 'UN', 8.76, NULL, NULL, NULL, NULL, ''),
(40, '50', 'RECUP. CINTO DE SEGURANÇA', 'UN', 5.49, NULL, NULL, NULL, NULL, ''),
(41, '44', 'RECUP. COBERTOR', 'UN', 4.53, NULL, NULL, NULL, NULL, ''),
(42, '45', 'RECUP. COLAR SERVICAL', 'UN', 1.98, NULL, NULL, NULL, NULL, ''),
(43, '49', 'RECUP. COLCHA', 'UN', 4.43, NULL, NULL, NULL, NULL, ''),
(44, '51', 'RECUP. CAPA DE COLCHÃO', 'UN', 20.00, NULL, NULL, NULL, NULL, ''),
(45, '52', 'RECUP. COLETE SALVA-VIDAS', 'UN', 3.97, NULL, NULL, NULL, NULL, ''),
(46, '53', 'RECUP. COMPRESSAS CIRURGICAS', 'UN', 1.19, NULL, NULL, NULL, NULL, ''),
(47, '54', 'RECUP. CONE', 'UN', 3.67, NULL, NULL, NULL, NULL, ''),
(48, '55', 'RECUP. CORDA ( METRO )', 'MT', 0.78, NULL, NULL, NULL, NULL, ''),
(49, '56', 'RECUP. CORTINA DE CAMINHÃO', 'UN', 1.44, NULL, NULL, NULL, NULL, ''),
(50, '57', 'RECUP. EDREDON SOLTEIRO', 'UN', 4.97, NULL, NULL, NULL, NULL, ''),
(51, '58', 'RECUP. EDREDON CASAL', 'UN', 6.45, NULL, NULL, NULL, NULL, ''),
(52, '58', 'RECUP. ENXARPE', 'UN', 1.50, NULL, NULL, NULL, NULL, ''),
(53, '59', 'RECUP. FAIXA REFLETIVA', 'UN', 1.98, NULL, NULL, NULL, NULL, ''),
(54, '60', 'RECUP. FRONHA', 'UN', 0.89, NULL, NULL, NULL, NULL, ''),
(55, '61', 'RECUP. GARRAFA TÉRMICA', 'UN', 5.31, NULL, NULL, NULL, NULL, ''),
(56, '62', 'RECUP. GORRO', 'UN', 1.45, NULL, NULL, NULL, NULL, ''),
(57, '63', 'RECUP. GRAVATA', 'UN', 1.19, NULL, NULL, NULL, NULL, ''),
(58, '64', 'RECUP. GUARDA-PÓ', 'UN', 2.56, NULL, NULL, NULL, NULL, ''),
(59, '65', 'RECUP. JALECO', 'UN', 2.69, NULL, NULL, NULL, NULL, ''),
(60, '66', 'RECUP. LENÇOL', 'UN', 1.74, NULL, NULL, NULL, NULL, ''),
(61, '67', 'RECUP. LUVA DE GRAFATEX', 'PAR', 2.56, NULL, NULL, NULL, NULL, ''),
(62, '68', 'RECUP. LUVA DE HELANCA', 'PAR', 0.49, NULL, NULL, NULL, NULL, ''),
(63, '69', 'RECUP. LUVA DE LONA C/ CURTO', 'PAR', 1.19, NULL, NULL, NULL, NULL, ''),
(64, '70', 'RECUP. LUVA DE MALHA', 'PAR', 0.69, NULL, NULL, NULL, NULL, ''),
(65, '71', 'RECUP. LUVA DE MALHA PIGMENTADA', 'PAR', 0.69, NULL, NULL, NULL, NULL, ''),
(66, '72', 'RECUP. LUVA DE NITRILON', 'PAR', 2.26, NULL, NULL, NULL, NULL, ''),
(67, '73', 'RECUP. LUVA DE PVC C/ CURTO', 'PAR', 2.26, NULL, NULL, NULL, NULL, ''),
(68, '74', 'RECUP. LUVA DE PVC C/ LONGO', 'PAR', 2.56, NULL, NULL, NULL, NULL, ''),
(69, '75', 'RECUP. LUVA DE RASPA C/ CURTO', 'PAR', 2.69, NULL, NULL, NULL, NULL, ''),
(70, '76', 'RECUP. LUVA DE RASPA C/ LONGO', 'PAR', 2.91, NULL, NULL, NULL, NULL, ''),
(71, '77', 'RECUP. LUVA DE VAQUETA C/ CURTO', 'PAR', 2.69, NULL, NULL, NULL, NULL, ''),
(72, '78', 'RECUP. LUVA DE VAQUETA C/ LONGO', 'PAR', 2.91, NULL, NULL, NULL, NULL, ''),
(73, '79', 'RECUP. LUVA FRIGORÍFICO', 'PAR', 2.91, NULL, NULL, NULL, NULL, ''),
(74, '80', 'RECUP. LUVA TÉRMICA', 'PAR', 2.91, NULL, NULL, NULL, NULL, ''),
(75, '81', 'RECUP. MACACÃO ACOLCHOADO', 'UN', 5.31, NULL, NULL, NULL, NULL, ''),
(76, '82', 'RECUP. MACACÃO PVC ACOPLADO', 'UN', 8.25, NULL, NULL, NULL, NULL, ''),
(77, '83', 'RECUP. MACACÃO PVC', 'UN', 5.75, NULL, NULL, NULL, NULL, ''),
(78, '84', 'RECUP. MACACÃO BRIM', 'UN', 4.03, NULL, NULL, NULL, NULL, ''),
(79, '85', 'RECUP. MACACÃO NOMEX', 'UN', 6.43, NULL, NULL, NULL, NULL, ''),
(80, '86', 'RECUP. MACACÃO TIVEK', 'UN', 4.50, NULL, NULL, NULL, NULL, ''),
(81, '87', 'RECUP. MALHA TUBOLAR', 'UN', 0.35, NULL, NULL, NULL, NULL, ''),
(82, '88', 'RECUP. MANGA DE BRIM', 'PAR', 3.98, NULL, NULL, NULL, NULL, ''),
(83, '89', 'RECUP. MANGA DE LONA', 'PAR', 4.28, NULL, NULL, NULL, NULL, ''),
(84, '90', 'RECUP. MANGA DE RASPA', 'PAR', 4.28, NULL, NULL, NULL, NULL, ''),
(85, '91', 'RECUP. MANGOTE DE RASPA', 'PAR', 8.50, NULL, NULL, NULL, NULL, ''),
(86, '92', 'RECUP. MANGUEIRA PARA COMPRESSOR', 'UN', 3.98, NULL, NULL, NULL, NULL, ''),
(87, '93', 'RECUP. MANTA DE RASPA', 'UN', 25.00, NULL, NULL, NULL, NULL, ''),
(88, '94', 'RECUP. MÁSCARA', 'UN', 2.32, NULL, NULL, NULL, NULL, ''),
(89, '95', 'RECUP. MÁSCARA DE SOLDA', 'UN', 3.53, NULL, NULL, NULL, NULL, ''),
(90, '96', 'RECUP. MEIÃO', 'UN', 1.19, NULL, NULL, NULL, NULL, ''),
(91, '97', 'RECUP. MOCHILA ESCOLAR', 'UN', 3.95, NULL, NULL, NULL, NULL, ''),
(92, '98', 'RECUP. MOCHILA DE CAMPING', 'UN', 5.61, NULL, NULL, NULL, NULL, ''),
(93, '99', 'RECUP. ÓCULOS DE PROTEÇÃO', 'UN', 2.25, NULL, NULL, NULL, NULL, ''),
(94, '100', 'RECUP. PALETÓ DE VAQUETA', 'UN', 6.82, NULL, NULL, NULL, NULL, ''),
(95, '101', 'RECUP. PANTUFA', 'PAR', 0.76, NULL, NULL, NULL, NULL, ''),
(96, '102', 'RECUP. PERNEIRA DE LONA', 'PAR', 4.28, NULL, NULL, NULL, NULL, ''),
(97, '103', 'RECUP. PERNEIRA DE PVC', 'PAR', 4.28, NULL, NULL, NULL, NULL, ''),
(98, '104', 'RECUP. PERNEIRA DE RASPA', 'PAR', 4.28, NULL, NULL, NULL, NULL, ''),
(99, '105', 'RECUP. POCHETE', 'UN', 2.29, NULL, NULL, NULL, NULL, ''),
(100, '106', 'RECUP. MÁSCARA RESPIRATÓRIA', 'UN', 3.57, NULL, NULL, NULL, NULL, ''),
(101, '106', 'RECUP. SHORT', 'UN', 2.10, NULL, NULL, NULL, NULL, ''),
(102, '107', 'RECUP. TALA 1º SOCORROS', 'UN', 1.49, NULL, NULL, NULL, NULL, ''),
(103, '108', 'RECUP. TALABARTE', 'UN', 1.19, NULL, NULL, NULL, NULL, ''),
(104, '109', 'RECUP. TAPETE', 'UN', 2.59, NULL, NULL, NULL, NULL, ''),
(105, '110', 'RECUP. TOALHA DE BANHO', 'UN', 1.29, NULL, NULL, NULL, NULL, ''),
(106, '111', 'RECUP. TOALHA DE MESA', 'UN', 6.90, NULL, NULL, NULL, NULL, ''),
(107, '112', 'RECUP. TOALHA DE ROSTO', 'UN', 0.98, NULL, NULL, NULL, NULL, ''),
(108, '113', 'RECUP. TOUCA', 'UN', 0.99, NULL, NULL, NULL, NULL, ''),
(109, '114', 'RECUP. CINTO DE SEGURANÇA COM BARRIGUEIRA', 'UN', 7.31, NULL, NULL, NULL, NULL, ''),
(110, '114', 'RECUP. JAQUETA DE MOTO', 'UN', 4.53, NULL, NULL, NULL, NULL, ''),
(111, '115', 'RECUP. TRAPO ESPECIAL - KG', 'KG', 8.30, NULL, NULL, NULL, NULL, ''),
(112, '116', 'RECUP. TRAPO', 'KG', 4.50, NULL, NULL, NULL, NULL, ''),
(113, '117', 'RECUP. TRAVA QUEDA', 'UN', 0.90, NULL, NULL, NULL, NULL, ''),
(114, '118', 'RECUP. PUNHO', 'PAR', 0.69, NULL, NULL, NULL, NULL, ''),
(115, '119', 'TOALHA INDUSTRIAL - NOVA', 'UN', 1.35, NULL, NULL, NULL, NULL, ''),
(116, '120', 'RECUP. LUVA WORKMAN', 'PAR', 1.71, NULL, NULL, NULL, NULL, ''),
(117, '121', 'HIGIENIZAÇÃO DE TOALHA INDUSTRIAL', 'UN', 0.45, NULL, NULL, NULL, NULL, ''),
(118, '122', 'RECUP. TRAVESSEIRO', 'UN', 1.98, NULL, NULL, NULL, NULL, ''),
(119, '123', 'RECUP. CALÇA DE RASPA - REFORMA', 'UN', 0.72, NULL, NULL, NULL, NULL, ''),
(120, '124', 'RECUP. BLUSÃO DE RASPA - REFORMA', 'UN', 7.85, NULL, NULL, NULL, NULL, ''),
(121, '125', 'RECUP. PALETÓ DE VAQUETA - REFORMA', 'UN', 10.25, NULL, NULL, NULL, NULL, ''),
(122, '126', 'RECUP. BANDEIROLA', 'UN', 1.36, NULL, NULL, NULL, NULL, ''),
(123, '127', 'RECUP. MACACÃO ABELHA', 'UN', 10.21, NULL, NULL, NULL, NULL, ''),
(124, '128', 'RECUP. CAPA ENCOSTO DE CABEÇA', 'UN', 0.79, NULL, NULL, NULL, NULL, ''),
(125, '129', 'RECUP. CALÇA ANTI-CHAMAS', 'UN', 3.48, NULL, NULL, NULL, NULL, ''),
(126, '130', 'RECUP. CAMISA ANTI-CHAMAS', 'UN', 3.48, NULL, NULL, NULL, NULL, ''),
(127, '131', 'RECUP. JAQUETA ANTI-CHAMAS', 'UN', 5.28, NULL, NULL, NULL, NULL, ''),
(128, '132', 'RECUP. FILTRO DE MANGA LINEAR', 'MT', 4.73, NULL, NULL, NULL, NULL, ''),
(129, '133', 'RECUP. BLUSÃO ANTI-CHAMAS', 'UN', 10.58, NULL, NULL, NULL, NULL, ''),
(130, '134', 'RECUP. FILTRO DE MANGA LINEAR', 'UN', 7.80, NULL, NULL, NULL, NULL, ''),
(131, '135', 'RECUP. CAPACETE ACOPLADO', 'UN', 4.28, NULL, NULL, NULL, NULL, ''),
(132, '136', 'RECUP. JAQUETA DE BRIM', 'UN', 3.98, NULL, NULL, NULL, NULL, ''),
(133, '137', 'RECUP. TIPOIA', 'UN', 2.29, NULL, NULL, NULL, NULL, ''),
(134, '138', 'TAXA DE SERVIÇO', 'UN', 20.00, NULL, NULL, NULL, NULL, ''),
(135, '139', 'RECUP. AVENTAL ALUMINIZADO', 'UN', 9.58, NULL, NULL, NULL, NULL, ''),
(136, '140', 'RECUP. MINI PERNEIRA DE LONA', 'UN', 2.87, NULL, NULL, NULL, NULL, ''),
(137, '141', 'RECUP. BOLSA DE VIAGEM', 'UN', 5.90, NULL, NULL, NULL, NULL, ''),
(138, '142', 'RECUP. JAQUETA JEANS', 'UN', 3.98, NULL, NULL, NULL, NULL, ''),
(139, '143', 'RECUP. REFORMA EM LUVA DE RASPA', 'PAR', 1.20, NULL, NULL, NULL, NULL, ''),
(140, '144', 'RECUP. LUVA DE RASPA C/ FORRO', 'PAR', 3.32, NULL, NULL, NULL, NULL, ''),
(141, '145', 'RECUP. LUVA DE PVC EXTRA LONGO', 'PAR', 2.98, NULL, NULL, NULL, NULL, ''),
(142, '146', 'RECUP. TOUCA ALUMINIZADA', 'UN', 2.98, NULL, NULL, NULL, NULL, ''),
(143, '147', 'RECUP. GUARDANAPO DE TECIDO', 'UN', 0.56, NULL, NULL, NULL, NULL, ''),
(144, '148', 'RECUP. PANO DE PRATO', 'UN', 0.85, NULL, NULL, NULL, NULL, ''),
(145, '149', 'RECUP. TOALHA DE MESA', 'MT', 1.30, NULL, NULL, NULL, NULL, ''),
(146, '150', 'RECUP. COLETE REFLETIVO', 'UN', 2.39, NULL, NULL, NULL, NULL, ''),
(147, '151', 'RECUP. MATERIAL DE RASPA', 'KG', 8.50, NULL, NULL, NULL, NULL, ''),
(148, '152', 'RECUP. MOP', 'UN', 2.90, NULL, NULL, NULL, NULL, ''),
(149, '154', 'RECUP. SAIA', 'UN', 2.39, NULL, NULL, NULL, NULL, ''),
(150, '153', 'RECUP. CAPUZ ANTI-CHAMAS', 'UN', 2.89, NULL, NULL, NULL, NULL, ''),
(151, '155', 'RECUP. MANGUEIRA', 'MT', 1.98, NULL, NULL, NULL, NULL, ''),
(152, '156', 'RECUP. CALÇA ABELHA', 'UN', 5.21, NULL, NULL, NULL, NULL, ''),
(153, '157', 'RECUP. CAMISA ABELHA', 'UN', 5.21, NULL, NULL, NULL, NULL, ''),
(154, '158', 'RECUP. LUVA DE LATEX', 'PAR', 2.26, NULL, NULL, NULL, NULL, ''),
(155, '159', 'RECUP. BLUSÃO DE ELETRICISTA', 'UN', 11.11, NULL, NULL, NULL, NULL, ''),
(156, '160', 'RECUP. CALÇA DE ELETRICISTA', 'UN', 4.29, NULL, NULL, NULL, NULL, ''),
(157, '161', 'RECUP. CAMISA DE ELETRICISTA', 'UN', 4.29, NULL, NULL, NULL, NULL, ''),
(158, '162', 'RECUP. CALÇA SECO', 'UN', 3.92, NULL, NULL, NULL, NULL, ''),
(159, '163', 'RECUP. CAMISA SECO', 'UN', 3.92, NULL, NULL, NULL, NULL, ''),
(160, '164', 'RECUP. CALÇA SOCIAL', 'UN', 3.90, NULL, NULL, NULL, NULL, ''),
(161, '165', 'RECUP. CAMISA SOCIAL', 'UN', 3.90, NULL, NULL, NULL, NULL, ''),
(162, '166', 'RECUP. PALETÓ SOCIAL', 'UN', 4.90, NULL, NULL, NULL, NULL, ''),
(163, '167', 'RECUP. CORDA P/ APITO', 'UN', 0.75, NULL, NULL, NULL, NULL, '');

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
