<?php

namespace App\CQRS\Commands;

use App\Repositories\ContactRepository;

class DeleteContactCommand
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function execute(int $id)
    {
        return $this->contactRepository->delete($id);
    }
}
