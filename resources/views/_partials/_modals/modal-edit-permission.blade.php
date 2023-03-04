<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3 p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="text-center mb-4">
            <h3 class="mb-2">Editar Permiso</h3>
            <p class="text-muted">Edite el permiso según sus requisitos.</p>
          </div>
          <div class="alert alert-warning" role="alert">
            <h6 class="alert-heading mb-2">Warning</h6>
            <p class="mb-0">Al editar el nombre del permiso, es posible que rompa la funcionalidad de los permisos del sistema. Asegúrese de estar absolutamente seguro antes de continuar.</p>
          </div>
          <form id="editPermissionForm" class="row" action="{{ Route('permission.update')}}" method="POST">
            @csrf @method('PATCH')
            <div class="col-sm-9">
              <label class="form-label" for="editPermissionName">Nombre del Permiso</label>
              <input type="text" id="editPermissionName" name="editPermissionName" class="form-control" placeholder="Permission Name" tabindex="-1" />
              <input type="hidden" id="editPermissionid" name="editPermissionid">
            </div>
            <div class="col-sm-3 mb-3">
              <label class="form-label invisible d-none d-sm-inline-block">Actualizar</label>
              <button type="submit" class="btn btn-primary mt-1 mt-sm-0">Actualizar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--/ Edit Permission Modal -->
