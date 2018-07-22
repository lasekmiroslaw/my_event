<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
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
        // check App\Security\GithubAuthenticator
    }
}