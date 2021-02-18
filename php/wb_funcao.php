<?php
@session_start();
@header("Pragma: no-cache");
@header ('Content-type: text/html; charset=ISO-8859-1',true);
//header ("Content-Type: text/html; charset=ISO-8859-1",true); 

include "adodb5/adodb.inc.php";
include "config/setvar.php";
include "wb_class.php";

/*session_register("nome");
session_register("login");
session_register("id");
session_register("IdCliente_session");
session_register("Nome_session");
session_register("ip_session"); 
*/

$namespace = date("Ymd") .  date("His");


function getValor($strNome, $cn){
	@$valor = rsRetornacampo("select valor from tblpropriedades where nome = '$strNome'", "valor", $cn);
	return $valor;
}

function setValor($strNome, $strValor, $cn){
	$rs = $cn->open("select valor from tblpropriedades where nome = '$strNome'");
	if ($rs->RecordCount() != 0){
		$sql = "update tblpropriedades set valor = '$strValor' where nome = '$strNome'";
	}else{
		$sql = "insert into tblpropriedades (nome, valor) values ('$strNome','$strValor')";
	}
	$cn->open($sql);
}

function formatarCPF_CNPJ($campo, $formatado = true){
	//retira formato
	@$codigoLimpo = ereg_replace("[' '-./ t]",'',$campo);
	// pega o tamanho da string menos os digitos verificadores
	$tamanho = (strlen($codigoLimpo) -2);
	//verifica se o tamanho do código informado é válido
	if ($tamanho != 9 && $tamanho != 12){
		return false;
	}

	if ($formatado){
		// seleciona a máscara para cpf ou cnpj
		$mascara = ($tamanho == 9) ? '###.###.###-##' : '##.###.###/####-##'; 

		$indice = -1;
		for ($i=0; $i < strlen($mascara); $i++) {
			if ($mascara[$i]=='#') $mascara[$i] = $codigoLimpo[++$indice];
		}
		//retorna o campo formatado
		$retorno = $mascara;

	}else{
		//se não quer formatado, retorna o campo limpo
		$retorno = $codigoLimpo;
	}

	return $retorno;

}

function pesquisacodigo($pcodigo, $cn, $tabela, $id){
	if ($id > 0){
		$rs = $cn->open("select codigo from $tabela where codigo = '$pcodigo' and id <> $id");
	}else{
		$rs = $cn->open("select codigo from $tabela where codigo = '$pcodigo'");
	}
	if ($rs->EOF == false){
		echo "MSG('O código $pcodigo já encontra-se cadastro no sistema.');";
		return false;
	}
	return true;
}

function Ultimo($campo, $tabela, $cn){
	
	@$result = rsRetornacampo("select max(cast($campo as SIGNED)) as ultimoReg from $tabela", "ultimoReg", $cn);
	if ($result == ''){
		$result = 0;
	}
	$result = $result+1;
	return $result;
}

function removeAS($strnome){
	
	$i = inStr(' as ', $strnome); 
	
	if ($i > 0){
		return right( $strnome, strlen($strnome) - $i-4);
	}else{
		return $strnome;
	}
	
}

function erro($strTexto){

	echo "wbErroUi = Ext.extend(Ext.Window, {
			title: 'Erro no sistema',
			width: 736,
			height: 372,
			modal: true,
			resizable: false,
			autoScroll: true,
			layout: 'absolute',
			initComponent: function() {
				this.items = [
					{
						xtype: 'container',
						width: 120,
						height: 90,
						x: 10,
						y: 30,
						html: '<Img src = \'images/process-stop.png\' border = 0>'
					},
					{
						xtype: 'container',
						width: 560,
						height: 270,
						x: 150,
						y: 20,
						autoScroll: true,
						html: '" . str_replace(chr(10) , '',str_replace(chr(13) , '<br>', str_replace("'", '', $strTexto)))  .  "'
					},
					{
						xtype: 'button',
						text: 'Fechar a janela',
						x: 600,
						y: 310,
						width: 110,
						handler: function(){Ext.getCmp('WinErro').close();}
					}
				];
				wbErroUi.superclass.initComponent.call(this);
			}
		});

		var JanelaErro = new wbErroUi({	id : 'WinErro', renderTo: Ext.getBody()});
		JanelaErro.show();
		";
	exit();
}

function info($strTexto){

	echo "wbErroUi = Ext.extend(Ext.Window, {
			title: 'WebFinan - Informativo',
			width: 736,
			height: 372,
			modal: true,
			resizable: false,
			autoScroll: true,
			layout: 'absolute',
			initComponent: function() {
				this.items = [
					{
						xtype: 'container',
						width: 120,
						height: 90,
						x: 10,
						y: 30,
						html: '<Img src = \'images/computer.png\' border = 0>'
					},
					{
						xtype: 'container',
						width: 560,
						height: 270,
						x: 150,
						y: 20,
						autoScroll: true,
						html: '" . str_replace(chr(10) , '',str_replace(chr(13) , '<br>', str_replace("'", '', $strTexto)))  .  "'
					},
					{
						xtype: 'button',
						text: 'Fechar a janela',
						x: 600,
						y: 310,
						width: 110,
						handler: function(){Ext.getCmp('WinErro').close();}
					}
				];
				wbErroUi.superclass.initComponent.call(this);
			}
		});

		var JanelaErro = new wbErroUi({	id : 'WinErro', renderTo: Ext.getBody()});
		JanelaErro.show();
		";
}

function Randomizar($iv_len)
{
    $iv = '';
    while ($iv_len-- > 0) {
        $iv .= chr(mt_rand() & 0xff);
    }
    return $iv;
}

function Encriptar($texto, $senha, $iv_len = 16)
{
    $texto .= "\x13";
    $n = strlen($texto);
    if ($n % 16) $texto .= str_repeat("\0", 16 - ($n % 16));
    $i = 0;
    $Enc_Texto = Randomizar($iv_len);
    $iv = substr($senha ^ $Enc_Texto, 0, 512);
    while ($i < $n) {
        $Bloco = substr($texto, $i, 16) ^ pack('H*', md5($iv));
        $Enc_Texto .= $Bloco;
        $iv = substr($Bloco . $iv, 0, 512) ^ $senha;
        $i += 16;
    }
    return base64_encode($Enc_Texto);
}

function Desencriptar($Enc_Texto, $senha, $iv_len = 16)
{
    $Enc_Texto = base64_decode($Enc_Texto);
    $n = strlen($Enc_Texto);
    $i = $iv_len;
    $texto = '';
    $iv = substr($senha ^ substr($Enc_Texto, 0, $iv_len), 0, 512);
    while ($i < $n) {
        $Bloco = substr($Enc_Texto, $i, 16);
        $texto .= $Bloco ^ pack('H*', md5($iv));
        $iv = substr($Bloco . $iv, 0, 512) ^ $senha;
        $i += 16;
    }
    return preg_replace('/\\x13\\x00*$/', '', $texto);
}
function URLopen($url){
        // Fake the browser type
        ini_set('user_agent','MSIE 4\.0b2;');

        $dh = fopen("$url",'r');
        $result = fread($dh,8192);                                                                                                                            
        return $result;
}


function verAcesso($cn){

	if ($_SESSION['id'] == ''){
		// perdeu a sessão e preciso pedir usuario e senha novamente.
		require_once('wb_expirou.php');
		exit();
	}

	@$pagina = $_SERVER["SCRIPT_NAME"];
	@$tipo   = $_GET['tipo'];
	if ($tipo == ''){
		@$tipo   = $_GET['acao'];
	}
	
	$posicao =strrpos(substr($pagina,0,strlen($pagina)),'/');
	
	if ($posicao != 0){
		$pagina = right($pagina, strlen($pagina) - ($posicao+1));
	}
	/*

	$sql = "select count(*) as totalreg From tblacessomenu a, 
				    tblpermissaoacessomenu b where b.idmenu = a.id and b.idusuario = ".$_SESSION['id']." 
					and  pagina = '$pagina' and checked = '1'";
					
	if ($tipo != ''){
		$sql = $sql . " and acao = '$tipo' ";
	}
	$total = rsRetornacampo($sql, "totalreg", $cn);
	
	if ($total == '0'){
		echo "MSG('Acesso negado a esta rorina!');";
		exit();
	}*/
}

function montaTreeCliente($idcliente, $cn, $where, $check='false'){
	$sql = "select id, nome from tblcliente order by nome";
	
	if ($where != ''){
		$sql = $sql . " where ".$where;
	}
	// echo "/* $sql */"; 
	$rs = $cn->open($sql);
	$result = '';
	while ($rs->EOF == false){
		$result = $result . "{id:'".$rs->fields['id']."',text: '".$rs->fields['nome']."', leaf: true , iconCls: 'cliente16', ";
		$result = $result . "checked: $check";
		$result = $result . "},";
		$rs->MoveNext();
	}
	$result = left($result, strlen($result)-1);
	return $result;
}

function montaTreeRota($idcliente, $cn, $where){
	$sql = "select distinct a.*, b.checked
			From tblrota a left join tblrotacliente b on
			a.id = b.idrota and b.idcliente = $idcliente";
	
	if ($where != ''){
		$sql = $sql . " where ".$where;
	}
	// echo "/* $sql */"; 
	$rs = $cn->open($sql);
	$result = '';
	while ($rs->EOF == false){
		$result = $result . "{id:'".$rs->fields['id']."',text: '".$rs->fields['descricao']."', leaf: true , iconCls: 'camposextras16', ";
		//echo "// ".$rs->fields['checked'];
		if ($idcliente =='0'){
			$result = $result . "checked: false";
		}else{
			if ($rs->fields['checked'] == '1') {
				$result = $result . "checked: true";
			}else{
				$result = $result . "checked: false";
			}
		}
		$result = $result ."},";
		$rs->MoveNext();
	}
	$result = left($result, strlen($result)-1);
	return $result;
}


function montaTreeMercadoria2($idfornecedor, $cn, $where){
	$sql = "select distinct a.*, b.checked
			From tblmercadoria a left join tblfornecedormercadoria b on
			a.id = b.idmercadoria and b.idfornecedor = $idfornecedor";
	
	if ($where != ''){
		$sql = $sql . " where ".$where;
	}

	$rs = $cn->open($sql);
	$result = '';
	while ($rs->EOF == false){
		$result = $result . "{id:'".$rs->fields['id']."',text: '".$rs->fields['descricao']."', leaf: true , iconCls: 'mercadoria16'},";
		$rs->MoveNext();
	}
	$result = left($result, strlen($result)-1);
	return $result;
}

function montaTreeMercadoria($idfornecedor, $cn, $where){
	$sql = "select distinct a.*, b.checked
			From tblmercadoria a left join tblfornecedormercadoria b on
			a.id = b.idmercadoria and b.idfornecedor = $idfornecedor";
	
	if ($where != ''){
		$sql = $sql . " where ".$where;
	}

	$rs = $cn->open($sql);
	$result = '';
	while ($rs->EOF == false){
		$result = $result . "{id:'".$rs->fields['id']."',text: '".$rs->fields['descricao']."', leaf: true , iconCls: 'mercadoria16' ,";
		//echo "// ".$rs->fields['checked'];
		if ($idfornecedor =='0'){
			$result = $result . "checked: false";
		}else{
			if ($rs->fields['checked'] == '1') {
				$result = $result . "checked: true";
			}else{
				$result = $result . "checked: false";
			}
		}
		$result = $result ."},";
		$rs->MoveNext();
	}
	$result = left($result, strlen($result)-1);
	return $result;
}


function montaTreeServico($idcliente, $cn, $where, $check = 'false'){
	$sql = "select id, descricao from tblservicos order by descricao";
	
	if ($where != ''){
		$sql = $sql . " where ".$where;
	}
	// echo "/* $sql */"; 
	$rs = $cn->open($sql);
	$result = '';
	while ($rs->EOF == false){
		$result = $result . "{id:'".$rs->fields['id']."',text: '".$rs->fields['descricao']."', leaf: true , iconCls: 'servico16', ";
		$result = $result . "checked: $check";
		$result = $result ."},";
		$rs->MoveNext();
	}
	$result = left($result, strlen($result)-1);
	return $result;
}


function DiferenciaEntreDatas($data1, $data2){

	$data1 = strtotime($data1);
	$data2 = strtotime($data2);
	
	$dia = date('d', $data1);
	$mes = date('m', $data1);
	$ano = date('y', $data1);

	$Xdata1 = ($mes * 30) + (($ano * 12) * 30);
	

	$dia = date('d', $data2);
	$mes = date('m', $data2);
	$ano = date('y', $data2);

	$Xdata2 = ($mes * 30) + (($ano * 12) * 30);
	
	$diastotal = (($Xdata1 - $Xdata2));
	$diastotal = (($Xdata1 - $Xdata2) / 30);
	
	return  $diastotal;
}

function strzero($pValor, $pTamanho){

	if ($pTamanho<=strlen($pValor)){
		$function_ret=$pValor;
	}else{
		if (!isset($pValor)){
			$function_ret = $pValor;
		}else{
			$function_ret = str_repeat("0",$pTamanho-strlen($pValor)) . $pValor;
		} 
    } 

	//$function_ret = str_repeat("0",$pTamanho-strlen($pValor)) . $pValor;
	return $function_ret;

}

function fn($valor){
	return number_format($valor, 2, ',', '.');
}

function primeiroDiaMes($data=""){
    if (!$data) {
       $dia = '1';
       $mes = date("m");
       $ano = date("Y");
    } else {
       $dia = '1';
       $mes = date("m",$data);
       $ano = date("Y",$data);
    }
    $data = "$mes/$dia/$ano";
    return $data;
}

function ultimoDiaMes($data=""){
    if (!$data) {
       $dia = date("d");
       $mes = date("m");
       $ano = date("Y");
    } else {
       $dia = date("d",$data);
       $mes = date("m",$data);
       $ano = date("Y",$data);
    }
    $data = mktime(0, 0, 0, ($mes+1), 1, $ano);
    return ($mes) . '/' . date("d",$data-1) . '/'.$ano;
  }
  
function rsRetornacampo($sql, $campo, $cn){
	//echo "/*$sql*/";
	$rsRetornacampoX  = $cn->open($sql);
	if ($rsRetornacampoX->EOF == true){
		return '';
	}else{
		return $rsRetornacampoX->fields[$campo];
	}
}

function formatadata($pData){
	// Delimiters may be slash, dot, or hyphen
	
	
	if (strrpos($pData, 'T') > 0){
		$valor = explode('T', $pData);
		$data1 = explode('-', $valor[0]);
		if ($data1[0] > 1900){
			$year   = $data1[0];
			$month  = $data1[1];
			$day    = $data1[2];
		}else{
			$year   = $data1[2];
			$month  = $data1[0];
			$day    = $data1[1];	
		}
	}else{
		if (strrpos($pData, '/') > 0){
			$data1 = explode('/', $pData);
			$year   = $data1[2];
			$month  = $data1[1];
			$day    = $data1[0];	
		}else{
			if (strrpos($pData, '-') > 0){
				$data1 = explode('-', $pData);
				$year   = $data1[0];
				$month  = $data1[1];
				$day    = $data1[2];	
			}
		}
	}
	//list($day, $month , $year) = split('[/.-]', $pData);
	
	if (strlen($year)==2){
		$data_formatadata = '20'.$year . '-' . $month . '-'. $day;
	}else{
		$data_formatadata = $year . '-' . $month . '-'. $day;	
	}
	return $data_formatadata; 
}

function datediff($startDate, $endDate){
	
	/*//echo "startDate = " . $startDate;
	//echo "endDate = " . $endDate;

	$startDate = strtotime($startDate);
	$endDate = strtotime($endDate);
	
	$diasInicio = (date("Y", $startDate)*365); // + (date("M", $startDate)*30)+ (date("D", $startDate)) ;
	$diasFim    = (date("Y", $endDate)*365); // + (date("M", $endDate)*30)+ (date("D", $endDate)) ;
	*/
	//Datas no formato mm/dd/aaaa
	 $datainicio=strtotime($startDate);
	 $datafim  =strtotime($endDate);
	 $intervalo=($datafim-$datainicio)/86400; //transformação do timestamp em dias

	return ($intervalo);
} 

function removeCHR($pValor){
	$xvalor = str_replace('/', "", $pValor);
	$xvalor = str_replace('-', '', $xvalor);
	return $xvalor;
}

function formatadataBR($pData){
	// Delimiters may be slash, dot, or hyphen
	
	//list($pData1, $pData2) = split('[T]', $pData);
	
	$pData1 = left($pData, 10);

	if (strrpos($pData, '/') > 0){
		$data1 = explode('/', $pData);
		$year   = $data1[2];
		$month  = $data1[1];
		$day    = $data1[0];	
	}else{
		$data1 = explode('-', $pData);
		$year   = $data1[0];
		$month  = $data1[1];
		$day    = $data1[2];	
	}

	//list($year, $month, $day) = split('[/.-]', $pData1);
	return $day . '/' . $month . '/'.  $year ;
}
//echo "Month: $month; Day: $day; Year: $year<br />\n";


function sDados($pPhp, $pName, $namespace, $pcampo){
	$nomefuncao = $pname . $namespace;
	echo $nomefuncao . ' = Ext.extend(Ext.data.JsonStore, {';
	echo 'constructor: function(cfg) {';
	echo 'cfg = cfg || {};';
	echo $nomefuncao.'.superclass.constructor.call(this, Ext.apply({';
	echo 'storeId: "'.$nomefuncao.'",';
	echo 'url: "php/'.$nomefuncao.'",';
	echo 'autoSave: false,';
	echo 'autoLoad: true,';
	echo 'totalProperty: "results", ';
	echo 'root: "rows",';
	echo 'baseParams : {';
	echo 'limit: 9999,';
	echo 'start: 0';
	echo '}, ';
	echo 'fields: [';
	echo montacampostored($pcampo);
	echo ']		}, cfg));';
	echo '}';
	echo '});';
	echo 'new $nomefuncao<?php echo $namespace?>();';
}

function rsdados($sql, $cn, $extra = ''){
		//echo $sql;
		$rsdados = $cn->open($sql);
		echo "[";
		$linha = '';
		$totalregistro = $rsdados->RecordCount();

		if ($extra != ''){
			if ($totalregistro == 0){
				echo left($extra, strlen($extra)-2);
			}else{
				echo $extra;
			}
		}
	
		for ($y = 1; $y <= $totalregistro; $y++) {		
			$xregistro = $rsdados->fields;
			$linha = $linha . '[';
			while (list($nome, $valor) = each($xregistro)) { 
				if (is_numeric($nome) == false){
				
					$xvalor = $rsdados->fields[$nome];
					$xvalor = str_replace("'", '', $xvalor);
					$xvalor = str_replace(chr(13), "'+String.fromCharCode(13)+'", $xvalor);
					$xvalor = str_replace(chr(10), "'+String.fromCharCode(10)+'", $xvalor);
					
				
					if ($nome == 'vencimento' || $nome == 'data' || $nome == 'emissao' || $nome == 'entrada'){
						$linha = $linha . "'" .  str_replace("'", '', formatadataBR($xvalor)) . "', " ;
					}else{
						$linha = $linha . "'" .  str_replace("'", "", $xvalor) . "', " ;
					}
				}
			}
			$linha = left($linha, strlen($linha)-2);
			$linha = $linha . "], ";
			$rsdados->MoveNext();
		};
		$linha = left($linha, strlen($linha)-2);
		echo $linha;
		echo "];";
		return $rsdados;

}
function rsdados1($sql, $cn, $extra = ''){

		$rsdados = $cn->open($sql);
		echo "[";
		$linha = '';
		$totalregistro = $rsdados->RecordCount();

		if ($extra != ''){
		
			echo $extra;
		}
		//$rsdados->MoveLast();
		//$rsdados->Move($totalregistro);
		for ($y = $totalregistro - 1; $y >= 0; $y--) {		
			$rsdados->Move($y);
			$xregistro = $rsdados->fields;
			$linha = $linha . '[';
			while (list($nome, $valor) = each($xregistro)) { 
				if (is_numeric($nome) == false){
					if ($nome != 'vencimento' && $nome != 'data' && $nome != 'emissao'){
						$linha = $linha . "'" .  str_replace("'", '', $rsdados->fields[$nome]) . "', " ;
					}else{
						$linha = $linha . "'" .  str_replace("'", '', formatadata($rsdados->fields[$nome])) . "', " ;
					}
				}
			}
			$linha = left($linha, strlen($linha)-2);
			$linha = $linha . "], ";
			
		};
		$linha = left($linha, strlen($linha)-2);
		echo $linha;
		echo "];";
		return $rsdados;

}

function rsdados2($sql, $cn, $extra = ''){
		//echo $sql;
		$rsdados = $cn->open($sql);
		echo "[";
		$linha = '';
		$totalregistro = $rsdados->RecordCount();

		if ($extra != ''){
			if ($totalregistro == 0){
				echo left($extra, strlen($extra)-2);
			}else{
				echo $extra;
			}
		}
	
		for ($y = 1; $y <= $totalregistro; $y++) {		
			$xregistro = $rsdados->fields;
			$linha = $linha . '[';
			while (list($nome, $valor) = each($xregistro)) { 
				if (is_numeric($nome) == false){
					if ($nome != 'vencimento' && $nome != 'data'){
						$linha = $linha . "'" .  str_replace("'", "", $rsdados->fields[$nome]) . "', " ;
					}else{
						$linha = $linha . "'" .  str_replace("'", '', formatadata($rsdados->fields[$nome])) . "', " ;
					}
				}
			}
			$linha = left($linha, strlen($linha)-2);
			$linha = $linha . "], ";
			$rsdados->MoveNext();
		};
		$linha = left($linha, strlen($linha)-2);
		echo $linha;
		echo "]";
		return $rsdados;

}
function listadados($sql, $cn){

	$resultado = mysql_query($sql, $cn);
	echo "<table border = 01 class = 'tabela'><tr>";
	$registro = mysql_fetch_array($resultado);
	$xarray = $registro; 

	while (list($nome, $valor) = each($xarray)) { 
		if (!is_numeric($nome)){
			echo "<td>$nome</td>";
		}
	}
	echo "</tr>";

	mysql_data_seek($resultado, 0);
	
	while($registro = mysql_fetch_array($resultado))
		{	//aqui começa a pegar so dados no campo da tabela no mysql e jogar no formulario 
			$xarray = $registro; 
			echo "<tr>";
			
			$i = 0;
			while (list($nome, $valor) = each($xarray)) {  
				if ($i==0){
					echo "<td>$valor</td>";
					$i = 1;
				}else{
					$i =0;
				}
			}
			echo "</tr>";
		}
	echo "</table>";
}



function exibidadosDescripita($rs, $campos, $namespace){
	
		$xcampo  = split('[,]', $campos);
		
		for($i = 0; $i < count($xcampo); ++$i){
				$xvalor = $rs[trim($xcampo[$i])];
				$xvalor = Desencriptar($xvalor, 'nsltr');				
				echo "Ext.getCmp('".$namespace.trim($xcampo[$i])."').setValue('".$xvalor."');";
		}
			
		/*while (list($nome, $valor) = each($rs)) {  
			echo $nome .', ';
		}*/
}

function exibidadosNovo($campos, $namespace){
	
		$xcampo  = explode(',', $campos);
		
		for($i = 0; $i < count($xcampo); ++$i){				
				echo "Ext.getCmp('".$namespace.trim($xcampo[$i])."').setValue('');";
		}
}

function exibidados($rs, $campos, $namespace){
	
		$xcampo  = explode(',', $campos);
		
		for($i = 0; $i < count($xcampo); ++$i){
				$xvalor = $rs[trim($xcampo[$i])];
				$xvalor = str_replace("'", '', $xvalor);
				$xvalor = str_replace(chr(13), "'+String.fromCharCode(13)+'", $xvalor);
				$xvalor = str_replace(chr(10), "'+String.fromCharCode(10)+'", $xvalor);
				
				/*if (is_numeric($xvalor) == true){
					if (strrpos($xvalor, ".") > 0){
						$xvalor  = number_format($xvalor, 2, ',', ' ');	
					}
				}*/
				
				echo "Ext.getCmp('".$namespace.trim($xcampo[$i])."').setValue('".$xvalor."');";
		}
			
		/*while (list($nome, $valor) = each($rs)) {  
			echo $nome .', ';
		}*/
}

function montacampostored($campos){
	
		$xcampo  = explode(',', $campos);
		for($i = 0; $i < count($xcampo); ++$i){
			echo "
			{
				name: '".trim($xcampo[$i])."',
				type: 'auto'
			}";
			if ($i+1 != count($xcampo)){
					echo ',';
			}
		}
					
		//for($i = 0; $i < count($xcampo); ++$i){
		//		$xvalor = $rs[trim($xcampo[$i])];
		//		$xvalor = str_replace(chr(13), '<br>', $xvalor);
		//		$xvalor = str_replace(chr(10), '', $xvalor);
		//		$xvalor = str_replace("'", '', $xvalor);
		//		echo "Ext.getCmp('".$namespace.trim($xcampo[$i])."').setValue('".$xvalor."');";
		//}
			
		/*while (list($nome, $valor) = each($rs)) {  
			echo $nome .', ';
		}*/
}



function montastored($sql, $cn){

	$rs_stored  = $cn->open($sql);
	/*if ($rs_stored->EOF == true){
		return false;
	}*/
	echo "{";
		$totalregistro = $rs_stored->RecordCount();
		echo "results: " . $totalregistro . ", \n";
		echo "rows: [", "\n";
		$linha = '';
		$i = 0;
		while ($rs_stored->EOF==false){
			$linha = $linha . '{';
			$xregistro = $rs_stored->fields;
			while (list($nome, $valor) = each($xregistro)) { 
				if (is_numeric($nome) == false){
					$linha = $linha . "$nome: '";
					$xvalor = $rs_stored->fields[trim($nome)];
					if (is_numeric($xvalor) == true){
						if (strrpos($xvalor, ".") > 0){
							$xvalor  = number_format($xvalor, 2, ',', ' ');	
						}
					}else{
						$xvalor  = $xvalor;
					}
					if ($nome =='vencimento' || $nome =='data' || $nome =='entrada'){
						$xvalor  = formatadataBR($xvalor);
					}
					if ($nome == 'doc'){
						$xvalor  = formatarCPF_CNPJ($xvalor);
					}
					$xvalor = $xvalor .  "', " ;
					$linha = $linha . $xvalor;
				}
			}
			$linha = left($linha, strlen($linha)-2);
			$linha = $linha . "}, ";
			$i = 0;
			$rs_stored->MoveNext();
		};
		$linha = left($linha, strlen($linha)-2);
		echo $linha, "\n";
		echo "]",	 "\n";
	echo "}";

}


function montasql($strtabela, $strid){

	$xarray = $_POST; 
	reset($xarray); 
	
	$comando_sql = '';
	$comando_sqlX = '';
	if ($strid == '0' or $strid == ''){
		$comando_sql = "insert into $strtabela(";
		while (list($nome, $valor) = each($xarray)) {  
			if ($valor == 'true'){
				$valor = '1';
			}
			if ($valor == 'false'){
				$valor = '0';
			}			
			if (is_numeric (left($nome, 14))){
				$nome = right($nome, strlen($nome)-14);
			}
			if (left($nome, 1) != '0'){
				$comando_sql  = $comando_sql  . " $nome,";
				$comando_sqlX = $comando_sqlX . "'" . utf8_decode(str_replace("'", "''", $valor)) . "',";
			}
		}
		$comando_sql  = left($comando_sql , strlen($comando_sql)-1);
		$comando_sqlX = left($comando_sqlX, strlen($comando_sqlX)-1);
		$comando_sql  = "$comando_sql) values ($comando_sqlX)";
		return $comando_sql;
	}else{
		$comando_sql = "update $strtabela set ";
		while (list($nome, $valor) = each($xarray)) {  
			if ($valor == 'true'){
				$valor = '1';
			}
			if ($valor == 'false'){
				$valor = '0';
			}			
			if (is_numeric (left($nome, 14))){
				$nome = right($nome, strlen($nome)-14);
			}
			
			if (left($nome, 1) != '0'){
				$comando_sql = $comando_sql  . " $nome = '" . utf8_decode(str_replace("'", "''", $valor)) . "', ";
			}
		}
		$comando_sql = left($comando_sql, strlen($comando_sql)-2) . " where id = $strid"; 
		return $comando_sql;
	}
}


function contaregistro($strtabela,  $cn){
	$rscount  = $cn->open("select count(*) as totalregistro from $strtabela");
	return $rscount->fields['totalregistro'];
}

function right($value, $count){
    return substr($value, ($count*-1));
}

function left($string, $count){
    return substr($string, 0, $count);
}

function abrebanco(){
	
	
	$cn = NewADOConnection('mysql');

	$usuario = $_SESSION['Banco_user'];
	$banco   = $_SESSION['Banco_Name'];

	//echo $_SESSION["Banco_Host"] . $_SESSION["Banco_user"] . $_SESSION['Banco_Senha'] . $_SESSION["Banco_Name"];
	                          //localhost           lavaluvas                 lavaluvas                lavaluvas
	//echo 'teste';
	$ret = $cn->Connect('localhost','ajato_lavanderia', '123456789', 'ajato_lavanderia');
	
	
	if ($ret == ''){
		
		echo "<i><b>
				<font size='5' color='#FF0000' face='Trebuchet MS'>Atenção</font><font size='4' face='Trebuchet MS' color='#0000FF'><br>
				<br>
				</font></b></i><font face='Trebuchet MS' color='#0000FF'>Não foi possível </font><b>
				<font face='Trebuchet MS' color='#FF0000'>conectar</font></b><font face='Trebuchet MS' color='#0000FF'> 
				em seu banco de dados.<br>
				Usuário ou senha ou servidor invalido.<br>
				Check as informações no arquivo </font>
				<font face='Trebuchet MS' color='#FF0000'><b>setvar.php</b></font><font face='Trebuchet MS' color='#0000FF'> 
				que se encontrar dentro de php/config/</font>

				<br>
				<font face='Trebuchet MS' color='#0000FF'><br>
				<b>Dados de conexão</b><br>
				&nbsp;</font><table border='1' width='396' cellspacing='0' cellpadding='0' bordercolorlight='#C0C0C0' bordercolordark='#C0C0C0'>
					<tr>
						<td align='right'><font face='Trebuchet MS' color='#0000FF'>Servidor:</font></td>
						<td width='284'><font face='Trebuchet MS' color='#0000FF'>&nbsp;".$_SESSION['Banco_Host']."</font></td>
					</tr>
					<tr>
						<td align='right'><font face='Trebuchet MS' color='#0000FF'>Banco:</font></td>
						<td width='284'><font face='Trebuchet MS' color='#0000FF'>&nbsp;$banco </font></td>
					</tr>
					<tr>
						<td align='right'><font face='Trebuchet MS' color='#0000FF'>Usuário:</font></td>
						<td width='284'><font face='Trebuchet MS' color='#0000FF'>&nbsp;$usuario</font></td>
					</tr>
					<tr>
						<td align='right'><font face='Trebuchet MS' color='#0000FF'>Senha:</font></td>
						<td width='284'><font face='Trebuchet MS' color='#0000FF'>&nbsp;".$_SESSION['Banco_Senha']."</font></td>
					</tr>
				</table>
				";
		exit();
	}
	
	//echo "$usuario / $banco / ". $_SESSION['Banco_Senha'] . " / $ret";
	return $cn;
}

function mappath($caminho)
{
  extract($GLOBALS);
  
  $xcaminho=$_SERVER['SCRIPT_FILENAME'];
  $xcaminho=str_replace("\\", "/", $xcaminho); 
  $lngx=(strrpos($xcaminho,"/") ? strrpos($xcaminho,"/")+1 : 0);
  $xmappath=substr($xcaminho,0,$lngx);
  $xmappath=$xmappath.$caminho;
  $function_ret=$xmappath;

  return $function_ret;
  
} 
function buscaempresa($Url,$ArquivoIni)
{
        extract($GLOBALS);
         $Arquivo=$ArquivoIni;
		 $function_ret = '';
         if (!file_exists($Arquivo))
         {
            $function_ret="Não encontrei o arquivo $Arquivo";
            $function_ret="";
            return $function_ret;
         }

         $FsFile=fopen($Arquivo,"r");
         while(!feof($FsFile) )
         {
			$Linha=strtolower(fgets($FsFile));
			if (trim($Linha)=="")
			{
				break;
			}
			$nDelimitador=(strpos($Linha,"=",1) ? strpos($Linha,"=",1)+1 : 0);
			if ($nDelimitador==0){
				$function_ret="";
				break;
			}
  
			$cDomArq = trim(substr($Linha,0,$nDelimitador-1));
			$cDomArq = str_replace("www.","",$cDomArq);
			$cDomArq = str_replace("www","",$cDomArq);
			$cCodEmp = trim(substr($Linha,strlen($Linha)-(strlen($Linha)-$nDelimitador)));
		
			$Url=str_replace("www.","",$Url);
			$Url=str_replace("www","",$Url);
			
			if (strtolower($Url) == strtolower($cDomArq))
			{
			  $function_ret = $cCodEmp;
			  break;
			}
		}

		fclose($FsFile);

	return $function_ret;
} 
function instrrev($n,$s) {
  $x=strpos(chr(0).strrev($n),$s)+0;
  return (($x==0) ? 0 : strlen($n)-$x+1);
} 

function inStr($needle, $haystack)
{
    return strpos($haystack, $needle);
}   



function SEND($de, $para, $titulo, $msg){
	if ($de == ''){
		return false;
	}else{
		set_time_limit(0);
        require_once('wb_sendmail.php');
        
		@$servidor_smtp = $_SESSION['servidor_smtp'];
		@$usuario_smtp  = $_SESSION['usuario_smtp'];
		@$senha_smtp    = $_SESSION['senha_smtp'];
		
		
		if ($servidor_smtp == ''){
			$sqlMail = "select servidor_smtp , usuario_smtp, senha_smtp from tblconfig";
			$cnAux = abrebanco();
			$rsMail = $cnAux->open($sqlMail);
			if ($rsMail->EOF == false){
				@$servidor_smtp = $rsMail->fields['servidor_smtp'];
				@$usuario_smtp  = $rsMail->fields['usuario_smtp'];
				@$senha_smtp    = $rsMail->fields['senha_smtp'];
			}
			$cnAux = null;
			$rsMail = null;	
		}
		
		
        $smtp = new SMTPMAIL();
        $smtp->Servidor    = $servidor_smtp;
        
        $smtp->Autenticado = TRUE;
        $smtp->Usuario     = $usuario_smtp;
        $smtp->Senha       = $senha_smtp;
        
        $smtp->Codificacao = "UTF-8";

        $smtp->EmailDe = $de;
        $smtp->EmailDeVisual = $de;

        $smtp->EmailPara = $para;
        
        $smtp->Assunto = $titulo;
        
        $smtp->Corpo = $msg;
        
        //$smtp->anexarArquivo('D:\\Bruno\\Fotos&Videos\\Carros\\03-07-07_0916.jpg');
        //$smtp->anexarArquivo('D:\\Bruno\\Fotos&Videos\\Carros\\Carro_11.jpg');
        
        if($smtp->Enviar()) {
            return true;
			//echo 'ok';
        } else {
			echo "alert('Erro ao enviar o e-mail, favor conferir os dados de servidor SMTP');";
			return false;
        }
		
	}
}



?>

