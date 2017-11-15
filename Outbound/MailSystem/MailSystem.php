<?php

namespace Everlution\SendinBlueBundle\Outbound\MailSystem;

use DateTime;
use Everlution\EmailBundle\Outbound\MailSystem\MailSystemException;
use Everlution\EmailBundle\Outbound\MailSystem\MailSystem as MailSystemInterface;
use Everlution\EmailBundle\Outbound\Message\UniqueOutboundMessage;
use Sendinblue\Mailin;

class MailSystem implements MailSystemInterface
{
    /** @var Mailin */
    protected $mailin;

    /** @var RawMessageTransformer[] */
    protected $rawMessageTransformers = [];

    /** @var MessageConverter */
    protected $messageConverter;

    /**
     * MailSystem constructor.
     *
     * @param $baseUrl
     * @param $apiKey
     * @param $timeout
     * @param MessageConverter $messageConverter
     */
    public function __construct($baseUrl, $apiKey, $timeout, MessageConverter $messageConverter)
    {
        $this->mailin = new Mailin($baseUrl, $apiKey, $timeout);
        $this->messageConverter = $messageConverter;
    }

    /**
     * @param RawMessageTransformer $transformer
     */
    public function addRawMessageTransformer(RawMessageTransformer $transformer)
    {
        $this->rawMessageTransformers[] = $transformer;
    }

    /**
     * @param UniqueOutboundMessage $uniqueMessage
     *
     * @return MailSystemResult
     */
    public function sendMessage(UniqueOutboundMessage $uniqueMessage)
    {
        return $this->sendMessageToSendinBlue($uniqueMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function scheduleMessage(UniqueOutboundMessage $uniqueMessage, DateTime $sendAt)
    {
        return $this->sendMessageToSendinBlue($uniqueMessage);
    }

    protected function sendMessageToSendinBlue(UniqueOutboundMessage $uniqueMessage)
    {
        $rawMessage = $this->messageConverter->convertToRawMessage($uniqueMessage);
        $this->transformRawMessage($rawMessage);

        $result = $this->sendRawMessage($rawMessage);

        return new MailSystemResult($result, $uniqueMessage);
    }

    /**
     * @param array $rawMessage
     */
    protected function transformRawMessage(array &$rawMessage)
    {
        foreach ($this->rawMessageTransformers as $transformer) {
            $transformer->transform($rawMessage);
        }
    }

    /**
     * @param $rawMessage
     *
     * @return mixed
     *
     * @throws MailSystemException
     */
    protected function sendRawMessage($rawMessage)
    {
        try {
            return $this->mailin->send_email($rawMessage);
        } catch (\Exception $e) {
            return [
                'code' => 'failure',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @return string
     */
    public function getMailSystemName()
    {
        return 'sendin_blue';
    }
}
