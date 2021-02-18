<?php
	session_start();
	include 'wb_funcao.php';
	
	@$id = $_GET['id'];
	@$idcliente = $_POST['idcliente'];
	@$ref = $_POST['space'];
	@$acao = $_GET['acao'];
	
	$descricao = '';
	$cn = abrebanco();
	
	if ($acao == 'salvar'){
	
		$ref = $_GET['ref'];
		$sql = montasql('tblnf', $id);
		$cn->Execute($sql);
		echo "/* $sql */";
		
		
		if ($id == '0'){
			$id = rsRetornacampo("select max(id) as ultimo from tblnf", "ultimo", $cn);
		}else{
			$cn->execute("delete from tblitennf where idnf = $id");
		}
		
		@$dadosgrid        = $_POST[$ref."0grid"];
		
		if ($dadosgrid != ''){
			$registro = explode("|", $dadosgrid);
			for($i = 0; $i < count($registro); ++$i){
				$campo = explode("$!", $registro[$i]);
				$sql  = '';				
				if ($registro[$i] != ''){
					
					for($x = 0; $x < count($campo); ++$x){
						$dados = explode("=", $campo[$x]);
						
						if ($dados[0] == 'idromanei'){
							$sql= "insert into tblitennf(idnf, idromanei) values ($id, " . $dados[1] . ")";
							echo "/* $sql */";
							$cn->execute($sql);
						}
					}
				}
			}
		}
		echo "MSG('Dados salvo com sucesso', DadosSalvos".$ref.");";
		exit();
	}
	
	
	if ($idcliente != ''){
		
		$sql = "select a.id, CONCAT('OS: ', a.os, '  Data: ',  a.data, ' Pedido ' , a.pedido,  ' Valor : ' , a.valortotal) as cbo, 
					   a.os, a.data, a.entrada, a.pedido, a.desconto, a.valortotal, a.solicitante
					   from tblromanei a where a.idcliente = $idcliente ";
		echo "/* $sql */";
		?>
		sDados   = <?php rsdados($sql, $cn)?>
		<?php
		echo "	
				OBJ('".$ref."0ronameni').getStore().loadData(sDados);
			";
		exit();
		
	}
	
	if ($id != ''  && $id != '0'){
		
		$rs = $cn->open("SELECT id, numero,  natureza, emissao, status, idcliente, total, desconto, valortotal, idforma, pedido, idvendedor, saida, obs FROM tblnf where id = $id");
		
		if ($rs->EOF==true){
			echo "Ext.MessageBox.alert('WebFinan', 'falha na tentativa de acessar o registro!')";
			exit();
		}	
	
	}
	$sql = "select a.os, a.data, a.entrada, a.pedido, a.desconto, a.valortotal, a.solicitante, a.id as idromanei from tblromanei a inner join tblitennf b on a.id = b.idromanei where b.idnf = $id ";
?>

DadosSalvos<?php echo $namespace?> = function(e){
	
	habilitaobjectos<?php echo $namespace?>();

	OBJ('<?php echo $namespace?>cmdimprimir').focus();
}

function habilitaobjectos<?php echo $namespace?>(){
	
	sform = OBJ('<?php echo $namespace?>post').getForm();
	//total = sform.getForm().items.length;
	var i=0;
	for (i=0;i<=13;i++) // >
	{
		obj = sform.items.item(i);
		obj.setDisabled(true);
	}
	OBJ('<?php echo $namespace?>cmdimprimir').setDisabled(false);
	OBJ('<?php echo $namespace?>cmdcancelar').setDisabled(false);
	OBJ('<?php echo $namespace?>cmdsalvar').setDisabled(true);

}

pesquisacliente<?php echo $namespace?> = function(e){

	idcliente = OBJ('<?php echo $namespace?>idcliente').getValue();
	
	dados = e.getStore();
	
	endereco   = dados.data.items[dados.find('field1', e.getValue())].data.field3;
	idforma    = dados.data.items[dados.find('field1', e.getValue())].data.field4;
	extra3     = dados.data.items[dados.find('field1', e.getValue())].data.field5;
	idvendedor = dados.data.items[dados.find('field1', e.getValue())].data.field6;
	
	OBJ('<?php echo $namespace?>0endereco').setValue(endereco);
	OBJ('<?php echo $namespace?>idforma').setValue(idforma);
	OBJ('<?php echo $namespace?>idvendedor').setValue(idvendedor);

	if (idvendedor == '0' || idvendedor == ''){
		OBJ('<?php echo $namespace?>idvendedor').setDisabled(false);
	}else{
		OBJ('<?php echo $namespace?>idvendedor').setDisabled(true);
	}

	
}

storecliente<?php echo $namespace?>   = <?php rsdados("SELECT  id, nome, CONCAT(endereco1, ', ', numero1, ' - ', bairro1, ', ', cidade1, ' ', estado1, ' ', cep1) as endereco, idforma, extra3, idvendedor FROM tblcliente order by nome", $cn)?>

storevendedor<?php echo $namespace?>  = <?php rsdados("select id, nome from tblvendedor order by nome", $cn)?>

storeformapag<?php echo $namespace?>  = <?php rsdados("select id, descricao from tblforma order by Descricao", $cn)?>

stored_status<?php echo $namespace;?> = [
	[0, 'Aberto'],
	[1, 'Cancelada'],
	[2, 'Impressa']
];



var sdados<?php echo $namespace?> = new Ext.data.ArrayStore({
	fields:[
		{name:'os', format: '0'},
		{name:'data'},
		{name:'entrada'},
		{name:'pedido'},
		{name:'desconto'},
		{name:'valortotal'},
		{name:'solicitante'},
		{name:'idromanei'}
	]
});

sUpDados = <?php rsdados($sql, $cn)?>

sdados<?php echo $namespace?>.loadData(sUpDados);

salvar<?php echo $namespace?> = function(){

	if (Ext.getCmp('<?php echo $namespace?>post').getForm().isValid()==true){
		dados = OBJ('<?php echo $namespace?>grid').getStore();
		totalregistro = dados.data.items.length;
		if (totalregistro == 0){
			alert('É preciso ter pelo menos 1 romanei para notal fiscal');
			return false
		}

		Ext.getCmp('<?php echo $namespace?>0grid').setValue(montasqlURL(dados));		

		salvardados('php/wb_nf.php?acao=salvar&ref=<?php echo $namespace?>&id=<?php echo $id?>', Ext.getCmp('<?php echo $namespace?>post').getForm().getFieldValues(true))
		
	}else{
		MSG('Favor preecher os campos em vermelho.');
	}
	
}

preechedadosgrid<?php echo $namespace?> = function(){

	idcliente = OBJ('<?php echo $namespace?>idcliente').getValue();
	
	if (idcliente == ''){
		alert('É preciso escolher um cliente');
		OBJ('<?php echo $namespace?>idcliente').focus();
		return false;
	}
	
	OPENURL('php/wb_pesquisa_romanei.php?varspace=<?php echo $namespace?>&idcliente='+idcliente);

	
/*	dados     = OBJ('<?php echo $namespace?>0ronameni').getStore();
	dadosGrid = OBJ('<?php echo $namespace?>grid').getStore();
	
	rs = dados.data.items[dados.find('field1', OBJ('<?php echo $namespace?>0ronameni').getValue())].data

	var myArray=new Array();
	
	//a.os, a.data, a.entrada, a.pedido, a.desconto, a.valortotal, a.solicitante
	
	i = dadosGrid.find('idromanei', rs.field1);
	if (i >= 0){
		if (confirm('já exite este romanei adicionando nesta nota, \nDeseja adicionar novamente?')==false){
			return false
		}
	}
	
	myArray['id']          = -1;
	myArray['idromanei']   = rs.field1;
	myArray['os']          = rs.field3;
	myArray['data']        = rs.field4;
	myArray['entrada']     = rs.field5;
	myArray['pedido']      = rs.field6;
	myArray['desconto']    = rs.field7;
	myArray['valortotal']  = rs.field8;
	myArray['solicitante'] = rs.field9;

	var rec = new Ext.data.Record(myArray);
	dadosGrid.add(rec);
	
	recalcula<?php echo $namespace?>();
*/
}

recalcula<?php echo $namespace?> = function(){

	dados = OBJ('<?php echo $namespace?>grid').getStore();
	totalregistro = dados.data.items.length;
	totaldecampos = dados.fields.length;
	
	valor     = 0.00;
	desconto  = 0.00;
	
	for (i=0; i < totalregistro; i++) { // >
		vv    = parseFloat(dados.data.items[i].data.valortotal.replace(",","."));
		valor = valor + vv;
		desc  = dados.data.items[i].data.desconto || 0;
		desconto = desconto + parseFloat(desc.toString().replace(",","."));
	}
	//alert('Desconto =' +desconto);
	//alert('Valor ='+valor);
	OBJ('<?php echo $namespace?>desconto').setValue(fn(desconto));
	valorComDesconto = parseFloat(valor) - parseFloat(desconto);
	OBJ('<?php echo $namespace?>total').setValue(fn(valor));
	OBJ('<?php echo $namespace?>valortotal').setValue(fn(valorComDesconto));

}

removerol<?php echo $namespace?> = function(){
	
	if (confirm('Deseja remover este item?')==true){
		
		objGrid = OBJ('<?php echo $namespace?>grid');
		objGrid.getStore().remove(objGrid.getSelectionModel().selections.items[0]);
		
	}
	
	recalcula<?php echo $namespace?>();
}

PrintNota<?php echo $namespace?> = function(){


	numero = Ext.getCmp('<?php echo $namespace?>numero').getValue();
	window.open('./php/wb_print_nf.php?numero='+numero, 'printgrid');

}



nfiscalUi = Ext.extend(Ext.Window, {
    title: 'Nota Fiscal',
    width: 814,
    height: 426,
    iconCls: 'baixadetitulo16',
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                width: 811,
                height: 421,
                iconCls: 'baixadetitulo16',
                layout: 'absolute',
                padding: 10,
                id: '<?php echo $namespace?>post',
                items: [
				    {
                        xtype: 'hidden',
                        itemId: '<?php echo $namespace?>0grid',
                        name: '<?php echo $namespace?>0grid',
                        id: '<?php echo $namespace?>0grid'
                    },
                    {
                        xtype: 'container',
                        x: 15,
                        y: 5,
                        width: 240,
                        height: 95,
                        layout: 'form',
                        labelAlign: 'right',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Número',
                                anchor: '100%',
                                tabIndex: 1,
                                itemId: '<?php echo $namespace?>numero',
                                name: '<?php echo $namespace?>numero',
                                allowBlank: false,
								selectOnFocus:true,
                                id: '<?php echo $namespace?>numero'
                            },
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Emissão',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>emissao',
                                name: '<?php echo $namespace?>emissao',
                                tabIndex: 3,
                                allowBlank: false,
								selectOnFocus:true,
								format: 'd/m/Y',
                                id: '<?php echo $namespace?>emissao'
                            },
                            {
                                xtype: 'combo',
                                fieldLabel: 'Status',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>status',
                                name: '<?php echo $namespace?>status',
                                tabIndex: 4,
                                store: stored_status<?php echo $namespace;?>,
                                triggerAction: 'all',
								selectOnFocus:true,
                                allowBlank: false,
                                id: '<?php echo $namespace?>status'
                            }
                        ]
                    },
                    {
                        xtype: 'container',
                        x: 265,
                        y: 5,
                        width: 525,
                        height: 75,
                        layout: 'form',
                        labelAlign: 'right',
                        items: [
                            {
                                xtype: 'combo',
                                fieldLabel: 'Cliente',
                                anchor: '100%',
                                tabIndex: 2,
								selectOnFocus:true,
                                itemId: '<?php echo $namespace?>idcliente',
                                name: '<?php echo $namespace?>idcliente',
                                store: storecliente<?php echo $namespace?>,
                                triggerAction: 'all',
                                allowBlank: false,
                                id: '<?php echo $namespace?>idcliente'
                            },
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Endereco',
                                anchor: '100%',
                                disabled: true,
                                height: 48,
								selectOnFocus:true,
                                itemId: '<php echo $namespace?>0endereco',
                                name: '<php echo $namespace?>0endereco',
                                id: '<?php echo $namespace?>0endereco'
                            }
                        ]
                    },
                    {
                        xtype: 'grid',
                        title: '',
                        store: sdados<?php echo $namespace?>,
                        x: 10,
                        y: 85,
                        width: 780,
                        height: 185,
                        itemId: '<?php echo $namespace?>grid',
                        id: '<?php echo $namespace?>grid',
                        columns: [
                            {
                                xtype: 'numbercolumn',
                                dataIndex: 'os',
                                header: 'Os',
                                sortable: true,
                                width: 60,
                                editable: false,
                                align: 'right',
								format: '0'
                            },
                            {
                                xtype: 'gridcolumn',
                                dataIndex: 'data',
                                header: 'Data',
                                sortable: true,
                                width: 85,
                                editable: false
                            },
                            {
                                xtype: 'gridcolumn',
                                dataIndex: 'entrada',
                                header: 'Entrada',
                                sortable: true,
                                width: 85,
                                editable: false
                            },
                            {
                                xtype: 'gridcolumn',
                                dataIndex: 'pedido',
                                header: 'Pedido',
                                sortable: true,
                                width: 60,
                                editable: false,
                                align: 'right'
                            },
                            {
                                xtype: 'gridcolumn',
                                dataIndex: 'desconto',
                                header: 'Desconto',
                                sortable: true,
                                width: 70,
                                editable: false,
                                align: 'right'
                            },
                            {
                                xtype: 'gridcolumn',
                                dataIndex: 'valortotal',
                                header: 'Total',
                                sortable: true,
                                width: 80,
                                editable: false,
                                align: 'right'
                            }/*,
                            {
                                xtype: 'gridcolumn',
                                dataIndex: 'solicitando',
                                header: 'Solicitando',
                                sortable: true,
                                width: 150,
                                editable: false
                            }*/
                        ],
                        tbar: {
                            xtype: 'toolbar',
                            items: [
                                {
                                    xtype: 'label',
                                    text: '<b>Listagem de OS</b>',
                                    html: '<font color = Blue><b>Listagem de OS</b></font>'
                                },
                                {
                                    xtype: 'tbfill'
                                },
                                {
                                    xtype: 'button',
                                    text: 'Incluir romanei',
                                    width: 100,
                                    itemId: '<?php echo $namespace?>cmdincluirromanei',
                                    id: '<?php echo $namespace?>cmdincluirromanei'
                                },
                                {
                                    xtype: 'button',
                                    text: 'Excluir Romanei romanei',
                                    width: 100,
                                    itemId: '<?php echo $namespace?>cmdremoverromanei',
                                    id: '<?php echo $namespace?>cmdremoverromanei'
                                }
                            ]
                        }
                    },
                    {
                        xtype: 'container',
                        x: -32,
                        y: 280,
                        width: 390,
                        height: 95,
                        layout: 'form',
                        labelAlign: 'right',
                        items: [
                            {
                                xtype: 'combo',
                                fieldLabel: 'Vendedor',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>idvendedor',
                                name: '<?php echo $namespace?>idvendedor',
                                store: storevendedor<?php echo $namespace?>,
                                tabIndex: 6,
                                triggerAction: 'all',
                                allowBlank: false,
								selectOnFocus:true,
                                id: '<?php echo $namespace?>idvendedor'
                            },
                            {
                                xtype: 'combo',
                                fieldLabel: 'Forma pag.',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>idforma',
                                name: '<?php echo $namespace?>idforma',
                                store: storeformapag<?php echo $namespace?>,
                                tabIndex: 7,
                                triggerAction: 'all',
                                allowBlank: false,
								selectOnFocus:true,
                                id: '<?php echo $namespace?>idforma'
                            },
                            {
                                xtype: 'numberfield',
                                fieldLabel: 'Pedido',
                                anchor: '100%',
                                tabIndex: 8,
                                itemId: '<?php echo $namespace?>pedido',
                                name: '<?php echo $namespace?>pedido',
                                boxMaxWidth: 100,
								selectOnFocus:true,
                                //allowBlank: false,
                                id: '<?php echo $namespace?>pedido'
                            },
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Saida',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>saida',
                                name: '<?php echo $namespace?>saida',
                                boxMaxWidth: 100,
                                tabIndex: 9,
								selectOnFocus:true,
                                allowBlank: false,
								format: 'd/m/Y',
                                id: '<?php echo $namespace?>saida'
                            }
                        ]
                    },
                    {
						xtype: 'container',
						x: 180,
						y: 331,
						width: 180,
						height: 95,
						layout: 'form',
						labelAlign: 'right',
						labelWidth: 60,
						items: [
							{
								xtype: 'masktextfield',
								fieldLabel: 'Sub-Total',
								anchor: '100%',
								tabIndex: 10,
								selectOnFocus:true,
								itemId: '<?php echo $namespace?>total',
								name: '<?php echo $namespace?>total',
								allowBlank: false,
								id: '<?php echo $namespace?>total',
								style: 'text-align: right',
								money: true,
								selectOnFocus:true,
								mask: '#9.999.990,00'
							},
							{
								xtype: 'masktextfield',
								fieldLabel: 'Desconto',
								anchor: '100%',
								itemId: '<?php echo $namespace?>desconto',
								name: '<?php echo $namespace?>desconto',
								tabIndex: 12,
								allowBlank: false,
								selectOnFocus:true,
								decimalSeparator: ',',
								id: '<?php echo $namespace?>desconto',
								style: 'text-align: right',
								money: true,
								selectOnFocus:true,
								mask: '#9.999.990,00'
							}
						]
					},
                    {
                        xtype: 'container',
                        x: 360,
                        y: 280,
                        width: 430,
                        height: 125,
                        layout: 'form',
                        labelAlign: 'right',
                        labelWidth: 80,
                        items: [
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Obs.',
                                anchor: '100%',
                                tabIndex: 13,
                                itemId: '<?php echo $namespace?>obs',
                                name: '<?php echo $namespace?>obs',
                                height: 69,
								selectOnFocus:true,
                                id: '<?php echo $namespace?>obs'
                            },
                            {
                                xtype: 'masktextfield',
                                fieldLabel: 'Valor total',
								decimalSeparator: ',',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>valortotal',
                                name: '<?php echo $namespace?>valortotal',
                                boxMaxWidth: 100,
                                tabIndex: 14,
								selectOnFocus:true,
                                allowBlank: false,
                                id: '<?php echo $namespace?>valortotal',
								style: 'text-align: right',
								money: true,
								selectOnFocus:true,
								mask: '#9.999.990,00'
                            }
                        ]
                    },
                    {
                        xtype: 'button',
                        text: 'Salvar',
                        x: 550,
                        y: 355,
                        width: 55,
                        height: 22,
                        itemId: '<?php echo $namespace?>cmdsalvar',
                        id: '<?php echo $namespace?>cmdsalvar'
                    },
                    {
                        xtype: 'button',
                        text: 'Imprimir',
                        x: 615,
                        y: 355,
                        width: 55,
                        height: 22,
                        itemId: '<?php echo $namespace?>cmdimprimir',
                        disabled: true,
                        id: '<?php echo $namespace?>cmdimprimir'
                    },
                    {
                        xtype: 'button',
                        text: 'Cancelar',
                        x: 675,
                        y: 355,
                        width: 55,
                        height: 22,
                        itemId: '<?php echo $namespace?>cmdcancelar',
                        id: '<?php echo $namespace?>cmdcancelar'
                    },
					{
                        xtype: 'button',
                        text: 'Novo',
                        x: 735,
                        y: 355,
                        width: 55,
                        height: 22,
                        itemId: '<?php echo $namespace?>cmdnovo',
                        id: '<?php echo $namespace?>cmdnovo'
                    }
                ]
            }
        ];
        nfiscalUi.superclass.initComponent.call(this);
    }
});




var myWinFiscal<?php echo $namespace?> = new nfiscalUi({
	id : 'myWinFiscal<?php echo $namespace?>',
	name : 'myWinFiscal<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
myWinFiscal<?php echo $namespace?>.show();
addtask('myWinFiscal<?php echo $namespace?>');

setTimeout("OBJ('<?php echo $namespace?>numero').focus();", 1000);
OBJ('<?php echo $namespace?>idcliente').on('select', pesquisacliente<?php echo $namespace?>);
OBJ('<?php echo $namespace?>cmdincluirromanei').on('click', preechedadosgrid<?php echo $namespace?>);
OBJ('<?php echo $namespace?>cmdsalvar').on('click', salvar<?php echo $namespace?>);
OBJ('<?php echo $namespace?>cmdcancelar').on('click', function(){
	OBJ('myWinFiscal<?php echo $namespace?>').close();
});

OBJ('<?php echo $namespace?>cmdremoverromanei').on('click', removerol<?php echo $namespace?>);
OBJ('<?php echo $namespace?>cmdimprimir').on('click', PrintNota<?php echo $namespace?>);

OBJ('<?php echo $namespace?>cmdnovo').on('click', function(){
	OBJ('myWinFiscal<?php echo $namespace?>').close();
	OpenUrl('php/<?php echo $_SESSION['nf_php'];?>?acao=add&id=0');

});

<?php
	if ($id == '' or $id == '0' ){
		echo "Ext.getCmp('".$namespace."numero').setValue('".Ultimo('numero', 'tblnf', $cn)."');";
		echo "Ext.getCmp('".$namespace."status').setValue('0');";
	}else{
		exibidados($rs->fields, 'numero, emissao, status, idcliente, total, desconto, valortotal, idforma, pedido, idvendedor, saida, obs', $namespace);
		echo "pesquisacliente".$namespace."(OBJ('".$namespace."idcliente'));";
		echo "setTimeout('recalcula".$namespace."()', 1000);";
	}
?>	
OBJ('<?php echo $namespace?>desconto').on('blur', recalcula<?php echo $namespace?>);
OBJ('<?php echo $namespace?>emissao').on('blur', function(){
	OBJ('<?php echo $namespace?>saida').setValue(OBJ('<?php echo $namespace?>emissao').getValue());
});
