<?php
class Dbinsert
{
    function submit_form()
    {
        foreach($_POST as $key => $val) if($val && $val != 'Velg kjÃ¸nn') $array[$key] = "'".$val."'";

        $columns        = implode(", ",array_keys($array));
        $escaped_values = array_map('returnSelf', array_values($array));
        $values         = implode(", ", $escaped_values);
        $sql            = "INSERT INTO `form_submit`($columns) VALUES ($values)";
        $con            = mysqli_connect("127.0.0.1","root","","test");

        mysqli_query($con,$sql);
        mysqli_close($con);

        return $sql;
    }
}