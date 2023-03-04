<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3 p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="text-center mb-4">
            <h3 class="mb-2">Crear nuevo permiso</h3>
            <p class="text-muted">Permisos que puede usar y asignar a sus usuarios.</p>
          </div>
          <form id="addPermissionForm" class="row" action="{{Route('permission.store')}}" method="POST">
            @csrf @method('POST')
            <div class="col-12 mb-3">
              <label class="form-label" for="modalPermissionName">Nuevo Permiso</label>
              <input type="text" id="modalPermissionName" name="modalPermissionName" class="form-control" placeholder="Permission Name" autofocus required/>
            </div>
            <div class="col-12 mb-2">
            </div>
            <div class="col-12 text-center demo-vertical-spacing">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Crear Permiso</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--/ Add Permission Modal -->
