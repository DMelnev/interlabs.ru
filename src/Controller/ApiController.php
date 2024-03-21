<?php

namespace App\Controller;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/v1/user/add", name="app_api_v1_user_add", method="POST")
     */
    public function addUser()
    {
        $user = $this->security->getUser();
        if (!$user) return $this->httpError(403, "Access Denied!");

        return $this->renderJson(['test' => 'ok']);
    }
}