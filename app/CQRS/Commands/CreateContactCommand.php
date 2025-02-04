<?php
namespace App\CQRS\Commands;

use App\Repositories\ContactRepository;

class CreateContactCommand
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function execute(array $data)
    {
        return $this->contactRepository->create($data);
    }
}
