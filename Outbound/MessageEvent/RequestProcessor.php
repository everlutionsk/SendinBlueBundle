<?php

namespace Everlution\SendinBlueBundle\Outbound\MessageEvent;

use Everlution\SendinBlueBundle\Support\AbstractRequestProcessor;
use Symfony\Component\HttpFoundation\Request;
use Everlution\EmailBundle\Outbound\MessageEvent\MessageEvent;
use Everlution\EmailBundle\Outbound\MessageEvent\RequestProcessor as RequestProcessorInterface;

class RequestProcessor extends AbstractRequestProcessor implements RequestProcessorInterface
{
    /**
     * @param Request $request
     *
     * @return MessageEvent[]
     */
    public function createMessageEvents(Request $request)
    {
        return [];
    }
}
