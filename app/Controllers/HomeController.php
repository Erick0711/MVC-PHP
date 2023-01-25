<?php
namespace App\Controllers;
use App\Controllers\Controller;

use App\Models\Contact;
class HomeController extends Controller
{
    public function index()
    {
        $contactModel = new Contact();
        return $this->view('home', [
            'title' => 'Home',
            'description' => 'Esta es la pagina home'
        ]);
    }
}
?>