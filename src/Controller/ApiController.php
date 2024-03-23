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
        $inputJSON = file_get_contents('php://input');
        if (!$inputJSON
            || $inputJSON == '[]'
            || $inputJSON == '{}'
        ) return $this->renderJson(['status' => 'error', 'messages' => ['unknown' => 'Request is empty!']]);

        return $this->renderJson($this->apiHandler->addUser(json_decode($inputJSON, true)));
    }

    /**
     * @Route("/api/v1/user/remove", name="app_api_v1_user_remove", method="POST")
     */
    public function removeUser()
    {
        $user = $this->security->getUser();
        if (!$user || !$user->isAdmin()) return $this->httpError(403, "Access Denied!");

        $inputJSON = file_get_contents('php://input');
        if (!$inputJSON) return $this->renderJson(['status' => 'error', 'message' => 'Request is empty!']);

        $this->apiHandler->deleteUsers(json_decode($inputJSON));

        return $this->renderJson(['status' => 'success']);
    }

    /**
     * @Route("/api/v1/user/edit", name="app_api_v1_user_edit", method="POST")
     */
    public function editUser()
    {
        $user = $this->security->getUser();
        if (!$user) return $this->httpError(403, "Access Denied!");

        $inputJSON = file_get_contents('php://input');
        if (!$inputJSON) return $this->renderJson(['status' => 'error', 'message' => 'Request is empty!']);

        return $this->renderJson(['test' => 'ok']);
    }

}