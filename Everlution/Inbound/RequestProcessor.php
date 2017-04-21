<?php

namespace Everlution\SendinBlueBundle\Inbound;

use Everlution\SendinBlueBundle\Support\AbstractRequestProcessor;
use Symfony\Component\HttpFoundation\Request;
use Everlution\EmailBundle\Inbound\Message\InboundMessage;
use Everlution\EmailBundle\Inbound\RequestProcessor as RequestProcessorInterface;

class RequestProcessor extends AbstractRequestProcessor implements RequestProcessorInterface
{
    /**
     * @param Request $request
     *
     * @return InboundMessage[]
     */
    public function createInboundMessages(Request $request)
    {
        return [];
    }

    /**
     * @param array $rawMessage
     *
     * @return InboundMessage
     */
    protected function createInboundMessage(array $rawMessage)
    {
        return new InboundMessage();
    }
}
