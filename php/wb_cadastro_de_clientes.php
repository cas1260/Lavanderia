<?php
	// ALTER TABLE `tblcliente` ADD `obs1` TEXT NOT NULL ;
	/*
		CREATE TABLE `tblenderecos` (
		`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`idcliente` INT NULL ,
		`endereco` VARCHAR( 300 ) NULL ,
		`numero` VARCHAR( 4 ) NULL ,
		`bairro` VARCHAR( 300 ) NULL ,
		`cidade` VARCHAR( 300 ) NULL ,
		`UF` VARCHAR( 2 ) NULL
		) ENGINE = MYISAM ;

		ALTER TABLE `tblenderecos` ADD `cep` VARCHAR( 9 ) NULL ;
		ALTER TABLE  `tblenderecos` ADD  `referencia` VARCHAR( 255 ) NULL ;
		
	*/
		
		
	session_start();
	include 'wb_funcao.php';
	@$id    = $_GET['id'];
	$dados  = false;
	$cn = abrebanco();	

	@$ref   = $_GET['ref'];
	
	if ($ref != ''){
		
		$codigo = $_POST[$ref . 'codigo'];
		
		$tabela = 'tblcliente';
		
		if (pesquisacodigo($codigo, $cn, $tabela, $id) == false){
			exit();
		}
	
		$sql = montasql($tabela, $id);
		$cn->Execute($sql);
		
		if ($id == '0'){
			$id = rsRetornacampo("select max(id) as ultimo from tblcliente", "ultimo", $cn);
		}else{
			//echo '// apaguei tudo na tabela';	
			$cn->execute("delete from tblrotacliente where idcliente = $id");
		}
		
		/* Dados de lançamento de serviço */
		@$dadosgrid        = $_POST[$ref."0servicogrid"];
		@$registroapagados = $_POST[$ref."0registroapagados"];
		@$endereco         = $_POST[$ref.'0gridendereco'];
		
		
		if ($registroapagados != '0'){
			if ($registroapagados != ''){
				$cn->execute("delete from tblclienteservico where id in ($registroapagados)");
			}
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
						if ($dados[0] == 'idservico' || $dados[0] == 'valor' || $dados[0] == 'idcliente' || $dados[0] == 'id'){
							if ($dados[0] == 'idcliente'){
								$dados[1] = $id; 
							}
							
							if ($dados[0] == 'id'){
								if ($dados[1] == '-1'){
									$sql  = "insert into tblclienteservico ( ";
									$sql1 = "";
									$tipo = 0;
								}else{
									$tipo = 1;
									$sql  ="update tblclienteservico set  ";
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
		$cn->execute("delete from tblenderecos where idcliente = $id");
		/*
		endereco=Teste 1$!numero=1$!bairro=bairro$!cidade=cidade$!cep=332000$!uf=mg$!|
		endereco=teste$!numero=25$!bairro=bairro2$!cidade=cidade2$!cep=00333$!uf=MG$!| 
		*/
		if ($endereco != ''){
			$registro = explode("|", $endereco);
			for($i = 0; $i < count($registro); ++$i){
				$campo = explode("$!", $registro[$i]);
				$tipo = 0;

				$sql   = "insert into tblenderecos (idcliente,  ";
				$sql1  = "$id, ";
				
				$devoInsert = 0;
				for($x = 0; $x < count($campo); ++$x){
					if ($campo[$x] != ''){
						$devoInsert = 1;
						$dados = explode("=", $campo[$x]);						
						$sql  = $sql  . $dados[0] . ", ";
						if ('undefined' == $dados[1]){
							$sql1 = $sql1 . "'', ";
						}else{
							$sql1 = $sql1 . "'" . $dados[1] . "', ";
						}
					}
				}
				if ($devoInsert == 1){
					$sql = left($sql, strlen($sql)-2);
					$sql1 = left($sql1, strlen($sql1)-2);
					$sql = $sql . ") values (" . $sql1 . ")";
					$cn->open($sql);
					echo "/* $sql */";
				}
			}
		}
		
		
		
		
		/* FIM */
		
		
		@$ids = $_POST[$ref.'0rota'];
		//echo "// $ids * ". count(explode(",", $ids));
		//exit();
		if ($ids != ''){
			//echo '//entrei no passo 1';
			$reg = explode(",", $ids);
			for ($i = 0 ; $i<count($reg); $i++){
				if ($reg[$i] != ''){
					$sql = "insert into tblrotacliente (idrota, idcliente, checked) values ('".$reg[$i]."', '$id', '1')";
					//echo "// $sql". chr(13);
					$cn->execute($sql);
				}
			}
			//echo '//entrei no passo 2';
		}
		
		
		
		
		
		echo "MSG('Dados gravado com sucesso!', function(){Ext.getCmp('wb_cadclienteUi".$ref."').close();});";
		exit();
	}
	
	if ($id != '' and $id != '0' ){
		
		$sql = "select  id, codigo, nome, fantasia, tipo, doc, estadual, municipal, contato, email, telefone, fax, celular, inicio, extra, extra2, extra3, endereco, numero, bairro, cidade, estado, cep, endereco1, numero1, bairro1, cidade1, estado1, cep1, reajuste, status, idforma, obs, obs1, idvendedor from tblcliente where id = $id";
		
		
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
function salvadados<?php echo $namespace;?>(oCMD){

	if (oCMD.text == 'Editar'){
		hObj('<?php echo $namespace;?>post', false, 60);
		oCMD.setText('Salvar');
		Ext.getCmp('<?php echo $namespace;?>codigo').focus();
		return true;
	}

	if (oCMD.text == 'Salvar'){

		OBJ('<?php echo $namespace;?>tabpanel').setActiveTab(3);
		OBJ('<?php echo $namespace;?>tabpanel').setActiveTab(2);
		OBJ('<?php echo $namespace;?>tabpanel').setActiveTab(1);
		OBJ('<?php echo $namespace;?>tabpanel').setActiveTab(0);
		
		if (Ext.getCmp('<?php echo $namespace;?>post').getForm().isValid()==true){

				var msg = ''
				var selNodes = Ext.getCmp('<?php echo $namespace;?>rota').getChecked();
				Ext.each(selNodes, function(node){
					if(msg.length > 0){
						msg += ', ';
					}
					msg += node.id;
				});
				Ext.getCmp('<?php echo $namespace;?>0rota').setValue(msg);

				OBJ('<?php echo $namespace;?>0servicogrid').setValue(montasqlURL(Ext.getCmp('<?php echo $namespace;?>grid').getStore()));		
				OBJ('<?php echo $namespace;?>0gridendereco').setValue(montasqlURL(Ext.getCmp('<?php echo $namespace;?>gridcoleta').getStore()));		
				
				
				salvardados('php/wb_cadastro_de_clientes.php?ref=<?php echo $namespace;?>&id=<?php echo $id?>', Ext.getCmp('<?php echo $namespace;?>post').getForm().getFieldValues(true))
		}else{
			MSG('Favor preecher os campos em vermelho.');
		}
	}
}

tipo<?php echo $namespace;?> = [
	['J'  , 'Jurídico'], 
	['F'  , 'Fisica']
];

status<?php echo $namespace;?> = [
	['0'  , 'Ativo'], 
	['1'  , 'Inativo'],
	['2'  , 'Inadimplente'],
	['3'  , 'Exporádico']
];


<?php
	if ($id ==''){
		$id = '0';
	}
	$sql = "select a.id, b.descricao, a.valor, a.idcliente, a.idservico, b.unidade from tblclienteservico a inner join tblservicos b on a.idservico = b.id where a.idcliente = " . $id;

	//echo "/* $sql */";
?>


rota<?php echo $namespace;?> = <?php rsdados("select id, CONCAT(codigo, ' - ', descricao) as descricao from tblrota order by descricao", $cn)?>


formapagamento<?php echo $namespace;?> = <?php rsdados("select id, descricao from tblforma order by descricao", $cn)?>
vendedor<?php echo $namespace;?> = <?php rsdados("select id, nome from tblvendedor order by nome", $cn)?>

clienteservico<?php echo $namespace;?> = <?php rsdados($sql, $cn)?>

var gridclienteservico<?php echo $namespace;?> = new Ext.data.ArrayStore({
	fields:[{name:'id'}, {name:'descricao'}, {name:'valor'}, {name:'idcliente'}, {name:'idservico'}, {name:'unidade'}]
});

gridclienteservico<?php echo $namespace;?>.loadData(clienteservico<?php echo $namespace;?>);

servico<?php echo $namespace;?> = <?php rsdados("select id, descricao from tblservicos order by descricao", $cn)?>	

/*var comboservico<?php echo $namespace;?> = new Ext.data.ArrayStore({
	fields:[{name:'id'}, {name:'descricao'}, {name:'precounitario'}]
});

comboservico<?php echo $namespace;?>.loadData(servico<?php echo $namespace;?>);
*/


var DadosEndereco<?php echo $namespace;?> = new Ext.data.ArrayStore({
	fields:[
		{name:'endereco'},
		{name:'numero'},
		{name:'bairro'},
		{name:'cidade'},
		{name:'cep'},
		{name:'uf'},
		{name:'referencia'}
		]
});
<?php 
//exit();
?>
xvalor = <?php $rsEndereco = rsdados("select endereco, numero, bairro, cidade, cep, UF, referencia from tblenderecos where idcliente = $id", $cn)?>;
DadosEndereco<?php echo $namespace;?>.loadData(xvalor);

var totalRegistro = <?php echo $rsEndereco->RecordCount();?>


cadclienteUi = Ext.extend(Ext.Window, {
    title: 'Cadastro de Cliente',
    width: 670,
    height: 439,
    iconCls: 'cliente16',
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                width: 768,
                height: 462,
                layout: 'absolute',
                itemId: '<?php echo $namespace;?>post',
                id: '<?php echo $namespace;?>post',
                items: [
						{
							xtype: 'hidden',
							itemId: '<?php echo $namespace;?>0rota',
							name: '<?php echo $namespace;?>0rota',
							x: 104,
							y: 298,
							id: '<?php echo $namespace;?>0rota'
						},
						{
							xtype: 'hidden',
							itemId: '<?php echo $namespace;?>0servicogrid',
							name: '<?php echo $namespace;?>0servicogrid',
							x: 104,
							y: 298,
							id: '<?php echo $namespace;?>0servicogrid'
						},
						{
							xtype: 'hidden',
							itemId: '<?php echo $namespace;?>0gridendereco',
							name: '<?php echo $namespace;?>0gridendereco',
							x: 104,
							y: 298,
							id: '<?php echo $namespace;?>0gridendereco'
						},
						{
							xtype: 'hidden',
							itemId: '<?php echo $namespace;?>0registroapagados',
							name: '<?php echo $namespace;?>0registroapagados',
							value: 0,
							x: 30,
							y: 400,
							id: '<?php echo $namespace;?>0registroapagados'
						},
                    {

                        xtype: 'container',
                        x: 5,
                        y: 10,
                        width: 110,
                        height: 40,
                        layout: 'form',
                        labelAlign: 'right',
                        labelWidth: 50,
                        items: [
                            {
	                        x: 5,
        	                y: 10,
                	        width: 30,
                                xtype: 'textfield',
                                fieldLabel: 'Codigo',
                                anchor: '100%',
                                itemId: '<?php echo $namespace;?>codigo',
                                name: '<?php echo $namespace;?>codigo',
                                id: '<?php echo $namespace;?>codigo'
                            }
                        ]
                    },
                    {
                        xtype: 'container',
                        x: 110,
                        y: 10,
                        width: 280,
                        height: 35,
                        layout: 'form',
                        labelAlign: 'right',
                        labelWidth: 50,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Cliente',
                                anchor: '100%',
                                itemId: '<?php echo $namespace;?>nome',
                                name: '<?php echo $namespace;?>nome',
                                id: '<?php echo $namespace;?>nome'
                            }
                        ]
                    },
		    {
                        xtype: 'container',
                        x: 400,
                        y: 10,
                        width: 250,
                        height: 35,
                        layout: 'form',
                        labelAlign: 'right',
                        labelWidth: 50,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Fantasia',
                                anchor: '100%',
                                itemId: '<?php echo $namespace;?>fantasia',
                                name: '<?php echo $namespace;?>fantasia',
                                id: '<?php echo $namespace;?>fantasia'
                            }
                        ]
                    },
                    {
                        xtype: 'tabpanel',
                        activeTab: 0,
                        x: 15,
                        y: 40,
                        width: 635,
                        height: 325,
						id: '<?php echo $namespace;?>tabpanel',
						//deferredRender: false,
						
                        items: [
                            {
                                xtype: 'panel',
                                title: 'Dados basicos',
                                height: 31,
                                layout: 'absolute',
                                items: [
                                    {
                                        xtype: 'container',
                                        x: 10,
                                        y: 5,
                                        width: 280,
                                        height: 270,
                                        layout: 'form',
                                        labelAlign: 'right',
                                        items: [
                                            {
                                                xtype: 'combo',
                                                fieldLabel: 'Tipo de pessoa',
                                                anchor: '100%',
                                                triggerAction: 'all',
                                                itemId: '<?php echo $namespace;?>tipo',
                                                name: '<?php echo $namespace;?>tipo',
                                                store: tipo<?php echo $namespace;?>,
                                                id: '<?php echo $namespace;?>tipo'
                                            },
                                            {
                                                xtype: 'combo',
                                                fieldLabel: 'Status',
                                                anchor: '100%',
                                                triggerAction: 'all',
                                                itemId: '<?php echo $namespace;?>status',
                                                name: '<?php echo $namespace;?>status',
                                                store: status<?php echo $namespace;?>,
                                                id: '<?php echo $namespace;?>status'
                                            },
                                            {
                                                xtype: 'masktextfield',
                                                fieldLabel: 'CPF/CNPJ',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>doc',
                                                name: '<?php echo $namespace;?>doc',
                                                id: '<?php echo $namespace;?>doc',
												preventMark: false,
												money: false,
												selectOnFocus: true,
												mask: "999999999-96"
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'I. Estadual',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>estadual',
                                                name: '<?php echo $namespace;?>estadual',
                                                id: '<?php echo $namespace;?>estadual',
												plugins: [new Ext.ux.InputTextMask('999.999.999-9999', true)]
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'I. Municipal',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>municipal',
                                                name: '<?php echo $namespace;?>municipal',
                                                id: '<?php echo $namespace;?>municipal'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'E-mail',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>email',
                                                name: '<?php echo $namespace;?>email',
                                                id: '<?php echo $namespace;?>email'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Contato',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>contato',
                                                name: '<?php echo $namespace;?>contato',
                                                id: '<?php echo $namespace;?>contato'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Telefone',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>telefone',
                                                name: '<?php echo $namespace;?>telefone',
                                                style: '<?php echo $ptel;?>',
                                                id: '<?php echo $namespace;?>telefone'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Telefone',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>celular',
                                                name: '<?php echo $namespace;?>celular',
                                                style: '<?php echo $ptel;?>',
                                                id: '<?php echo $namespace;?>celular'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Fax',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>fax',
                                                name: '<?php echo $namespace;?>fax',
                                                style: '<?php echo $ptel;?>',
                                                id: '<?php echo $namespace;?>fax'
                                            },
											{
                                                xtype: 'combo',
                                                fieldLabel: 'Forma de pag.',
                                                anchor: '100%',
                                                triggerAction: 'all',
                                                itemId: '<?php echo $namespace;?>idforma',
                                                name: '<?php echo $namespace;?>idforma',
                                                store: formapagamento<?php echo $namespace;?>,
                                                id: '<?php echo $namespace;?>idforma'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'container',
                                        x: 300,
                                        y: 5,
                                        width: 310,
                                        height: 270,
                                        layout: 'form',
                                        labelAlign: 'right',
                                        items: [
                                            {
                                                xtype: 'datefield',
                                                fieldLabel: 'Data incio',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>inicio',
                                                name: '<?php echo $namespace;?>inicio',
                                                id: '<?php echo $namespace;?>inicio'
                                            },
											{
                                                xtype: 'datefield',
                                                fieldLabel: 'Data de reajuste',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>reajuste',
                                                name: '<?php echo $namespace;?>reajuste',
                                                id: '<?php echo $namespace;?>reajuste'
                                            }, 
                                            {
                                                xtype: 'combo',
                                                fieldLabel: 'Vendedor',
                                                anchor: '100%',
                                                triggerAction: 'all',
                                                itemId: '<?php echo $namespace;?>idvendedor',
                                                name: '<?php echo $namespace;?>idvendedor',
                                                store: vendedor<?php echo $namespace;?>,
                                                id: '<?php echo $namespace;?>idvendedor'
                                            },
                                            {
                                                xtype: 'treepanel',
                                                title: '',
                                                width: 203,
                                                height: 80,
                                                fieldLabel: 'Rota',
                                                lines: false,
                                                rootVisible: false,
                                                autoScroll: true,
                                                itemId: '<?php echo $namespace;?>rota',
                                                id: '<?php echo $namespace;?>rota',
                                                root: new Ext.tree.AsyncTreeNode({
														expanded: true,
														children: [
																	{id:'-1',text:'Rotas',  leaf: false,  children:[<?php echo montaTreeRota($id, $cn, "");?>]}
														]
														}),
                                               loader: new Ext.tree.TreeLoader()
                                            },
                                            {
                                                xtype: 'textarea',
                                                fieldLabel: 'Extra',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>extra',
                                                name: '<?php echo $namespace;?>extra',
                                                height: 50,
                                                id: '<?php echo $namespace;?>extra'
                                            },
                                            {
                                                xtype: 'textarea',
                                                fieldLabel: 'Extra 2',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>extra2',
                                                name: '<?php echo $namespace;?>extra2',
                                                height: 35,
                                                id: '<?php echo $namespace;?>extra2'
                                            },
                                            {
                                                xtype: 'textarea',
                                                fieldLabel: 'Restrições',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>extra3',
                                                name: '<?php echo $namespace;?>extra3',
                                                height: 35,
                                                id: '<?php echo $namespace;?>extra3'
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                title: 'Endereço',
                                layout: 'absolute',
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        title: 'Endereço de Cobrança',
                                        x: 5,
                                        y: 5,
                                        height: 270,
                                        width: 305,
                                        labelWidth: 55,
                                        labelAlign: 'right',
                                        items: [
                                            {
	                                        width: 220,
                                                xtype: 'textfield',
                                                fieldLabel: 'Endereço',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>endereco1',
                                                name: '<?php echo $namespace;?>endereco1',
                                                id: '<?php echo $namespace;?>endereco1'
                                            },
                                            {
	                                        width: 220,
                                                xtype: 'textfield',
                                                fieldLabel: 'Numero',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>numero1',
                                                name: '<?php echo $namespace;?>numero1',
                                                boxMaxWidth: 50,
                                                id: '<?php echo $namespace;?>numero1'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Bairro',
	                                        width: 220,
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>bairro1',
                                                name: '<?php echo $namespace;?>bairro1',
                                                id: '<?php echo $namespace;?>bairro1'
                                            },
                                            {
	                                        width: 220,
                                                xtype: 'textfield',
                                                fieldLabel: 'Cidade',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>cidade1',
                                                name: '<?php echo $namespace;?>cidade1',
                                                id: '<?php echo $namespace;?>cidade1'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Cep',
                                                anchor: '100%',
												width: 220,
												style: '<?php echo $pcep;?>',
                                                itemId: '<?php echo $namespace;?>cep1',
                                                name: '<?php echo $namespace;?>cep1',
                                                id: '<?php echo $namespace;?>cep1'
                                            },
                                            {
												width: 220,
                                                xtype: 'textfield',
                                                fieldLabel: 'UF',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>estado1',
                                                name: '<?php echo $namespace;?>estado1',
                                                boxMaxWidth: 50,
                                                id: '<?php echo $namespace;?>estado1'
                                            }, 
											{
                                                xtype: 'textarea',
                                                width: 220,
												height: 75,
                                                fieldLabel: 'Obs.',
                                                itemId: '<?php echo $namespace;?>obs1',
                                                name: '<?php echo $namespace;?>obs1',
                                                id: '<?php echo $namespace;?>obs1'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'fieldset',
                                        title: 'Endereço de coleta',
                                        x: 325,
                                        y: 5,
                                        height: 270,
                                        width: 305,
                                        labelWidth: 55,
                                        labelAlign: 'right',
                                        items: [
                                            {
                                                xtype: 'textfield',
	                                        width: 200,
                                                fieldLabel: 'Endereço',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>endereco',
                                                name: '<?php echo $namespace;?>endereco',
                                                id: '<?php echo $namespace;?>endereco'
                                            },
                                            {
                                                xtype: 'textfield',
	                                        width: 295,
                                                fieldLabel: 'Numero',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>numero',
                                                name: '<?php echo $namespace;?>numero',
                                                boxMaxWidth: 50,
                                                id: '<?php echo $namespace;?>numero'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Bairro',
                                                anchor: '100%',
	                                        width: 200,
                                                itemId: '<?php echo $namespace;?>bairro',
                                                name: '<?php echo $namespace;?>bairro',
                                                id: '<?php echo $namespace;?>bairro'
                                            },
                                            {
	                                        width: 200,
                                                xtype: 'textfield',
                                                fieldLabel: 'Cidade',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>cidade',
                                                name: '<?php echo $namespace;?>cidade',
                                                id: '<?php echo $namespace;?>cidade'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Cep',
                                                anchor: '100%',
												style: '<?php echo $pcep;?>',
												width: 200,
                                                itemId: '<?php echo $namespace;?>cep',
                                                name: '<?php echo $namespace;?>cep',
                                                id: '<?php echo $namespace;?>cep'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'UF',
                                                anchor: '100%',
                                                itemId: '<?php echo $namespace;?>estado',
												width: 50,
                                                name: '<?php echo $namespace;?>estado',
                                                id: '<?php echo $namespace;?>estado'
                                            },
                                            {
                                                xtype: 'textarea',
                                                fieldLabel: 'Obs.',
                                                itemId: '<?php echo $namespace;?>obs',
                                                name: '<?php echo $namespace;?>obs',
                                                width: 220,
												height: 75,
                                                id: '<?php echo $namespace;?>obs'
                                            }

                                        ]
                                    }
                                ]
                            },
							{
								xtype: 'panel',
								//layout: 'anchor',
								title: 'Endereço de coleta',
								items: [
									{
										xtype: 'editorgrid',
										id: '<?php echo $namespace;?>gridcoleta',
										store: DadosEndereco<?php echo $namespace;?>,
										title: '',
										hideBorders: true,
										anchor: '100% 100%',
										clicksToEdit: 1,
										x: 0,
										y: 0,
										columns: [
											{
												xtype: 'gridcolumn',
												dataIndex: 'endereco',
												header: 'Endereço',
												sortable: true,
												width: 170,
												editor: {
													xtype: 'textfield'
												}
											},
											{
												xtype: 'numbercolumn',
												align: 'right',
												dataIndex: 'numero',
												header: 'Numero',
												sortable: true,
												width: 60,
												format: '0', 
												editor: {
													xtype: 'numberfield',
													format: '0'
												}
											},
											{
												xtype: 'gridcolumn',
												dataIndex: 'bairro',
												header: 'Bairro',
												sortable: true,
												width: 100,
												editor: {
													xtype: 'textfield'
												}
											},
											{
												xtype: 'gridcolumn',
												dataIndex: 'cidade',
												header: 'Cidade',
												sortable: true,
												width: 100,
												editor: {
													xtype: 'textfield'
												}
											},
											{
												xtype: 'gridcolumn',
												dataIndex: 'cep',
												header: 'Cep',
												sortable: true,
												width: 75,
												editor: {
													xtype: 'textfield'
												}
											},
											{
												xtype: 'gridcolumn',
												dataIndex: 'uf',
												header: 'UF',
												sortable: true,
												width: 30,
												editor: {
													xtype: 'textfield'
												}
											},
											{
												xtype: 'gridcolumn',
												dataIndex: 'referencia',
												header: 'ref',
												sortable: true,
												width: 100,
												editor: {
													xtype: 'textfield'
												}
											}
											
										],
										tbar: {
											xtype: 'toolbar',
											items: [
												{
													xtype: 'button',
													width: 150,
													text: 'Incluir novo endereço',
													handler: function(){
															var Plant = OBJ('<?php echo $namespace;?>gridcoleta').getStore().recordType;
															var p = new Plant({
																id: 0,
																nome: ''
															});
															OBJ('<?php echo $namespace;?>gridcoleta').stopEditing();
															OBJ('<?php echo $namespace;?>gridcoleta').getStore().insert(totalRegistro, p);
															OBJ('<?php echo $namespace;?>gridcoleta').startEditing(totalRegistro, 0);
															totalRegistro = totalRegistro + 1;

													}
												},
												{
													xtype: 'tbfill'
												},
												{
													xtype: 'button',
													width: 150,
													text: 'Excluir endereço',
													handler: function(){													
														SIMNAO('Deseja realmente apagar este registro?', function(){

															objGrid = Ext.getCmp('<?php echo $namespace;?>gridcoleta')
															
															objGrid.stopEditing();
																	
															objRec = objGrid.getSelectionModel().selection.record
															objGrid.getStore().remove(objRec);
															
															totalRegistro = totalRegistro -1;
														});

													}
													
												}
											]
										}
									}
								]
							},
							
                            {
                                xtype: 'panel',
								id: '<?php echo $namespace;?>panel', 
                                title: 'Tabela de preço',
                                layout: 'absolute',
								deferredRender: false,
                                items: [
										
									{
										xtype: 'label',
										text: 'Serviço',
										x: 80,
										y: 12
									},
									{
										xtype: 'label',
										text: 'Código',
										x: 8,
										y: 12
									},
									{
										xtype: 'label',
										text: 'Valor',
										x: 405,
										y: 12
									},
									{
										xtype: 'label',
										text: 'Unidade',
										x: 350,
										y: 12
									},
									{
										xtype: 'textfield',
										x: 8,
										y: 27,
										width: 65,
										selectOnFocus: true,
										itemId: '<?php echo $namespace;?>0codigo',
										name: '<?php echo $namespace;?>0codigo',
										id: '<?php echo $namespace;?>0codigo'
									},
									{
										xtype: 'combo',
										x: 80,
										y: 27,
										width: 265,
										itemId: '<?php echo $namespace;?>0servico',
										name: '<?php echo $namespace;?>0servico',
										store: servico<?php echo $namespace;?>,
										id: '<?php echo $namespace;?>0servico',
										selectOnFocus: true,
										triggerAction: 'all'
									},
									{
										xtype: 'textfield',
										x: 350,
										y: 27,
										disabled: true,
										width: 50,
										selectOnFocus: true,
										itemId: '<?php echo $namespace;?>0unidade',
										name: '<?php echo $namespace;?>0unidade',
										id: '<?php echo $namespace;?>0unidade'
									},
									{
										xtype: 'textfield',
										x: -100,
										y: -100,
										width: 0,
										selectOnFocus: true,
										itemId: '<?php echo $namespace;?>0qtd',
										name: '<?php echo $namespace;?>0qtd',
										id: '<?php echo $namespace;?>0qtd'
									},
									{
										xtype: 'masktextfield',
										x: 405,
										y: 27,
										width: 75,
										itemId: '<?php echo $namespace;?>0valor',
										name: '<?php echo $namespace;?>0valor',
										id: '<?php echo $namespace;?>0valor',
										style: 'text-align: right',
										money: true,
										selectOnFocus: true,
										mask: '#9.999.990,00'
									},
									{
										xtype: 'button',
										text: 'Incluir',
										x: 485,
										y: 27,
										width: 70,
										height: 22,
										id: '<?php echo $namespace;?>incluir',
										handler: addclick<?php echo $namespace;?>
									},
									{
										xtype: 'button',
										text: 'Excluir',
										x: 560,
										y: 27,
										width: 70,
										height: 22,
										id: '<?php echo $namespace;?>excluir',
										handler: removeclick<?php echo $namespace;?>
									},
									{
											xtype: 'fieldset',
											title: 'Serv',
											x: 0,
											y: 50,
											height: 250,
											width: 635,
											labelWidth: 55,
											labelAlign: 'right',
											layout: 'anchor',
											
											items: [
												{
													xtype: 'grid',
													store: gridclienteservico<?php echo $namespace;?>,
													id: '<?php echo $namespace;?>grid',
													width: 610,
													height: 230,
													border: false,
													columns: [
														{
															xtype: 'gridcolumn',
															dataIndex: 'descricao',
															header: 'Serviço',
															sortable: true,
															width: 425,
															editable: false
														},
														{
															xtype: 'gridcolumn',
															dataIndex: 'unidade',
															header: 'Unidade',
															sortable: true,
															width: 65,
															editable: false
														},
														{
															xtype: 'numbercolumn',
															dataIndex: 'valor',
															header: 'Valor',
															sortable: true,
															width: 100,
															align: 'right',
															editable: false
														}
													]
												}
											]
									}
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'button',
                        text: 'Editar',
						id: '<?php echo $namespace;?>cmdEdit',
                        x: 555,
                        y: 375,
                        width: 95,
                        height: 22,
						handler: salvadados<?php echo $namespace;?>
                    },
                    {
                        xtype: 'button',
                        text: 'Cancelar',
                        x: 15,
                        y: 375,
                        width: 95,
                        height: 22,
						handler: function(){
							Ext.getCmp('wb_cadclienteUi<?php echo $namespace;?>').close();
						}
                    }
                ]
            }
        ];
        cadclienteUi.superclass.initComponent.call(this);
    }
});



var wb_cadclienteUi<?php echo $namespace;?> = new cadclienteUi({
	id : 'wb_cadclienteUi<?php echo $namespace;?>',
	name : 'wb_cadclienteUi<?php echo $namespace;?>',
	renderTo: Ext.getBody()
});
wb_cadclienteUi<?php echo $namespace;?>.show();

addtask('wb_cadclienteUi<?php echo $namespace;?>');


//Ext.getCmp('<?php echo $namespace;?>grid').setAutoScroll(true);


Ext.getCmp('<?php echo $namespace;?>0qtd').on('focus', function(){Ext.getCmp('<?php echo $namespace;?>0valor').focus();})

validaDOC<?php echo $namespace;?> = function(){

	strMask = '';
	if (Ext.getCmp('<?php echo $namespace;?>tipo').getValue() == 'J'){
		strMask = '99.999.999/9999-99';
	}else{
		strMask = '999.999.999-99';
	}

	Ext.getCmp('<?php echo $namespace;?>doc').setMask(strMask);
	//alert('teste');
}

Ext.getCmp('<?php echo $namespace;?>tipo').on('blur', validaDOC<?php echo $namespace;?>);

buscaregistro<?php echo $namespace;?> = function(e){
	dados = e.getStore();
	
	valor = dados.data.items[dados.find('field1', e.getValue())].data.field3;
	Ext.getCmp('<?php echo $namespace;?>0valor').setValue(valor);
	
	//console.dir(dados.data);
}

function removeclick<?php echo $namespace;?>(){

	SIMNAO('Deseja remover o serviço selecionado?', function(btn){
		if (btn == 'yes'){
			objGrid = Ext.getCmp('<?php echo $namespace;?>grid');
			Ext.getCmp('<?php echo $namespace;?>0registroapagados').setValue(Ext.getCmp('<?php echo $namespace;?>0registroapagados').getValue() + ', ' + varId);
			
			objRec = objGrid.getSelectionModel().getSelected();			
			objGrid.getStore().remove(objRec);
		}
	});

}

function addclick<?php echo $namespace;?>(){
		
	dados = Ext.getCmp('<?php echo $namespace;?>grid').getStore();
	cboServ = Ext.getCmp('<?php echo $namespace;?>0servico').getStore();

	idservico = cboServ.data.items[cboServ.find('field2', Ext.getCmp('<?php echo $namespace;?>0servico').getValue())].data.field1;
	
	//alert(idservico);
	
	f = 0;
	
	dados.each(function(e){
		if (e.data.idservico == idservico){
			f=1;
		}
	});
	
	
	
	// f = dados.find('idservico', idservico);
	// alert(f);
	
	if (f > 0){
		MSG('Serviço já cadastrado para este cliente.', function(){
			Ext.getCmp('<?php echo $namespace;?>0codigo').focus();
		});
		return false;
	}
	
	
	var myArray=new Array();
	myArray['id']        = '-1';
	myArray['descricao'] = Ext.getCmp('<?php echo $namespace;?>0servico').getRawValue();
	myArray['valor']     = Ext.getCmp('<?php echo $namespace;?>0valor').getValue();
	myArray['idservico'] = idservico;
	myArray['unidade']     = Ext.getCmp('<?php echo $namespace;?>0unidade').getValue();
	
	//Ext.getCmp('<?php echo $namespace;?>0servico').getValue();

	var rec = new Ext.data.Record(myArray);
	dados.add(rec);	
	
	Ext.getCmp('<?php echo $namespace;?>0servico').setValue('');
	Ext.getCmp('<?php echo $namespace;?>0valor').setValue('0,00');
	Ext.getCmp('<?php echo $namespace;?>0codigo').setValue('');
	Ext.getCmp('<?php echo $namespace;?>0unidade').setValue('');
	Ext.getCmp('<?php echo $namespace;?>0codigo').focus();
	
	//Ext.getCmp('<?php echo $namespace;?>grid').setSize(615, 230)
}

Ext.getCmp('<?php echo $namespace;?>rota').expandAll();
Ext.getCmp('<?php echo $namespace;?>0servico').on('select', buscaregistro<?php echo $namespace;?>);
setTimeout("Ext.getCmp('<?php echo $namespace;?>nome').focus();",1000);

function buscaservico<?php echo $namespace;?>(){
	codigo    = Ext.getCmp('<?php echo $namespace;?>0codigo').getValue();
	idcliente = "<?php echo $id;?>";
	
	if (codigo ==''){
		return false;
	}
	
/*	if (idcliente == '0' || idcliente == ''){
		MSG('É Preciso preecher um cliente antes de lançar serviços', function(){
			Ext.getCmp('<?php echo $namespace;?>idcliente').focus();
			});
		return false;
	}*/
	if (id != '' || codigo != ''){
		OpenUrl('./php/wb_buscaproduto.php?acao=cliente&id=0&codigo='+codigo+'&idcliente='+idcliente+'&n=<?php echo $namespace;?>');
	}
	
}

function buscaservicoID<?php echo $namespace;?>(){
	id        = Ext.getCmp('<?php echo $namespace;?>0servico').getValue();
	codigo    = "";
	idcliente = "<?php echo $id;?>";

	if (id == ''){
		return false;
	}
	
	/*if (idcliente == '0' || idcliente == ''){
		MSG('É Preciso preecher um cliente antes de lançar serviços', function(){
			Ext.getCmp('<?php echo $namespace;?>idcliente').focus();
			});
		return false;
	}*/
	
	if (id != '' || codigo != ''){
		OpenUrl('./php/wb_buscaproduto.php?acao=cliente&id='+id+'&codigo='+codigo+'&idcliente='+idcliente+'&n=<?php echo $namespace;?>');
	}
	
}

Ext.getCmp('<?php echo $namespace;?>0servico').on('select', buscaservicoID<?php echo $namespace;?>);
Ext.getCmp('<?php echo $namespace;?>0codigo').on('blur', buscaservico<?php echo $namespace;?>);

Ext.getCmp('<?php echo $namespace;?>grid').setSize(610, 215)
OBJ('<?php echo $namespace;?>gridcoleta').setSize(650, 300);

<?php
	echo "/* Var Dados = $dados */";
	if ($dados == true){
		exibidados($rs->fields, 'codigo, nome, fantasia, tipo, doc, estadual, municipal, contato, email, telefone, fax, celular, inicio, extra, extra2, extra3, endereco, numero, bairro, cidade, estado, cep, endereco1, numero1, bairro1, cidade1, estado1, cep1, reajuste, status, idforma, obs, obs1, idvendedor', $namespace);
	}
?>
validaDOC<?php echo $namespace;?>();
<?php
	if ($id == '' or $id == '0' ){
		echo "Ext.getCmp('".$namespace."codigo').setValue('".Ultimo('codigo', 'tblcliente', $cn)."');";
		echo "Ext.getCmp('".$namespace."status').setValue('0');";
	}else{
		echo "Ext.getCmp('".$namespace."doc').setValue('".$rs->fields['doc']."');";
	}
?>	
//Ext.getCmp('<?php echo $namespace;?>grid').on("refresh", function(){alert('teste');Ext.getCmp('<?php echo $namespace;?>grid').setSize(615, 200)});
//Ext.getCmp('<?php echo $namespace;?>panel').on("activate', function(){alert('teste');});
OBJ('<?php echo $namespace;?>panel').on('click', function(){alert('teste')});
OBJ('<?php echo $namespace;?>0codigo').on('focus', function(){trocaTAB()});
hObj('<?php echo $namespace;?>post', true, 60);