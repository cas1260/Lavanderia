<?php
	session_start();
	include 'wb_funcao.php';
	$cn = abrebanco();

	@$acao = $_GET['acao'];
	
	if ($acao == 'salvar'){
		
		$dados    = $_POST['dados'];
			$campo = explode("$!", $dados);
			for($x = 0; $x < count($campo)-1; ++$x){
				$dados = explode("=", $campo[$x]);
				//echo "/* campo = ".$campo[$i]." */";
				
				$ids = explode("z", $dados[0]);
				$ids[0] = right($ids[0], strlen($ids[0])-1);
				//$ids[1] = right($ids[1], strlen($ids[1])-1);
				$sql = "delete from tblapropriacao where idfornecedor = ".$ids[0]." and idmercadoria = " . $ids[1];
				$cn->execute($sql);
				
				echo "/*  $sql */";
				
				$sql = "insert into tblapropriacao (idfornecedor, idmercadoria, valor) values (".$ids[0].", ".$ids[1]." , " . $dados[1] . ")";
				$cn->execute($sql);
				
				echo "/*  $sql */";
			}
		//a2z3=15.5$!a3z3=0$!|
		
	
		exit();
	}
	
	function buscavalor($pidfornecedor, $pidmercadoria, $cn){
		$sql = "select valor from tblapropriacao where idfornecedor = $pidfornecedor and idmercadoria = $pidmercadoria";
		echo " /* $sql */ ";
		$ret = rsRetornacampo($sql, "valor", $cn);
		if ($ret == ''){
			$ret=0;
		}
		return $ret;
	}
	
	@$idprod = $_POST['idprod'];
	if ($idprod != ''){
		$idprod = $idprod . ', 0';
		$sql = "select a.idmercadoria, b.id as idfornecedor, b.nome, c.descricao
						 From tblfornecedormercadoria a 
						inner join tblfornecedor b on a.idfornecedor = b.id
						inner join tblmercadoria c on c.id = a.idmercadoria
						where idmercadoria in ($idprod)"; 
		//echo "/* $sql */";
		$rs = $cn->open($sql);
		
		echo "grid = OBJ('gridRel');";
		echo "grid.destroy();";
		
		//echo "grid.colModel.setConfig([]);";
		
		$columa = '';
		//$valor  = "grid.getStore().loadData([['', ";
		
		$dados  = "var dadosfornecedor = new Ext.data.ArrayStore({fields:[";
		$valor  = '';
		
		$gGrid = "gGrid = {
                xtype: 'editorgrid',
                title: '',
                width: 837,
				id: 'gridRel',
				store: dadosfornecedor,
                height: 338,
                anchor: '100% 100%',
                x: 241,
                y: 0,
                columns: [";
		
		while ($rs->EOF == false){
			
			$nameobj = "a".$rs->fields['idfornecedor'] . "z".$rs->fields['idmercadoria'];
			//$valor = $valor . "'$nameobj' : '".buscavalor($rs->fields['idfornecedor'], $rs->fields['idmercadoria'])."', ";
			$valor = $valor . "'".buscavalor($rs->fields['idfornecedor'], $rs->fields['idmercadoria'], $cn)."', ";
			
			$dados = $dados . "{name:'$nameobj'}, ";
				
			$gGrid = $gGrid . "{
                        xtype: 'numbercolumn',
                        dataIndex: '$nameobj',
                        header: '".$rs->fields['nome']."',
                        sortable: true,
                        width: 300,
                        align: 'center',
                        editor: {
                            xtype: 'numberfield'
                        }
                    }, ";

			
			//$columa = $columa . "grid.addColumn({name: '".$nameobj."', type: 'numbercolumn', defaultValue: 0, header: '".$rs->fields['nome']."', dataIndex: '".$nameobj."', sortable: false, width: 300, editor: {xtype: 'numberfield'}});";
			$rs->MoveNext();
		}
		
		if ($rs->RecordCount() > 0 ){
			$valor = left($valor, strlen($valor)-2);
			$dados = left($dados, strlen($dados)-2);
			$gGrid = left($gGrid, strlen($gGrid)-2);
		}
		
		$gGrid = $gGrid . "]};";
		$dados = $dados . "]});";
		
		
		echo $dados;
		
		echo "dadosfornecedor.loadData([[$valor]]);";
		
		echo $gGrid;
		
		echo "OBJ('wWin').add(gGrid);";
		echo "OBJ('wWin').doLayout();";
		echo "OBJ('gridRel').on('afteredit',salvadadostela );";
		
		exit();
	}
?>

dados = [[0, '', 0, '', '', 0, 0, 0, '']];

var dadosfornecedor = new Ext.data.ArrayStore({
	fields:[
		{name:'fornecedor'}
	]
});

pesquisaTREE = function(node){

	//console.dir(node);
	//return false;
	/*vid = '';
	OBJ('mercadoria').getNodeById(-1).cascade(function(node){
		if (node.attributes.checked==true){
			vid = vid + node.attributes.id + ', ';
		};
	})

	if (vid == ''){
		alert('É preciso selecionar um produto.');
		return false;
	}*/
	
	vid = node.attributes.id;

	Ext.MessageBox.wait('Aguarde, buscando informações do produto/Fornecedor', 'webLuvas');

	Ext.Ajax.request({
				url : 'php/wb_apropriacao.php',
				method: 'POST',
				params : 'idprod='+vid,
				success: function (result, request) {
					xcomando = result.responseText;
					Ext.MessageBox.hide();
					RunJavaScript(xcomando);
				},
				failure: function ( result, request ) {
							alert('Falha ');
				}
				});

	
}

salvadadostela = function(){

	dados = 'dados=' + montasqlURL(OBJ('gridRel').getStore());	
	
	Ext.Ajax.request({
					url : 'php/wb_apropriacao.php?acao=salvar',
					method: 'POST',
					params : dados,
					success: function (result, request) {
						xcomando = result.responseText;
						Ext.MessageBox.hide();
						RunJavaScript(xcomando);
					},
					failure: function ( result, request ) {
								alert('Falha ');
					}
					});
}

teste = function(obj){
	alert('teste');
}

//dadosfornecedor.loadData(sUpDados);

MyWindowUi = Ext.extend(Ext.Window, {
    title: 'Apropriação de valor por fornecedor',
    width: 918,
    height: 506,
    iconCls: 'baixadetitulo16',
    layout: 'absolute',
    modal: true,
    initComponent: function() {
        this.items = [
            {
                xtype: 'editorgrid',
                title: '',
                width: 837,
				id: 'gridRel',
				store: dadosfornecedor,
                height: 338,
                anchor: '100% 100%',
                x: 241,
                y: 0,
                columns: [
                    {
                        xtype: 'numbercolumn',
                        dataIndex: 'number',
                        header: '&nbsp;',
                        sortable: true,
                        width: 0,
                        align: 'right',
                        editor: {
                            xtype: 'numberfield'
                        }
                    }
                ]
            },
			{
				xtype: 'treepanel',
				title: '',
				width: 240,
				height: 450,
				itemId: 'mercadoria',
				x: 0,
				y: 0,
				id: 'mercadoria',
				lines: false,
				rootVisible: false,
				autoScroll: true,
				//anchor: '100px 100%',
				root: new Ext.tree.AsyncTreeNode({
										expanded: true,
										children: [
													{id:'-1',text:'Mercadoria',  leaf: false,  children:[<?php echo montaTreeMercadoria2(0, $cn, "");?>]}
										]
										}),
				loader: new Ext.tree.TreeLoader()
			}
        ];
                this.bbar = {
            xtype: 'toolbar',
            items: [

                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    text: 'Salvar',
                    width: 150,
                    id: 'cmdsalvar'
                },
                {
                    xtype: 'button',
                    text: 'Imprimir',
                    width: 150,
                    id: 'cmdimprmir'
                }
            ]
        };
        MyWindowUi.superclass.initComponent.call(this);
    }
});

var wWin = new MyWindowUi({
	id : 'wWin',
	name : 'wWin',
	renderTo: Ext.getBody()
});
wWin.show();
addtask('wWin');

OBJ('mercadoria').on('click', pesquisaTREE);
//OBJ('cmdpesq').on('click', pesquisaTREE);
OBJ('mercadoria').expandAll();