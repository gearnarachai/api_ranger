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
    $sql = "SELECT engage_service.*,
    projects.project_name,
    concat(initial.initial_name,' ',engage_name,' ',engage_lastname) as name_engage 
    FROM engage_service
    LEFT JOIN projects
    ON engage_service.project_code = projects.project_code
    LEFT JOIN initial
    ON initial.initial_code = engage_service.initial_id 
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

    $total_rows =$strExe->count_rows("engage_service");
    $page_url = "{$home_url}/crud_engage_service.php?cmd=select&";
    $paging = $utilities->getPaging($page,$total_rows,$records_per_page,$page_url);
    $data_arr["paging"]=$paging;

    echo json_encode($data_arr);
    
    break;

    case "insert" :

    ///GET
    $engage_code = $_GET['engage_code'];
    $project_code = $_GET['project_code'];
    $initial_id = $_GET['initial_id'];
    $engage_name = $_GET['engage_name'];
    $engage_lastname = $_GET['engage_lastname'];
    $duration_engage = $_GET['duration_engage'];
    $engage_start = $_GET['engage_start'];
    $engage_end = $_GET['engage_end'];
    $chief1 = $_GET['chief1'];
    $chief2 = $_GET['chief2'];
    $chief3 = $_GET['chief3'];
    $asst_chief2 = $_GET['asst_chief2'];
    $servitor = $_GET['servitor'];
    $other = $_GET['other'];
    $cost_chief1 = $_GET['cost_chief1'];
    $cost_chie2 = $_GET['cost_chie2'];
    $cost_chief3 = $_GET['cost_chief3'];
    $cost_asst_chief2 = $_GET['cost_asst_chief2'];
    $cost_servitor = $_GET['cost_servitor'];
    $cost_other = $_GET['cost_other'];
    $cost_engage = $_GET['cost_engage'];
    
    
    if($project_code!=null  ){
    
    $sql = "INSERT INTO engage_service 
    (engage_code, project_code, initial_id, engage_name, engage_lastname, duration_engage,
    engage_start, engage_end, chief1, chief2, chief3, asst_chief2, servitor, other,
    cost_chief1, cost_chie2, cost_chief3, cost_asst_chief2, cost_servitor, cost_other, cost_engage) 
    VALUES ('".$engage_code."', '".$project_code."', '".$initial_id."', '".$engage_name."', '".$engage_lastname."', 
    '".$duration_engage."', '".$engage_start."', '".$engage_end."', '".$chief1."', '".$chief2."', '".$chief3."',
     '".$asst_chief2."', '".$servitor."', '".$other."', '".$cost_chief1."'
    , '".$cost_chie2."', '".$cost_chief3."', '".$cost_asst_chief2."', '".$cost_servitor."', '".$cost_other."'
    , '".$cost_engage."');";

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
    $engage_code = $_GET['engage_code'];
    $project_code = $_GET['project_code'];
    $initial_id = $_GET['initial_id'];
    $engage_name = $_GET['engage_name'];
    $engage_lastname = $_GET['engage_lastname'];
    $duration_engage = $_GET['duration_engage'];
    $engage_start = $_GET['engage_start'];
    $engage_end = $_GET['engage_end'];
    $chief1 = $_GET['chief1'];
    $chief2 = $_GET['chief2'];
    $chief3 = $_GET['chief3'];
    $asst_chief2 = $_GET['asst_chief2'];
    $servitor = $_GET['servitor'];
    $other = $_GET['other'];
    $cost_chief1 = $_GET['cost_chief1'];
    $cost_chie2 = $_GET['cost_chie2'];
    $cost_chief3 = $_GET['cost_chief3'];
    $cost_asst_chief2 = $_GET['cost_asst_chief2'];
    $cost_servitor = $_GET['cost_servitor'];
    $cost_other = $_GET['cost_other'];
    $cost_engage = $_GET['cost_engage'];
   

    if($engage_code!=null){

    $sql = " UPDATE engage_service SET 
    project_code = '".$project_code."', initial_id = '".$initial_id."', engage_name = '".$engage_name."', 
    engage_lastname = '".$engage_lastname."',duration_engage = '".$duration_engage."', engage_start = '".$engage_start."', 
    engage_end = '".$engage_end."',chief1 = '".$chief1."',chief2 = '".$chief2."',chief3 = '".$chief3."',
    asst_chief2 = '".$asst_chief2."',servitor = '".$servitor."',other = '".$other."',
    cost_chief1 = '".$cost_chief1."',cost_chie2 = '".$cost_chie2."',cost_chief3 = '".$cost_chief3."',
    cost_asst_chief2 = '".$cost_asst_chief2."',cost_servitor = '".$cost_servitor."',
    cost_other = '".$cost_other."' ,cost_engage = '".$cost_engage."'
    WHERE engage_service.engage_code = '".$engage_code."' ";

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

    $engage_code = $_GET['engage_code'];
    
    if($engage_code!=null){

    $sql = " DELETE FROM engage_service 
    WHERE engage_code = '".$engage_code."' ";

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