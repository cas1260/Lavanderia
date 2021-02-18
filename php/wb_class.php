<?php
class MoIP
{

    private $credenciais;
    private $razao;
    private $ambiente = 'sandbox';
    private $id_proprio;
    private $formas_pagamento = array('boleto'=>'BoletoBancario',
        'financiamento'=>'FinanciamentoBancario',
        'debito'=>'DebitoBancario',
        'cartao_credito'=>'CartaoCredito',
        'cartao_debito'=>'CartaoDebito',
        'carteira_moip'=>'CarteiraMoIP');

    private $instituicoes = array('moip'=>'MoIP',
        'visa'=>'Visa',
        'american_express'=>'AmericanExpress',
        'mastercard'=>'Mastercard',
        'diners'=>'Diners',
        'banco_brasil'=>'BancoDoBrasil',
        'bradesco'=>'Bradesco',
        'itau'=>'Itau',
        'real'=>'BancoReal',
        'unibanco'=>'Unibanco',
        'aura'=>'Aura',
        'hipercard'=>'Hipercard',
        'paggo'=>'Paggo', //oi paggo
        'banrisul'=>'Banrisul'
    );

    private $tipo_frete = array('proprio'=>'Proprio','correios'=>'Correios');

    private $tipo_prazo = array('corridos'=>'Corridos','uteis'=>'Uteis');

    private $forma_pagamento = array();
    private $forma_pagamento_args;
    private $tipo_pagamento = 'Unico';
    private $pagador;
    var $resposta;
    private $valor;

    //simplexml object
    private $xml;

    function __construct()
    {
        $this->initXMLObject();
    }

    private function initXMLObject()
    {
        $this->xml = new SimpleXmlElement('<EnviarInstrucao></EnviarInstrucao>');
        $this->xml->addChild('InstrucaoUnica');
    }
    
    public function setTipoPagamento($tipo)
    {
        if ($tipo=='Unico' || $tipo=='Direto') {
            $this->tipo_pagamento = $tipo;
        }
        return $this;
    }

    public function setPagamentoDireto($params)
    {
        if (!isset($params['forma']))
            throw new InvalidArgumentException("Você deve especificar a forma de pagamento em setPagamentoDireto.");
        

        if (
            ($params['forma']=='debito' or $params['forma']=='cartao_credito')
            and
            (!isset($params['instituicao']) or !isset($this->instituicoes[$params['instituicao']]))

        )
        {
            throw new InvalidArgumentException("Você deve especificar uma instituição de pagamento válida quando".
                " a forma de forma de pagamento é via débito ou cartao");
        }

        if ($params['forma'] == 'cartao_credito' and
            (!isset($params['cartao']) or
            !isset($params['cartao']['numero']) or
            !isset($params['cartao']['expiracao']) or
            !isset($params['cartao']['codigo_seguranca']) or
            !isset($params['cartao']['portador']) or
            !isset($params['cartao']['portador']['nome']) or
            !isset($params['cartao']['portador']['identidade_numero']) or
            !isset($params['cartao']['portador']['identidade_tipo']) or
            !isset($params['cartao']['portador']['telefone']) or
            !isset($params['cartao']['portador']['data_nascimento']) or
            !isset($params['cartao']['parcelamento']) or
            !isset($params['cartao']['parcelamento']['parcelas']) or
            !isset($params['cartao']['parcelamento']['recebimento'])
           )
          )
        {
            throw new InvalidArgumentException("Os dados do cartão foram passados de forma incorreta.");
        }

        $pd = $this->xml->InstrucaoUnica->addChild('PagamentoDireto');
        
        $pd->addChild('Forma',$this->formas_pagamento[$params['forma']]);

        if ($params['forma']=='debito' or $params['forma']=='cartao_credito')
        {
            $pd->addChild('Instituicao',$this->instituicoes[$params['instituicao']]);
        }

        if ($params['forma']=='cartao_credito')
        {
            $cartao = $pd->addChild('CartaoCredito');
            $cartao->addChild('Numero',$params['cartao']['numero']);
            $cartao->addChild('Expiracao',$params['cartao']['expiracao']);
            $cartao->addChild('CodigoSeguranca',$params['cartao']['codigo_seguranca']);

            $portador = $cartao->addChild('Portador');
            $portador->addChild('Nome',$params['cartao']['portador']['nome']);
            $portador->addChild('Identidade',$params['cartao']['portador']['identidade_numero'])
                     ->addAttribute('tipo',$params['cartao']['portador']['identidade_tipo']);

            $parcelamento = $cartao->addChild('Parcelamento');
            $parcelamento->addChild('Parcelas',$params['cartao']['parcelamento']['parcelas']);
            $parcelamento->addChild('Recebimento',$params['cartao']['parcelamento']['recebimento']);
        }

        $this->tipo_pagamento = 'Direto';
        return $this;
    }

    public function setCredenciais($credenciais)
    {
        if (!isset($credenciais['token']) or
            !isset($credenciais['key']) or
            strlen($credenciais['token'])!=32 or
            strlen($credenciais['key'])!=40)
            throw new InvalidArgumentException("Credenciais inválidas");

        $this->credenciais = $credenciais;
        return $this;
    }

    public function setAmbiente($ambiente)
    {
        if ($ambiente!='sandbox' and $ambiente!='producao')
            throw new InvalidArgumentException("Ambiente inválido");

        $this->ambiente = $ambiente;
        return $this;
    }

    public function valida()
    {
        if (!isset($this->credenciais) or
            !isset($this->razao) or
            !isset($this->id_proprio))
            throw new InvalidArgumentException("Dados requeridos não preenchidos. Você deve especificar as credenciais, a razão do pagamento e seu ID próprio");

        $pagador = $this->pagador;
        
        if ($this->tipo_pagamento=='Direto') {

            if( empty($pagador) or
                !isset($pagador['nome']) or
                !isset($pagador['email']) or
                !isset($pagador['celular']) or
                !isset($pagador['apelido']) or
                !isset($pagador['identidade']) or
                !isset($pagador['endereco']) or
                !isset($pagador['endereco']['logradouro']) or
                !isset($pagador['endereco']['numero']) or
                !isset($pagador['endereco']['complemento']) or
                !isset($pagador['endereco']['bairro']) or
                !isset($pagador['endereco']['cidade']) or
                !isset($pagador['endereco']['estado']) or
                !isset($pagador['endereco']['pais']) or
                !isset($pagador['endereco']['cep']) or
                !isset($pagador['endereco']['telefone'])
            )
            {
                throw new InvalidArgumentException("Dados do pagador especificados de forma incorreta");
            }
        }

        return $this;
    }

    public function setIDProprio($id)
    {
        $this->id_proprio = $id;
        return $this;
    }

    public function setRazao($razao)
    {
        $this->razao = $razao;
        return $this;
    }

    public function addFormaPagamento($forma,$args=null)
    {
        if(!isset($this->formas_pagamento[$forma]))
            throw new InvalidArgumentException("Forma de pagamento indisponivel");

        if($args!=null)
        {
            if (!is_array($args))
                throw InvalidArgumentException("Os parâmetros extra devem ser passados em um array");

            if($forma=='boleto')
            {
                //argumentos possíveis: dias de expiração, instruções e logo da URL
                if (isset($args['dias_expiracao']) and isset($args['dias_expiracao']['tipo']) and isset($args['dias_expiracao']['dias']))
                {
                    $this->forma_pagamento_args = $args;
                }
                else
                {
                    throw new InvalidArgumentException("Parâmetros passados de forma incorreta");
                }
            }
        }
        $this->forma_pagamento[] = $forma;
        return $this;
    }

    public function setPagador($pagador)
    {
        $this->pagador = $pagador;
        return $this;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    public function setAcrescimo($valor)
    {
        $this->acrescimo = $valor;
        return $this;
    }

    public function setDeducao($valor)
    {
        $this->deducao = $valor;
        return $this;
    }

    public function addMensagem($msg)
    {
        if(!isset($this->xml->InstrucaoUnica->Mensagens))
        {
            $this->xml->InstrucaoUnica->addChild('Mensagens');
        }

        $this->xml->InstrucaoUnica->Mensagens->addChild('Mensagem',$msg);
        return $this;
    }

    public function setUrlRetorno($url)
    {
        if (!isset($this->xml->InstrucaoUnica->URLRetorno))
        {
            $this->xml->InstrucaoUnica->addChild('URLRetorno',$url);
        }
    }

    public function setUrlNotificacao($url)
    {
        if (!isset($this->xml->InstrucaoUnica->URLNotificacao))
        {
            $this->xml->InstrucaoUnica->addChild('URLNotificacao',$url);
        }
    }

    public function addComissao($param)
    {
        if (!isset($param['login_moip']))
            throw new InvalidArgumentException('Você deve especificar um usuário para comissionar.');

        if (!isset($param['valor_fixo']) or !isset($param['valor_percentual']))
            throw new InvalidArgumentException('Você deve especificar um tipo de valor para comissionar.');

        if (isset($param['valor_fixo']) and isset($param['valor_percentual']))
            throw new InvalidArgumentException('Você deve especificar somente um tipo de valor de comissão');

        if (!isset($this->xml->InstrucaoUnica->Comissoes))
            $this->xml->InstrucaoUnica->addChild('Comissoes');

        if (isset($param['valor_fixo']))
        {
            $node = $this->xml->InstrucaoUnica->Comissoes->addChild('Comissao');
            $node->addChild('Comissionado')->addChild('LoginMoIP',$param['login_moip']);
            $node->addChild('ValorFixo',$param['valor_fixo']);
        }
        else
        {
            $node = $this->xml->InstrucaoUnica->Comissoes->addChild('Comissao');
            $node->addChild('Comissionado')->addChild('LoginMoIP',$param['login_moip']);
            $node->addChild('ValorPercentual',$param['valor_percentual']);
        }
    }

    public function addParcela($min,$max,$juros='')
    {
        if (!isset($this->xml->InstrucaoUnica->Parcelamentos))
        {
            $this->xml->InstrucaoUnica->addChild('Parcelamentos');
        }

        $parcela = $this->xml->InstrucaoUnica->Parcelamentos->addChild('Parcelamento');
        $parcela->addChild('MinimoParcelas',$min);
        $parcela->addChild('MaximoParcelas',$max);
        $parcela->addChild('Recebimento','AVista');

        if (!empty($juros))
        {
            $parcela->addChild('Juros',$min);
        }

        return $this;
    }

    public function addEntrega($params)
    {
        //validações dos parâmetros de entrega

        if (empty($params) or !isset($params['tipo']) or !isset($params['prazo']))
        {
            throw new InvalidArgumentException('Você deve especificar o tipo de frete (proprio ou correios) e o prazo de entrega');
        }

        if (!isset($this->tipo_frete[$params['tipo']]))
        {
            throw new InvalidArgumentException('Tipo de frete inválido. Opções válidas: "proprio" ou "correios"');
        }

        if (is_array($params['prazo']))
        {
            if (is_array($params['prazo']) and !isset($this->tipo_prazo[$params['prazo']['tipo']]))
            {
                throw new InvalidArgumentException('Tipo de prazo de entrega inválido. Opções válidas: "uteis" ou "corridos".');
            }

            if (!isset($params['prazo']['dias']))
            {
                throw new InvalidArgumentException('Você deve especificar os dias do prazo de entrega');
            }
        }

        if ($params['tipo']=='correios')
        {
            if ((!isset($params['correios']) or empty($params['correios'])) )
            {
                throw new InvalidArgumentException('É necessário especificar os '.
                    'parâmetros dos correios quando o '.
                    'tipo de frete é Correios');

            }

            if (!isset($params['correios']['peso']) or !isset($params['correios']['forma_entrega']))
            {
                throw new InvalidArgumentException('É necessário passar os parâmetros'.
                    ' dos correios quando a forma de envio são os Correios');
            }

        }
        else
        {
            if (!isset($params['valor_fixo']) and !isset($params['valor_percentual']))
                throw new InvalidArgumentException('Você deve especificar valor_fixo ou valor_percentual quando o tipo de frete é próprio');
        }

        //fim das validações
        if (!isset($this->xml->InstrucaoUnica->Entrega))
        {
            $this->xml->InstrucaoUnica->addChild('Entrega')->addChild('Destino','MesmoCobranca');
        }

        $entrega = $this->xml->InstrucaoUnica->Entrega;
        $calculo_frete = $entrega->addChild('CalculoFrete');
        $calculo_frete->addChild('Tipo',$this->tipo_frete[$params['tipo']]);

        $calculo_frete->addChild('Prazo',$params['prazo']['dias'])
            ->addAttribute('Tipo',$this->tipo_prazo[$params['prazo']['tipo']]);

        if ($params['tipo']=='proprio')
        {
            if (isset($params['valor_fixo']))
                $calculo_frete->addChild('ValorFixo',$params['valor_fixo']);
            else
                $calculo_frete->addChild('ValorPercentual',$params['valor_percentual']);
        }
        else
        {
            $correios = $calculo_frete->addChild('Correios');
            $correios->addChild('PesoTotal',$params['correios']['peso']);
            $correios->addChild('FormaEntrega',$params['correios']['forma_entrega']);
        }

        return $this;
    }

    public function getXML()
    {
        $this->xml->InstrucaoUnica->addChild('IdProprio' , $this->id_proprio);
        $this->xml->InstrucaoUnica->addChild('Razao' , $this->razao);

        if (empty($this->valor))
            throw new InvalidArgumentException('Erro: o valor da transação deve ser especificado');

        $this->xml->InstrucaoUnica->addChild('Valores')
            ->addChild('Valor',$this->valor)
            ->addAttribute('moeda','BRL');

        if (isset($this->deducao))
        {
            $this->xml->InstrucaoUnica->Valores->addChild('Deducao',$this->deducao)
                ->addAttribute('moeda','BRL');
        }

        if (isset($this->acrescimo))
        {
            $this->xml->InstrucaoUnica->Valores->addChild('Acrescimo',$this->acrescimo)
                ->addAttribute('moeda','BRL');
        }

        if (!empty($this->forma_pagamento))
        {
            $instrucao = $this->xml->InstrucaoUnica;
            $formas = $instrucao->addChild('FormasPagamento');

            foreach ($this->forma_pagamento as $forma)
            {

                $formas->addChild('FormaPagamento',$this->formas_pagamento[$forma]);

                if($forma == 'boleto' and !empty($this->forma_pagamento_args))
                {
                    $instrucao->addChild('Boleto')
                        ->addChild('DiasExpiracao',$this->forma_pagamento_args['dias_expiracao']['dias'])
                        ->addAttribute('Tipo',$this->forma_pagamento_args['dias_expiracao']['tipo']);

                    if(isset($this->forma_pagamento_args['instrucoes']))
                    {
                        $numeroInstrucoes = 1;
                        foreach($this->forma_pagamento_args['instrucoes'] as $instrucaostr)
                        {
                            $instrucao->Boleto->addChild('Instrucao'.$numeroInstrucoes,$instrucaostr);
                            $numeroInstrucoes++;
                        }
                    }
                }

            }
        }

        if(!empty($this->pagador))
        {
            $p = $this->pagador;
            $this->xml->InstrucaoUnica->addChild('Pagador');
            (isset($p['nome']))?$this->xml->InstrucaoUnica->Pagador->addChild( 'Nome' , $this->pagador[ 'nome' ] ):null;
            (isset($p['login_moip']))?$this->xml->InstrucaoUnica->Pagador->addChild( 'LoginMoIP' , $this->pagador[ 'login_moip' ] ):null;
            (isset($p['email']))?$this->xml->InstrucaoUnica->Pagador->addChild( 'Email' , $this->pagador['email']):null;
            (isset($p['celular']))?$this->xml->InstrucaoUnica->Pagador->addChild( 'TelefoneCelular' , $this->pagador['celular']):null;
            (isset($p['apelido']))?$this->xml->InstrucaoUnica->Pagador->addChild( 'Apelido' , $this->pagador['apelido']):null;
            (isset($p['identidade']))?$this->xml->InstrucaoUnica->Pagador->addChild( 'Identidade' , $this->pagador['identidade']):null;

            $p = $this->pagador['endereco'];
            $this->xml->InstrucaoUnica->Pagador->addChild( 'EnderecoCobranca' );
            (isset($p['endereco']))?$this->xml->InstrucaoUnica->Pagador->EnderecoCobranca->addChild( 'Logradouro' , $this->pagador['endereco']['logradouro']):null;
            
            (isset($p['endereco']))?$this->xml->InstrucaoUnica->Pagador->EnderecoCobranca->addChild( 'Numero' , $this->pagador['endereco']['numero']):null;

            (isset($p['endereco']))?$this->xml->InstrucaoUnica->Pagador->EnderecoCobranca->addChild( 'Complemento' , $this->pagador['endereco']['complemento']):null;

            (isset($p['endereco']))?$this->xml->InstrucaoUnica->Pagador->EnderecoCobranca->addChild( 'Bairro' , $this->pagador['endereco']['bairro']):null;

            (isset($p['endereco']))?$this->xml->InstrucaoUnica->Pagador->EnderecoCobranca->addChild( 'Cidade' , $this->pagador['endereco']['cidade']):null;

            (isset($p['endereco']))?$this->xml->InstrucaoUnica->Pagador->EnderecoCobranca->addChild( 'Estado' , $this->pagador['endereco']['estado']):null;

            (isset($p['endereco']))?$this->xml->InstrucaoUnica->Pagador->EnderecoCobranca->addChild( 'Pais' , $this->pagador['endereco']['pais']):null;

            (isset($p['endereco']))?$this->xml->InstrucaoUnica->Pagador->EnderecoCobranca->addChild( 'CEP' , $this->pagador['endereco']['cep']):null;

            (isset($p['endereco']))?$this->xml->InstrucaoUnica->Pagador->EnderecoCobranca->addChild( 'TelefoneFixo' , $this->pagador['endereco']['telefone']):null;

        }

        $return = $this->xml->asXML();
        $this->initXMLObject();
        return str_ireplace("\n","",$return);
    }

    public function envia($client=null)
    {
        $this->valida();

        if($client==null)
            $client = new MoIPClient();

        if ($this->ambiente=='sandbox')
            $url = 'https://desenvolvedor.moip.com.br/sandbox/ws/alpha/EnviarInstrucao/Unica';
        else
            $url = 'https://www.moip.com.br/ws/alpha/EnviarInstrucao/Unica';

        $this->resposta = $client->send($this->credenciais['token'].':'.$this->credenciais['key'],
            $this->getXML(),
            $url);
        return $this;
    }

    public function getResposta()
    {
        if (!empty($this->resposta->erro))
            return (object) array('sucesso'=>false,'mensagem'=>$this->resposta->erro);

        $xml = new SimpleXmlElement($this->resposta->resposta);
        $return = (object) array();
        $return->sucesso = (bool)$xml->Resposta->Status=='Sucesso';
        $return->id = (string)$xml->Resposta->ID;
        $return->token = (string)$xml->Resposta->Token;

        return $return;
    }

    public function checarPagamentoDireto($login_moip,$client=null)
    {
        if (!isset($this->credenciais))
            throw new Exception("Você deve especificar as credenciais (token/key) da API antes de chamar este método");

        if ($client==null) {
            $client = new MoIPClient();
        }

        $url = "https://www.moip.com.br/ws/alpha/ChecarPagamentoDireto/$login_moip";
        $resposta = $client->send($this->credenciais['token'].':'.$this->credenciais['key'],'',$url,'GET');
        $xml = new SimpleXmlElement($resposta->resposta);

        return (object)array(
            'erro'=>$resposta->erro,
            'id'=>(string)$xml->Resposta->ID,
            'sucesso'=>$xml->Resposta->Status=='Sucesso',
            'carteira_moip'=>$xml->Resposta->CarteiraMoIP=='true',
            'cartao_credito'=>$xml->Resposta->CartaoCredito=='true',
            'cartao_debito'=>$xml->Resposta->CartaoDebito=='true',
            'debito_bancario'=>$xml->Resposta->DebitoBancario=='true',
            'financiamento_bancario'=>$xml->Resposta->FinanciamentoBancario=='true',
            'boleto_bancario'=>$xml->Resposta->BoletoBancario=='true',
            'debito_automatico'=>$xml->Resposta->DebitoAutomatico=='true');
    }

    public function checarValoresParcelamento($login_moip,$total_parcelas,$juros,$valor_simulado,$client=null)
    {
        if (!isset($this->credenciais)) {
            throw new Exception("Você deve especificar as credenciais (token/key) da API antes de chamar este método");
        }

        if ($client==null) {
            $client = new MoIPClient();
        }

        $url = "https://www.moip.com.br/ws/alpha/ChecarValoresParcelamento/$login_moip/$total_parcelas/$juros/$valor_simulado";
        $resposta = $client->send($this->credenciais['token'].':'.$this->credenciais['key'],'',$url,'GET');
        $xml = new SimpleXmlElement($resposta->resposta);

        $return = array('sucesso'=>(bool)$xml->Resposta->Status=='sucesso',
            'id'=>(string)$xml->Resposta->ID,
            'parcelas'=>array());

        $i = 1;

        foreach($xml->Resposta->ValorDaParcela as $parcela)
        {
            $attrib = $parcela->attributes();
            $return['parcelas']["$i"] = array('total'=>(string)$attrib['Total'],'juros'=>(string)$attrib['Juros'],'valor'=>(string)$attrib['Valor']);
            $i++;
        }

        return $return;
    }

}

/**
* Cliente HTTP "burro"
*
* @author Herberth Amaral
* @version 0.0.1
*/
class MoIPClient
{
    function send_without_curl($credentials, $xml, $url='http://desenvolvedor.moip.com.br/sandbox/ws/alpha/EnviarInstrucao/Unica',$method='POST')
    {
        $auth = base64_encode($credentials);
        $url = str_replace('https','http',$url);

        $header[] = "Authorization: Basic " . $auth;


        $params = array('http' => array(
            'method' => $method,
            'content' => $xml,
            'header'=>$header
        ));
        $ctx = stream_context_create($params);
        $fp = fopen($url, 'r', false, $ctx);

        $response = stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problemas ao ler dados de $url, $php_errormsg");
        }
        return (object)array('resposta'=>$response,'erro'=>null);
    }

    function send($credentials,$xml,$url='https://desenvolvedor.moip.com.br/sandbox/ws/alpha/EnviarInstrucao/Unica',$method='POST')
    {
        $header[] = "Authorization: Basic " . base64_encode($credentials);
        if (!function_exists('curl_init'))
            return $this->send_without_curl($credentials, $xml, $url);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_USERPWD, $credentials);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0");

        $method=='POST'?curl_setopt($curl, CURLOPT_POST, true):null;

        $xml!=''?curl_setopt($curl, CURLOPT_POSTFIELDS, $xml):null;
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return (object) array('resposta'=>$ret,'erro'=>$err);
    }

}

class pgs {
  var $_itens = array();
  var $_config = array ();
  var $_cliente = array ();
  /**
   * pgs
   *
   * Função de inicialização
   * você pode passar os parâmetros padrão alterando as informações padrão como o tipo de moeda ou
   * o tipo de carrinho (próprio ou do pagseguro)
   *
   * Ex:
   * <code>
   * array (
   *   'email_cobranca' => 'raposa@vermelha.com.br',
   *   'tipo'           => 'CBR',
   *   'ref_transacao'  => 'A36',
   *   'tipo_frete'     => 'PAC',
   * )
   * </code>
   *
   * @access public
   * @param array $args    Array associativo contendo as configurações que você deseja alterar
   * @return               void
   */
  function pgs($args = array()) {
    if ('array'!=gettype($args)) $args=array();
    $default = array(
      'email_cobranca'  => '',
      'tipo'            => 'CP',
      'moeda'           => 'BRL',
    );
    $this->_config = $args+$default;
  }
  /**
   * error
   *
   * Retorna a mensagem de erro
   *
   * @access public
   * @return string
   */
  function error($msg){
    trigger_error($msg);
    return $this;
  }
  /**
   * adicionar
   *
   * Adiciona um item ao carrinho
   *
   * O elemento adicionado deve ser um array associativo com as seguintes chaves
   * id         => string com até 100 caracteres
   * descricao  => string com até 100 caracteres
   * quantidade => integer
   * valor      => integer ou float
   * peso       => integer (opcional) coloque o peso (em gramas) do produto, caso seja um peso único para todos os
   *               produtos é preferivel inplantá-lo no new pgs(array('item_peso_1' => 1300))
   * frete      => integer ou float (opcional) coloque o valor do frete, caso seja um frete único
   *               para todos os produtos é preferivel inplantá-lo no new pgs(array('item_frete_1' => 30))
   *
   * @access public
   * @param array $item O elemento que será adicionado
   * @return object pgs O próprio objeto para que possa ser concatenado a outro comando dele mesmo
   */
  function adicionar($item) {

    if ('array' !== gettype($item))
      return $this->error("Item precisa ser um array.");
    if(isset($item[0]) && 'array' === gettype($item[0])){
      foreach ($item as $elm) {
        if('array' === gettype($elm)) {
          $this->adicionar($elm);
        }
      }
      return $this;
    }

    $tipos=array(
      "id" =>         array(1,"string",                '@\w@'         ),
      "quantidade" => array(1,"string,integer",        '@^\d+$@'      ),
      "valor" =>      array(1,"double,string,integer", '@^\d*\.?\d+$@'),
      "descricao" =>  array(1,"string",                '@\w@'         ),
      "frete" =>      array(0,"string,integer",        '@^\d+$@'      ),
      "peso" =>       array(0,"string,integer",        '@^\d+$@'      ),
    );

    foreach($tipos as $elm=>$valor){
      list($obrigatorio,$validos,$regexp)=$valor;
      if(isset($item[$elm])){
        if(strpos($validos,gettype($item[$elm])) === false ||
          (gettype($item[$elm]) === "string" && !preg_match($regexp,$item[$elm]))){
          return $this->error("Valor invalido passado para $elm.");
        }
      }elseif($obrigatorio){
        return $this->error("O item adicionado precisa conter $elm");
      }
    }

    $this->_itens[] = $item;
    return $this;
  }
  /**
   * cliente
   *
   * Define o cliente a ser inserido no sistema.
   * Recebe como parametro um array associativo contendo os dados do cliente.
   *
   * Ex:
   * <code>
   * array (
   *   'nome'   => 'José de Arruda',
   *   'cep'    => '12345678',
   *   'end'    => 'Rua dos Tupiniquins',
   *   'num'    => 37,
   *   'compl'  => 'apto 507',
   *   'bairro' => 'Sto Amaro',
   *   'cidade' => 'São Camilo',
   *   'uf'     => 'SC',
   *   'pais'   => 'Brasil',
   *   'ddd'    => '48',
   *   'tel'    => '55554877',
   *   'email'  => 'josearruda@teste.com',
   * )
   * </code>
   *
   * @access public
   * @param array $args Dados sobre o cliente, se não forem passados os dados corretos,
   * o pagseguro se encarrega de perguntar os dados ao cliente
   * @return void
   */
  function cliente($args=array()) {
    if ('array'!==gettype($args)) return;
    $this->_cliente = $args;
  }
  /**
   *
   * mostra
   *
   * Mostra o formulário de envio de post do PagSeguro
   *
   * Após configurar o objeto, você pode usar este método para mostrando assim o
   * formulário com todos os inputs necessários para enviar ao pagseguro.
   *
   * <code>
   * array (
   *   'print'       => false,        // Cancelará o evento de imprimir na tela, retornando o formulário
   *   'open_form'   => false,        // Não demonstra a tag <form target="pagseguro" ... >
   *   'close_form'  => false,        // Não demonstra a tag </form>
   *   'show_submit' => false,        // Não mostra o botão de submit (imagem ou um dos 5 do pagseguro)
   *   'img_button'  => 'imagem.jpg', // Usa a imagem (url) para formar o botão de submit
   *   'btn_submit'  => 1,            // Mostra um dos 5 botões do pagseguro no botão de submit
   * )
   * </code>
   *
   * @access public
   * @param array $args Array associativo contendo as configurações que você deseja alterar
   */
  function mostra ($args=array()) {
    $default = array (
      'print'       => true,
      'open_form'   => true,
      'close_form'  => true,
      'show_submit' => true,
      'img_button'  => false,
      'bnt_submit'  => false,
    );
    $args = $args+$default;
    $_input = '  <input type="hidden" name="%s" value="%s"  />';
    $_form = array();
    if ($args['open_form'])
      $_form[] = '<form target="_self" id = "FormPagSeguro" action="https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx" method="post">';
    foreach ($this->_config as $key=>$value)
      $_form[] = sprintf ($_input, $key, $value);
    foreach ($this->_cliente as $key=>$value)
      $_form[] = sprintf ($_input, "cliente_$key", $value);

    $assoc = array (
      'id' => 'item_id',
      'descricao' => 'item_descr',
      'quantidade' => 'item_quant',
    );
    $i=1;
    foreach ($this->_itens as $item) {
      foreach ($assoc as $key => $value) {
        $sufixo=($this->_config['tipo']=="CBR")?'':'_'.$i;
        $_form[] = sprintf ($_input, $value.$sufixo, $item[$key]);
        unset($item[$key]);
      }
      $_form[] = str_replace ('.', '', sprintf ('  <input type="hidden" name="%s" value="%.2f"  />', "item_valor$sufixo", $item['valor']));
      unset($item['valor']);

      foreach ($item as $key=>$value)
        $_form[] = sprintf ($_input, "item_{$key}{$sufixo}", $value);

      $i++;
    }
	$_form[] = '<center>';
	$_form[] = '<br><br><img src = "../images/businesspeople.png"><br>Aguarde, dentro de instantes sua fatura será exibida!<br>Caso isto não ocorra automaticamente, basta clicar no botão abaixo.<br><br>';
	
    if ($args['show_submit']) {
      if ($args['img_button']) {
        $_form[] = sprintf('  <input type="image" src="%s" name="submit" alt="Pague com o PagSeguro - &eacute; r&aacute;pido, gr&aacute;tis e seguro!"  />', $args['img_button']);
      } elseif (@$args['btn_submit']) {
        switch ($args['btn_submit']) {
          case 1:  $btn = 'btnComprarBR.jpg'; break;
          case 2:  $btn = 'btnPagarBR.jpg'; break;
          case 3:  $btn = 'btnPagueComBR.jpg'; break;
          case 4:  $btn = 'btnComprar.jpg'; break;
          case 5:  $btn = 'btnPagar.jpg'; break;
          default: $btn = 'btnComprarBR.jpg';
        }
        $_form[] = sprintf ('  <input type="image" src="https://pagseguro.uol.com.br/Security/Imagens/%s"  name="submit" alt="Pague com o PagSeguro - &eacute; r&aacute;pido, gr&aacute;tis e seguro!" />', $btn);
      } else {
        $_form[] = '  <input type="submit" value="Pague com o PagSeguro"  />';
      }
    }
    if($args['close_form']) $_form[] = '</form>';
	$_form[] = '<script>';
	$_form[] = 'document.getElementById("FormPagSeguro").submit();';
	$_form[] = '</script>';


    $return = implode("\n", $_form);
    if ($args['print']) print ($return);
    return $return;
  }
}

class Whm {

	public $idDominio; //Id do dominio
	public $idCliente; //Id do cliente
	public $web;       //objecto do acesso ao dominio
	
	public $url;
	public $login;
	public $senha;
	
	function LogarPainel($pUrl, $pLogin, $pSenha){
		if (right($pUrl, 1) != '/'){
			$pUrl = $pUrl . '/';
		}
		
		$this->web = new Curl(1);
		$this->url   = $pUrl;
		$this->login = $pLogin;
		$this->senha = $pSenha;
		
		/*$this->web->addPostVar('user',$pLogin);
		$this->web->addPostVar('pass',$pSenha);*/
		$texto = $this->web->exec($pUrl . "login/?user=$pLogin&pass=$pSenha");
		//erro($texto);
		
		if (inStr("Login Attempt Failed!", $texto)!= 0){
			return false;
		}

		if (inStr("Failed!", $texto)!= 0){
			return false;
		}

		return true;
		
	}
	function criadominio($pSite, $plano, $login, $Senha){
		$texto = $this->web->exec($this->url . "scripts/wwwacct?remote=1&nohtml=1&username=$login&password=$Senha&domain=$pSite&plan=$plano");
		if (left($texto, 1) == '1'){
			$texto = right($texto, strlen($texto)-2);	
		}
		return $texto;
	}
	
	
}


class Plesk {
	
	public $idDominio; //Id do dominio
	public $idCliente; //Id do cliente
	public $web;       //objecto do acesso ao dominio
	
	public $url;
	public $login;
	public $senha;
	
	function LogarPainel($pUrl, $pLogin, $pSenha){

		if (right($pUrl, 1) != '/'){
			$pUrl = $pUrl . '/';
		}

		$this->url   = $pUrl;
		$this->login = $pLogin;
		$this->senha = $pSenha;
		$this->web = new Curl(1);
		$this->web->addPostVar('login_name',$pLogin);
		$this->web->addPostVar('passwd',$pSenha);
		$this->web->addPostVar('locale_id','en-US');			
		$texto = $this->web->exec($pUrl . 'login_up.php3');
		
		if (inStr("top.location='/'", $texto)== 0){
			return false;
		}
		
		$texto = $this->web->exec($this->url."plesk/reseller@/dashboard/");	

		$inicio = InStr("plesk\/reseller", $texto);
		$texto  = right($texto, strlen($texto)-$inicio);

		$inicio = InStr("@", $texto);
		$texto  = right($texto, strlen($texto)-$inicio);

		$fim    = InStr("\/", $texto);
		$this->idCliente = left($texto, $fim);
		$this->idCliente = str_replace('@', '', $this->idCliente);
		
		return true;
	}
	
	function getIdDominio($pDominio){

		$this->web->addPostVar('iesingletextinputworkaround', '');
		$this->web->addPostVar('filter', $pDominio);
		$this->web->addPostVar('filter_state', 'any');
		$this->web->addPostVar('filter_status', 'any');
		$this->web->addPostVar('filter_ownership', 'all');
		$this->web->addPostVar('filter_owner_pname', '');
		$this->web->addPostVar('subrows', 'off');
		$this->web->addPostVar('page', '0');
		$this->web->addPostVar('page_size', '25');
		$this->web->addPostVar('sort', 'name');
		$this->web->addPostVar('DomainsListSelectAll', 'true');
		$this->web->addPostVar('del[]', '');
		$this->web->addPostVar('cmd', 'setFilter');
		$this->web->addPostVar('lock', 'false');
		$this->web->addPostVar('previous_page', '');
		$this->web->addPostVar('wizaction', 'update');
		$urltmp = $this->url."plesk/client@any/domain@/";
		$texto = $this->web->exec($urltmp);

		
		$inicio = InStr("function b(event, turn_on_off_enabled)", $texto);
		$texto  = right($texto, strlen($texto)-$inicio);


		$pesq = $pDominio;
		$inicio = InStr($pesq, $texto) + strlen($pesq);
		$texto  = right($texto, strlen($texto)-$inicio);

		$pesq = $pDominio;
		$inicio = InStr($pesq, $texto) -70;
		$texto  = right($texto, strlen($texto)-$inicio);


		$pesq = "A href=";
		$inicio = InStr($pesq, $texto)+strlen($pesq)+1 ;
		$texto  = right($texto, strlen($texto)-$inicio);

		$fim        = InStr('"', $texto);
		$texto      = left($texto, $fim);

		$this->idDominio = str_replace('/plesk/client@'.$this->idCliente.'/domain@', '', $texto);
		return $this->idDominio;
	}
	
	function criadominio($pSite, $plano, $loginFtp, $SenhaFtp){

		// Vou  pegar o nome do plano
		$texto = $this->web->exec($this->url . "plesk/client@". $this->idCliente ."/domain@new/properties/");
		$inicio = InStr($plano, $texto)-30 ;
		if ($inicio <= 0){
			return 'Não foi possivel encontrar o plano em seu servidor plesk: '.$plano;
		}
		
		$texto  = right($texto, strlen($texto)-$inicio);

		$inicio = InStr("'", $texto);
		$texto  = right($texto, strlen($texto)-$inicio);

		$fim        = InStr("'", $texto);
		$texto      = left($texto, $fim);
		$idPlano    = $texto;
		
		
		set_time_limit(0);
		$urltmp = $this->url . "plesk/client@" . $this->idCliente . "/domain@new/properties/?www_prefix=true&domain_name=" . $pSite;
		$urltmp = $urltmp . "&ip_address_id=2&dom_tmpl_id=".$idPlano."&dom_service_mail=true&dom_service_dns=true&hosting_type=vrt_hst&";
		$urltmp = $urltmp . "login=".$loginFtp."&passwd=".$SenhaFtp."&confirm_passwd=".$SenhaFtp."&confirmed=yes&cmd=update&lock=false&previous_page=&wizstep=1&wizard=/plesk/client@".$this->idCliente."/domain@new/properties/&wizaction=&redirect=http://";
		$texto = $this->web->exec($urltmp);
		
		if (Instr("foi criado", $texto) > 0 || Instr("was createdr", $texto) > 0){
			return 1;
		}else{
			if (Instr("já existe", $texto) > 0 || Instr("already exists", $texto) > 0){
				return 2;
			}
		}
		return 2;
	}
	
	function setDadosAdm($idDominio, $senha, $personalName, $companyName, $phone, $fax, $email, $address, $city, $state, $zip){

		$this->web->addPostVar("status", "true");
		$this->web->addPostVar("password", $senha);
		$this->web->addPostVar("confirm", $senha);
		$this->web->addPostVar("max_button_length", "");
		$this->web->addPostVar("locale", "pt-BR");
		$this->web->addPostVar("skin_id", "8");
		$this->web->addPostVar("preset_id", "3");
		$this->web->addPostVar("multiply_login", "true");
		$this->web->addPostVar("disable_lock_screen", "false");
		$this->web->addPostVar("manage_phosting", "true");
		$this->web->addPostVar("manage_sh_access", "true");
		$this->web->addPostVar("manage_subdomains", "true");
		$this->web->addPostVar("manage_domain_aliases", "true");
		$this->web->addPostVar("manage_log", "true");
		$this->web->addPostVar("manage_subftp", "true");
		$this->web->addPostVar("manage_crontab", "true");
		$this->web->addPostVar("manage_dns", "true");
		$this->web->addPostVar("manage_webapps", "true");
		$this->web->addPostVar("manage_maillists", "true");
		$this->web->addPostVar("manage_spamfilter", "true");
		$this->web->addPostVar("site_builder", "true");
		$this->web->addPostVar("manage_webstat", "true");
		$this->web->addPostVar("manage_additional_permissions", "true");
		$this->web->addPostVar("manage_dashboard", "true");
		$this->web->addPostVar("personalName", $personalName);
		$this->web->addPostVar("companyName", $companyName);
		$this->web->addPostVar("phone", $phone);
		$this->web->addPostVar("fax", '');
		$this->web->addPostVar("email", $email);
		$this->web->addPostVar("address", $address);
		$this->web->addPostVar("city", $city);
		$this->web->addPostVar("state", $state);
		$this->web->addPostVar("zip", $zip);
		$this->web->addPostVar("country", "BR");
		$this->web->addPostVar("cmd", "update");
		$this->web->addPostVar("lock", "false");
		$this->web->addPostVar("previous_page", "");
		$this->web->addPostVar("wizaction", "");
		$this->web->addPostVar("bname_ok", "OK");
		set_time_limit(0);
		$urlPost = $this->url.'plesk/client@'.$this->idCliente.'/domain@'.$idDominio.'/domain-user/';
		//echo "/* $urlPost */";
		$texto = $this->web->exec($urlPost);
		
		//erro($texto);
	}
}

class cURL {
	/**
	 * Contains the vars to send by POST
	 * @var array
	 */
	private $postVars;
	
	/**
	 * cURL handler
	 * @var ressource
	 */
	private $ch;
	
	/**
	 * The headers to send
	 * @var string
	 */
    private $headers;
	
	/**
	 * The number of the current channel
	 * @var int
	 */
	private $n;
	
	/**
	 * The resulted text
	 * @var string
	 */
	private $r_text;
	
	/**
	 * The resulted headers
	 * @var string
	 */
	private $r_headers;
	
	/**
	 * Constructor
	 */
	public function __construct($n = 0) {
		putenv('TZ=US/Pacific');
		set_time_limit(0);
		$headers				= array();
		$headers['agent']		= 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)';
		$headers['cookie']		= '/tmp/curl/cookies/'.$n;
		$headers['randDate']	= mktime(0, 0, 0, date('m'), date('d') - rand(3,26),date('Y'));
		
		$this->n			= $n;
		$this->headers		= $headers;
		$this->postVars		= array();
		$this->ch			= curl_init();
	}
	
	/**
	 * Add post vars
	 * @param string $name
	 * @param stringe $value
	 */
	public function addPostVar($name,$value) {
		$this->postVars[$name] = $value;
	}
	
	/**
	 * Execute the request and return the result
	 * @param string $url
	 * @return string
	 */
	public function exec($url) {
		// Set the options
		set_time_limit(0);
		curl_setopt ($this->ch, CURLOPT_URL,$url);
		curl_setopt ($this->ch, CURLOPT_USERAGENT, $this->headers['agent']);
		curl_setopt ($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($this->ch, CURLOPT_TIMEOUT, 0);
		curl_setopt ($this->ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt ($this->ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt ($this->ch, CURLOPT_COOKIEJAR,  $this->headers['cookie']);
		curl_setopt ($this->ch, CURLOPT_COOKIEFILE,  $this->headers['cookie']);
		
		curl_setopt ($this->ch, CURLINFO_HEADER_OUT, true);
		curl_setopt ($this->ch, CURLOPT_HEADER, true);
		
		// Send the POST vars
		if (sizeof($this->postVars) > 0) {
			$postVars = '';
			foreach($this->postVars as $name => $value) {
				$postVars .= $name.'='.$value.'&';
			}
			$postVars = substr($postVars,0,strlen($postVars)-1);
			
			curl_setopt ($this->ch, CURLOPT_POSTFIELDS, $postVars);
			curl_setopt ($this->ch, CURLOPT_POST, 1);
			$this->postVars		= array();
		}
		
		// Execute and retrieve the result
		$t = '';
		//while ($r == '') {
			$t = curl_exec($this->ch);
		//}
		
		$this->r_text		= $t;
		$this->r_headers	= curl_getinfo($this->ch,CURLINFO_HEADER_OUT);
		
		return $this->r_text;
	}
	
	/**
	 * Return the resulted text
	 * @return string
	 */
	public function getResult() {
		return $this->r_text;
	}
	
	/**
	 * Return the headers
	 *
	 * @return string
	 */
	public function getHeader() {
		return $this->r_headers;
	}
} 
?>