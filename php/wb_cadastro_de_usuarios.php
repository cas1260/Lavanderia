<?php
	session_start();
	include 'wb_funcao.php';
	
	@$id    = $_GET['id'];
	$dados  = false;
	$cn     = abrebanco();
	@$ref   = $_GET['ref'];
	
	if ($ref != ''){
	
		$tabela = 'tblusuario';
		$sql = montasql($tabela, $id);
		
		$cn->Execute($sql);
		
		if ($id == '0'){
			$id = rsRetornacampo("select max(id) as ultimo from tblusuario", "ultimo", $cn);
		}else{
			//echo '// apaguei tudo na tabela';	
			$cn->execute("delete from tblpermissaoacessomenu where idusuario = $id");
		}
		
		$ids = $_POST[$ref.'0permissao'];
		//echo "// $ids * ". count(explode(",", $ids));
		//exit();
		if ($ids != ''){
			//echo '//entrei no passo 1';
			$reg = explode(",", $ids);
			for ($i = 0 ; $i<count($reg); $i++){
				if ($reg[$i] != ''){
					$sql = "insert into tblpermissaoacessomenu (idmenu, idusuario, checked) values ('".$reg[$i]."', '$id', '1')";
					echo "// $sql". chr(13);
					$cn->execute($sql);
				}
			}
			//echo '//entrei no passo 2';
		}
		echo "MSG('Dados gravado com sucesso!', function(){Ext.getCmp('cadastrousuario".$ref."').close();});";
		exit();
	}
	
	if ($id != '' and $id != '0' ){	
		$rs    = $cn->open("select * from tblusuario where id = $id");
		$dados = true;
	}
?>/*
var grid = new Ext.tree.TreePanel({
  renderTo: 'ext-test',
  frame:true,
  title: 'Tree Panel',
  iconCls: 'icon-basket',
  collapsible:true,
  titleCollapse: true,
  style: 'padding-bottom: 5px',
  loader: new Ext.tree.TreeLoader(),
  rootVisible: false,
  lines: false,
  root: new Ext.tree.AsyncTreeNode({
	id: 'isroot',
	expanded: true,
	children: [
	{
	  id:'1',text:'Menu1',  leaf: false, children:
	  [ {id:'1',text: 'test', leaf: true } ]
	},
	{ id:'2',text:'Menu2',leaf: true }
	]
  })
});
*/

SalvarDados = function(){

	if (Ext.getCmp('<?php echo $namespace?>post').getForm().isValid()==false){
		MSG('Favor, preecher os campos em vermelho.');
		return false;
	}

	var msg = ''
	var selNodes = Ext.getCmp('<?php echo $namespace?>acesso').getChecked();
	Ext.each(selNodes, function(node){
		if(msg.length > 0){
			msg += ', ';
		}
		msg += node.id;
	});
	Ext.getCmp('<?php echo $namespace?>0permissao').setValue(msg);

	salvardados('php/wb_cadastro_de_usuarios.php?ref=<?php echo $namespace?>&id=<?php echo $id?>',Ext.getCmp('<?php echo $namespace?>post').getForm().getFieldValues(true))

	
}

wb_cadusuarioUi = Ext.extend(Ext.Window, {
    title: 'Cadastro de usuarios',
    width: 717,
    height: 354,
    iconCls: 'usuario16',
    layout: 'anchor',
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
				itemId: '<?php echo $namespace?>post',
				anchor: '100% 100%',
				id: '<?php echo $namespace?>post',
                layout: 'absolute',
                items: [
						{
							xtype: 'hidden',
							itemId: '<?php echo $namespace?>0permissao',
							name: '<?php echo $namespace?>0permissao',
							x: 104,
							y: 298,
							id: '<?php echo $namespace?>0permissao'
						},
						{
                        xtype: 'container',
                        x: 0,
                        y: 20,
                        width: 301,
                        height: 259,
                        layout: 'form',
                        labelAlign: 'right',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Login',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>login',
                                name: '<?php echo $namespace?>login',
                                allowBlank: false,
                                id: '<?php echo $namespace?>login'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Senha',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>senha',
                                name: '<?php echo $namespace?>senha',
                                allowBlank: false,
                                id: '<?php echo $namespace?>senha'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Nome',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>Nome',
                                name: '<?php echo $namespace?>Nome',
                                allowBlank: false,
                                id: '<?php echo $namespace?>Nome'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Endereço',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>Endereco',
                                name: '<?php echo $namespace?>Endereco',
                                id: '<?php echo $namespace?>Endereco'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Bairro',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>Bairro',
                                name: '<?php echo $namespace?>Bairro',
                                id: '<?php echo $namespace?>Bairro'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Cidade',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>Cidade',
                                name: '<?php echo $namespace?>Cidade',
                                id: '<?php echo $namespace?>Cidade'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Cep',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>Cep',
                                name: '<?php echo $namespace?>Cep',
                                id: '<?php echo $namespace?>Cep'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'UF',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>UF',
                                name: '<?php echo $namespace?>UF',
                                id: '<?php echo $namespace?>UF'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'E-mail',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>Email',
                                name: '<?php echo $namespace?>Email',
                                allowBlank: false,
                                id: '<?php echo $namespace?>Email'
                            },
                            {
                                xtype: 'textfield',
								plugins: [new Ext.ux.InputTextMask('(99) 9999 - 9999', true)], 
                                fieldLabel: 'Telefone',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>Telefone',
                                name: '<?php echo $namespace?>Telefone',
                                allowBlank: false,
                                id: '<?php echo $namespace?>Telefone'
                            }
                        ]
                    },	
                    {
						xtype: 'treepanel',
						title: 'Acesso',
						x: 311,
						y: 18,
						width: 377,
						height: 255,
						floating: true,
						pathSeparator: '.',
						itemId: '<?php echo $namespace?>acesso',
						id: '<?php echo $namespace?>acesso',
						loader: new Ext.tree.TreeLoader(),
						rootVisible: false,
						lines: true,
        useArrows:true,
        autoScroll:true,
        animate:true,
        //enableDD:true,
        //containerScroll: true,
		//frame: true,

						root: new Ext.tree.AsyncTreeNode({
						expanded: true,
						children: [
									{id:'-1',text:'Cadastro',  leaf: false,  children:[<?php echo montaAcessoUser($id, $cn, "grupo like '1%'");?>]},
									{id:'-2',text:'Financeiro',  leaf: false,  children:[<?php echo montaAcessoUser($id, $cn, "grupo like '2%'");?>]},
									{id:'-3',text:'Relatorio',  leaf: false,  children:[<?php echo montaAcessoUser($id, $cn, "grupo like '3%'");?>]},
									{id:'-4',text:'Configurações',  leaf: false,  children:[<?php echo montaAcessoUser($id, $cn, "grupo like '4%'");?>]},
									{id:'-5',text:'Sistema',  leaf: false,  children:[<?php echo montaAcessoUser($id, $cn, "grupo like '5%'");?>]},
						]
						})
                    },
                    {
                        xtype: 'button',
                        text: 'Salvar',
                        x: 506,
                        y: 286,
                        width: 89,
                        height: 22,
						handler: SalvarDados
                    },
                    {
                        xtype: 'button',
                        text: 'Cancelar',
                        x: 599,
                        y: 285,
                        width: 89,
                        height: 22,
						handler: function(){
							Ext.getCmp('cadastrousuario<?php echo $namespace?>').close();
						}
                    }
                ]
            }
        ];
        wb_cadusuarioUi.superclass.initComponent.call(this);
    }
});


var cadastrousuario<?php echo $namespace?> = new wb_cadusuarioUi({
	id : 'cadastrousuario<?php echo $namespace?>',
	name : 'cadastrousuario<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
cadastrousuario<?php echo $namespace?>.show();

addtask('cadastrousuario<?php echo $namespace?>');


Ext.getCmp('<?php echo $namespace?>acesso').expandAll();
<?php
	if ($dados == true){
		exibidados($rs->fields, 'login, senha, Nome, Email, Endereco, Bairro, Cidade, Cep, UF, Telefone', $namespace);
	}
?>

/*var tree = new Ext.tree.TreePanel({
	renderTo:'tree-div',
	title: 'My Task List',
	height: 300,
	width: 400,
	useArrows:true,
	autoScroll:true,
	animate:true,
	enableDD:true,
	containerScroll: true,
	rootVisible: false,
	frame: true,
	root: {
		nodeType: 'async'
	},
	
	// auto create TreeLoader
	dataUrl: 'php/check-nodes.json',
	
	listeners: {
		'checkchange': function(node, checked){
			if(checked){
				node.getUI().addClass('complete');
			}else{
				node.getUI().removeClass('complete');
			}
		}
	},
	
	buttons: [{
		text: 'Get Completed Tasks',
		handler: function(){
			var msg = '', selNodes = tree.getChecked();
			Ext.each(selNodes, function(node){
				if(msg.length > 0){
					msg += ', ';
				}
				msg += node.text;
			});
			Ext.Msg.show({
				title: 'Completed Tasks', 
				msg: msg.length > 0 ? msg : 'None',
				icon: Ext.Msg.INFO,
				minWidth: 200,
				buttons: Ext.Msg.OK
			});
		}
	}]
});

tree.getRootNode().expand(true);



MyWindowUi = Ext.extend(Ext.Window, {
    title: 'My Window',
    width: 630,
    height: 486,
    layout: 'absolute',
    initComponent: function() {
        this.items = [
            {
                xtype: 'container',
                width: 450,
                height: 270,
                x: 30,
                y: 60,
                items: [
                    {
                        xtype: 'checkbox',
                        boxLabel: 'BoxLabel'
                    },
					tree
                ]
            }
        ];
        MyWindowUi.superclass.initComponent.call(this);
    }
});


var x = new MyWindowUi({
	id : 'xxx',
	name : 'xxx',
	renderTo: Ext.getBody()
});
x.show();
*/