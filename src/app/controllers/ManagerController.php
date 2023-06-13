<?php

use Phalcon\Mvc\Controller;


class ManagerController extends Controller
{
    public function indexAction()
    {
        $data = $this->mongo->food->find();
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
                    <p class='card-text'>Price: ".$value->price."Rs</p>
                    <p class='card-text'>Rating: ".$value->rating."/5</p>
                    <p class='card-text'>Origin: ".$value->origin."</p>
                </div>
                <div>
                    <a class='btn btn-warning' href='/manager/edit?id=$value->_id'>Edit</a>
                    <a class='btn btn-danger' href='/manager/delete?id=$value->_id'>Delete</a>
                </div>
            </div>
        </div>";
        }
        $info = $this->mongo->restaurant->findOne(['m_email'=>$this->session->get('email')]);
        $detail = $this->mongo->orders->find(array("r_id" => new MongoDB\BSON\ObjectId($info->_id)));
        foreach ($detail as $value) {
            $this->view->order .= "<tr>
            <td>".$value->name."</td>
            <td>".$value->price."</td>
            <td>".$value->u_id."</td>
            </tr>";
        }
    }
    public function foodAction()
    {
        // Redirect to view
    }
    public function addAction()
    {
        $info = $this->mongo->restaurant->findOne(['m_email'=>$this->session->get('email')]);
        $arr = [
            'name' => $_POST['name'],
            'rating' => $_POST['rating'],
            'price' => $_POST['price'],
            'image' => $_POST['image'],
            'origin' => $_POST['origin'],
            'r_id' => $info->_id,
        ];
        $success = $this->mongo->food->insertOne($arr);
        if ($success) {
            $this->response->redirect('/manager');
        }
    }
    public function editAction()
    {
        $this->view->data = $this->mongo->food->findOne(array("_id" => new MongoDB\BSON\ObjectId($_GET['id'])));
    }
    public function updateAction()
    {
        $success = $this->mongo->food->
        updateOne(array("_id" => new MongoDB\BSON\ObjectId($_GET['id'])), array('$set' => $_POST));
        if ($success) {
            $this->response->redirect("/manager");
        }
    }
    public function deleteAction()
    {
        $success = $this->mongo->food->deleteOne(array("_id" => new MongoDB\BSON\ObjectId($_GET['id'])));
        if ($success) {
            $this->response->redirect("/manager");
        }
    }
}
