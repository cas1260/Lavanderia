<?php

	@session_start();

	$_SESSION['servicos_campos']  = "id, codigo, descricao, unidade, precounitario,  imposto";
	$_SESSION['servicos_table']   = "tblservicos";
	$_SESSION['servicos_texto']   = "idservico, codigo, descricao, unidade, preço unitario,  imposto";
	$_SESSION['servicos_tamanho'] = "0 ,          100 , 300 ,        100  , 100          ,  100   , 100, 100, 100";
	$_SESSION['servicos_php']     = "wb_cadastro_de_servicos.php";
	$_SESSION['servicos_order']   = "descricao";

	$_SESSION['fornecedor_campos']  = "id, nome, fantasia, codigo, endereco, bairro, cidade, cep, uf, telefone1, telefone2, telefone3, cnpj, estadual, municipal, contato, email";
	$_SESSION['fornecedor_table']   = "tblfornecedor";
	$_SESSION['fornecedor_texto']   = "id, Nome, Fantasia, Codigo, Endereço, Bairro, Cidade, Cep, Estado, Telefone, Celular, FAX, CNPJ/CPF, I. Estadual, I. Municipal, Contato, Email";
	$_SESSION['fornecedor_tamanho'] = "0 , 300 , 300,      100, 150     , 100   , 70    , 50,  30    , 100     , 100    , 100, 150     , 100        , 100         , 200, 200";
	$_SESSION['fornecedor_php']     = "wb_cadastro_de_fornecedores.php";
	$_SESSION['fornecedor_order']   = "Nome";

	$_SESSION['centrocusto_campos']  = "id, Descricao";
	$_SESSION['centrocusto_table']   = "tblcentro";
	$_SESSION['centrocusto_texto']   = "id, Descrição";
	$_SESSION['centrocusto_tamanho'] = "0 , 675";
	$_SESSION['centrocusto_php']     = "wb_cadastro_de_centrocusto.php";
	$_SESSION['centrocusto_order']   = "Descricao";
	

	$_SESSION['rota_campos']  = "id, codigo, descricao, grupo";
	$_SESSION['rota_table']   = "tblrota";
	$_SESSION['rota_texto']   = "id, Código, Descrição, Grupo";
	$_SESSION['rota_tamanho'] = "0 , 100, 450, 200";
	$_SESSION['rota_php']     = "wb_cadastro_de_rota.php";
	$_SESSION['rota_order']   = "descricao";
	
	$_SESSION['clientes_campos']  = "id, nome, fantasia, codigo, doc";
	$_SESSION['clientes_table']   = "tblcliente";
	$_SESSION['clientes_texto']   = "id, Nome, Fantasia, Codigo, CPF/CNPJ";
	$_SESSION['clientes_tamanho'] = "0 , 400   ,350,     70, 100  ";
	$_SESSION['clientes_php']     = "wb_cadastro_de_clientes.php";
	$_SESSION['clientes_order']   = "nome";

	$_SESSION['usuario_campos']  = "id, Nome, Email, Endereco, Bairro, Cidade, UF ";
	$_SESSION['usuario_table']   = "tblusuario";
	$_SESSION['usuario_texto']   = "id, Nome, Email, Endereço, Bairro, Cidade, UF";
	$_SESSION['usuario_tamanho'] = "0 , 250 , 125  , 150     ,150    , 150   , 40";
	$_SESSION['usuario_php']     = "wb_cadastro_de_usuarios.php";
	$_SESSION['usuario_order']   = "Nome";

	$_SESSION['vendedor_campos']  = "id, codigo, nome";
	$_SESSION['vendedor_table']   = "tblvendedor";
	$_SESSION['vendedor_texto']   = "id, codigo, nome";
	$_SESSION['vendedor_tamanho'] = "0 , 100, 300";
	$_SESSION['vendedor_php']     = "wb_cadastro_de_vendedor.php";
	$_SESSION['vendedor_order']   = "Nome";
	
	$_SESSION['mercadoria_campos']  = "id, codigo, descricao, valor, qtd, qtdminimo, unidade ";
	$_SESSION['mercadoria_table']   = "tblmercadoria";
	$_SESSION['mercadoria_texto']   = "id, Código, Descrição, valor , Qtd, Qtd mim., Unidade";
	$_SESSION['mercadoria_tamanho'] = "0 , 100 , 250        , 150   ,150 , 150, 100";
	$_SESSION['mercadoria_php']     = "wb_cadastro_de_mercadoria.php";
	$_SESSION['mercadoria_order']   = "descricao";

	$_SESSION['motorista_campos']  = "id, codigo, nome";
	$_SESSION['motorista_table']   = "tblmotorista";
	$_SESSION['motorista_texto']   = "id, Código, Nome";
	$_SESSION['motorista_tamanho'] = "0 , 100, 450";
	$_SESSION['motorista_php']     = "wb_cadastro_de_motorista.php";
	$_SESSION['motorista_order']   = "nome	";

	$_SESSION['formapagamento_campos']  = "id, descricao, tipo";
	$_SESSION['formapagamento_table']   = "tblforma";
	$_SESSION['formapagamento_texto']   = "id, Descricao, Forma de pagamento";
	$_SESSION['formapagamento_tamanho'] = "0 , 300, 300";
	$_SESSION['formapagamento_php']     = "wb_cadastro_de_formapagamento.php";
	$_SESSION['formapagamento_order']   = "Descricao";


	$_SESSION['romanei_campos']  = "tblromanei.id as id, os, data, nome, pedido, valortotal";
	$_SESSION['romanei_table']   = "tblromanei left join tblcliente on tblromanei.idcliente = tblcliente.id";
	$_SESSION['romanei_texto']   = "id, OS, Data, Cliente, Pedido, Valor";
	$_SESSION['romanei_tamanho'] = "0 , 100, 100, 300, 100, 100";
	$_SESSION['romanei_php']     = "wb_romanei.php";
	$_SESSION['romanei_order']   = "OS desc";

	$_SESSION['nf_campos']  = "tblnf.id as id, tblnf.numero as numero, pedido, emissao, tblcliente.nome as nome, valortotal ";
	$_SESSION['nf_table']   = "tblnf left join tblcliente on tblnf.idcliente = tblcliente.id";
	$_SESSION['nf_texto']   = "id, Numero, Pedido, Emissão, Cliente, Total";
	$_SESSION['nf_tamanho'] = "0 , 100, 100, 100, 300, 100, 100";
	$_SESSION['nf_php']     = "wb_nf.php";
	$_SESSION['nf_order']   = "emissao";
	
	
?>
