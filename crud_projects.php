<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once("config/db.php");
require_once("cmd/exec.php");
require_once("shared/utiltties.php");
require_once("config/core.php");

$db = new Database();
$strConn = $db->getConnection();
$strExe = new ExecSQL($strConn);
$utilities = new Utiltties();


 /// POST
$data = json_decode(file_get_contents("php://input"));
/*
$project_code = $data->project_code;
$project_name = $data->project_name;
$address = $data->address;
$district_id = $data->district_id;
$amphur_id = $data->amphur_id;
$province_id = $data->province_id;
$zipcode = $data->zipcode;
$phone = $data->phone;
*/
   
$action = $_GET['cmd'];

switch($action){

    case "select" :
    $sql = "SELECT projects.*,
    district.district_name as district,
    amphur.amphur_name as amphur,
    province.province_name as province
    FROM projects
    LEFT JOIN district
    ON district.district_id = projects.district_id
    LEFT JOIN amphur
    ON amphur.amphur_id = projects.amphur_id
    LEFT JOIN province
    ON province.province_id = projects.province_id 
    LIMIT $from_record_num,$records_per_page ";

    $stmt = $strExe->read($sql);
    $rowCount= $stmt->rowCount();

    if($rowCount>0){
        $data_arr['rs'] = array();
        $data_arr["paging"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            array_push($data_arr["rs"],$row);
        }
    }else{
        echo json_encode(array("message"=>"No data found"));
    }

    $total_rows =$strExe->count_rows("projects");
    $page_url = "{$home_url}/crud_projects.php?cmd=select&";
    $paging = $utilities->getPaging($page,$total_rows,$records_per_page,$page_url);
    $data_arr["paging"]=$paging;

    echo json_encode($data_arr);
    
    break;

    case "insert" :

    ///GET
    $project_code = $_GET['project_code'];
    $project_name = $_GET['project_name'];
    $address = $_GET['address'];
    $district_id = $_GET['district_id'];
    $amphur_id = $_GET['amphur_id'];
    $province_id = $_GET['province_id'];
    $zipcode = $_GET['zipcode'];
    $phone = $_GET['phone'];
    
    
    if($project_name!=null  ){
        
    $sql = "INSERT INTO projects 
    (project_name, address, district_id, amphur_id, province_id,zipcode, phone ) 
    VALUES ('".$project_name."', '".$address."', '".$district_id."', '".$amphur_id."', '".$province_id."', 
    '".$zipcode."', '".$phone."');";

    $stmt = $strExe->dataTransection($sql);

    if ($stmt == 1) {
        echo json_encode(['status' => 'ok','message' => 'บันทึกข้อมูลเรียบร้อยแล้ว']);
    } else {
        echo json_encode(['status' => 'error','message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
    }

    }else {
        echo json_encode(['status' => 'no','message' => 'กรอกข้อมูลไม่ครบ()']);
    }
    
    break;

    case "update" :

    ////GET
    $project_code = $_GET['project_code'];
    $project_name = $_GET['project_name'];
    $address = $_GET['address'];
    $district_id = $_GET['district_id'];
    $amphur_id = $_GET['amphur_id'];
    $province_id = $_GET['province_id'];
    $zipcode = $_GET['zipcode'];
    $phone = $_GET['phone'];
   

    if($project_code!=null){

    $sql = " UPDATE projects SET 
    project_name = '".$project_name."', address = '".$address."', district_id = '".$district_id."', 
    amphur_id = '".$amphur_id."',province_id = '".$province_id."', zipcode = '".$zipcode."', 
    phone = '".$phone."'
    WHERE projects.project_code = '".$project_code."' ";

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
    ////GET
    $project_code = $_GET['project_code'];

    if($project_code!=null){

    $sql = " DELETE FROM projects 
    WHERE project_code = '".$project_code."' ";

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