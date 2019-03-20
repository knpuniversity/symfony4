<?php

namespace App\Controller;

use Faker\Factory;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends BaseController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function index(LoggerInterface $logger)
    {
        $faker = Factory::create();
        $this->addFlash('success', sprintf(
            'Say hello to our new user @%s who registered just a few minutes ago!',
            $faker->userName
        ));
        $totalUsersOnline = $faker->numberBetween(1, 5);

        $logger->debug('Checking account page for '.$this->getUser()->getEmail());
        return $this->render('account/index.html.twig', [
            'totalUsersOnline' => $totalUsersOnline,
        ]);
    }

    /**
     * @Route("/api/account", name="api_account")
     */
    public function accountApi()
    {
        $user = $this->getUser();

        return $this->json($user, 200, [], [
            'groups' => ['main'],
        ]);
    }
}
