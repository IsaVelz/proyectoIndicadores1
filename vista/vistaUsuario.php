<?php
include '../control/ControlUsuario.php';
include '../modelo/Usuario.php';

$controlUsuario = new ControlUsuario(null); // Crea un objeto de la clase ControlUsuario sin un objeto Usuario
$comandoSql = $controlUsuario->listar(); // Ejecuta la consulta para listar los usuarios

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Si se envió un formulario por el método POST
    $email = $_POST["email"]; // Obtiene el email del formulario
    $contrasena = $_POST["contrasena"]; // Obtiene la contraseña del formulario
    $action = $_POST["action"]; // Obtiene la acción del formulario

    if (!empty($email)) { // Si el email no está vacío
        $objUsuario = new Usuario($email, $contrasena); // Crea un objeto de la clase Usuario con los datos del formulario
        $controlUsuario = new ControlUsuario($objUsuario); // Crea un objeto de la clase ControlUsuario con el objeto Usuario
        if($action == 'modificar'){ // Si la acción es modificar
            $controlUsuario->modificar();
            header("Location: VistaUsuario.php");
            exit();
        }elseif($action == 'guardar'){
            // $objUsuario = new Usuario($email, $contrasena);
            // $controlUsuario = new ControlUsuario($objUsuario);
            $controlUsuario->guardar();
            header("Location: VistaUsuario.php");
            exit();
        } elseif ($action == 'delete') {
            // Acción de eliminar usuario en la base de datos
            // $objUsuario = new Usuario($email, $contrasena);
            // $controlUsuario = new ControlUsuario($objUsuario);
            $controlUsuario->borrar($email);
            header("Location: VistaUsuario.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Administración de usuarios</title>
<!-- Favicons -->
<link href="assets/img/favicon.png" rel="icon">
<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="assets/css/miStyle.css">
</head>
<body>
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-5">
                        <h2><b>Administrar</b> Usuarios</h2>
                    </div>
                    <div class="col-sm-7">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser"><i class="bi bi-person-plus"></i><span>Nuevo Usuario</span></button>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Contraseña</th>						
                        <th>Rol</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    $num = 0;
                    foreach ($comandoSql as $dato) {
                        $num++;
                    ?>
                    <tr>
                        <td><?= $num ?></td>
                        <td><?= $dato['email'] ?></td>
                        <td><?= $dato['contrasena'] ?></td>
                        <td></td>
                        <td>
                            <div class="btn-group" role="group">
                                <form method="post" action="VistaUsuario.php" enctype="multipart/form-data">
                                    <button type="button" class="btn btn-warning btn-sm" name="modificar" data-bs-toggle="modal" data-bs-target="#editUser" data-bs-whatever="<?= $dato['email'] ?>"><i class="bi bi-pencil-square" style="font-size: 0.75rem;"></i></button>
                                </form>
                                <form method="post" action="VistaUsuario.php" enctype="multipart/form-data">
                                    <button type="button" class="btn btn-danger btn-sm" name="delete" data-bs-toggle="modal" data-bs-target="#deleteUser" data-bs-email="<?= $dato['email'] ?>"><i class="bi bi-trash-fill" style="font-size: 0.75rem;"></i></button>
                                </form>
                            </div>
                        </td>                        
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Add Modal HTML -->
<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUser" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="post" action="VistaUsuario.php" enctype="multipart/form-data">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Usuario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">@</span>
                        <input type="email" name='email' value="" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">**</span>
                        <input type="password" name='contrasena' class="form-control" placeholder="Contraseña" aria-label="Contrasena" aria-describedby="basic-addon1">
                    </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="action" value="guardar">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" formmethod="post" name="guardar">Guardar</button>
            </div>
        </form>
    </div>
  </div>
</div>
<!-- Edit Modal HTML -->
<div class="modal fade" id="editUser" tabindex="-1" aria-labelledby="editUser" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="post" action="VistaUsuario.php" enctype="multipart/form-data">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modificar Usuario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">@</span>
                        <input type="email" name='email' value="" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" id="email" readonly>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">**</span>
                        <input type="password" name='contrasena' class="form-control" placeholder="Contraseña" aria-label="Contrasena" aria-describedby="basic-addon1">
                    </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="action" value="modificar">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning" formmethod="post" name="modificar">Guardar</button>
            </div>
        </form>
    </div>
  </div>
</div>
<!-- Delete Modal HTML -->
<div class="modal fade" id="deleteUser" tabindex="-1" aria-labelledby="editUser" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form id="deleteForm" method="post" action="VistaUsuario.php" enctype="multipart/form-data">
            <div class="modal-header">						
                <h4 class="modal-title">Borrar Usuario</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">					
                <p>Esta seguro que desea eliminar este usuario?</p>
                <p class="text-warning"><small>Esta accion no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="email" value="<?= $dato['email'] ?>" id="email">
                <input type="hidden" name="action" value="delete">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning" formmethod="post" name="delete" id="confirmDelete">Eliminar</button>
            </div>
        </form>
    </div>
  </div>
</div>     
</body>
<script>
const editUser = document.getElementById('editUser')
if (editUser) {
    editUser.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget
        // Extract info from data-bs-* attributes
        const user = button.getAttribute('data-bs-whatever')
        // If necessary, you could initiate an Ajax request here
        // and then do the updating in a callback.

        // Update the modal's content.
        const modalTitle = editUser.querySelector('.modal-title')
        const emailInput = editUser.querySelector('#email')
        
        modalTitle.textContent = `Modificar usuario ${user}`
        emailInput.value = user
    })
}

const deleteUser = document.getElementById('deleteUser')
if (deleteUser) {
    deleteUser.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget
        // Extract info from data-bs-* attributes
        const email = button.getAttribute('data-bs-email')
        const contrasena = button.getAttribute('data-bs-contrasena')
        // If necessary, you could initiate an Ajax request here
        // and then do the updating in a callback.

        // Update the modal's content.
        const modalTitle = deleteUser.querySelector('.modal-title')
        const emailInput = deleteUser.querySelector('#email')
        
        modalTitle.textContent = `Eliminar usuario ${email}`
        emailInput.value = email
    })
}
</script>
</html>