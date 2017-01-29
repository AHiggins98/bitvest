<?php

namespace App\Model;

use App\Util\Session;

class Menu
{

    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getLinks()
    {

        $links = [
            [
                'route' => '',
                'label' => 'CoinShare - Job Listings',
            ]
        ];

        if (!$this->session->get('loggedIn')) {
            $links[] = [
                'route' => 'user/login',
                'label' => 'Login',
            ];

            $links[] = [
                'route' => 'user/signup',
                'label' => 'Signup',
            ];
        } else {
            $links[] = [
                'route' => 'user/jobs',
                'label' => 'My jobs',
            ];

            $links[] = [
                'route' => 'biz/start',
                'label' => 'Start a business',
            ];

            $links[] = [
                'route' => 'user/account',
                'label' => 'My account',
            ];

            $links[] = [
                'route' => 'user/logout',
                'label' => 'Logout',
            ];
        }

        $links[] = [
            'route' => 'help/faq',
            'label' => 'Help',
        ];
        
        return $links;
    }

}
