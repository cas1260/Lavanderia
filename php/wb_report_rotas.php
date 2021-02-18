<?php
	session_start();
	include 'wb_funcao.php';
	
	$cn = abrebanco();	
	$id = '0';

	@$acao      = $_POST['acao'];
	@$idrota    = $_POST['idrota'];
	@$bairro    = $_POST['bairro'];
	@$municipio = $_POST['municipio'];
	@$grupo     = $_POST['grupo'];
	
	if ($acao != ''){
	
		$sql = "SELECT distinct a.id, a.nome as cliente FROM tblcliente a left join tblrotacliente b on a.id = b.idcliente 
										              left join tblrota c on b.idrota = c.id
		where a.id <> 0 ";
		
		if ($idrota != ''){
			$sql = $sql . " and b.idrota in ($idrota) ";
		}
		if ($bairro != ''){
			$sql = $sql . " and (a.bairro like '%".utf8_decode($bairro). "%' or a.bairro1 like '%".utf8_decode($bairro)."%') ";
		}

		if ($municipio != ''){
			$sql = $sql . " and (a.cidade = '".utf8_decode($municipio)."' or a.cidade1 = '".utf8_decode($municipio). "') ";
		}

		if ($grupo != ''){
			$sql = $sql . " and c.grupo = '$grupo'";
		}
		$sql = $sql . " order by a.nome ";
//		echo "/* $sql */";
		montastored($sql, $cn);
		exit();
	}
	
?>


bairro<?php echo $namespace?>    = <?php rsdados("SELECT DISTINCT bairro as valor, bairro as bairro    FROM tblcliente UNION SELECT DISTINCT bairro1 as valor, bairro1 as bairro FROM tblcliente order by bairro", $cn)?>
municipio<?php echo $namespace?> = <?php rsdados("SELECT DISTINCT cidade as valor, cidade as municipio FROM tblcliente UNION SELECT DISTINCT cidade1 as valor, cidade1 as municipio FROM tblcliente order by municipio", $cn)?>
grupo<?php echo $namespace?>     = <?php rsdados("SELECT distinct grupo, grupo as descricao from tblrota order by grupo", $cn)?>

gridcliente = Ext.extend(Ext.data.JsonStore, {
    constructor: function(cfg) {
        cfg = cfg || {};
        gridcliente.superclass.constructor.call(this, Ext.apply({
            storeId: 'gridcliente<?php echo $namespace;?>',
            totalProperty: 'results',
            root: 'rows',
            url: 'php/wb_report_rotas.php',
            autoLoad: false,
            autoDestroy: true,
            fields: [{name:'id'}, {name:'cliente'}]
        }, cfg));
    }
});

xdados<?php echo $namespace?> = new gridcliente();
xdados<?php echo $namespace?>.on('load', function(){
	Ext.MessageBox.hide()
});


MyWinReport = Ext.extend(Ext.Window, {
    title: 'Relatório de rotas',
    width: 811,
    height: 464,
    layout: 'anchor',
    itemId: '<?php echo $namespace?>WinRel',
    id: '<?php echo $namespace?>WinRel',
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                width: 700,
                height: 430,
                layout: 'absolute',
                anchor: '100% 100%',
                itemId: '<?php echo $namespace?>post',
                x: 200,
                y: 150,
                id: '<?php echo $namespace?>post',
                items: [
                    {
                        xtype: 'container',
                        width: 350,
                        height: 90,
                        x: -1,
                        y: 10,
                        layout: 'form',
                        labelAlign: 'right',
                        labelWidth: 70,
                        items: [
                            {
                                xtype: 'combo',
                                fieldLabel: 'Bairro',
                                anchor: '100%',
                                triggerAction: 'all',
                                itemId: '<?php echo $namespace?>cbobairro',
                                name: '<?php echo $namespace?>cbobairro',
                                store: bairro<?php echo $namespace?>,
                                id: '<?php echo $namespace?>cbobairro'
                            },
                            {
                                xtype: 'combo',
                                fieldLabel: 'Municipio',
                                anchor: '100%',
								triggerAction: 'all',
                                itemId: '<?php echo $namespace?>municipio',
                                name: '<?php echo $namespace?>municipio',
                                store: municipio<?php echo $namespace?>,
                                id: '<?php echo $namespace?>municipio'
                            },
                            {
                                xtype: 'combo',
                                fieldLabel: 'Grupo',
                                anchor: '100%',
								triggerAction: 'all',
                                itemId: '<?php echo $namespace?>grupo',
                                name: '<?php echo $namespace?>grupo',
                                store: grupo<?php echo $namespace?>,
                                id: '<?php echo $namespace?>grupo'
                            }
                        ]
                    },
                    {
						xtype: 'treepanel',
						title: '',
                        width: 430,
                        height: 75,
                        x: 355,
                        y: 10,
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
                        xtype: 'button',
                        text: 'Buscar',
                        x: 665,
                        y: 95,
                        width: 120,
                        id: '<?php echo $namespace?>cmdBusca'
                    },
                    {
                        xtype: 'button',
                        text: 'Imprimir',
                        x: 535,
                        y: 95,
                        width: 120,
                        id: '<?php echo $namespace?>cmdImprimir'
                    },
                    {
                        xtype: 'grid',
                        title: 'Resultado do filtro',
						store: 'gridcliente<?php echo $namespace;?>',
                        x: 10,
                        y: 130,
                        width: 775,
                        height: 290,
                        id: '<?php echo $namespace;?>gridReport',
                        columns: [
                            {
                                xtype: 'gridcolumn',
                                dataIndex: 'cliente',
                                header: 'Cliente',
                                sortable: true,
                                width: 750
                            }
                        ]
                    }
                ]
            }
        ];
        MyWinReport.superclass.initComponent.call(this);
    }
});

var WB_REPORT<?php echo $namespace;?> = new MyWinReport({
	id : 'WB_REPORT<?php echo $namespace;?>',
	name : 'WB_REPORT<?php echo $namespace;?>',
	renderTo: Ext.getBody()
});
WB_REPORT<?php echo $namespace;?>.show();

addtask('WB_REPORT<?php echo $namespace;?>');
OBJ('<?php echo $namespace;?>rota').expandAll();
setTimeout("Ext.getCmp('<?php echo $namespace?>cbobairro').focus();",1000);

exibirCliente<?php echo $namespace?> = function(){
	var msg = ''
	var selNodes = Ext.getCmp('<?php echo $namespace;?>rota').getChecked();
	Ext.each(selNodes, function(node){
		if(msg.length > 0){
			msg += ', ';
		}
		msg += node.id;
	});
	
	Ext.MessageBox.wait('Aguarde, Pesquisando informações no banco de dados.', 'Webluvas');
	//dados = OBJ('<?php echo $namespace;?>gridReport').getStore()
	dados = xdados<?php echo $namespace?>;
	dados.setBaseParam('acao', 'pesq');
	dados.setBaseParam('idrota', msg);
	dados.setBaseParam('bairro', OBJ('<?php echo $namespace?>cbobairro').getValue());
	dados.setBaseParam('municipio', OBJ('<?php echo $namespace?>municipio').getValue());
	dados.setBaseParam('grupo', OBJ('<?php echo $namespace?>grupo').getValue());
	dados.reload();
}

OBJ('<?php echo $namespace?>cmdBusca').on('click', exibirCliente<?php echo $namespace?>);
OBJ('<?php echo $namespace?>cmdImprimir').on('click', function(){
	GRID = Ext.getCmp('<?php echo $namespace;?>gridReport');
	Ext.ux.GridPrinter.print(GRID, "Rota de clientes");
});