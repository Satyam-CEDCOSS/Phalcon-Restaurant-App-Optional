<?php

use Phalcon\Mvc\Controller;


class AdminController extends Controller
{
    public function indexAction()
    {
        $data = $this->mongo->restaurant->find();
        foreach ($data as $value) {
            $this->view->data .= "<div class='col-lg-4 col-md-12 mb-4'>
            <div class='cards'>
                <div class='bg-image hover-overlay ripple' data-mdb-ripple-color='light'>
                    <img src=".$value->image." class='img-fluid' />
                     <a href=''>
                        <div class='mask' style='background-color: rgba(251, 251, 251, 0.15);'></div>
                    </a>
                </div>
                <div class='card-body'>
                    <h4 class='card-title'>Name: ".$value->name."</h4>
                    <p class='card-text'>Rating: ".$value->rating."/5</p>
                    <p class='card-text'>Area: ".$value->area."</p>
                </div>
                <div>
                    <a class='btn btn-warning' href='/admin/edit?id=$value->_id'>Edit</a>
                    <a class='btn btn-danger' href='/admin/delete?id=$value->_id'>Delete</a>
                </div>
            </div>
        </div>";
        }
        $info = $this->mongo->users->find();
        foreach ($info as $value) {
            $this->view->user .= "<tr>
            <td>".$value->name."</td> <td>".$value->email."</td> <td>".$value->type."</td></tr>";
        }
        $detail = $this->mongo->review->find();
        foreach ($detail as $value) {
            $this->view->review .= "<tr>
            <td>".$value->u_id."</td><td>".$value->r_id."</td><td>".$value->food."</td>
            <td>".$value->review."</td></tr>";
        }
    }
    public function restaurantAction()
    {
        // Redirect to view
    }
    public function addAction()
    {
        $arr = [
            'name' => $_POST['name'],
            'area' => $_POST['area'],
            'zipcode' => $_POST['zipcode'],
            'image' => $_POST['image'],
            'rating' => $_POST['rating'],
            'manager' => $_POST['manager'],
            'm_email' => $_POST['email'],
        ];
        $success = $this->mongo->restaurant->insertOne($arr);
        if ($success) {
            $this->response->redirect('/admin');
        }
    }
    public function editAction()
    {
        $this->view->data = $this->mongo->restaurant->findOne(array("_id" => new MongoDB\BSON\ObjectId($_GET['id'])));
    }
    public function updateAction()
    {
        $success = $this->mongo->restaurant->
        updateOne(array("_id" => new MongoDB\BSON\ObjectId($_GET['id'])), array('$set' => $_POST));
        if ($success) {
            $this->response->redirect("/admin");
        }
    }
    public function deleteAction()
    {
        $success = $this->mongo->restaurant->deleteOne(array("_id" => new MongoDB\BSON\ObjectId($_GET['id'])));
        if ($success) {
            $this->response->redirect("/admin");
        }
    }
}
