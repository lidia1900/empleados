<?php
//phpinfo();
function getConexion1() {
    $servername = "localhost:33060";
    $database = "empleados";
    $username = "root";
    $password = "";
    $conexion = new mysqli($servername, $username, $password, $database);
   
    if ($conexion->connect_error) {
         $conexion->set_charset("utf8");
        return null;
    } else {
        return $conexion;
    }
}
//Borra emple y devuelve mensaje
function borrarEmple($emp) {
    $consulta = "delete from empleados where emp_no = $emp ";
    $mensaje = null;
    $con = getConexion1();
    if ($con == null) {
        // echo "<br>ERROR.";
        return null;
    } else {
        $resultado = $con->query($consulta);

        if ($resultado) {
            if ($con->affected_rows == 1)
                $mensaje = "Empleado borrado : $emp";
            else
                $mensaje = "No se ha borrado el empleado: $emp";
        } else {

            $mensaje = "ERROR AL BORRAR EMPLEADO $emp. <br> ";
            //. $con->error . "<br>Código de error: " . $con->errno;
            if ($con->errno == 1451) {
                $mensaje = $mensaje . "El empleado es director de otro/s empleado/s";
            }
        }
    }
    return $mensaje;
}

//Devuelve un empleado, null si no existe
function unemple($emp) {
    $consulta = "select emp_no, apellido, oficio, dir, fecha_alt, salario,comision, dept_no "
            . " from empleados where emp_no = $emp ";
    $con = getConexion1();
    if ($con == null) {
        // echo "<br>ERROR.";
        return null;
    } else {
        $con->set_charset("utf8");
        $resultado = $con->query($consulta);
        if ($con->affected_rows == 0)
            return null;
        if ($resultado) {
            $con->close();
            return $resultado;
        }

        return null;
    }
}

// Devuelve los emples que son directores
function getDirectores() {
    $consulta = "select emp_no, apellido, oficio, dir, fecha_alt, salario,comision from empleados where emp_no in (select dir from empleados) ";
    $con = getConexion1();
    if ($con == null) {
        // echo "<br>ERROR.";
        return null;
    } else {
        $con->set_charset("utf8");
        $resultado = $con->query($consulta);
        if ($con->affected_rows == 0)
            return null;
        if ($resultado) {
            $con->close();
            return $resultado;
        }

        return null;
    }
}

//Devuelve todos los emples
function getEmples() {
    $consulta = "select emp_no, apellido, oficio, dir, fecha_alt, salario,comision, dept_no from empleados ";
    $con = getConexion1();
    if ($con == null) {
        // echo "<br>ERROR.";
        return null;
    } else {
        $con->set_charset("utf8");
        $resultado = $con->query($consulta);
        if ($con->affected_rows == 0)
            return null;
        if ($resultado) {
            $con->close();
            return $resultado;
        }

        return null;
    }
}

//Devuelve los datos de un dep
function getUndep($dept) {
    $consulta = "select dept_no, dnombre, loc from departamentos where dept_no = $dept ";
    $con = getConexion1();
    if ($con == null) {
        // echo "<br>ERROR.";
        return null;
    } else {
        $con->set_charset("utf8");
        $resultado = $con->query($consulta);
        if ($con->affected_rows == 0)
            return null;
        if ($resultado) {
            $con->close();
            return $resultado;
        }

        return null;
    }
}

//devuelve los emples de un dep
function getEmplesDep($dept) {
    $consulta = "select emp_no, apellido, oficio, dir, fecha_alt, salario,comision, dept_no "
            . " from empleados where dept_no = $dept ";
    $con = getConexion1();
    if ($con == null) {
        // echo "<br>ERROR.";
        return null;
    } else {
        $con->set_charset("utf8");
        $resultado = $con->query($consulta);
        if ($con->affected_rows == 0)
            return null;
        if ($resultado) {
            $con->close();
            return $resultado;
        }

        return null;
    }
}

//Devuelve datos de los dep, con num emple y media salario
function getDeparts() {
    $consulta = "select dept_no, dnombre, loc, count(emp_no) as num, 
coalesce(avg(salario),0) as med from departamentos left join empleados using (dept_no) group by dept_no, dnombre, loc ";
    $con = getConexion1();
    if ($con == null) {
        // echo "<br>ERROR.";
        return null;
    } else {
        $con->set_charset("utf8");
        $resultado = $con->query($consulta);
        if ($con->affected_rows == 0)
            return null;
        if ($resultado) {
            $con->close();
            return $resultado;       
        }

        return null;
    }
}

//Inserta empleados, recibe los datos y devuelve mensaje de lo ocurrido
function insertaemple($emp_no, $apellido, $oficio, $dir, $fecha_alt, $salario, $comision, $dept_no) {
    //Comprobar los null
    if (trim($dir) == "")
        $dir = 'null';
    if (trim($fecha_alt) == "")
        $fecha_alt = null;
    if (trim($salario) == "")
        $salario = 'null';
    if (trim($comision) == "")
        $comision = 'null';

    $consulta = "insert into empleados (emp_no, apellido, oficio, dir, fecha_alt, salario, comision, dept_no) values (" .
            $emp_no . ",'" . $apellido . "', '" . $oficio . "'," . $dir . ", '" . $fecha_alt . "', " . $salario . "," .
            $comision . ", " . $dept_no . ")";
    $con = getConexion1();
    $con->set_charset("utf8");
    $mensaje = "";
    if ($con == null)
        $mensaje = "ERROR EN LA CONEXIÓN";
    else {
        $resultado = $con->query($consulta);
        if ($resultado) {
            $mensaje = "Empleado insertado correctamente: $emp_no";
        } else {
            if ($con->errno == 1452) {
                $mensaje = "<br>Error al insertar. La clave ajena no se encuentra. <br>Revisa director y departamento";
                $mensaje = $con->error;
                $con->close();
            } else {

                $mensaje = "<br>Error al insertar: " . $con->error .
                        "<br>Código de error: " . $con->errno;
                echo "consulta=" . $consulta . "<br>Código de error: " . $con->errno;
                $con->close();
            }
        }
    }
    return $mensaje;
}

//Actualiza empleados, recibe los datos, y devuelve mensaje 
function updateemple($emp_no, $apellido, $oficio, $dir, $fecha_alt, $salario, $comision, $dept_no) {
    //Comprobar los null
    if (trim($dir) == "")
        $dir = 'null';
    if (trim($fecha_alt) == "")
        $fecha_alt = null;
    if (trim($salario) == "")
        $salario = 'null';
    if (trim($comision) == "")
        $comision = 'null';

    $consulta = "update empleados set apellido = '" . $apellido . "', salario = " . $salario .
            ", oficio = '" . $oficio . "', dir=" . $dir . ", fecha_alt = '" . $fecha_alt . "', comision=" .
            $comision . ", dept_no=" . $dept_no . " where emp_no=" . $emp_no;

    $con = getConexion1();
    $con->set_charset("utf8");
    $mensaje = "";
    if ($con == null)
        $mensaje = "ERROR EN LA CONEXIÓN";
    else {
        $resultado = $con->query($consulta);
        if ($resultado) {
            $mensaje = "Empleado actualizado: $emp_no";
        } else {
            if ($con->errno == 1452) {
                $mensaje = "<br>Error al actualizar. La clave ajena no se encuentra. <br>Revisa director y/o departamento";
            } else {
                $mensaje = "<br>Error al actualizar: " . $con->error .
                        "<br>Código de error: " . $con->errno;
            }
        }
    }
    return $mensaje;
}
