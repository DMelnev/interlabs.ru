<?php

namespace App\Controller;

class SecurityController extends AbstractController_
{

    /**
     * @Route("/login", name="app_login")
     */
    public function login()
    {
        $error = null;

        $login = $_POST['login'] ?? null;
        $plainPassword = $_POST['password'] ?? '';
        if ($login) {
            if ($this->security->authorise($login, $plainPassword)) {
                return $this->redirectToLast();
            } else {
                $error = 'Не верный логин или пароль!';
            }
        }

        return $this->render('/templates/content/login.php', ['error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logOut()
    {
        $this->security->unAuthorise();

        return $this->redirectToRoute('app_login');
    }
}