<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as MainAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractController extends MainAbstractController
{
    private Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function logData()
    {
        if ($this->container->get('user_log')) {
            /** @var User|null $user */
            $user = $this->getUser();
            if ($user && $user->getLog()) {
                file_put_contents('/app/var/log/user.log', 'DATE: '.date('Y-m-d H:i:s').': '. $this->request->getContent() . "\n", FILE_APPEND);
            }
        }
    }
}
