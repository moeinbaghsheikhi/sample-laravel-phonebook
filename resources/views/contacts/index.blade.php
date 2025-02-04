@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">لیست مخاطبین</div>
            <div class="card-body">
                <table class="table table-striped table-borderless">
                    <thead class="table-dark">
                        <tr>
                            <th>نام</th>
                            <th>نام خانوادگی</th>
                            <th>تلفن</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $contact->first_name }}</td>
                                <td>{{ $contact->last_name }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>
                                    <!-- دکمه ویرایش -->
                                    <button class="btn btn-warning btn-sm edit-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editContactModal" 
                                        data-id="{{ $contact->id }}" 
                                        data-first_name="{{ $contact->first_name }}" 
                                        data-last_name="{{ $contact->last_name }}" 
                                        data-phone="{{ $contact->phone }}">
                                        ویرایش
                                    </button>

                                    <!-- دکمه حذف -->
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $contact->id }}">
                                        حذف
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="editContactModal" tabindex="-1" aria-labelledby="editContactModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editContactModalLabel">ویرایش مخاطب</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin-left: 0;"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editContactForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="edit-contact-id">
                            <div class="mb-3">
                                <label class="form-label">نام</label>
                                <input type="text" class="form-control" id="edit-first-name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">نام خانوادگی</label>
                                <input type="text" class="form-control" id="edit-last-name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">تلفن</label>
                                <input type="text" class="form-control" id="edit-phone" required>
                            </div>
                            <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">بستن</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="mt-4">
            <div class="card" style="margin: auto 250px;">
                <div class="card-header">افزودن مخاطب جدید</div>
                <div class="card-body">
                <form method="POST" action="{{ route('contacts.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">نام</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="نام">
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="form-label">نام خانوادگی</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="نام خانوادگی">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">تلفن</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="تلفن">
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100">افزودن</button>
                </form>

                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function () {
        $('.edit-btn').click(function () {
            let id = $(this).data('id');
            let firstName = $(this).data('first_name');
            let lastName = $(this).data('last_name');
            let phone = $(this).data('phone');

            $('#edit-contact-id').val(id);
            $('#edit-first-name').val(firstName);
            $('#edit-last-name').val(lastName);
            $('#edit-phone').val(phone);
        });

        $('#editContactForm').submit(function (e) {
            e.preventDefault();

            let id = $('#edit-contact-id').val();
            let firstName = $('#edit-first-name').val();
            let lastName = $('#edit-last-name').val();
            let phone = $('#edit-phone').val();

            $.ajax({
                url: '/contacts/' + id,
                type: 'PUT',
                data: {
                    _token: "{{ csrf_token() }}",
                    first_name: firstName,
                    last_name: lastName,
                    phone: phone,
                },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'ویرایش با موفقیت انجام شد',
                            showConfirmButton: false,
                            timer: 1000 
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1100);
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطا',
                            text: 'مشکلی در ویرایش مخاطب پیش آمد',
                        });
                    }
                });
            });
        });

        $('.delete-btn').click(function () {
            let contactId = $(this).data('id');

            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: "این عملیات قابل بازگشت نیست!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'بله، حذف شود!',
                cancelButtonText: 'لغو'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/contacts/' + contactId,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            Swal.fire(
                                'حذف شد!',
                                'مخاطب مورد نظر با موفقیت حذف شد.',
                                'success'
                            );
                            $('#contact-row-' + contactId).fadeOut(); 
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        },
                        error: function () {
                            Swal.fire(
                                'خطا!',
                                'مشکلی در حذف مخاطب پیش آمد.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>

@endsection