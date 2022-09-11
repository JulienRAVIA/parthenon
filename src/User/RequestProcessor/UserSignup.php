<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022, all rights reserved.
 */

namespace Parthenon\User\RequestProcessor;

use Parthenon\Common\LoggerAwareTrait;
use Parthenon\Common\RequestHandler\RequestHandlerManagerInterface;
use Parthenon\User\Creator\UserCreator;
use Parthenon\User\Form\Type\UserSignUpType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserSignup
{
    use LoggerAwareTrait;

    public function __construct(
        private FormFactoryInterface $formFactory,
        private UserCreator $userCreator,
        private UserSignUpType $signUpType,
        private RequestHandlerManagerInterface $requestHandlerManager,
        private bool $selfSignupEnabled,
    ) {
    }

    public function process(Request $request)
    {
        $requestHandler = $this->requestHandlerManager->getRequestHandler($request);

        $formType = $this->formFactory->create(get_class($this->signUpType));

        if ($request->isMethod('POST')) {
            $inviteCode = $request?->get('code', null);

            if (!$this->selfSignupEnabled && is_null($inviteCode)) {
                $this->getLogger()->warning('A user sign up failed due not having an invite code while self sign up is disabled');

                return $requestHandler->generateErrorOutput($formType);
            }

            $requestHandler->handleForm($formType, $request);

            if ($formType->isSubmitted() && $formType->isValid()) {
                $user = $formType->getData();

                $this->userCreator->create($user);

                $this->getLogger()->info('A user has signed up successfully');

                return $requestHandler->generateSuccessOutput($formType);
            } else {
                $this->getLogger()->info('A user sign up failed due to form validation');

                return $requestHandler->generateErrorOutput($formType);
            }
        }

        return $requestHandler->generateDefaultOutput($formType);
    }
}
