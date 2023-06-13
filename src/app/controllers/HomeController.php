<?php

use Phalcon\Mvc\Controller;


class HomeController extends Controller
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
                    <a class='btn btn-warning' href='/home/view?id=$value->_id'>View</a>
                </div>
            </div>
        </div>";
        }
    }
    public function viewAction()
    {
        $data = $this->mongo->food->find(array("r_id" => new MongoDB\BSON\ObjectId($_GET['id'])));
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
                    <a class='btn btn-primary' href='/home/buy?id=$value->_id'>Buy Now</a>
                    <a class='btn btn-warning' href='/home/review?id=$value->_id'>Review</a>
                </div>
            </div>
        </div>";
        }
    }
    public function buyAction()
    {
        $info = $this->mongo->food->findOne(array("_id" => new MongoDB\BSON\ObjectId($_GET['id'])));

        $arr = [
            'name' => $info['name'],
            'price' => $info['price'],
            'image' => $info['image'],
            'rating' => $info['rating'],
            'r_id' => $info['r_id'],
            'u_id' => $this->session->get('id'),
            'status' => 'in-process'
        ];
        $success = $this->mongo->orders->insertOne($arr);
        if ($success) {
            $this->response->redirect('/home');
        }
    }
    public function orderAction()
    {
        $data = $this->mongo->orders->find(array("u_id" => new MongoDB\BSON\ObjectId($this->session->get('id'))));
        foreach ($data as $value) {
            $this->view->data .= " <div class='col-lg-4 col-md-12 mb-4'>
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
                    <p class='card-text'>Origin: ".$value->status."</p>
                </div>
            </div>
        </div>";
        }
    }
    public function reviewAction()
    {
        $this->view->data = $_GET['id'];
    }
    public function sendAction()
    {
        $info = $this->mongo->food->findOne(array("_id" => new MongoDB\BSON\ObjectId($_GET['id'])));
        $arr = [
            'u_id' => $this->session->get('id'),
            'r_id' => $info->r_id,
            'food' => $info->name,
            'review' => $_POST['review'],
        ];
        $success = $this->mongo->review->insertOne($arr);
        if ($success) {
            $this->response->redirect('/home');
        }
    }
}
