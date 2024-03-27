<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "ql_nhansu";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}

// Xác định mã nhân viên cần xóa từ URL và thực hiện xóa
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $sql = "DELETE FROM NHANVIEN WHERE Ma_NV='$employeeId'";
    if ($conn->query($sql) === TRUE) {
        echo "Xóa nhân viên thành công";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}
?>
<br>
<a href="index.php">Quay lại</a>
