<?php

namespace App\Controller;

use App\Service\ApiHandler;

class ApiController extends AbstractController
{
    private ApiHandler $apiHandler;

    public function __construct()
    {
        $this->apiHandler = new ApiHandler();
        parent::__construct();
    }

    /**
     * @Route("/api/v1/user/add", name="app_api_v1_user_add", method="POST")
     */
    public function addUser()
    {
        $user = $this->security->getUser();
        if (!$user || !$user->isAdmin()) return $this->httpError(403, "Access Denied!");
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) return $this->renderError('Request is empty!');

        return $this->renderJson($this->apiHandler->addUser($data));
    }

    /**
     * @Route("/api/v1/user/remove", name="app_api_v1_user_remove", method="POST")
     */
    public function removeUser()
    {
        $user = $this->security->getUser();
        if (!$user || !$user->isAdmin()) return $this->httpError(403, "Access Denied!");

        $data = json_decode(file_get_contents('php://input'));
        if (!$data) return $this->renderError('Request is empty!');

        return $this->renderJson($this->apiHandler->deleteUsers($data));
    }

    /**
     * @Route("/api/v1/user/edit", name="app_api_v1_user_edit", method="POST")
     */
    public function editUser()
    {
        $user = $this->security->getUser();
        if (!$user) return $this->httpError(403, "Access Denied!");

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) return $this->renderError('Request is empty!');
        if(!$user->isAdmin() && $user->getId() != $data['id']) return $this->httpError(403, "Access Denied!");

        return $this->renderJson($this->apiHandler->editUser($data));
    }

}