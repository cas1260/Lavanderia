alter table tblrota add `grupo` varchar(200) DEFAULT NULL;
truncate table tblitemromanei;
truncate table tblromanei;
truncate table tblitennf;
truncate table tblnf;
CREATE TABLE `tblpropriedades` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `valor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
