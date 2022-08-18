<?php
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

require './vendor/autoload.php';
$username = "admin";
$password = "admin123";
$host = "localhost";
$database = "BMS";
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$title = [];
$info = [];
$file = $_FILES['file']['tmp_name'];
$fileName = $_FILES['file']['name'];
$path = "./";
move_uploaded_file($file, $path . $fileName);
$spreadsheet = IOFactory::load($fileName);
$whatTable = 0;
try {
    echo "<script>alert('数据正在上传中，请耐心等待!');</script>";
    $sheet = $spreadsheet->getSheet($whatTable);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    try {
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
        $sql = "INSERT INTO test(component_id, person_id, car_id, component_name, person_number, person_name, repair, start_time, end_time) VALUES ";
        for ($i = 2; $i <= $highestRow; $i++) {
            $idx1 = $sheet->getCellByColumnAndRow(1, $i)->getValue();
            $idx2 = $sheet->getCellByColumnAndRow(2, $i)->getValue();
            $idx3 = $sheet->getCellByColumnAndRow(3, $i)->getValue();
            $idx4 = $sheet->getCellByColumnAndRow(4, $i)->getValue();
            $idx5 = $sheet->getCellByColumnAndRow(5, $i)->getValue();
            $idx6 = $sheet->getCellByColumnAndRow(6, $i)->getValue();
            $idx7 = $sheet->getCellByColumnAndRow(7, $i)->getValue();
            $tmp1 = $sheet->getCellByColumnAndRow(8, $i)->getValue();
            $toTimestamp1 = Date::excelToTimestamp($tmp1);
            $date1 = date('Y-m-d H:i:s', $toTimestamp1);
            $tmp2 = $sheet->getCellByColumnAndRow(9, $i)->getValue();
            $toTimestamp2 = Date::excelToTimestamp($tmp2);
            $date2 = date('Y-m-d H:i:s', $toTimestamp2);
            $sql .= "('$idx1', '$idx2', '$idx3', '$idx4', '$idx5', '$idx6', '$idx7', '$date1', '$date2'),";
        }
        $sql = rtrim($sql, ",");
//        echo $sql;
//        echo $sql;
//        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
//        $sql = "INSERT INTO borrowingrecords(BookBarcode, RBarcode, StartTime, ExpiryTime, DisabledRenew, Operator) VALUES ";
//        for ($i = 2; $i <= $highestRow; $i++) {
//            $idx1 = $sheet->getCellByColumnAndRow(1, $i)->getValue();
//            $idx2 = $sheet->getCellByColumnAndRow(2, $i)->getValue();
////            $idx3 = $sheet->getCellByColumnAndRow(3, $i)->getValue();
////            $idx4 = $sheet->getCellByColumnAndRow(4, $i)->getValue();
//            $sql .= "('$idx1', '$idx2', '2018-01-01 00:00:00', '2019-10-01 00:00:00', _binary '\\0', 'Admin'),";
//        }
////        echo $sql;
//        $sql = rtrim($sql, ",");
//        echo $sql;
        $server = "localhost";
        $user = "admin";
        $pw = "admin123";
        $db = "BMS";
        $conn = new PDO("mysql:host=$server;dbname=$db", $user, $pw);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec($sql);
        echo "<script>alert('数据已成功上传！麻烦您重新刷新页面以查看数据！');</script>";
    } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
        echo "<script>alert('数据上传失败！');history.go(-1);</script>";
    }
} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
    echo "<script>alert('数据上传失败！');history.go(-1);</script>";
}