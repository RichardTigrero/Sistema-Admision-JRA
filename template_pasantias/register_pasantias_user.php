<!-- Botón para abrir el modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crearPasanteModal">
    <i class="fas fa-plus"></i> Crear
</button>

<!-- Modal para crear nuevo pasante -->
<div class="modal fade" id="crearPasanteModal" tabindex="-1" role="dialog" aria-labelledby="crearPasanteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearPasanteModalLabel">Registrar Nuevo Pasante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="guardar_pasante.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cédula</label>
                                <input type="text" class="form-control" name="cedula" required>
                            </div>
                            <div class="form-group">
                                <label>Nombres</label>
                                <input type="text" class="form-control" name="nombres" required>
                            </div>
                            <div class="form-group">
                                <label>Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" required>
                            </div>
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="tel" class="form-control" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label>Institución</label>
                                <input type="text" class="form-control" name="institucion">
                            </div>
                            <div class="form-group">
                                <label>Carrera</label>
                                <input type="text" class="form-control" name="carrera">
                            </div>
                            <div class="form-group">
                                <label>Área</label>
                                <input type="text" class="form-control" name="area">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha Fin (Opcional)</label>
                                <input type="date" class="form-control" name="fecha_fin">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Horario</label>
                                <input type="text" class="form-control" name="horario" placeholder="Ej: L-V 8am-4pm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Pasante</button>
                </div>
            </form>
        </div>
    </div>
</div>