<?php

namespace Everlution\SendinBlueBundle\Support;

use Everlution\EmailBundle\Support\RequestSignatureVerifier;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRequestProcessor implements RequestSignatureVerifier
{
    /**
     * @param Request $request
     *
     * @return bool
     */
    public function isRequestSignatureCorrect(Request $request)
    {
        return true;
    }
}
