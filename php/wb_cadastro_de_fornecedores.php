<?php
	/**RESSALVA: ROMANEIO
	*ENTÃO VAMOS LÁ
	*O CAMPO "NÚMERO DE OS DEVE FICAR CONGELADO"
	*QUANDO PEDIR UMA OS NOVA DEVERÁ APARECER A TELA DA ÚLTIMA QUE FOI GERADA / SALVA
	*QUNDO PEDIR NOVA
	*DEVERÁ ABRIR ESSA TELA QUE ESTÁ ABRINDO
	*ABERTO PARA LANÇAMENTO
	*GOSTARIA QUE A DATA ESTIVESSE ATUALIZADA, PORÉM COM A OPÇÃO DE ALTERAR
	*AO GERAR UM ROMANEIO, A FORMA DE PGTO QUE ESTIVER PREENCHIDA NO CADASTRO DO CLIENTE, DEVE PERMANECER
	*PORÉM COM STATUS DE ALTERAR, CASO NECESSÁIRO
	*/
	session_start();
	include 'wb_funcao.php';
	@$id    = $_GET['id'];
	$dados  = false;
	$cn = abrebanco();	

	@$ref   = $_GET['ref'];

	if ($ref != ''){
	
		$tabela = 'tblfornecedor';
		$codigo = $_POST[$ref . 'codigo'];

		if (pesquisacodigo($codigo, $cn, $tabela, $id) == false){
			exit();
		}
		
		$sql = montasql($tabela, $id);
		
		$cn->Execute($sql);
		
		if ($id == '0'){
			$id = rsRetornacampo("select max(id) as ultimo from tblfornecedor", "ultimo", $cn);
		}else{
			//echo '// apaguei tudo na tabela';	
			$cn->execute("delete from tblfornecedormercadoria where idfornecedor = $id");
		}
		
		@$ids = $_POST[$ref.'0mercadoria'];
		//echo "// $ids * ". count(explode(",", $ids));
		//exit();
		if ($ids != ''){
			//echo '//entrei no passo 1';
			$reg = explode(",", $ids);
			for ($i = 0 ; $i<count($reg); $i++){
				if ($reg[$i] != ''){
					$sql = "insert into tblfornecedormercadoria (idmercadoria, idfornecedor, checked) values ('".$reg[$i]."', '$id', '1')";
					echo "// $sql". chr(13);
					$cn->execute($sql);
				}
			}
			//echo '//entrei no passo 2';
		}
		echo "MSG('Dados gravado com sucesso!', function(){Ext.getCmp('wb_cadastrofornecedorUi".$ref."').close();});";
		exit();
	}
	
	if ($id != '' and $id != '0' ){
		
		$sql = "select  id, codigo, nome, fantasia, endereco, bairro, cidade, cep, uf, telefone1, telefone2, telefone3, cnpj, estadual, municipal, contato, email, obs, tipo, idforma from tblfornecedor where id = $id";
		

		$rs = $cn->open($sql);
		if ($rs->EOF==true){
			echo "Ext.MessageBox.alert('WebFinan', 'falha na tentativa de acessar o registro id $id!')";
			exit();
		}
		$dados = true;
	}
	$ptel = "', " . chr(13) . "plugins: [new Ext.ux.InputTextMask('(99) 9999 - 9999', true)], " . chr(13) . "value :'";
	$pcep = "', " . chr(13) . "plugins: [new Ext.ux.InputTextMask('99.999-999', true)], " . chr(13) . "value :'";
	

?>	
function salvadados<?php echo $namespace?>(){
	if (Ext.getCmp('<?php echo $namespace?>post').getForm().isValid()==true){

			var msg = ''
			var selNodes = Ext.getCmp('<?php echo $namespace?>mercadoria').getChecked();
			Ext.each(selNodes, function(node){
				if(msg.length > 0){
					msg += ', ';
				}
				msg += node.id;
			});
			Ext.getCmp('<?php echo $namespace?>0mercadoria').setValue(msg);

			salvardados('php/wb_cadastro_de_fornecedores.php?ref=<?php echo $namespace?>&id=<?php echo $id?>',Ext.getCmp('<?php echo $namespace?>post').getForm().getFieldValues(true))
	}else{
		MSG('Favor preecher os campos em vermelho.');
	}
}

tipo<?php echo $namespace?> = [
	['J'  , 'Jurídico'], 
	['F'  , 'Fisica']
];

formapagamento<?php echo $namespace?> = <?php rsdados("select id, descricao from tblforma order by descricao", $cn)?>


cadastrofornecedorUi = Ext.extend(Ext.Window, {
    title: 'Cadastro de fornecedor',
    width: 701,
    height: 504,
    iconCls: 'fornecedor16',
    layout: 'anchor',
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                width: 666,
                height: 400,
                anchor: '100 100',
                layout: 'absolute',
                itemId: '<?php echo $namespace?>post',
                id: '<?php echo $namespace?>post',
                items: [
						{
							xtype: 'hidden',
							itemId: '<?php echo $namespace?>0mercadoria',
							name: '<?php echo $namespace?>0mercadoria',
							x: 0,
							y: 0,
							id: '<?php echo $namespace?>0mercadoria'
						},
                    {
                        xtype: 'container',
                        x: 10,
                        y: 6,
                        width: 642,
                        height: 412,
                        layout: 'form',
                        labelAlign: 'right',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Codigo',
                                anchor: '100%',
                                boxMaxWidth: 100,
                                allowBlank: false,
                                itemId: '<?php echo $namespace?>codigo',
                                name: '<?php echo $namespace?>codigo',
                                id: '<?php echo $namespace?>codigo'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Nome',
                                anchor: '100%',
                                allowBlank: false,
                                itemId: '<?php echo $namespace?>nome',
                                name: '<?php echo $namespace?>nome',
                                id: '<?php echo $namespace?>nome'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Fantasia',
                                anchor: '100%',
                                allowBlank: false,
                                itemId: '<?php echo $namespace?>fantasia',
                                name: '<?php echo $namespace?>fantasia',
                                id: '<?php echo $namespace?>fantasia'
                            },
							
							
							
							
							
{
                                xtype: 'combo',
                                fieldLabel: 'Tipo de Pessoa',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                allowBlank: false,
                                itemId: '<?php echo $namespace?>tipo',
                                name: '<?php echo $namespace?>tipo',
                                store: tipo<?php echo $namespace?>,
								triggerAction: 'all',
                                id: '<?php echo $namespace?>tipo'
                            },
                            {
                                xtype: 'masktextfield',
                                fieldLabel: 'CNPJ/CPF',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                allowBlank: false,
                                itemId: '<?php echo $namespace?>cnpj',
                                name: '<?php echo $namespace?>cnpj',
                                id: '<?php echo $namespace?>cnpj'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'I. Estadual',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                itemId: '<?php echo $namespace?>estadual',
                                name: '<?php echo $namespace?>estadual',
                                id: '<?php echo $namespace?>estadual',
								plugins: [new Ext.ux.InputTextMask('999.999.999-9999', true)]
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'I. Municipal',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                itemId: '<?php echo $namespace?>municipal',
                                name: '<?php echo $namespace?>municipal',
                                id: '<?php echo $namespace?>municipal'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Endereço',
                                anchor: '100%',
                                allowBlank: false,
				boxMaxWidth: 150,
                                itemId: '<?php echo $namespace?>endereco',
                                name: '<?php echo $namespace?>endereco',
                                id: '<?php echo $namespace?>endereco'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Bairro',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                allowBlank: false,
                                itemId: '<?php echo $namespace?>bairro',
                                name: '<?php echo $namespace?>bairro',
                                id: '<?php echo $namespace?>bairro'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Cidade',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                allowBlank: false,
                                itemId: '<?php echo $namespace?>cidade',
                                name: '<?php echo $namespace?>cidade',
                                id: '<?php echo $namespace?>cidade'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Estado',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                allowBlank: false,
                                itemId: '<?php echo $namespace?>uf',
                                name: '<?php echo $namespace?>uf',
                                id: '<?php echo $namespace?>uf'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'CEP',
                                anchor: '100%',
                                boxMaxWidth: 90,
                                allowBlank: false,
								style: '<?php echo $pcep;?>',
                                itemId: '<?php echo $namespace?>cep',
                                name: '<?php echo $namespace?>cep',
                                style: '<?php echo $pcep;?>',
                                id: '<?php echo $namespace?>cep'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Telefone',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                allowBlank: false,
                                itemId: '<?php echo $namespace?>telefone1',
                                name: '<?php echo $namespace?>telefone1',
                                style: '<?php echo $ptel;?>',
                                id: '<?php echo $namespace?>telefone1'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Celular',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                itemId: '<?php echo $namespace?>telefone2',
                                name: '<?php echo $namespace?>telefone2',
                                style: '<?php echo $ptel;?>',
                                id: '<?php echo $namespace?>telefone2'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Fax',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                itemId: '<?php echo $namespace?>telefone3',
                                name: '<?php echo $namespace?>telefone3',
                                style: '<?php echo $ptel;?>',
                                id: '<?php echo $namespace?>telefone3'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Contato',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                itemId: '<?php echo $namespace?>contato',
                                name: '<?php echo $namespace?>contato',
                                id: '<?php echo $namespace?>contato'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'E-mail',
                                anchor: '100%',
                                boxMaxWidth: 150,
                                itemId: '<?php echo $namespace?>email',
                                name: '<?php echo $namespace?>email',
                                id: '<?php echo $namespace?>email'
                            }
                        ]
                    },
                    {
                        xtype: 'fieldset',
                        title: 'Mercadoria',
                        x: 280,
                        y: 82,
                        width: 373,
                        height: 150,
                        layout: 'absolute',
                        items: [
                            {
                                xtype: 'treepanel',
                                title: '',
                                width: 350,
                                height: 110,
                                itemId: '<?php echo $namespace?>mercadoria',
                                x: 0,
                                y: 0,
                                id: '<?php echo $namespace?>mercadoria',
								lines: false,
								rootVisible: false,
								autoScroll: true,
								root: new Ext.tree.AsyncTreeNode({
														expanded: true,
														children: [
																	{id:'-1',text:'Mercadoria',  leaf: false,  children:[<?php echo montaTreeMercadoria($id, $cn, "");?>]}
														]
														}),
                                loader: new Ext.tree.TreeLoader()
                            }
                        ]
                    },
                    {
                        xtype: 'fieldset',
                        title: 'Observações',
                        x: 280,
                        y: 232,
                        width: 373,
                        height: 150,
                        layout: 'absolute',
                        items: [
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Label',
                                width: 350,
                                height: 115,
                                itemId: '<?php echo $namespace?>obs',
                                name: '<?php echo $namespace?>obs',
                                id: '<?php echo $namespace?>obs'
                            }
                        ]
                    },
					{
						xtype: 'label',
						text: 'Forma de pag.:',
						x: 280,
						y: 389
					},
					{
                        x: 360,
                        y: 384,
                        width: 291,
                        height: 150,
						xtype: 'combo',
						fieldLabel: 'Forma de pag.',
						triggerAction: 'all',
						itemId: '<?php echo $namespace?>idforma',
						name: '<?php echo $namespace?>idforma',
						store: formapagamento<?php echo $namespace?>,
						id: '<?php echo $namespace?>idforma'
					},

                    {
                        xtype: 'button',
                        text: 'Cancelar',
                        x: 280,
                        y: 423,
                        width: 95,
						handler: function(){Ext.getCmp('wb_cadastrofornecedorUi<?php echo $namespace?>').close();}
                    },
                    {
                        xtype: 'button',
                        text: 'Salvar',
                        x: 560,
                        y: 426,
                        width: 95,
						handler: salvadados<?php echo $namespace?>
                    }
                ]
            }
        ];
        cadastrofornecedorUi.superclass.initComponent.call(this);
    }
});

var wb_cadastrofornecedorUi<?php echo $namespace?> = new cadastrofornecedorUi({
	id : 'wb_cadastrofornecedorUi<?php echo $namespace?>',
	name : 'wb_cadastrofornecedorUi<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
wb_cadastrofornecedorUi<?php echo $namespace?>.show();

addtask('wb_cadastrofornecedorUi<?php echo $namespace?>');




validaDOC<?php echo $namespace?> = function(){

	strMask = '';
	if (Ext.getCmp('<?php echo $namespace?>tipo').getValue() == 'J'){
		strMask = '99.999.999/9999-99';
	}else{
		strMask = '999.999.999-99';
	}

	Ext.getCmp('<?php echo $namespace?>cnpj').setMask(strMask);
	//alert('teste');
}

Ext.getCmp('<?php echo $namespace?>tipo').on('blur', validaDOC<?php echo $namespace?>);
validaDOC<?php echo $namespace?>();


Ext.getCmp('<?php echo $namespace?>mercadoria').expandAll();
setTimeout("Ext.getCmp('<?php echo $namespace?>nome').focus();",1000);

<?php
	if ($dados == true){
		exibidados($rs->fields, 'codigo, nome, fantasia, endereco, bairro, cidade, cep, uf, telefone1, telefone2, telefone3, cnpj, estadual, municipal, contato, email, obs, tipo, idforma', $namespace);
	}
	if ($id == '' or $id == '0' ){
		echo "Ext.getCmp('".$namespace."codigo').setValue('".Ultimo('codigo', 'tblfornecedor', $cn)."');";
	}else{
		echo "Ext.getCmp('".$namespace."cnpj').setValue('".$rs->fields['cnpj']."');";
		?>
			validaDOC<?php echo $namespace?>();
		<?php
		echo "Ext.getCmp('".$namespace."cnpj').setValue('".$rs->fields['cnpj']."');";
	}
?>	
