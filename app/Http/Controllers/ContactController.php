<?php
namespace App\Http\Controllers;

use App\CQRS\Commands\CreateContactCommand;
use App\CQRS\Commands\UpdateContactCommand;
use App\CQRS\Commands\DeleteContactCommand;
use App\CQRS\Queries\GetContactsQuery;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $createContactCommand;
    protected $updateContactCommand;
    protected $deleteContactCommand;
    protected $getContactsQuery;

    public function __construct(
        CreateContactCommand $createContactCommand,
        UpdateContactCommand $updateContactCommand,
        DeleteContactCommand $deleteContactCommand,
        GetContactsQuery $getContactsQuery
    ) {
        $this->createContactCommand = $createContactCommand;
        $this->updateContactCommand = $updateContactCommand;
        $this->deleteContactCommand = $deleteContactCommand;
        $this->getContactsQuery = $getContactsQuery;
    }

    public function index()
    {
        $contacts = $this->getContactsQuery->execute();
        return view('contacts.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => ['required', 'unique:contacts', 'regex:/^09[0-9]{9}$/'],
        ]);

        $this->createContactCommand->execute($data);
        return redirect()->route('contacts.index');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
        ]);

        $this->updateContactCommand->execute($id, $data);
        // return redirect()->route('contacts.index');
        return response()->json(['message' => 'مخاطب با موفقیت ویرایش شد']);
    }

    public function destroy($id)
    {
        $this->deleteContactCommand->execute($id);
        return response()->json(['message' => 'مخاطب با موفقیت حذف شد']);
    }
}
