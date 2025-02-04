<?php
namespace App\Repositories;

use App\Models\Contact;

class ContactRepository
{
    public function getAll()
    {
        return Contact::all();
    }

    public function findById($id)
    {
        return Contact::findOrFail($id);
    }

    public function create($data)
    {
        return Contact::create($data);
    }

    public function update($id, $data)
    {
        $contact = Contact::findOrFail($id);
        $contact->update($data);
        return $contact;
    }

    public function delete($id)
    {
        return Contact::destroy($id);
    }
}
