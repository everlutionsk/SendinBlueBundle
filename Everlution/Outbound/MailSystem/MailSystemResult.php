<?php

namespace Everlution\SendinBlueBundle\Outbound\MailSystem;

use Everlution\EmailBundle\Message\Recipient\Recipient;
use Everlution\EmailBundle\Outbound\MailSystem\MailSystemMessageStatus;
use Everlution\EmailBundle\Outbound\MailSystem\MailSystemResult as MailSystemResultInterface;
use Everlution\EmailBundle\Outbound\Message\UniqueOutboundMessage;

class MailSystemResult implements MailSystemResultInterface
{
    const
        RESPONSE_CODE_FAILURE = 'failure',
        RESPONSE_KEY_CODE = 'code',
        RESPONSE_KEY_DATA = 'data',
        RESPONSE_KEY_MESSAGE = 'message',
        DATA_KEY_MESSAGE_ID = 'message-id';

    /** @var MailSystemMessageStatus[] */
    protected $mailSystemMessagesStatus;

    public function __construct(array $sendinBlueResult, UniqueOutboundMessage $uniqueMessage)
    {
        $recipients = $uniqueMessage->getMessage()->getRecipients();
        $this->mailSystemMessagesStatus = $this->createMailSystemMessagesStatus($sendinBlueResult, $recipients);
    }

    /**
     * {@inheritdoc}
     */
    public function getMailSystemMessagesStatus()
    {
        return $this->mailSystemMessagesStatus;
    }

    /**
     * @param array       $mandrillResult
     * @param Recipient[] $recipients
     *
     * @return MailSystemMessageStatus[]
     */
    protected function createMailSystemMessagesStatus(array $mandrillResult, array $recipients)
    {
        $messagesStatus = [];

        foreach ($recipients as $recipient) {
            $messagesStatus = $this->createMailSystemMessageStatus($mandrillResult, $recipient);
        }

        return $messagesStatus;
    }

    /**
     * @param array     $rawMessageStatus
     * @param Recipient $recipient
     *
     * @return MailSystemMessageStatus
     */
    protected function createMailSystemMessageStatus($rawMessageStatus, Recipient $recipient)
    {
        $rejectReason = null;
        $messageId = '';

        if ($rawMessageStatus[self::RESPONSE_KEY_CODE] == self::RESPONSE_CODE_FAILURE
            and isset($rawMessageStatus[self::RESPONSE_KEY_MESSAGE])
        ) {
            $rejectReason = $rawMessageStatus[self::RESPONSE_KEY_MESSAGE];
        }

        if (false === empty($rawMessageStatus[self::RESPONSE_KEY_DATA])
            and isset($rawMessageStatus[self::RESPONSE_KEY_DATA][self::DATA_KEY_MESSAGE_ID])
        ) {
            $messageId = $rawMessageStatus[self::RESPONSE_KEY_DATA][self::DATA_KEY_MESSAGE_ID];
        }

        return new MailSystemMessageStatus(
            $messageId,
            $rawMessageStatus[self::RESPONSE_KEY_CODE],
            $rejectReason,
            $recipient
        );
    }
}
