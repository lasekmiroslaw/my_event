<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GithubController extends Controller
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/github")
     */
    public function connectAction()
    {
        // will redirect to Github!
        return $this->get('oauth2.registry')
            ->getClient('github')// key used in knpu_oatuh2.yml
            ->redirect();
    }

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in knpu_oatuh2.yml
     *
     * @Route("/connect/github/check", name="connect_github_check")
     */
    public function connectCheckAction(Request $request)
    {
        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GithubClient $client */
        $client = $this->get('oauth2.registry')
            ->getClient('github');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\GithubResourceOwner $user */
            $user = $client->fetchUser();
            $username = $user->getNickname();
            $email = $user->getEmail();

            if ($this->getDoctrine()->getRepository(User::class)->findOneByUsername($username) === null) {
                $newUser = new User();
                $newUser->setUsername($username);
                $newUser->setEmail($email);
                $newUser->setRoles([$newUser::ROLE_USER]);
                $newUser->setPlainPassword('password');

                $em = $this->getDoctrine()->getManager();
                $em->persist($newUser);
                $em->flush();
            }

            return $this->redirectToRoute('home');
        } catch (IdentityProviderException $e) {
            var_dump($e->getMessage());
            die;
        }
    }
}