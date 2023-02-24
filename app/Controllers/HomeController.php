<?php
namespace App\Controllers;
use App\Models\Contact;

class HomeController extends Controller
{
    public function instance()
    {
        $instance = new Contact;
        return $instance;
    }
    public function index()
    {
        $contac =  $this->instance()->all();
        return $this->view('home', ['contacts' => $contac]);
    }
}
?>

