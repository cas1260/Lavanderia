<?php
	session_start();
	include 'wb_funcao.php';
	include 'wb_session_campos.php';
	
	if ($_SESSION['id'] == ''){
		// perdeu a sessão e preciso pedir usuario e senha novamente.
		require_once('wb_expirou.php');
		exit();
	}
	
	@$titulo        = utf8_decode($_GET['titulo']);
	@$w             = $_GET['w'];
	@$h             = $_GET['h'];
	@$tipo          = $_GET['tipo'];
	@$txtpesqbarra = $_GET['txtpesqbarra'];

	$cn = abrebanco();
	
	@$acao      = $_POST['acao'];
	@$namespace = $_GET['namespaceTELA'];
	
	if ($acao == ''){
		@$acao = $_GET['acao'];
	}
	if ($acao == 'delete'){
		$id       = $_GET['id'];
		$tabela   = $_GET['tabela'];
		
		
		$px = inStr(" ", $tabela);
		if ($px != 0){
			$tabela = left($tabela, $px);
		}
		
		if ($tabela == 'tblnf'){
			$cn->execute("delete from tblitennf where idnf in ($id)");
		}
		
		if ($tabela == 'tblromanei'){
			$cn->execute("delete from tblitemromanei where idromanei in ($id)");		
		}
		
		
		$sql      = "delete from $tabela ";
		$xCliente = inStr("where", $tabela);
		if ($xCliente != 0 || $xCliente != ''){
			$sql = $sql . " and ";
		}else{
			$sql = $sql . " where ";
		}
		$sql = $sql . " id in ($id)";
		echo "/* $sql */";
		$cn->execute($sql);
		echo "MSG('Registro apagado com sucesso!', function(){atualizadados".$namespace."()});";
		exit();
	}
	
	verAcesso($cn);
	
	$ncampostexto    = explode(',', $_SESSION[$tipo . '_texto']);
	$ncampostamanho  = explode(',', $_SESSION[$tipo . '_tamanho']);
	$ncampos         = explode(',', $_SESSION[$tipo . '_campos']);
	$php             = $_SESSION[$tipo . '_php'];
	

	$namespace = $tipo . date("Ymd") .  date("His");
	$paginacao = 9999999;
	
	$xCliente = inStr("cliente", $_SESSION[$tipo . '_table']);
	if ($xCliente != 0 || $xCliente != ''){
		$cbo = ",
				{
					'xtype': 'tbtext',
					'text': '&nbsp;',
				}, 
				{
					xtype: 'combo',
					width: 100,
					stateful: true,
					itemId: '".$namespace."Ativo',
					name: '".$namespace."Ativo',
					store: storedstatus".$namespace.",
					forceSelection: true,
					lazyRender: true,
					lazyInit: true,
					triggerAction: 'all',
					allowBlank: false,
					id: '".$namespace."Ativo'
				}";
	}else{
		$cbo = '';
	}
	
?>
//alert('<?php echo strpos($_SESSION[$tipo . '_table'], 'cliente');?>');
storedstatus<?php echo $namespace;?> = [
							[''  , 'Todos'], 
							['-1', 'Aguardando Liberação'], 
							['1' , 'Clientes Ativos'], 
							['0' , 'Clientes Inativos']];

storebanco<?php echo $namespace;?> = Ext.extend(Ext.data.JsonStore, {
	constructor: function(cfg) {
		cfg = cfg || {};
		storebanco<?php echo $namespace;?>.superclass.constructor.call(this, Ext.apply({
			storeId: 'conectabanco<?php echo $namespace;?>',
			url: './php/wb_listadados_stored.php?tipo=<?php echo $tipo;?>',
			autoSave: false,
			autoLoad: true,
			totalProperty: 'results', 
			root: 'rows',
			baseParams : {
							<?php 
							if ($txtpesqbarra != ''){
								echo "desktop : '$txtpesqbarra',  ";
							}?>
							limit: <?php echo $paginacao?>,
							start: 0
						}, 
			fields: [
			<?php 
				for($i = 0; $i < count($ncampos); ++$i){
					$tipocampo = 'auto';
					if (trim($ncampos[$i])=='os'){
						$tipocampo = "number',  align: 'left', format: '0";
					}

					if (trim($ncampos[$i])=='codigo'){
						$tipocampo = "number',  align: 'center', format: '0";
					}

					if (trim($ncampos[$i])=='valor' || trim($ncampos[$i])=='valortotal' || trim($ncampos[$i])== 'precounitario' ){
						$tipocampo = "auto', align: 'right";
					}

					echo "
					{
						name: '".removeAS(trim($ncampos[$i]))."',
						type: '$tipocampo'
					}";
					if ($i+1 != count($ncampos)){
							echo ',';
					}
				}
			?>
			]
		}, cfg));
	}
});
xbanco<?php echo $namespace;?> = new storebanco<?php echo $namespace;?>();

pesquisa<?php echo $namespace;?> = function(){

	Ext.MessageBox.wait('Aguarde, Pesquisando informações no banco de dados.', 'WebFinan');
	
	texto    = Ext.getCmp('txtpesq<?php echo $namespace;?>').getValue();
	filtroem = Ext.getCmp('cbo<?php echo $namespace;?>').getValue();

	//dados = Ext.getCmp('grid<?php echo $namespace;?>').getStore();
	dados = xbanco<?php echo $namespace;?>;
	
	dados.setBaseParam("filtro" ,  filtroem);
	dados.setBaseParam("valor"  ,  texto);
	dados.on('load', function(){Ext.MessageBox.hide()});
	dados.load();
	
	
};

executacao<?php echo $namespace;?> = function(stracao, strid){
	if (stracao == 'delete'){
		if (Ext.getCmp('grid<?php echo $namespace;?>').getSelectionModel().getSelected().data.id != '0'){
			strid = Ext.getCmp('grid<?php echo $namespace;?>').getSelectionModel().getSelected().data.id;
			SIMNAO('Confirma a exclusão deste registro?', function(btn){
				if (btn == 'yes'){
					OpenUrl('php/wb_listaregistros.php?namespaceTELA=<?php echo $namespace;?>&acao='+stracao+'&id='+strid+'&tabela=<?php echo $_SESSION[$tipo . '_table'];?>');
				}});
		}
	}else{
		OpenUrl('php/<?php echo $php?>?acao='+stracao+'&id='+strid);
	}
};

edit<?php echo $namespace;?> = function(){
	varId = Ext.getCmp('grid<?php echo $namespace;?>').getSelectionModel().getSelected().data.id;
	executacao<?php echo $namespace;?>('edit', varId);
}



storedPesquisa<?php echo $namespace;?> = [
			<?php
				$pesquisapadrao = '';
				for($i = 0; $i < count($ncampos); ++$i){
					if (trim($ncampostamanho[$i]) != '0'){
						echo '["'.removeAS($ncampos[$i]).'", "'.removeAS($ncampostexto[$i]).'"]';
						if ($i == 1){
							$pesquisapadrao = removeAS($ncampos[$i]);
						}
						if ($i+1 != count($ncampos)){
							echo ',';
						}
					}
				}
			?>
	];

function imprimirgrid<?php echo $namespace;?>(){
	GRID = Ext.getCmp('grid<?php echo $namespace?>');
	Ext.ux.GridPrinter.print(GRID, "<?php echo $titulo ?>");
}

var objwindow<?php echo $namespace;?> = new Ext.Window({
	minimizable: true,
	id   : 'objwindow<?php echo $namespace;?>',
	name : 'objwindow<?php echo $namespace;?>',
	title: '<?php echo $titulo ?>',
	layout: 'fit',
	anchor : '100% 100%',
	resizable  : true,
	renderTo: Ext.getBody(),
	/*width: <?php echo $w ?>,
	height: <?php echo $h ?>,*/
	width: (screen.width - 160),
	height: (screen.height - 220),
	iconCls: '<?php echo @$_GET['icon'];?>16',
	plain: true,
	modal: false,
	maximizable: true,
	items : [ 
		{
			"xtype": "form",
			"layout": "fit",
			"anchor" : "100% 100%",
			"labelWidth": 100,
			//"layout": "absolute",
			"width": 689,
			"height": 349,
			"padding": 0,
			"border": false,
			"url": "php/wb_salvaregistro.php",
			"headerAsText": true,
			"id": "formpadrao<?php echo $namespace;?>",
			"tbar": {
				"xtype": "toolbar",
				"enableOverflow": false,
				"autoHide": true,
				"autoDestroy": true,
				"items": [       
					{
						"xtype": "button",
						"text": "Incluir novo",
						"iconCls": "novo1",
						"arrowAlign": "right",
						"iconAlign": "left",
						"width": 70,
						"id": "cmdnovo<?php echo $namespace;?>",
						"handler": function(){executacao<?php echo $namespace;?>('add', 0)}
					},
					{
						"xtype": "button",
						"text": "Alterar",
						"arrowAlign": "bottom",
						"iconAlign": "left",
						"width": 70,
						"iconCls" : 'reabrir',
						"id": "cmdAlterar<?php echo $namespace;?>",
						"handler": function(){
							edit<?php echo $namespace;?>();
						}
					},
					{
						"xtype": "button",
						"text": "Excluir",
						"iconCls": "exclui16",
						"arrowAlign": "bottom",
						"iconAlign": "left",
						"width": 70,
						"id": "cmdexcluir<?php echo $namespace;?>",
						"handler": function(){
							executacao<?php echo $namespace;?>('delete')
						}
					},
					{
						xtype: 'menuitem',
						text: 'Imprimir',
						iconCls: 'print16',
						handler: imprimirgrid<?php echo $namespace;?>
					},
					{
						xtype: 'tbfill'
					},
					
					{
						"xtype": "tbtext",
						"text": "Pesquisa em:"
					},
					{
						"xtype": "combo",
						"fieldLabel": "Label",
						"width": 99,
						"typeAhead": true,
						"triggerAction": 'all',
						"lazyRender":true,
						"mode": 'local',
						store: storedPesquisa<?php echo $namespace;?>,
						"id": "cbo<?php echo $namespace;?>"
					},
					{
						"xtype": "tbspacer"
					},
					{
						"xtype": "textfield",
						"fieldLabel": "Label",
						"width": 199,
						"invalidText": "",
						"msgTarget": "title",
						"name": "txtpesq<?php echo $namespace;?>",
						"id": "txtpesq<?php echo $namespace;?>",
						"blankText": "Digita aqui o conteudo a ser pesquisado",
						"emptyText": "Digita aqui o conteudo a ser pesquisado",
						enableKeyEvents: true,
						"value" : "<?php if ($txtpesqbarra != ''){echo "$txtpesqbarra";}?>",
						listeners: {
							scope: this, 
							'keyup': function(txt,e){
								if(e.getKey() == e.ENTER)
								{
									e.stopEvent();
									pesquisa<?php echo $namespace;?>();
								}
							}
						}

					},
					{
						"xtype": "button",
						"text": "Ir",
						"width": 35,
						"handler": pesquisa<?php echo $namespace;?>
					}
				]
			},
			"items": [
				{
					"id" : 'grid<?php echo $namespace;?>',
					"name" : 'grid<?php echo $namespace;?>',
					"xtype": "grid",
					"store": "conectabanco<?php echo $namespace;?>",
					"x": 0,
					"y": 0,
					"columnLines": true,
					"columns": [
					<?php
						for($i = 0; $i < count($ncampos); ++$i){
							if (trim($ncampostamanho[$i]) != '0'){
								echo '
								{
									"xtype": "gridcolumn",
									"header": "'.trim(removeAS($ncampostexto[$i])).'",
									"sortable": true,
									"resizable": true,
									"width": '.trim($ncampostamanho[$i]).',
									"dataIndex": "'.trim(removeAS($ncampos[$i])).'",
									"menuDisabled": true,
									//"fixed": true,
									"id": "grid'.trim(removeAS($ncampos[$i])).'"';
									
									if (trim($ncampos[$i])=='valor' || trim($ncampos[$i])=='valortotal' || trim($ncampos[$i])== 'precounitario'){
										echo ", align: 'right'";
									}
								
								echo '}';
								
								if ($i+1 != count($ncampos)){
									echo ',';
								}
							}
						}
					?>
					]
				}
			]
		}
	]
});

objwindow<?php echo $namespace;?>.show();
addtask('objwindow<?php echo $namespace;?>');

atualizadados<?php echo $namespace;?> = function(){
	Ext.getCmp('txtpesq<?php echo $namespace;?>').focus();
	xbanco<?php echo $namespace;?>.load();
}
objwindow<?php echo $namespace;?>.on('activate', atualizadados<?php echo $namespace;?>, this);
Ext.getCmp('grid<?php echo $namespace;?>').on('dblclick', edit<?php echo $namespace;?>, this)
<?php
if ($pesquisapadrao != ''){?>
	Ext.getCmp('cbo<?php echo $namespace;?>').setValue('<?php echo $pesquisapadrao?>');
<?php
}?>
