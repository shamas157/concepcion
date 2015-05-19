<?php
session_start();
include('config/conexion.php');
include('header.php');
include ('menu.php');
if (!isset($_SESSION['sessA']))
  echo '<div class="row"><div class="col-sm-12 text-center"><h2>No ha iniciado sesión de Administrador</h2></div></div>';
else if ($_SESSION['perfil'] != "1")
  echo '<div class="row><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección</h2></div></div>';
else {
  $storeId = $_SESSION['storeId'];
  $userId = $_SESSION['userId'];

  $sqlGetCategory = "SELECT id, nombre, created, (SELECT CONCAT(nombre,' ',ap,' ',am) FROM $tUser WHERE id=$tCategory.created_by_user_id ) as created_by FROM $tCategory ";
  $resGetCategory = $con->query($sqlGetCategory);
  $optCategory = '';
  if ($resGetCategory->num_rows > 0) {
    while ($rowGetCategory = $resGetCategory->fetch_assoc()) {
      $optCategory .= '<tr>';
      $optCategory .= '<td>' . $rowGetCategory['id'] . '</td>';
      $optCategory .= '<td>' . $rowGetCategory['nombre'] . '</td>';
      $optCategory .= '<td>' . $rowGetCategory['created'] . '</td>';
      $optCategory .= '<td>' . $rowGetCategory['created_by'] . '</td>';
      $optCategory .= '</tr>';
    }
  } else {
    $optCategory.='<tr><td colspan="4">No existen categorías aún.</td></tr>';
  }
  ?>

  <!-- Cambio dinamico -->
  <div class="container">
    <div class="row">
      <div class="titulo-crud text-center">
        CATEGORIAS
      </div>
      <div class="col-md-12 error"></div>
      <div class="col-md-12">
        <div class="error"></div>	
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  	Nueva Categoría
        </button>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
  	  <div class="modal-content">
  	    <div class="modal-header">
  	      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  	      <h4 class="modal-title" id="myModalLabel">Nueva Categoría</h4>
  	    </div>
  	    <form id="formAddCategory" name="formAddCategory" method="POST">
  	      <div class="modal-body">
  		<div class="form-group">
  		  <label>Nombre de la Categoría</label>
  		  <input type="text" id="inputCategory" name="inputCategory" class="form-control">
  		</div>  
  		<input type="hidden" name="inputUser" value="<?= $userId; ?>" >
  	      </div>
  	      <div class="modal-footer">
  		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  		<button type="submit" class="btn btn-primary" >Crear categoría</button>
  	      </div>
  	    </form>
  	  </div>
  	</div>
        </div>
      </div>	  
    </div>

    <br>
    <table class="table table-striped">
      <thead>
        <tr>
  	<td class="t-head-first">Id</td>
  	<td class="t-head">Nombre</td>
  	<td class="t-head">Fecha de creación</td>
  	<td class="t-head-last">Creado por</td>
        </tr>
      </thead>
      <tbody>
	<?= $optCategory; ?>
      </tbody>    
    </table>
  </div>

  <script type="text/javascript">
    $(document).ready(function () {

      $('#formAddCategory').validate({
        rules: {
          inputCategory: {required: true}
        },
        messages: {
          inputCategory: "Debes introducir una categoría"
        },
        tooltip_options: {
          inputCategory: {trigger: "focus", placement: 'bottom'}
        },
        submitHandler: function (form) {
          $.ajax({
            type: "POST",
            url: "controllers/create_category.php",
            data: $('form#formAddCategory').serialize(),
            success: function (msg) {
              //alert(msg);
              if (msg == "true") {
                $('.error').html("Se creo la categoría con éxito.").css({color: "#00FF00"});
                setTimeout(function () {
                  location.href = 'form_add_category.php';
                }, 3000);
              } else {
                $('.error').css({color: "#FF0000"});
                $('.error').html(msg);
              }
            },
            error: function () {
              alert("Error al crear categoría ");
            }
          });
        }

      });
    });
  </script>

  <?php
}//fin else sesión
include ('footer.php');
?>