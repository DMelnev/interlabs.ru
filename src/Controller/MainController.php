<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\dbHandler;

class MainController extends AbstractController
{
    private DbHandler $db;

    public function __construct()
    {
        $this->db = new dbHandler();
    }

    /**
     * @Route("/", method = "GET")
     */
    public function index()
    {
        $sql = 'SELECT * FROM user';
        $params = [];
        $result = $this->db->query($sql, $params, User::class);
        return $this->render('/templates/content/list.php', ['users' => $result]);
    }

    /**
     * @Route("/name/{name}", method="GET")
     */
    public function getName(string $name)
    {
//        $sql = 'SELECT * FROM user';
//        $params = [];
//        $result = $this->db->query($sql, $params, User::class);
//        return $result;

        return $this->render('/templates/content/list.php', ['test' => 'test123']);
    }

}