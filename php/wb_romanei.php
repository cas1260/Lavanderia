<?php
	//ALTER TABLE `tblitemromanei` CHANGE `valor` `valor` DOUBLE( 15, 2 ) NULL DEFAULT NULL 
	//ALTER TABLE `tblitemromanei` CHANGE `subtotal` `subtotal` DOUBLE( 15, 2 ) NULL DEFAULT NULL 
	//ALTER TABLE `tblromanei` ADD `obs` TEXT NOT NULL ;
	session_start();
	include 'wb_funcao.php';
	$cn = abrebanco();
	$os = '';
	
	@$acao = $_GET['acao'];
	@$os = $_GET['os'];
	@$id = $_GET['id'];
	
	/*if ($id != ''){
		$os = $id;
	}*/
	
	if ($acao == 'salvar'){
	
		$n                 = $_GET['n'];
		@$registroapagados = $_POST[$n.'0registroapagados']; 
		$dadosgrid         = $_POST[$n.'0grid'];
		$tabela = 'tblromanei';

		$sql = montasql($tabela, $id);
		
		echo "/* $sql */ ";
		
		$cn->execute($sql);
		
		if ($id =='0'){
			$id = rsRetornacampo("select max(id) as ultimo from tblromanei", "ultimo", $cn);
			/*$os = rsRetornacampo("select max(os) as ultimo from tblromanei", "ultimo", $cn);
			if ($os == ''){
				$os = 1;
			}else{
				$os = $os +1;
			}
			$sql = "update tblromanei set os = $os where id = $id";
			$cn->execute($sql);
			*/
		}
		if ($registroapagados != ''){
			$sql = "delete from tblitemromanei where id in ($registroapagados)";
			$cn->execute($sql);
			echo "/* $sql */";
		}
		
		if ($dadosgrid != ''){
			$registro = explode("|", $dadosgrid);
			for($i = 0; $i < count($registro); ++$i){
				$campo = explode("$!", $registro[$i]);
				$sql  = '';
				$sql1 = '';
				$tipo = 0;
				
				//a.valor, a.idcliente, a.idservico
				if ($registro[$i] != ''){
					for($x = 0; $x < count($campo); ++$x){
						$dados = explode("=", $campo[$x]);
						if ($dados[0] == 'idservico' || $dados[0] == 'qtd' || $dados[0] == 'valor' || $dados[0] == 'subtotal' || $dados[0] == 'idromanei' || $dados[0] == 'id'){
							if ($dados[0] == 'idromanei'){
								$dados[1] = $id; 
							}
							if ($dados[0] == 'valor'){
								$dados[1] = str_replace(",", ".", $dados[1]); 
							}

							if ($dados[0] == 'id'){
								if ($dados[1] == '-1'){
									$sql  = "insert into tblitemromanei ( ";
									$sql1 = "";
									$tipo = 0;
								}else{
									$tipo = 1;
									$sql  ="update tblitemromanei set  ";
									$sql1 =" where id = " . $dados[1];
								}
							}else{
								if ($tipo == 1){
									$sql = $sql . $dados[0] . " = '" . $dados[1] . "', ";
								}else{
									$sql  = $sql  . $dados[0] . ", ";
									$sql1 = $sql1 . "'" . $dados[1] . "', ";
								}
							}
						}
					}
					$sql = left($sql, strlen($sql)-2);
					if ($tipo == 0){
						$sql1 = left($sql1, strlen($sql1)-2);
						$sql = $sql . ") values (" . $sql1 . ")";
					}else{
						$sql = $sql . $sql1;
					}
					$cn->execute($sql);
					echo "/* $sql */";
					//echo $sql . chr(13);
					//echo 'alert("' . $sql . '");';
				}
			}
		}		
		echo "
			MSG('Dados salvo com sucesso.', function(){
				
				var i=0;
				for (i=0;i<=28;i++) // >
				{
					Ext.getCmp('form".$n."').items.item(i).setDisabled(true);
				}
				Ext.getCmp('".$n."imprimir').setDisabled(false);
				Ext.getCmp('".$n."editar').setDisabled(false);
				Ext.getCmp('".$n."editar').setVisible(true);
				Ext.getCmp('".$n."novo').setDisabled(false);
				novoID".$n." = $id;
			});
		";
		
		exit();
	}
	
	if ($os != '0' && $os != ''){
		@$n = $_GET['n'];
		if ($n != ''){
			$rs = $cn->open("select id, os, data, idvendedor, pedido, volume, idcliente, solicitante, idmotorista, entrada, desconto, valortotal, idforma, status, obs from tblromanei where os = $os");
			if ($rs->EOF==true){
				echo "MSG('Não foi possivel encontrar o ronamei desejado.', function(){
					Ext.getCmp('".$n."os').focus();
					})";
				exit();
			}
			exibidados($rs->fields, 'data, idvendedor, pedido, volume, idcliente, solicitante, idmotorista, entrada, desconto, valortotal, idforma, status', $n);
			echo "dados".$n.".setBaseParam('os', '$os');";
			echo "dados".$n.".load();";
		}
		echo "Ext.getCmp('".$n."imprimir').setDisabled(false);";
		/*echo "dados".$n.".load();";
		rsdados2("select a.id, b.codigo, b.descricao, a.qtd, a.obra, a.valor, a.subtotal, a.idservico from tblitemromanei a inner join tblservicos b on a.idservico  = b.id inner join tblromanei c on a.idromanei = c.id where c.os = $os", $cn);
		echo ");";*/
		exit();
	}
	if ($os == ''){
		@$os = $_POST['os'];
		if ($os != ''){
			montastored("select a.id, b.codigo, b.descricao as servico, a.qtd, a.obra, a.valor, a.subtotal, a.idservico from tblitemromanei a inner join tblservicos b on a.idservico  = b.id inner join tblromanei c on a.idromanei = c.id where c.os = $os", $cn);
			exit();
		}
	};
?>

stored_forma<?php echo $namespace;?> = <?php rsdados("select id, descricao from tblforma order by Descricao", $cn)?>
stored_vendedor<?php echo $namespace;?> = <?php rsdados("select id, nome from tblvendedor order by nome", $cn)?>
stored_cliente<?php echo $namespace;?> = <?php rsdados("select id, nome, concat(endereco1, ' Nº ',  numero1, ' ', cidade1, ' ', estado1) as endereco, idforma, extra3, status, idvendedor from tblcliente order by nome", $cn)?>
stored_servico<?php echo $namespace;?> = <?php rsdados("select id, descricao from tblservicos  order by descricao", $cn)?>	
stored_motorista<?php echo $namespace;?> = <?php rsdados("select id, nome from tblmotorista", $cn)?>	

novoID<?php echo $namespace;?> = <?php echo $id;?>;

jDados = Ext.extend(Ext.data.JsonStore, {
    constructor: function(cfg) {
        cfg = cfg || {};
        jDados.superclass.constructor.call(this, Ext.apply({
            storeId: 'dados<?php echo $namespace?>',
            totalProperty: 'results',
            root: 'rows',
            url: './php/wb_romanei.php',
            autoLoad: false,
            autoDestroy: true,
            fields: [
                {name: 'id'},
                {name: 'codigo'},
                {name: 'servico'},
                {name: 'qtd',  type: 'number',  align: 'left', format: '0'},
                {name: 'obra'},
                {name: 'valor'},
				{name: 'subtotal'},
                {name: 'idservico'},
				{name: 'idromanei'}
            ]
        }, cfg));
    }
});
dados<?php echo $namespace?> = new jDados();

wbromaneiUi = Ext.extend(Ext.Window, {
    title: 'Romaneio',
    width: 777,
    height: 514,
    layout: 'anchor',
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                layout: 'absolute',
                width: 870,
                height: 328,
                anchor: '100 100',
                x: 0,
                y: 0,
                itemId: 'form<?php echo $namespace?>',
                id: 'form<?php echo $namespace?>',
                items: [
						{
							xtype: 'hidden',
							x: 207,
							y: 456,
							itemId: '<?php echo $namespace?>0grid',
							name: '<?php echo $namespace?>0grid',
							id: '<?php echo $namespace?>0grid'
						}, 
						{
							xtype: 'hidden',
							x: 0,
							y: 0,
							itemId: '<?php echo $namespace?>obs',
							name: '<?php echo $namespace?>obs',
							id: '<?php echo $namespace?>obs'
						}, 
						{
							xtype: 'hidden',
							itemId: '<?php echo $namespace?>0registroapagados',
							name: '<?php echo $namespace?>0registroapagados',
							value: '0',
							id: '<?php echo $namespace?>0registroapagados'
						},
					{
                        xtype: 'numberfield',
                        anchor: '100%',
                        boxMaxWidth: 100,
						allowBlank: false,
                        itemId: '<?php echo $namespace?>os',
                        name: '<?php echo $namespace?>os',
                        x: 85,
                        y: 15,
                        id: '<?php echo $namespace?>os'
						<?php if ($os != ''){echo ", readOnly: true";}?>
                    },
                    {
                        xtype: 'datefield',
                        anchor: '100%',
                        readOnly: true,
                        boxMaxWidth: 100,
                        itemId: '<?php echo $namespace?>data',
                        name: '<?php echo $namespace?>data',
                        x: 232,
                        y: 15,
                        allowBlank: false,
						selectOnFocus:true,
                        id: '<?php echo $namespace?>data'
                    },
                    {
                        xtype: 'combo',
                        x: 397,
                        y: 15,
                        width: 204,
                        triggerAction: 'all',
						allowBlank: true,
                        itemId: '<?php echo $namespace?>idvendedor',
                        name: '<?php echo $namespace?>idvendedor',
                        store: stored_vendedor<?php echo $namespace;?>,
						selectOnFocus:true,
						disabled:true,
                        id: '<?php echo $namespace?>idvendedor'
                    },
                    {
                        xtype: 'numberfield',
                        anchor: '100%',
                        boxMaxWidth: 100,
                        itemId: '<?php echo $namespace?>pedido',
                        name: '<?php echo $namespace?>pedido',
                        allowBlank: true,
                        x: 656,
                        y: 15,
						selectOnFocus:true,
                        id: '<?php echo $namespace?>pedido'
                    },
                    {
                        xtype: 'textfield',
                        x: 86,
                        y: 44,
                        width: 100,
                        itemId: '<?php echo $namespace?>volume',
                        name: '<?php echo $namespace?>volume',
						selectOnFocus:true,
                        id: '<?php echo $namespace?>volume'
                    },
                    {
                        xtype: 'combo',
                        x: 231,
                        y: 44,
                        width: 524,
                        triggerAction: 'all',
                        itemId: '<?php echo $namespace?>idcliente',
                        name: '<?php echo $namespace?>idcliente',
                        store: stored_cliente<?php echo $namespace;?>,
                        allowBlank: false,
						selectOnFocus:true,
                        id: '<?php echo $namespace?>idcliente'
                    },
                    {
                        xtype: 'textfield',
                        x: 85,
                        y: 70,
                        width: 670,
                        itemId: '<?php echo $namespace?>0endereco',
                        name: '<?php echo $namespace?>0endereco',
                        disabled: true,
						selectOnFocus:true,
                        id: '<?php echo $namespace?>0endereco'
                    },
                    {
                        xtype: 'textfield',
                        x: 85,
                        y: 98,
                        width: 257,
                        name: '<?php echo $namespace?>solicitante',
                        itemId: '<?php echo $namespace?>solicitante',
                        allowBlank: true,
						selectOnFocus:true,
                        id: '<?php echo $namespace?>solicitante'
                    },
                    {
                        xtype: 'combo',
                        x: 401,
                        y: 98,
                        width: 202,
                        triggerAction: 'all',
                        itemId: '<?php echo $namespace?>idmotorista',
                        name: '<?php echo $namespace?>idmotorista',
                        store: stored_motorista<?php echo $namespace;?>,
                        allowBlank: true,
						selectOnFocus:true,
                        id: '<?php echo $namespace?>idmotorista'
                    },
                    {
                        xtype: 'datefield',
                        anchor: '100%',
                        boxMaxWidth: 100,
                        itemId: '<?php echo $namespace?>entrada',
                        name: '<?php echo $namespace?>entrada',
                        allowBlank: false,
                        x: 654,
                        y: 98,
						selectOnFocus: true,
                        id: '<?php echo $namespace?>entrada'
                    },
                    {
                        xtype: 'hidden',
                        itemId: '<?php echo $namespace?>status',
                        name: '<?php echo $namespace?>status',
                        value: 1,
                        id: '<?php echo $namespace?>status'
                    },
                    {
                        xtype: 'panel',
                        title: 'Serviços',
                        layout: 'absolute',
                        width: 671,
                        height: 297,
                        x: 85,
                        y: 126,
                        items: [
                            {
                                xtype: 'label',
                                text: 'Serviço:',
                                x: 85,
                                y: 15
                            },
                            {
                                xtype: 'label',
                                text: 'Desconto:',
                                x: 295,
                                y: 250
                            },
                            {
                                xtype: 'label',
                                text: 'Valor total:',
                                x: 495,
                                y: 250
                            },
                            {
                                xtype: 'label',
                                text: 'Código:',
                                x: 15,
                                y: 15
                            },
                            {
                                xtype: 'label',
                                text: 'Qtd',
                                x: 409,
                                y: 15
                            },
                            {
                                xtype: 'label',
                                text: 'Valor',
                                x: 495,
                                y: 15
                            },
                            {
                                xtype: 'textfield',
                                x: 15,
                                y: 30,
                                width: 65,
								selectOnFocus:true,
                                itemId: '<?php echo $namespace?>0codigo',
                                name: '<?php echo $namespace?>0codigo',
                                id: '<?php echo $namespace?>0codigo'
                            },
                            {
                                xtype: 'combo',
                                x: 85,
                                y: 30,
                                width: 320,
								selectOnFocus:true,
                                itemId: '<?php echo $namespace?>0servico',
                                name: '<?php echo $namespace?>0servico',
                                store: stored_servico<?php echo $namespace;?>,
                                id: '<?php echo $namespace?>0servico',
								triggerAction: 'all'
                            },
                            {
                                xtype: 'textfield',
                                x: 410,
                                y: 30,
                                width: 80,
								selectOnFocus:true,
                                itemId: '<?php echo $namespace?>0qtd',
                                name: '<?php echo $namespace?>0qtd',
                                id: '<?php echo $namespace?>0qtd'
                            },
                            {
                                xtype: 'masktextfield',
                                x: 495,
                                y: 30,
                                width: 75,
								selectOnFocus:true,
                                itemId: '<?php echo $namespace?>0valor',
                                name: '<?php echo $namespace?>0valor',
                                id: '<?php echo $namespace?>0valor',
								style: 'text-align: right',
								selectOnFocus: true,
								money: true,
								mask: '#9.999.990,00'
                            },
                            {
                                xtype: 'button',
                                text: 'Incluir',
                                x: 575,
                                y: 30,
                                width: 45,
                                height: 22,
								id: '<?php echo $namespace?>incluir'
                            },
                            {
                                xtype: 'button',
                                text: 'Excluir',
                                x: 625,
                                y: 30,
                                width: 40,
                                height: 22,
								id: '<?php echo $namespace?>excluir'
                            },
                            {
                                xtype: 'grid',
                                title: '',
                                store: dados<?php echo $namespace?>,
                                x: 15,
                                y: 55,
                                width: 650,
                                height: 185,
                                id: '<?php echo $namespace?>grid',
                                columns: [
                                    {
                                        xtype: 'gridcolumn',
                                        dataIndex: 'codigo',
                                        header: 'Código',
                                        sortable: true,
                                        width: 60,
                                        align: 'right'
                                    },
                                    {
                                        xtype: 'gridcolumn',
                                        header: 'Serviço',
                                        sortable: true,
                                        width: 340,
                                        dataIndex: 'servico'
                                    },
                                    {
                                        xtype: 'numbercolumn',
                                        dataIndex: 'qtd',
                                        header: 'QTD',
                                        sortable: true,
                                        width: 50,
                                        align: 'right',
										format: '0'
                                    },
                                    {
                                        xtype: 'gridcolumn',
                                        dataIndex: 'valor',
                                        header: 'Valor',
                                        sortable: true,
                                        width: 90,
                                        align: 'right'
                                    },
                                    {
                                        xtype: 'gridcolumn',
                                        dataIndex: 'subtotal',
                                        header: 'Sub-Total',
                                        sortable: true,
                                        width: 90,
                                        align: 'right'
                                    }
                                ]
                            },
							{
                                xtype: 'container',
                                width: 230,
                                height: 20,
                                layout: 'absolute',
                                x: 15,
                                y: 240,
                                items: [
                                    {
                                        xtype: 'radio',
                                        x: 0,
                                        y: 0,
                                        boxLabel: 'Aberta',
                                        name: '<?php echo $namespace?>0status',
                                        value: 1,
                                        inputValue: 1,
                                        checked: true,
                                        id: '<?php echo $namespace?>status1'
                                    },
                                    {
                                        xtype: 'radio',
                                        x: 65,
                                        y: 0,
                                        boxLabel: 'Fechada',
                                        name: '<?php echo $namespace?>0status',
                                        value: 2,
                                        inputValue: 2,
                                        id: '<?php echo $namespace?>status2'
                                    },
                                    {
                                        xtype: 'radio',
                                        x: 135,
                                        y: 0,
                                        boxLabel: 'Cancelada',
                                        name: '<?php echo $namespace?>0status',
                                        value: 3,
                                        inputValue: 3,
                                        id: '<?php echo $namespace?>status3'
                                    }
                                ]
                            },
                            {
                                xtype: 'masktextfield',
                                x: 345,
                                y: 245,
                                itemId: '<?php echo $namespace?>desconto',
                                name: '<?php echo $namespace?>desconto',
                                id: '<?php echo $namespace?>desconto',
								style: 'text-align: right',
								money: true,
								selectOnFocus:true,
								mask: '#9.999.990,00'	
                            },
                            {
                                xtype: 'masktextfield',
                                x: 550,
                                y: 245,
								width: 113,
                                itemId: '<?php echo $namespace?>valortotal',
                                name: '<?php echo $namespace?>valortotal',
                                allowBlank: false,
                                id: '<?php echo $namespace?>valortotal',
								selectOnFocus: true,
								style: 'text-align: right',
								money: true,
								mask: '#9.999.990,00'								
                            }
                            
                        ]
                    },
                    {
                        xtype: 'label',
                        text: 'Numero de OS:',
                        x: 10,
                        y: 20
                    },
                    {
                        xtype: 'combo',
                        x: 440,
                        y: 427,
                        width: 316,
                        triggerAction: 'all',
                        itemId: '<?php echo $namespace?>idforma',
                        name: '<?php echo $namespace?>idforma',
                        store: stored_forma<?php echo $namespace;?>,
                        allowBlank: false,
						selectOnFocus:true,
                        id: '<?php echo $namespace?>idforma'
                    },
                    {
						xtype: 'button',
						text: 'Salvar',
						x: 657,
						y: 455,
						width: 100,
						itemId: '<?php echo $namespace?>salvar',
						id: '<?php echo $namespace?>salvar'
					},
					{
						xtype: 'button',
						text: 'Observação',
						x: 348,
						y: 455,
						width: 100,
						itemId: '<?php echo $namespace?>cmdobs',
						id: '<?php echo $namespace?>cmdobs'
					},
					{
						xtype: 'button',
						text: 'Imprimir',
						x: 76,
						y: 455,
						width: 100,
						disabled: true,
						itemId: '<?php echo $namespace?>imprimir',
						id: '<?php echo $namespace?>imprimir'
					},
					{
						xtype: 'button',
						text: 'Novo Romaneio',
						x: 180,
						y: 455,
						width: 100,
						disabled: true,
						itemId: '<?php echo $namespace?>novo',
						id: '<?php echo $namespace?>novo'
					},
					{
						xtype: 'button',
						text: 'Cancelar',
						x: 555,
						y: 455,
						width: 100,
						itemId: '<?php echo $namespace?>cancelar',
						id: '<?php echo $namespace?>cancelar'
					},
					{
						xtype: 'button',
						text: 'Editar',
						x: 452,
						y: 455,
						width: 100,
						itemId: '<?php echo $namespace?>editar',
						id: '<?php echo $namespace?>editar'
					},
                    {
                        xtype: 'label',
                        text: 'Data',
                        x: 194,
                        y: 20
                    },
                    {
                        xtype: 'label',
                        text: 'Vendedor:',
                        x: 339,
                        y: 20
                    },
                    {
                        xtype: 'label',
                        text: 'Pedido nº',
                        x: 607,
                        y: 20
                    },
                    {
                        xtype: 'label',
                        text: 'Volume:',
                        x: 40,
                        y: 46
                    },
                    {
                        xtype: 'label',
                        text: 'Cliente:',
                        x: 192,
                        y: 47
                    },
                    {
                        xtype: 'label',
                        text: 'Endereco:',
                        x: 31,
                        y: 74
                    },
                    {
                        xtype: 'label',
                        text: 'Solicitante:',
                        x: 30,
                        y: 102
                    },
                    {
                        xtype: 'label',
                        text: 'Entrada:',
                        x: 607,
                        y: 102
                    },
                    {
                        xtype: 'label',
                        text: 'Motorista:',
                        x: 347,
                        y: 102
                    },
                    {
                        xtype: 'label',
                        text: 'Forma de pag.:',
                        x: 364,
                        y: 434
                    }
                ]
            }
        ];
        wbromaneiUi.superclass.initComponent.call(this);
    }
});


var wbromaneiUi<?php echo $namespace?> = new wbromaneiUi({
	id : 'wbromaneiUi<?php echo $namespace?>',
	name : 'wbromaneiUi<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
wbromaneiUi<?php echo $namespace?>.show();

addtask('wbromaneiUi<?php echo $namespace?>');

function buscaRomanei(){
	os = Ext.getCmp('<?php echo $namespace?>os').getValue();
	Ext.getCmp('<?php echo $namespace?>imprimir').setDisabled(true);
	if (os ==''){
		//limpar os registro pra começar um novo registro.
		<?php 
			exibidadosNovo('data, idvendedor, pedido, volume, idcliente, solicitante, idmotorista, entrada, desconto, valortotal, idforma, status', $namespace);
		?>
		dados<?php echo $namespace?>.setBaseParam('os', '0');
		dados<?php echo $namespace?>.load();
	}else{
		OpenUrl('./php/wb_romanei.php?os='+os+'&n=<?php echo $namespace?>');
	}
}

function buscaservico<?php echo $namespace?>(){
	codigo    = Ext.getCmp('<?php echo $namespace?>0codigo').getValue();
	idcliente = Ext.getCmp('<?php echo $namespace?>idcliente').getValue();

	if (codigo == ''){
		//Ext.getCmp('<?php echo $namespace?>valortotal').focus();
		return false;
	}
	
	if (idcliente == '0' || idcliente == ''){
		MSG('É Preciso preecher um cliente antes de lançar serviços', function(){
			Ext.getCmp('<?php echo $namespace?>idcliente').focus();
			});
		return false;
	}
	if (id != '' || codigo != ''){
		OpenUrl('./php/wb_buscaproduto.php?id=0&codigo='+codigo+'&idcliente='+idcliente+'&n=<?php echo $namespace?>');
	}
	
}

function buscaservicoID<?php echo $namespace?>(){
	id        = Ext.getCmp('<?php echo $namespace?>0servico').getValue();
	codigo    = "";
	idcliente = Ext.getCmp('<?php echo $namespace?>idcliente').getValue();

	if (id == ''){
		return false;
	}
	
	if (idcliente == '0' || idcliente == ''){
		MSG('É Preciso preecher um cliente antes de lançar serviços', function(){
			Ext.getCmp('<?php echo $namespace?>idcliente').focus();
			});
		return false;
	}
	if (id != '' || codigo != ''){
		OpenUrl('./php/wb_buscaproduto.php?id='+id+'&codigo='+codigo+'&idcliente='+idcliente+'&n=<?php echo $namespace?>');
	}
	
}


function buscaendereco<?php echo $namespace?>(){

	id = Ext.getCmp('<?php echo $namespace?>idcliente').getValue();
	if (id != '' || codigo != ''){
		OpenUrl('./php/wb_buscaendereco.php?id='+id+'&n=<?php echo $namespace?>');
	}
	

}

function calculatotal<?php echo $namespace?>(){
	dados = Ext.getCmp('<?php echo $namespace?>grid').getStore();

	totalregistro = dados.data.items.length;
	totaldecampos = dados.fields.length;
	valor = 0;
	
	for (i=0; i < totalregistro; i++) { // >
		var subtotal = dados.data.items[i].data.valor.toString();
		var qtd = dados.data.items[i].data.qtd.toString();
		subtotal = parseFloat(subtotal.replace(",",".")) * parseFloat(qtd);
		dados.data.items[i].data.subtotal = subtotal;
		//alert('Valor = '+valor+'\nSubtotal = '+subtotal);
		valor = eval(valor) + eval(subtotal);
		valor = Math.round(valor * 100) / 100;
		//alert(valor);
	}
	
	Ext.getCmp('<?php echo $namespace?>valortotal').setValue(valor);
	
	valor = OBJ('<?php echo $namespace?>valortotal');
	
	var subtotal = valor.getValue()
	subtotal = subtotal.replace(",",".");
	
	var desconto = OBJ('<?php echo $namespace?>desconto').getValue();
	desconto = desconto.replace(",",".");
	
	valor.setValue(parseFloat(subtotal) -  parseFloat(desconto));
	
}

function incluirregistro(){

	Dados = Ext.getCmp('<?php echo $namespace?>grid').getStore();
	
	
}

function removeclick<?php echo $namespace?>(){

	SIMNAO('Deseja remover o serviço selecionado?', function(btn){
		if (btn == 'yes'){
		
			objGrid = Ext.getCmp('<?php echo $namespace?>grid');	
			varId   = objGrid.getSelectionModel().getSelected().data.id;
			
			Ext.getCmp('<?php echo $namespace?>0registroapagados').setValue(Ext.getCmp('<?php echo $namespace?>0registroapagados').getValue() + ', ' + varId);
			
			objRec = objGrid.getSelectionModel().getSelected();			
			objGrid.getStore().remove(objRec);
			calculatotal<?php echo $namespace?>()
		}
	});

}

function addclick<?php echo $namespace?>(){
		
	dados = Ext.getCmp('<?php echo $namespace?>grid').getStore();
	
	var myArray=new Array();
	myArray['id']         = '-1';
	myArray['codigo']     = Ext.getCmp('<?php echo $namespace?>0codigo').getValue();
	myArray['servico']    = Ext.getCmp('<?php echo $namespace?>0servico').getRawValue();
	myArray['qtd']        = Ext.getCmp('<?php echo $namespace?>0qtd').getValue();
	myArray['obra']       = '';
	myArray['valor']      = Ext.getCmp('<?php echo $namespace?>0valor').getValue();
	myArray['subtotal']   = (myArray['valor'] * myArray['qtd']).toFixed(2);
	
	cboServ = Ext.getCmp('<?php echo $namespace?>0servico').getStore();
	
	myArray['idservico']  = cboServ.data.items[cboServ.find('field2', Ext.getCmp('<?php echo $namespace?>0servico').getValue())].data.field1

	var rec = new Ext.data.Record(myArray);
	dados.add(rec);	
	
	Ext.getCmp('<?php echo $namespace?>0codigo').setValue('');
	Ext.getCmp('<?php echo $namespace?>0servico').setValue('');
	Ext.getCmp('<?php echo $namespace?>0qtd').setValue('');
	Ext.getCmp('<?php echo $namespace?>0valor').setValue('');
	Ext.getCmp('<?php echo $namespace?>0codigo').focus();
	calculatotal<?php echo $namespace?>();
}

function habilitaobjectos<?php echo $namespace?>(){
	
	sform = Ext.getCmp('form<?php echo $namespace?>');
	
	var i=0;
	for (i=0;i<=29;i++) // >
	{
		obj = sform.items.item(i);
		obj.setDisabled(true);
	}
	Ext.getCmp('<?php echo $namespace?>imprimir').setDisabled(false);
}


function editregistro<?php echo $namespace?>(){
	
	sform = Ext.getCmp('form<?php echo $namespace?>');
	
	var i=0;
	for (i=0;i<=29;i++) // >
	{
		obj = sform.items.item(i);
		obj.setDisabled(false);
	}
	
	
	Ext.getCmp('<?php echo $namespace?>imprimir').setDisabled(true);
	Ext.getCmp('<?php echo $namespace?>imprimir').setDisabled(true);
	Ext.getCmp('<?php echo $namespace?>0endereco').setDisabled(true);
	Ext.getCmp('<?php echo $namespace?>editar').setDisabled(true);
	Ext.getCmp('<?php echo $namespace?>novo').setDisabled(true);
	Ext.getCmp('<?php echo $namespace?>os').setReadOnly(true);
	Ext.getCmp('<?php echo $namespace?>data').setReadOnly(true);
	
	Ext.getCmp('<?php echo $namespace?>pedido').focus();
	
}


function salvaDados<?php echo $namespace?>(){

	dados = Ext.getCmp('<?php echo $namespace?>grid').getStore();
	totalregistro = dados.data.items.length;
	
	if (totalregistro == 0){
		MSG('Favor adicionar pelo menos 1 serviço antes de salvar o romaneio', function(){Ext.getCmp('<?php echo $namespace?>0codigo').focus()});
		return false;
	}
	
	if (novoID<?php echo $namespace?> == <?php echo $id?>){
		id = <?php echo $id?>;
	}else{
		id = novoID<?php echo $namespace?>;
	}
	
	
	sform = Ext.getCmp('form<?php echo $namespace?>').getForm();
	if (sform.isValid()==true){
		Ext.getCmp('<?php echo $namespace?>0grid').setValue(montasqlURL(Ext.getCmp('<?php echo $namespace?>grid').getStore()));	
		salvardados('./php/wb_romanei.php?acao=salvar&n=<?php echo $namespace?>&id='+id+'&os='+Ext.getCmp('<?php echo $namespace?>os').getValue(), sform.getFieldValues(true))
	}else{
		MSG('Favor preecher os campos em vermelhos.');
	}
}

buscaregistro<?php echo $namespace?> = function(e){
	dados = e.getStore();
	
	valor      = dados.data.items[dados.find('field1', e.getValue())].data.field3;
	idforma    = dados.data.items[dados.find('field1', e.getValue())].data.field4;
	extra3     = dados.data.items[dados.find('field1', e.getValue())].data.field5;
	status     = dados.data.items[dados.find('field1', e.getValue())].data.field6;
	idvendedor = dados.data.items[dados.find('field1', e.getValue())].data.field7;
	
	OBJ('<?php echo $namespace?>0endereco').setValue(valor);
	OBJ('<?php echo $namespace?>idforma').setValue(idforma);
	OBJ('<?php echo $namespace?>idvendedor').setValue(idvendedor);
	
	if (idvendedor == '0' || idvendedor == ''){
		OBJ('<?php echo $namespace?>idvendedor').setDisabled(false);
	}else{
		OBJ('<?php echo $namespace?>idvendedor').setDisabled(true);
	}
	
	if (status == '2'){
		MSG("<font color='red'>Restrição: Cliente Inadimplente</font>");
	}
	
	
	if (extra3 != ''){
		MSG("<font color='red'>Restrição: " + extra3 + "</font>");
	}
	
}

//Ext.getCmp('<?php echo $namespace?>os').on('blur', buscaRomanei);
Ext.getCmp('<?php echo $namespace?>0servico').on('select', buscaservicoID<?php echo $namespace?>);
Ext.getCmp('<?php echo $namespace?>0codigo').on('blur', buscaservico<?php echo $namespace?>);
Ext.getCmp('<?php echo $namespace?>idcliente').on('select', buscaregistro<?php echo $namespace?>);
Ext.getCmp('<?php echo $namespace?>incluir').on('click', addclick<?php echo $namespace?>);
Ext.getCmp('<?php echo $namespace?>excluir').on('click', removeclick<?php echo $namespace?>);
Ext.getCmp('<?php echo $namespace?>0valor').on('blur', function(){
	Ext.getCmp('<?php echo $namespace?>incluir').focus();
});


OBJ('<?php echo $namespace?>cancelar').on('click', function(){
	OBJ('wbromaneiUi<?php echo $namespace?>').close();
})


OBJ('<?php echo $namespace?>desconto').on('blur', calculatotal<?php echo $namespace?>);
OBJ('<?php echo $namespace?>0qtd').on('focus', function(o){o.selectText();});

OBJ('<?php echo $namespace?>cmdobs').on('click', function(){

	vobs = OBJ('<?php echo $namespace?>obs').getValue();

	Ext.MessageBox.show({
							title: 'Romaneio',
							msg: 'Observação:',
							width:500,
							buttons: Ext.MessageBox.OKCANCEL,
							multiline: true,
							value : vobs, 
							fn: function(btn, text){
								if (btn == 'ok'){
									Ext.getCmp('<?php echo $namespace?>obs').setValue(text);
								}
						    },
							animateTarget: '<?php echo $namespace?>cmdobs'
						});
	
});


Ext.getCmp('<?php echo $namespace?>salvar').on('click', salvaDados<?php echo $namespace?>);
Ext.getCmp('<?php echo $namespace?>editar').on('click', editregistro<?php echo $namespace?>);
//Ext.getCmp('<?php echo $namespace?>salvar').on('click', habilitaobjectos<?php echo $namespace?>);

function imprimir<?php echo $namespace?>(){
	os = Ext.getCmp('<?php echo $namespace?>os').getValue();
	window.open('./php/wb_print_romanei.php?os='+os, 'printgrid');
}
Ext.getCmp('<?php echo $namespace?>imprimir').on('click', imprimir<?php echo $namespace?>);

OBJ('<?php echo $namespace?>novo').on('click', function(){
	OBJ('wbromaneiUi<?php echo $namespace?>').close();
	
	OpenUrl('php/<?php echo $_SESSION['romanei_php'];?>?acao=add&id=0');

});

<?php
	if ($id != '0' && $id != ''){
		@$n = $namespace;
		if ($n != ''){
			
			$sql = "select tblromanei.id, tblromanei.os, tblromanei.data, tblromanei.idvendedor, tblromanei.pedido, 
									tblromanei.volume, tblromanei.idcliente, tblromanei.solicitante, tblromanei.idmotorista, 
									tblromanei.entrada, tblromanei.desconto, tblromanei.valortotal, tblromanei.idforma, tblromanei.status ,
								concat(tblcliente.endereco1, ' Nº ',  tblcliente.numero1, ' ', tblcliente.cidade1, ' ', tblcliente.estado1) as 0endereco, tblromanei.obs
								from tblromanei inner join tblcliente on tblromanei.idcliente = tblcliente.id
								where tblromanei.id = $id";
			echo "/* $sql */";
			$rs = $cn->open($sql);
			if ($rs->EOF==true){
				echo "MSG('Não foi possivel encontrar o ronamei desejado.', function(){
					Ext.getCmp('".$n."os').focus();
					})";
				exit();
			}
			$os = $rs->fields['os'];
			exibidados($rs->fields, 'os, data, idvendedor, pedido, volume, idcliente, solicitante, idmotorista, entrada, desconto, valortotal, idforma, status, 0endereco, obs', $n);
			echo "dados".$n.".setBaseParam('os', '$os');";
			echo "dados".$n.".load();";
		}
		
		if ($rs->fields['obs'] != ''){
			echo "OBJ('".$namespace."cmdobs').setText('<font color =red>Observação</font>');";
		}
		
		//echo "Ext.getCmp('".$n."imprimir').setDisabled(false);";
		/*echo "dados".$n.".load();";
		rsdados2("select a.id, b.codigo, b.descricao, a.qtd, a.obra, a.valor, a.subtotal, a.idservico from tblitemromanei a inner join tblservicos b on a.idservico  = b.id inner join tblromanei c on a.idromanei = c.id where c.os = $os", $cn);
		echo ");";*/
		
		echo "habilitaobjectos".$namespace."();";
		echo "Ext.getCmp('".$namespace."editar').setDisabled(false);";
		
		$com = 'Ext.getCmp("'.$namespace.'editar").focus();';
		
		echo "com = '$com';";
		echo "setTimeout(com,1000);";
		exit();
	}else{
	
		echo "Ext.getCmp('".$namespace."os').setValue('".Ultimo('os', 'tblromanei', $cn)."');";
		echo "Ext.getCmp('".$namespace."data').setValue('".formatadata(date("d/m/y"))."');";
		echo "Ext.getCmp('".$namespace."editar').setVisible(false);";

		$com = 'Ext.getCmp("'.$namespace.'pedido").focus();';
		echo "com = '$com';";
		echo "setTimeout(com,1000);";
		
	}
?>

