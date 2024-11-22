<?php
//Para cambiar de página con header, sólo funciona si no se ha escrito nada de html en la página
//Bueno, esto depende de las versiones, con las antiguas menor de 7.2 da error
//Si se pulsa a insertar me voy al formulario
if (isset($_POST['insertar'])) {
    //Si pulso botón insertar abro el formulario
    header('Location: formularioinsertarmodif.php');
} //insertar

include_once 'cabecera.html';
include_once 'funciones.php';
if (isset($_POST['dept'])) {
    $dept = $_POST['dept'];
    echo "hola";
    $i = 98;
} else
    $dept = "";
?>
<div id="bloque" align='center'>
    <h3>Operaciones con empleados y departamentos</h3>
    <form method="post" action="index.php">
        <p>Teclea departamento: <input type="number" name="dept" value="<?php echo $dept ?>" />
            <input type="submit" name="empledep" value="Listar empleados del departamento." />
        </p>
        <p> <input type="submit" name="listardepar" value="Listar departamento, con num emples y media salario." /> </p>
        <p> <input type="submit" name="insertar" value="Insertar Empleado." /> </p>
        <p> <input type="submit" name="modificarborrar" value="Modificar-Borrar Empleado." /> </p>
    </form>
</div>
<div id="bloque" align='center'>
    <hr>
    <hr>
    <?php
    //Si se pulsa otro botón lo hago aquí 
    if (isset($_POST['borraremp'])) {
        echo "<h3>BORRAR EMPLEADOS</h3>";
        $mensaje = borrarEmple($_POST['emp_no']);
        echo "<h3>$mensaje</h3>";
    } //borraremp


    if (isset($_POST['modifemple'])) {
        echo "<h3>MODIFICACIÓN DE EMPLEADOS</h3>";
        $em = $_POST['emp_no'];
        header('Location: formularioinsertarmodif.php');
    } //modifemple

    if (isset($_POST['modificarborrar'])) {
        echo "<h3>MODIFICAR BORRAR EMPLEADOS</h3>";
        $resultado = getEmples();
        if ($resultado != null) {
            if ($resultado->num_rows > 0) {
                echo "<table><tr><th>Num emple</th><th>Apllido</th><th>Salario</th><th>Oficio</th><th>Fecha de alta</th><th>Comision</th><th>Director</th><th>Depart</th><th>Borrar</th><th>Modificar</th></tr>";
                while ($fila = $resultado->fetch_assoc()) {

                    echo "<tr><td>" . $fila['emp_no'] . "</td><td>" . $fila['apellido'] . "</td><td>" .
                        $fila['salario'] . "</td><td>" . $fila['oficio'] . "</td><td>" . $fila['fecha_alt'] .
                        "</td><td>" . $fila['comision'] . "</td><td>" . $fila['dir'] . "</td><td>" . $fila['dept_no'] . "</td>";
    ?>
                    <td>
                        <form method="post" action="index.php">
                            <input type="hidden" name="emp_no" value="<?php echo $fila['emp_no'] ?>" />
                            <input type="submit" name="borraremp" value="Borrar" />
                        </form>
                    </td>
                    <td>
                        <form method="post" action="formularioinsertarmodif.php">
                            <input type="hidden" name="emp_no" value="<?php echo $fila['emp_no'] ?>" />
                            <input type="submit" name="modifemple" value="Modificar" />
                        </form>
                    </td>
    <?php
                    echo "</tr>";
                }
                echo "</table>";
                echo "<hr> <hr><br>";
            }
        } else
            echo "<h3>NO HAY empleados.</h3>";
    } //modificarborrar

    if (isset($_POST['empledep'])) {
        $dept = $_POST['dept'];
        $depar = getUndep($dept);
        if ($depar != null) {
            //Cargo el departamento
            $row = $depar->fetch_assoc();
            echo "<h3>Datos del departamento: " . $row['dept_no'];
            echo "<br>Nombre: " . $row['dnombre'] . ". Localidad: " . $row['loc'] . "</h3>";
            $resultado = getEmplesDep($dept);
            if ($resultado != null) {
                echo "<h3>Número de empleados: " . $resultado->num_rows . "</h3>";
                if ($resultado->num_rows > 0) {
                    echo "<table><tr><th>Num emple</th><th>Apllido</th><th>Salario</th><th>Oficio</th><th>Fecha de alta</th><th>Comision</th><th>Director</th><th>Nombre Director</th></tr>";
                    while ($fila = $resultado->fetch_assoc()) {

                        $direc = unemple($fila['dir']);
                        if ($direc == null)
                            $director = "NO TIENE";
                        else {
                            $row2 = $direc->fetch_assoc();
                            $director = $row2['apellido'];
                        }
                        echo "<tr><td>" . $fila['emp_no'] . "</td><td>" . $fila['apellido'] . "</td><td>" .
                            $fila['salario'] . "</td><td>" . $fila['oficio'] . "</td><td>" . $fila['fecha_alt'] .
                            "</td><td>" . $fila['comision'] . "</td><td>" . $fila['dir'] . "</td><td>" . $director . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<hr> <hr><br>";
                }
            } else
                echo "<h3>Departamento sin empleados.</h3>";
        } //if depart
        else {
            echo "<h3>El departamento con el código tecleado no existe: " . $dept . "</h3>";
        }
    } //fin empledep

    if (isset($_POST['listardepar'])) {
        $resultado = getDeparts();
        echo "<h3>Listado de departamentos.<br>Recuperados: " . $resultado->num_rows . "</h3>";
        if ($resultado->num_rows > 0) {
            echo "<table><tr><th>Num departamento</th><th>Nombre</th><th>Localidad</th><th>Num empleados</th><th>Salario medio</th></tr>";
            while ($fila = $resultado->fetch_assoc()) {

                echo "<tr><td>" . $fila['dept_no'] . "</td><td>" . $fila['dnombre'] . "</td><td>" . $fila['loc'] . "</td><td>" . $fila['num'] . "</td><td>" . $fila['med'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<hr> <hr><br>";
        }
    } //fin listardepar
    ?>
</div>
<?php
include_once 'pie.html';
?>