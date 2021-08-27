# Take Blip - SDK PHP

SDK PHP para a API de Notificações WhatsApp da Take Blip

## Requisitos

* PHP >= 7.4

## Descrição

SDK PHP para a [API de Notificações WhatsApp da Take Blip](https://docs.blip.ai/#whatsapp).

## Instalação

Via Composer

```bash
composer require vitorccs/takeblip-php
```

## Parâmetros

Parâmetro | Obrigatório | Padrão | Comentário
------------ | ------------- | ------------- | -------------
TAKEBLIP_API_KEY | Sim | null | Token de acesso da API
TAKEBLIP_API_TIMEOUT | Não | 20 | Timeout em segundos para estabelecer conexão com a API

## Como usar

1) Os parâmetros podem ser definidos por variáveis de ambiente:

```php
putenv('TAKEBLIP_API_KEY=myApiKey');
putenv('TAKEBLIP_API_TIMEOUT=20');
```

ou passados como argumento de instância:

```php
new \TakeBlip\TakeBlip($token, $timeout);
```

2) Em seguida, basta solicitar os endpoints:

```php
$takeBlip = new \TakeBlip\TakeBlip();

// Obter Templates de Mensagens 
$response = $takeBlip->getMessageTemplates();

// Obter Identificador do usuário (altere este número)
$response = $takeBlip->getUserIdentity('551190000000');

// Enviar Notificação Ativa
$takeBlip->sendNotification($template);

// Consultar os eventos da Notificação disparada
$takeBlip->getNotificationEvents('myNotificationId');
```

## Tratamento de erros

Esta biblioteca lança as seguintes exceções:

* `HttpClientException` para erros HTTP 4xx
* `HttpServerException` para erros HTTP 5xx

Importante: como a API da Take Blip sempre retorna código HTTP 2xx (sucesso) mesmo quando ela não foi bem-sucedida, foi
implementando um tratamento que verifica o corpo da resposta, e caso encontre o valor "failure", ele lançará uma exceção
do tipo `HttpClientException`.

Exemplo de corpo da resposta:

```json
{
    "method": "get",
    "status": "failure",
    "reason": {
        "code": 61,
        "description": "The string supplied did not seem to be a phone number."
    }
}
```

## Exemplo de implementação

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/vendor/autoload.php';

putenv('TAKEBLIP_API_KEY=myApiKey');

use TakeBlip\Exceptions\HttpClientException;
use TakeBlip\Exceptions\HttpServerException;

try {
    $takeBlip = new \TakeBlip\TakeBlip();
    
    # Step 1/4 - Obter Identificador do usuário
    $response = $takeBlip->getUserIdentity('551190000000');
    $userIdentity = $response->resource->identity;
        
    # Step 2/4 - Construir o Template da Mensagem
    $builder = new \TakeBlip\Builders\TemplateBuilder();
    $template = $builder
        ->create('user_identity',
            'my_template_name',
            'my_template_namespace')
        ->addVariable('MyVar1')
        ->addVariable('MyVar2')
        ->addReply('QuickReply1')
        ->addReply('QuickReply2')
        ->setUrl('https://www.domain.com/boleto.pdf', 'document', 'BoletoBancario.pdf')
        ->get();
            
    # Step 3/4 - Enviar Notificação Ativa
    $takeBlip->sendNotification($template);
    
    # Step 4/4 - Consultar os eventos da Notificação (opcional)
    // Esta consulta não deveria ser feita imediatamente após o disparo
    // e os eventos podem levar vários minutos para chegarem
    sleep(5); // apenas para fins de teste
    $response = $takeBlip->getNotificationEvents($template->id);
    print_r($response);
   
} catch (HttpClientException $e) { // erros de cliente (HTTP 4xx)
    echo sprintf('Client: %s (%s)', $e->getMessage(), $e->getHttpCode());
} catch (HttpServerException $e) { // erros de servidor (HTTP 5xx)
    echo sprintf('Server: %s (%s)', $e->getMessage(), $e->getHttpCode());
} catch (\Exception $e) { // demais erros
    echo $e->getMessage();
}
```

## Aviso

O template da mensagem precisa estar em acordo com o template aprovado no WhatsApp.

Para diminuir o riscos de erros, foi implementado o construtor de templates `TemplateBuilder`.

Contudo, ele não será capaz de identificar determinados erros:

* Dados inválidos para Identity, Nome de template ou Namespace
* O template possui URL e esta não foi fornecida ou apresenta falha
* A quantidade de Variáveis ou Quick Replies está incorreta
* Definir URL, Variável ou Quick Reply sem o template possuir

Os erros mencionados acima não conseguem ser detectados pela API Take Blip no momento do disparo, que irá disparar como
sucesso (HTTP 2xx). Somente poderá ser identificado consultando o endpoint `getNotificationEvents`.

## Testes

Caso queira contribuir, por favor, implementar testes de unidade em PHPUnit.

Para executar:

1) Faça uma cópia de phpunit.xml.dist em phpunit.xml na raíz do projeto
2) Execute o comando abaixo no terminal dentro da pasta deste projeto:

```bash
composer test
```

## Anexos

* [Tutorial Notificações WhatsApp da Take Blip](https://help.blip.ai/hc/pt-br/articles/360057514334-Como-enviar-notifica%C3%A7%C3%B5es-WhatsApp-via-API-do-Blip)
 
