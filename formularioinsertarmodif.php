<?php
include_once 'cabecera.html';
include_once 'funciones.php';
// Este formulario se utiliza tanto para insertar como para modificar
// Se cambiará el submit. Si el empleado se acaba de insertar 
// el botón que aparecerá será el de modificar.

//Los inicializamos a vacio, los utilizamos para congelar el formulario
$emp_no = "";
$apellido = "";
$oficio = "";
$dir = "";
$fecha_alt = "";
$salario = "";
$comision = "";
$dept_no = "";

$cabecera = "<h3>INSERCIÓN DE EMPLEADOS</h3>";

//si se pulsa modificaremple, de este formulario
if (isset($_POST['modifemple'])) {
    $cabecera = "<h3>MODIFICACIÓN DE EMPLEADOS</h3>";
    $resultado = unemple($_POST['emp_no']);
    $row2 = $resultado->fetch_assoc();
    $emp_no = $_POST['emp_no'];
    $apellido = $row2['apellido'];
    $oficio = $row2['oficio'];
    $dir = $row2['dir'];
    $fecha_alt = $row2['fecha_alt'];
    $salario = $row2['salario'];
    $comision = $row2['comision'];
    $dept_no = $row2['dept_no'];
}
//si se pulsa submit modificauno de este formulario
//Este saldrá después de insertar uno
if (isset($_POST['modificauno']))
    $cabecera = "<h3>MODIFICACIÓN DE EMPLEADOS</h3>";

//En ambos casos cargamos los datos del form
if (isset($_POST['modificauno']) or isset($_POST['insertauno'])) {
    $emp_no = $_POST['emp_no'];
    $apellido = $_POST['apellido'];
    $oficio = $_POST['oficio'];
    $dir = $_POST['dir'];
    $fecha_alt = $_POST['fecha_alt'];
    $salario = $_POST['salario'];
    $comision = $_POST['comision'];
    $dept_no = $_POST['dept_no'];
}
?>
<div id = "bloque" align = 'center'>
    <?php echo $cabecera; ?>

    <form method = "post" action = "formularioinsertarmodif.php">
        <p>* Número de empleado: <input type = "text" name = "emp_no"  required="required" value="<?php echo $emp_no; ?>"
                                        <?php if (isset($_POST['modificauno']) or isset($_POST['emp_no'])) echo "readonly= 'readonly'"; ?> />  
        </p>
        <p>* Apellido: <input type = "text" name = "apellido" required="required" value="<?php echo $apellido; ?>"/></p>
        <p>* Oficio: <input type = "text" required="required" name = "oficio" value="<?php echo $oficio; ?>"/></p>
        <p>Director:  <select name="dir"><option  value=""></option>
                <?php
                $director = getEmples();
                while ($fila = $director->fetch_assoc()) {
                    if ($dir == $fila['emp_no']) {
                        echo '<option selected value="' . $fila['emp_no'] . '">' . $fila['emp_no'] . " - " . $fila['apellido'] . '</option>';
                    } else {
                        echo '<option  value="' . $fila['emp_no'] . '">' . $fila['emp_no'] . " - " . $fila['apellido'] . '</option>';
                    }
                }//fin while
                ?>
            </select>
        </p>


        <p>Fecha de alta: <input type = "text" name = "fecha_alt" value="<?php echo $fecha_alt; ?>" /></p>
        <p>Salario: <input type = "number" name = "salario" value="<?php echo $salario; ?>"/></p>
        <p>Comisión: <input type = "number" name = "comision" value="<?php echo $comision; ?>"/></p>
        <p>* Departamento:  <select name="dept_no" required="required">
                <?php
                $depar = getDeparts();
                while ($fila = $depar->fetch_assoc()) {
                    if ($dept_no == $fila['dept_no']) {
                        echo '<option selected value="' . $fila['dept_no'] . '">' . $fila['dept_no'] . " - " . $fila['dnombre'] . '</option>';
                    } else {
                        echo '<option  value="' . $fila['dept_no'] . '">' . $fila['dept_no'] . " - " . $fila['dnombre'] . '</option>';
                    }
                }//fin while
                ?>
            </select>
        </p>

        <?php if (isset($_POST['emp_no']) or isset($_POST['modificauno'])) { ?>
            <p> <input type = "submit" value = "Modificar el empleado" name="modificauno"/> </p>
        <?php } else { ?>
            <p> <input type = "submit" value = "Insertar el empleado" name="insertauno"/> </p>
        <?php } ?>
    </form>
    <hr>
    <hr>
    <?php
    if (isset($_POST['modificauno'])) {
        $mensaje = updateemple($emp_no, $apellido, $oficio, $dir, $fecha_alt, $salario, $comision, $dept_no);
        echo "<h3>$mensaje.</h3>";
    }

    if (isset($_POST['insertauno'])) {
        $mensaje = insertaemple($emp_no, $apellido, $oficio, $dir, $fecha_alt, $salario, $comision, $dept_no);
        echo "<h3>$mensaje.</h3>";
    }
    ?>

</div>

<?php
include_once 'pie.html';
?>