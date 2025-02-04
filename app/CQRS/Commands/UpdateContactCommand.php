<?php

namespace App\CQRS\Commands;

use App\Repositories\ContactRepository;

class UpdateContactCommand
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function execute(int $id, array $data)
    {
        return $this->contactRepository->update($id, $data);
    }
}
