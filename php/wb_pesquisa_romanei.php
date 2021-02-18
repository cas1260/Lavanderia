<?php
	session_start();
	include 'wb_funcao.php';

	$cn = abrebanco();

	@$varspace       = $_GET['varspace'];
	@$txtdatainicial = $_POST['txtdatainicial'];
	@$txtdatafinal   = $_POST['txtdatafinal'];
	@$idcliente      = $_POST['idcliente'];
	
	if ($txtdatainicial != '') {

		setValor('txtdatainicial', $txtdatainicial, $cn);
		setValor('txtdatafinal', $txtdatafinal, $cn);
	
		$sql = "select distinct a.os, a.data, a.entrada, a.pedido, a.desconto, a.valortotal, a.solicitante, a.id as idromanei, c.numero as nf from tblromanei a 
					left join tblitennf b on a.id = b.idromanei 
					left join tblnf c on c.id = b.idnf
					 where a.idcliente = $idcliente
					 and data >= '".formatadata($txtdatainicial)."' and data <= '".formatadata($txtdatafinal)."' order by c.numero, a.OS  ";
	
		montastored($sql, $cn);
		exit();
	}
	$idcliente = $_GET['idcliente'];
	
?>	

mydados = Ext.extend(Ext.data.JsonStore, {
    constructor: function(cfg) {
        cfg = cfg || {};
        mydados.superclass.constructor.call(this, Ext.apply({
            storeId: 'MeuDadosRomanei',
            url: 'php/wb_pesquisa_romanei.php',
            root: 'rows',
            totalProperty: 'results',
            autoDestroy: true,
            fields: [
                {
                    name: 'os'
                },
                {
                    name: 'data'
                },
                {
                    name: 'entrada'
                },
                {
                    name: 'pedido'
                },
                {
                    name: 'valortotal'
                },
                {
                    name: 'nf'
                },
                {
                    name: 'solicitante'
                },
                {
                    name: 'idromanei'
                }
            ]
        }, cfg));
    }
});

MYDADOS = new mydados();

MYDADOS.on('load', function(dados){
	
	totalregistro = dados.data.items.length;
	selCol = '';
	for (i=0; i < totalregistro; i++) { // >
		if (dados.data.items[i].data.nf == ''){
			selCol =  selCol + i + ', ';
		}
	}
	selCol = selCol + '0';
	OBJ('gridromanei').getSelectionModel().selectRows(selCol)
	
	Ext.MessageBox.hide()

});

pesquisaRomanei = function(){

	Ext.MessageBox.wait('Aguarde, Pesquisando informações no banco de dados.', 'webLuvas');	

	Dados = OBJ('gridromanei').store;
	Dados.setBaseParam("txtdatainicial",  Ext.getCmp('txtdatainicialPesq').getRawValue());
	Dados.setBaseParam("txtdatafinal",  Ext.getCmp('txtdatafinalPesq').getRawValue());
	Dados.setBaseParam("idcliente",  '<?php echo $idcliente?>');
	Dados.reload();

}

confirmadadosPesqRomanei = function(){
	
	var rows = Ext.getCmp('gridromanei').getSelectionModel().getSelections();
	
	if (rows.length == 0){
		MSG("É necessario selecionar pelo menos um romanei.");
	}else{
		var cont = rows.length-1;
		strId = '';
		dadosGrid = OBJ('<?php echo $varspace?>grid').getStore();
		for(i=0; i<=cont; i++){ //>
			
			rs = rows[i].data;
			
			var myArray=new Array();
			myArray['id']          = -1;
			myArray['idromanei']   = rs.idromanei;
			myArray['os']          = rs.os;
			myArray['data']        = rs.data;
			myArray['entrada']     = rs.entrada;
			myArray['pedido']      = rs.pedido;
			myArray['desconto']    = rs.desconto;
			myArray['valortotal']  = rs.valortotal;
			myArray['solicitante'] = rs.solicitante;

			var rec = new Ext.data.Record(myArray);
			dadosGrid.add(rec);

		}
		recalcula<?php echo $varspace?>();
		OBJ('wWinPesqUi<?php echo $namespace?>').close()
		
	}
	

}

var sm = new Ext.grid.CheckboxSelectionModel({
        singleSelect: false,
        sortable: false,
        checkOnly: true
    });
//var sm = new Ext.grid.CheckboxSelectionModel();

	
wWinPesqUi = Ext.extend(Ext.Window, {
    title: 'Pesquisa de romanei',
    width: 525,
    height: 292,
    modal: true,
    layout: 'absolute',
    initComponent: function() {
        this.items = [
            {
                xtype: 'label',
                text: 'Data Inicial:',
                x: 105,
                y: 10
            },
            {
                xtype: 'label',
                text: 'Data Final:',
                x: 265,
                y: 10
            },
            {
                xtype: 'datefield',
                x: 165,
                y: 5,
				selectOnFocus:true,
                id: 'txtdatainicialPesq'
            },
            {
                xtype: 'datefield',
                x: 325,
                y: 5,
				selectOnFocus:true,
                id: 'txtdatafinalPesq'
            },
            {
                xtype: 'button',
                text: 'Pesquisa',
                height: 22,
                x: 425,
                y: 5,
                width: 80,
                id: 'cmdpesquisa'
            },
            {
                xtype: 'grid',
                title: 'Romaneis',
                x: 5,
                y: 35,
                width: 500,
                height: 195,
                id: 'gridromanei',
				store: 'MeuDadosRomanei',
				selModel: sm , 
                columns: [
					sm, 
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'os',
                        header: 'OS',
                        sortable: true,
                        width: 50
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'data',
                        header: 'Data',
                        sortable: true,
                        width: 100
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'entrada',
                        header: 'Entrada',
                        sortable: true,
                        width: 80
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'pedido',
                        header: 'Pedido',
                        sortable: true,
                        width: 80
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'valortotal',
                        header: 'Valor',
                        sortable: true,
                        width: 80,
                        align: 'right'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'nf',
                        header: 'Nf',
                        sortable: true,
                        width: 80
                    }
                ]
            },
            {
                xtype: 'button',
                text: 'Confirmar',
                x: 310,
                y: 235,
                width: 95,
                id: 'cmdconfirma'
            },
            {
                xtype: 'button',
                text: 'Cancelar',
                x: 410,
                y: 235,
                width: 95,
                id: 'cmdcancelar'
            }
        ];
        wWinPesqUi.superclass.initComponent.call(this);
    }
});


var wWinPesqUi<?php echo $namespace?> = new wWinPesqUi({
	id : 'wWinPesqUi<?php echo $namespace?>',
	name : 'wWinPesqUi<?php echo $namespace?>',
	renderTo: Ext.getBody()
});

wWinPesqUi<?php echo $namespace?>.show();
addtask('wWinPesqUi<?php echo $namespace?>');

OBJ('cmdpesquisa').on('click', pesquisaRomanei);
OBJ('cmdcancelar').on('click', function(){OBJ('wWinPesqUi<?php echo $namespace?>').close();});
OBJ('cmdconfirma').on('click', confirmadadosPesqRomanei);
setTimeout("OBJ('txtdatainicialPesq').focus();",1000);
<?php

$d = getValor('txtdatainicial', $cn);
if ($d != ''){
	echo "OBJ('txtdatainicialPesq').setValue('$d');";
}
$d = getValor('txtdatafinal', $cn);
if ($d != ''){
	echo "OBJ('txtdatafinalPesq').setValue('$d');";
}

?>