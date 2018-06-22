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


 /// POST
 /*
$data = json_decode(file_get_contents("php://input"));
$emp_code = $data->emp_code;
$initial_id = $data->initial_id;
$name_thai = $data->name_thai;
$lastname_thai = $data->lastname_thai;
$initial_eng_id = $data->initial_eng_id;
$name_eng = $data->name_eng;
$lastname_eng = $data->lastname_eng;
$id_card = $data->id_card;
$email = $data->email;
$phone = $data->phone;
$dept_code = $data->dept_code;
$division_code = $data->division_code;
$position_code = $data->position_code;
$salary = $data->salary;
$worked_start_date = $data->worked_start_date;
$leave_date = $data->leave_date;
$emp_status = $data->emp_status;
$emp_type = $data->emp_type;
$img = $data->img;
$blood = $data->blood;
$address = $data->address;
$district_id = $data->district_id;
$amphur_id = $data->amphur_id;
$province_id = $data->province_id;
*/
   
///GET
$emp_code = $_GET['emp_code'];
$initial_id = $_GET['initial_id'];
$name_thai = $_GET['name_thai'];
$lastname_thai = $_GET['lastname_thai'];
$initial_eng_id = $_GET['initial_eng_id'];
$name_eng = $_GET['name_eng'];
$lastname_eng = $_GET['lastname_eng'];
$id_card = $_GET['id_card'];
$email = $_GET['email'];
$phone = $_GET['phone'];
$dept_code = $_GET['dept_code'];
$division_code = $_GET['division_code'];
$position_code = $_GET['position_code'];
$salary = $_GET['salary'];
$worked_start_date = $_GET['worked_start_date'];
$leave_date = $_GET['leave_date'];
$emp_status = $_GET['emp_status'];
$emp_type = $_GET['emp_type'];
$img = $_GET['img'];
$blood = $_GET['blood'];
$address = $_GET['address'];
$district_id = $_GET['district_id'];
$amphur_id = $_GET['amphur_id'];
$province_id = $_GET['province_id'];





$action = $_GET['cmd'];

switch($action){

    case "select" :
    $sql = "SELECT employee.*,
    concat(initial.initial_name,' ',employee.name_thai,' ',employee.lastname_thai) as thai_name,
    concat(employee.initial_eng_id,' ',employee.name_eng,' ',employee.lastname_eng) as eng_name,
    province.province_name as province,
    amphur.amphur_name as amphur,
    district.district_name as district,
    department.dept_name as department,
    division.division_name as division,
    position.position_name as position,
    emp_type.emp_type_name as type_emp
    FROM employee
    LEFT JOIN initial
    ON initial.initial_code = employee.initial_id
    LEFT JOIN province
    ON province.province_id = employee.province_id
    LEFT JOIN amphur
    ON amphur.amphur_id = employee.amphur_id
    LEFT JOIN district
    ON district.district_id = employee.district_id
    LEFT JOIN department
    ON department.dept_code = employee.dept_code
    LEFT JOIN division
    ON division.division_code = employee.division_code
    LEFT JOIN position
    ON position.position_code = employee.position_code
    LEFT JOIN emp_type
    ON emp_type.emp_type_code = employee.emp_type ";
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

    case "insert" :

    if($emp_code!=null  ){
        
    $sql = "INSERT INTO employee 
    (emp_code, initial_id, name_thai, lastname_thai, initial_eng_id,
    name_eng, lastname_eng, id_card, email, phone, dept_code, 
    division_code, position_code, salary, worked_start_date, 
    leave_date, emp_status, emp_type, img, blood, address, 
    district_id, amphur_id, province_id) 
    VALUES ('".$emp_code."', '".$initial_id."', '".$name_thai."', '".$lastname_thai."', '".$initial_eng_id."', 
    '".$name_eng."', '".$lastname_eng."', '".$id_card."', '".$email."','".$phone."', '".$dept_code."', 
    '".$division_code."', '".$position_code."', '".$salary."', '".$worked_start_date."', '".$leave_date."', 
    '".$emp_status."', '".$emp_type."', '".$img."', '".$blood."', '".$address."', '".$district_id."', 
    '".$amphur_id."', '".$province_id."');";

    $stmt = $strExe->dataTransection($sql);

    if ($stmt == 1) {
        echo json_encode(['status' => 'ok','message' => 'บันทึกข้อมูลเรียบร้อยแล้ว']);
    } else {
        echo json_encode(['status' => 'error','message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
    }

    }else {
        echo json_encode(['status' => 'no','message' => 'กรอกข้อมูลไม่ครบ(emp_code)']);
    }
    
    break;

    case "update" :

    if($emp_code!=null){

    $sql = " UPDATE employee SET 
    initial_id = '".$initial_id."', name_thai = '".$name_thai."', lastname_thai = '".$lastname_thai."', 
    initial_eng_id = '".$initial_eng_id."', name_eng = '".$name_eng."', lastname_eng = '".$lastname_eng."',
    id_card = '".$id_card."',email = '".$email."', phone = '".$phone."', dept_code = '".$dept_code."', 
    division_code = '".$division_code."',position_code = '".$position_code."', salary = '".$salary."', 
    worked_start_date = '".$worked_start_date."', leave_date = '".$leave_date."', 
    emp_status = '".$emp_status."', emp_type = '".$emp_type."', 
    img = '".$img."', blood = '".$blood."',address = '".$address."', district_id = '".$district_id."', 
    amphur_id = '".$amphur_id."', province_id = '".$province_id."' 
    WHERE employee.emp_code = '".$emp_code."' ";

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

    if($emp_code!=null){

    $sql = " DELETE FROM employee 
    WHERE emp_code = '".$emp_code."' ";

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