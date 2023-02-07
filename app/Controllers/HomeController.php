<?php
namespace App\Controllers;
use App\Controllers\Controller;

use App\Models\Contact;
class HomeController extends Controller
{
    public function index()
    {
        $contactModel = new Contact();
        $contacts =  $contactModel->create([
            'name' => 'Juan Laura',
            'lastname' => 'Padilla',
            'age' => 20,
        ]);

        return $this->view('home', ['contacts' => $contacts]);
    }
}
?>