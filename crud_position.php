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


  /* POST
$data = json_decode(file_get_contents("php://input"));
$position_code = $data->position_code;
$position_name = $data->position_name;
$division_code = $data->division_code;
   */

$position_code = $_GET['position_code'];
$position_name = $_GET['position_name'];
$division_code = $_GET['division_code'];

$action = $_GET['cmd'];

switch($action){

    case "select" :
    $sql = "position";
    $stmt = $strExe->readAll($sql);
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

    case "insert" :

    if($position_code!=null && $position_name!=null && $division_code!=null ){

    $sql = " INSERT INTO position (position_code, position_name, division_code)
    VALUES ('".$position_code."', '".$position_name."', '".$division_code."') ";

    $stmt = $strExe->dataTransection($sql);

    if ($stmt == 1) {
        echo json_encode(['status' => 'ok','message' => 'บันทึกข้อมูลเรียบร้อยแล้ว']);
    } else {
        echo json_encode(['status' => 'error','message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
    }


    }else {
        echo json_encode(['status' => 'no','message' => 'กรอกข้อมูลไห้ครบ']);
    }
    break;


    case "update" :

    if($position_code!=null){

    $sql = " UPDATE position 
    SET position_name = '".$position_name."', division_code = '".$division_code."' 
    WHERE position_code = '".$position_code."' ";

    $stmt = $strExe->dataTransection($sql);

        if ($stmt == 1) {
        echo json_encode(['status' => 'ok','message' => 'แก้ไขข้อมูลเรียบร้อยแล้ว']);
        } else {
            echo json_encode(['status' => 'error','message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
        }


    } else {
        echo json_encode(['status' => 'no','message' => 'กรอกข้อมูลไห้ครบ']);
    }
    break;


    case "delete" :

    if($position_code!=null){

    $sql = " DELETE FROM position 
    WHERE position_code = '".$position_code."' ";

    $stmt = $strExe->dataTransection($sql);

        if ($stmt == 1) {
            echo json_encode(['status' => 'ok','message' => 'ลบข้อมูลเรียบร้อยแล้ว']);
        } else {
            echo json_encode(['status' => 'error','message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
        }

    } else {
        echo json_encode(['status' => 'no','message' => 'กรอกข้อมูลไห้ครบ']);
    }
    break;


}


?>