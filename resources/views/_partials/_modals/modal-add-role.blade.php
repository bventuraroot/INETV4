@foreach ($roles as $rol)
    <!-- Add Role Modal -->
    <div class="modal fade" id="UpdateRoleModal{{ $rol->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
            <div class="modal-content p-3 p-md-5">
                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h3 class="role-title mb-2">Editar Role {{ $rol->name }}</h3>
                        <p class="text-muted">Establecer permisos de rol</p>
                    </div>
                    <!-- Add role form -->
                    <form id="addRoleForm{{ $rol->id }}" class="row g-3" action="{{route('rol.update')}}" method="POST">
                        @csrf @method('POST')
                        <div class="col-12">
                            <h5>Permisos de Rol</h5>
                            <!-- Permission table -->
                            <div class="table-responsive">
                                <input type="hidden" name="rolid" id="rolid" value="{{$rol->id}}">
                                <table class="table table-flush-spacing">
                                    <tbody>
                                        <!--<tr>
                                            <td class="text-nowrap fw-semibold">¿Super Usuario?<i
                                                    class="ti ti-info-circle" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Allows a full access to the system"></i></td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="selectAll" />
                                                    <label class="form-check-label" for="selectAll">
                                                        Seleccionar Todos
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>-->
                                        @foreach ($permissiondata as $indexmodul => $modul)
                                            <tr>
                                                <td class="text-nowrap fw-semibold">{{ Str::upper($modul->modules) }}
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @if (@$permissionsbyrol[$rol->name][$modul->modules]['index']=='index' || $rol->name=='Admin')
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="{{$modul->modules}}_index" name="{{$modul->modules}}_index" checked/>
                                                            <label class="form-check-label" for="userManagementView">
                                                                Ver
                                                            </label>
                                                        </div>
                                                        @else
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="{{$modul->modules}}_index" name="{{$modul->modules}}_index"/>
                                                            <label class="form-check-label" for="userManagementView">
                                                                Ver
                                                            </label>
                                                        </div>
                                                        @endif
                                                        @if (@$permissionsbyrol[$rol->name][$modul->modules]['store']=='store' || $rol->name=='Admin')
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="{{$modul->modules}}_store" name="{{$modul->modules}}_store" checked/>
                                                            <label class="form-check-label" for="userManagementWrite">
                                                                Crear
                                                            </label>
                                                        </div>
                                                        @else
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="{{$modul->modules}}_store" name="{{$modul->modules}}_store" />
                                                            <label class="form-check-label" for="userManagementWrite">
                                                                Crear
                                                            </label>
                                                        </div>
                                                        @endif
                                                        @if (@$permissionsbyrol[$rol->name][$modul->modules]['update']=='update' || $rol->name=='Admin')
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="{{$modul->modules}}_update" name="{{$modul->modules}}_update" checked/>
                                                            <label class="form-check-label" for="userManagementUpdate">
                                                                Actualizar
                                                            </label>
                                                        </div>
                                                        @else
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="{{$modul->modules}}_update" name="{{$modul->modules}}_update" />
                                                            <label class="form-check-label" for="userManagementUpdate">
                                                                Actualizar
                                                            </label>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Permission table -->
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Enviar</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                aria-label="Close">Cancelar</button>
                        </div>
                    </form>
                    <!--/ Add role form -->
                </div>
            </div>
        </div>
    </div>
    <!--/ Add Role Modal -->
@endforeach

<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Crear Nuevo Rol</h3>
                    <p class="text-muted">Establecer permisos de rol</p>
                </div>
                <!-- Add role form -->
                <form id="addRoleForm" class="row g-3" action="{{route('rol.store')}}" method="POST">
                    @csrf @method('POST')
                    <div class="col-12 mb-4">
                        <label class="form-label" for="modalRoleName">Nombre Rol</label>
                        <input type="text" id="modalRoleName" name="modalRoleName" class="form-control"
                            placeholder="Enter a role name" tabindex="-1" required/>
                    </div>
                    <div class="col-12">
                        <h5>Role Permissions</h5>
                        <!-- Permission table -->
                        <div class="table-responsive">
                            <table class="table table-flush-spacing">
                                <tbody>
                                    <!--<tr>
                                        <td class="text-nowrap fw-semibold">¿Super Usuario?<i class="ti ti-info-circle"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Allows a full access to the system"></i></td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll" />
                                                <label class="form-check-label" for="selectAll">
                                                    Seleccionar Todos
                                                </label>
                                            </div>
                                        </td>
                                    </tr>-->
                                    @foreach ($permissiondata as $modul)
                                        <tr>
                                            <td class="text-nowrap fw-semibold">{{ Str::upper($modul->modules)}}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="form-check me-3 me-lg-5">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="{{$modul->modules}}_index" name="{{$modul->modules}}_index" />
                                                        <label class="form-check-label" for="userManagementRead">
                                                            Ver
                                                        </label>
                                                    </div>
                                                    <div class="form-check me-3 me-lg-5">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="{{$modul->modules}}_store" name="{{$modul->modules}}_store" />
                                                        <label class="form-check-label" for="userManagementWrite">
                                                            Agregar
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                        id="{{$modul->modules}}_update" name="{{$modul->modules}}_update" />
                                                        <label class="form-check-label" for="userManagementCreate">
                                                            Actualizar
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Permission table -->
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Enviar</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancelar</button>
                    </div>
                </form>
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
<!--/ Add Role Modal -->
