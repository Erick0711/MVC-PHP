<?php
namespace App\Controllers;
use App\Models\Contact;

class ContactController extends Controller
{
    public function instance()
    {
        $instance = new Contact;
        return $instance;
    }
    public function index()
    {
        return $this->instance()->where('id', '>', 3)
                                ->where('id', '<=', 41)
                                ->get();
        if(isset($_GET['search']))
        {
            $contacts = $this->instance()->where('direccion','LIKE' ,'%' . $_GET['search'] . '%')->paginate(3);
            return $this->view('contact.index', compact('contacts'));
        }else{
            $contacts =  $this->instance()->paginate(3);
            return $this->view('contact.index', compact('contacts'));
        }
    }
    public function create()
    {
        return $this->view('contact.create');
    }
    public function store()
    {
        $data = $_POST;
        $this->instance()->create($data);
        $this->redirect('contacts/create');
    }
    public function show($id)
    {
        $contact = $this->instance()->find($id);
        return $this->view('contact.show', compact('contact'));
    }
    public function edit($id)
    {
        $contact = $this->instance()->find($id);
        return $this->view('contact.edit', compact('contact'));
    }
    public function update($id)
    {
        $data = $_POST;
        $this->instance()->update($id, $data);
        $this->redirect('contact/show');
    }
    public function destroy()
    {
       
    }
}
?>

