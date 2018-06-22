<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require_once("config/db.php");
require_once("cmd/exec.php");
$db = new Database();
$strConn = $db->getConnection();
$strExe = new ExecSQL($strConn);

$action = $_GET['cmd'];

switch($action){

    case "select" :

    $dept = $_GET['dept'];
    
    $sql = " SELECT division_code,division_name,division.dept_code,department.dept_name FROM division
    LEFT JOIN department
    ON department.dept_code=division.dept_code ";

    if($dept!=null){
        $sql .= " WHERE division.dept_code = '".$dept."' or department.dept_name LIKE '%".$dept."%' ";
    }
        

    $stmt = $strExe->read($sql);
    $rowCount= $stmt->rowCount();

    if($rowCount>0){
        $data_arr['rs'] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            array_push($data_arr["rs"],$row);
        }
    }else{
        echo json_encode(array("message"=>"No data found"));
    }
    echo json_encode($data_arr);
    
    break;

} 

?>