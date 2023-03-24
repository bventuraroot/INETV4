@extends('layouts/layoutMaster')

@section('title', 'Usuarios')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <style>
        .swal2-container {
            z-index: 20000 !important;
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-user-list.js') }}"></script>
@endsection

@section('content')
    <!-- Users List Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                    <tr>
                        <th></th>
                        <th>Usuarios</th>
                        <th>Rol</th>
                        <th>Status</th>
                        <th>Empresas</th>
                        <th>Fecha creación</th>
                        <th>Fecha modificación</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- Offcanvas to add new user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Crear Usuario</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                <form class="add-new-user pt-0" id="addNewUserForm" action="{{ route('user.store') }}"
                    enctype="multipart/form-data" method="POST">
                    @csrf @method('POST')
                    <div class="mb-3">
                        <label class="form-label" for="name">Nombre Completo</label>
                        <input type="text" class="form-control" id="name" placeholder="John Doe" name="name"
                            aria-label="John Doe" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="text" id="email" class="form-control" placeholder="john.doe@example.com"
                            aria-label="john.doe@example.com" name="email" onchange="valemail(this.value);" required/>
                    </div>
                    <div class="mb-3">
                        <label for="pass" class="form-label">Password</label>
                        <input class="form-control" type="password" value="" id="pass" name="pass" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="permissioncompany">Permiso a empresas</label>
                        <input id="permissioncompany" name="permissioncompany" class="form-control" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="role">Rol de usuario</label>
                        <select id="role" name="role" class="select2 form-select" data-allow-clear="true" required>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="status">Estado</label>
                        <select id="status" name="status" class="select2status form-select" data-allow-clear="true">
                            <option value="Active" selected>Activo</option>
                            <option value="Disable">Deshabilitado</option>
                            <option value="Suspend">Suspendido</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Foto</label>
                        <input class="form-control" type="file" id="avatar" name="avatar">
                    </div>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" id="send">Crear</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Modal to update information of users -->

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateUser" aria-labelledby="offcanvasUpdateUserLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasUpdateUserLabel" class="offcanvas-title">Editar Usuario</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                <form class="add-new-user pt-0" id="addNewUpdateForm" action="{{ route('user.update') }}"
                    enctype="multipart/form-data" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="idedit" id="idedit">
                    <div class="mb-3">
                        <label class="form-label" for="nameedit">Nombre Completo</label>
                        <input type="text" class="form-control" id="nameedit" placeholder="John Doe" name="nameedit"
                            aria-label="John Doe" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="emailedit">Email</label>
                        <input type="text" id="emailedit" class="form-control" placeholder="john.doe@example.com"
                            aria-label="john.doe@example.com" name="emailedit" onchange="valemail(this.value);" readonly disabled/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="permissioncompanyedit">Permiso a empresas</label>
                        <input id="permissioncompanyedit" name="permissioncompanyedit" class="form-control" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="roleedit">Rol de usuario</label>
                        <select id="roleedit" name="roleedit" class="select2roleedit form-select" data-allow-clear="true" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="statusedit">Estado</label>
                        <select id="statusedit" name="statusedit" class="select2status form-select" aria-readonly="true" disabled>
                            <option value="Active">Activo</option>
                            <option value="Disable">Deshabilitado</option>
                            <option value="Suspend">Suspendido</option>
                        </select>
                    </div>
                    <div class="mb-3" id="avatarview">
                    </div>
                    <div class="mb-3">
                        <label for="avataredit" class="form-label">Foto</label>
                        <input class="form-control" type="file" id="avataredit" name="avataredit">
                    </div>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" id="sendedit">Guardar</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>
    </div>
@endsection
