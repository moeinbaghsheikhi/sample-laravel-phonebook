<?php
namespace App\CQRS\Queries;

use App\Repositories\ContactRepository;

class GetContactsQuery
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function execute()
    {
        return $this->contactRepository->getAll();
    }
}