# SendinBlueBundle

This Symfony bundle provides *mail system* for [Email Bundle](https://github.com/everlutionsk/EmailBundle2). Bundle uses [SendinBlue](https://www.sendinblue.com) transactional email platform.


# Installation

```sh
composer require everlutionsk/sendin-blue-bundle
```


### Enable the bundle

```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Everlution\EmailBundle\EverlutionEmailBundle(),
        new Everlution\SendinBlueBundle\EverlutionSendinBlueBundle(),
    );
}
```


### Configure the bundle

Following configuration snippet describes how to configure the bundle.<br>

Firstly, you must modify EmailBundle configuration to work with SendinBlueBundle's services.

```yml
# app/config/config.yml

# EmailBundle Configuration
everlution_email:
    domain_name: '%domain%' # example.com
    mail_system: everlution.sendin_blue.mail_system
    async_stream: everlution.email.stream.kernel_terminate
    request_processors:
        inbound: everlution.sendin_blue.inbound.request_processor
        outbound_message_event: everlution.sendin_blue.outbound.message_event.request_processor
```

Secondly, you must configure SendinBlueBundle itself

```yml
# app/config/config.yml

# SendinBlueBundle Configuration
everlution_sendin_blue:
    api_key: SECRET_API_KEY
    timeout: int|null
```

**timeout** - [Optional] Email timeout in ms, default is 30000 (max. 60000)
# Usage

### Message transformers
*Mail system* service provided by this bundle transform [OutboundMessage](https://github.com/everlutionsk/EmailBundle2/blob/master/Outbound/Message/OutboundMessage.php) into JSON and then POST this JSON to [SendinBlue API](https://apidocs.sendinblue.com/).
However, this JSON can be modified just before it is posted to SendinBlue. To do this you must create a service, which implements [RawMessageTransformer interface](Outbound/MailSystem/RawMessageTransformer.php) and add following tag:

```yml
everlution.sendin_blue.outbound.raw_message_transformer
```


# TODO
----
- Request Processors
- Request signature calculation
- Webhook keys configuration
