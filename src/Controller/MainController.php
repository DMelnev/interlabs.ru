<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\DbHandler;

class MainController extends AbstractController_
{
    private DbHandler $db;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->db = new DbHandler();
        $this->userRepository = new UserRepository();
        parent::__construct();
    }

    /**
     * @Route("/", name="app_main", method = "GET")
     */
    public function index()
    {
        $user = $this->security->getUser();
        if (!$user) return $this->redirectToRoute('app_login');

        return $this->render('/templates/content/list.php', [
            'users' => $this->userRepository->getAll(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/name/{name}", name="app_name_get", method="GET")
     */
    public function getName(string $name)
    {
        $user = $this->security->getUser();
        if (!$user) return $this->redirectToRoute('app_login');

        return $this->render('/templates/content/list.php', [
            'users' => $this->userRepository->getAll(),
            'name' => $name,
            'user' => $user,
        ]);
    }

}