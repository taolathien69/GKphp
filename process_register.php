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

// Xử lý đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    $sql = "INSERT INTO users (username, password, fullname, email, role) VALUES ('$username', '$password', '$fullname', '$email', 'user')";

    if ($conn->query($sql) === TRUE) {
        echo "Đăng ký thành công";
        // Chuyển hướng về trang đăng nhập sau khi đăng ký thành công
        header("Location: login.php");
        exit();
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
