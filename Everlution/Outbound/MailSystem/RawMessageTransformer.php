<?php

namespace Everlution\SendinBlueBundle\Outbound\MailSystem;

interface RawMessageTransformer
{
    /**
     * @param array &$rawMessage
     */
    public function transform(array &$rawMessage);
}
