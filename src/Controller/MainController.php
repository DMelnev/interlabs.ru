<?php
namespace App\Controller;

use App\Entity\User;
use App\Service\dbHandler;

class MainController
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
        return $result;
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

        echo 'sdvsd '. $name;
    }

}