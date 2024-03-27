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

// Xử lý việc thêm nhân viên
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maNV = $_POST['maNV'];
    $tenNV = $_POST['tenNV'];
    $gioiTinh = $_POST['gioiTinh'];
    $noiSinh = $_POST['noiSinh'];
    $maPhong = $_POST['maPhong'];
    $luong = $_POST['luong'];

    // Thực hiện thêm dữ liệu vào bảng NHANVIEN
    $sql = "INSERT INTO NHANVIEN (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) 
            VALUES ('$maNV', '$tenNV', '$gioiTinh', '$noiSinh', '$maPhong', '$luong')";

    if ($conn->query($sql) === TRUE) {
        echo "Thêm nhân viên thành công";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm nhân viên</title>
</head>
<body>
    <h2>Thêm nhân viên</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="maNV">Mã nhân viên:</label><br>
        <input type="text" id="maNV" name="maNV"><br>

        <label for="tenNV">Tên nhân viên:</label><br>
        <input type="text" id="tenNV" name="tenNV"><br>

        <label for="gioiTinh">Giới tính:</label><br>
        <select id="gioiTinh" name="gioiTinh">
            <option value="NAM">Nam</option>
            <option value="NU">Nữ</option>
        </select><br>

        <label for="noiSinh">Nơi sinh:</label><br>
        <input type="text" id="noiSinh" name="noiSinh"><br>

        <label for="maPhong">Mã phòng:</label><br>
        <input type="text" id="maPhong" name="maPhong"><br>

        <label for="luong">Lương:</label><br>
        <input type="text" id="luong" name="luong"><br>

        <input type="submit" value="Thêm">
    </form>
</body>
</html>
