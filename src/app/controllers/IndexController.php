<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {
        // Redirect to view
    }
    public function checkAction()
    {
        $check = $this->mongo->users->findOne(['$and' => [
            ['email' => $_POST['email']],
            ['password' => $_POST['password']]
        ]]);
        if ($check['_id']) {
            if ($check['type']=='admin') {
                $this->response->redirect('/admin');
            } elseif ($check['type']=='manager') {
                $this->session->set('email', $check['email']);
                $this->response->redirect('/manager');
            } else {
                $this->session->set('id', $check['_id']);
                $this->logger
                    ->excludeAdapters(['error'])
                    ->info("Login Successful => Name: " . $check["name"] . " Email: " . $check["email"]);
                $this->response->redirect('/home');
            }
        } else {
            $this->logger
                ->excludeAdapters(['login'])
                ->error("Authentication Failed => Email: " . $_POST["email"] . " Password: " . $_POST["password"]);
                $this->response->redirect('/');
        }
    }
}
