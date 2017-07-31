<?php

namespace Everlution\SendinBlueBundle\Outbound\MailSystem;

use Everlution\EmailBundle\Attachment\Attachment;
use Everlution\EmailBundle\Outbound\Message\UniqueOutboundMessage;
use Everlution\EmailBundle\Outbound\Message\OutboundMessage;

class MessageConverter
{
    /**
     * @param UniqueOutboundMessage $uniqueMessage
     *
     * @return array
     */
    public function convertToRawMessage(UniqueOutboundMessage $uniqueMessage)
    {
        $message = $uniqueMessage->getMessage();

        return [
            'headers' => $this->getRawHeaders($uniqueMessage),
            'subject' => $message->getSubject(),
            'html' => $message->getHtml(),
            'from' => $this->getRawFrom($message),
            'text' => $message->getText(),
            'to' => $this->getRawRecipients($message),
            'replyto' => $this->getRawReplyTo($message),
            'inline_image' => $this->getRawImages($message->getImages()),
            'attachment' => $this->getRawAttachments($message->getAttachments()),
        ];
    }

    /**
     * @param OutboundMessage $message
     *
     * @return array
     */
    protected function getRawFrom(OutboundMessage $message)
    {
        return [$message->getFromEmail(), $message->getFromName()];
    }
    
    /**
     * @param OutboundMessage $message
     *
     * @return array
     */
    protected function getRawReplyTo(OutboundMessage $message)
    {
        return false === empty($message->getReplyTo()) ? [$message->getReplyTo(), ''] : [];
    }

    /**
     * @param UniqueOutboundMessage $uniqueMessage
     *
     * @return array
     */
    protected function getRawHeaders(UniqueOutboundMessage $uniqueMessage)
    {
        $message = $uniqueMessage->getMessage();
        $headers = [];

        $headers['Message-ID'] = $uniqueMessage->getMessageId();

        if ($message->getReplyTo() !== null) {
            $headers['Reply-To'] = $message->getReplyTo();
        }
        if ($message->getReferences() !== null) {
            $headers['References'] = $message->getReferences();
        }
        if ($message->getInReplyTo() !== null) {
            $headers['In-Reply-To'] = $message->getInReplyTo();
        }

        $headers = array_merge($headers, $message->getCustomHeaders());

        return $headers;
    }

    /**
     * @param OutboundMessage $message
     *
     * @return array
     */
    protected function getRawRecipients(OutboundMessage $message)
    {
        $recipients = [];

        foreach ($message->getRecipients() as $recipient) {
            $recipients[$recipient->getEmail()] = $recipient->getName();
        }

        return $recipients;
    }

    /**
     * @param Attachment[] $images
     *
     * @return array
     */
    protected function getRawImages(array $images)
    {
        return $this->getRawAttachments($images);
    }

    /**
     * @param Attachment[] $attachments
     *
     * @return array
     */
    protected function getRawAttachments(array $attachments)
    {
        $processedAttachments = [];

        foreach ($attachments as $attachment) {
            $processedAttachments[$attachment->getName()] = chunk_split(base64_encode($attachment->getContent()));
        }

        return $processedAttachments;
    }
}
