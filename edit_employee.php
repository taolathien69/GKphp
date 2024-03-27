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
$employeeId = "";
// Lấy thông tin nhân viên từ database dựa trên mã nhân viên được truyền qua URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $sql = "SELECT * FROM NHANVIEN WHERE Ma_NV='$employeeId'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        ?>
        <form method="post">
            <label for="tenNV">Tên nhân viên:</label>
            <input type="text" id="tenNV" name="tenNV" value="<?php echo $row['Ten_NV']; ?>"><br>
            <label for="gioiTinh">Giới tính:</label>
            <input type="text" id="gioiTinh" name="gioiTinh" value="<?php echo $row['Phai']; ?>"><br>
            <label for="noiSinh">Nơi sinh:</label>
            <input type="text" id="noiSinh" name="noiSinh" value="<?php echo $row['Noi_Sinh']; ?>"><br>
            <label for="maPhong">Tên phòng:</label>
            <input type="text" id="maPhong" name="maPhong" value="<?php echo $row['Ma_Phong']; ?>"><br>
            <label for="luong">Lương:</label>
            <input type="text" id="luong" name="luong" value="<?php echo $row['Luong']; ?>"><br>
            <input type="submit" value="Lưu">
        </form>
        <?php
    } else {
        echo "Không tìm thấy nhân viên.";
    }
}

// Xử lý việc lưu thông tin sau khi sửa nhân viên
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form sửa và cập nhật vào cơ sở dữ liệu
    $name = $_POST['tenNV'];
    $gioiTinh = $_POST['gioiTinh'];
    $noiSinh = $_POST['noiSinh'];
    $maPhong = $_POST['maPhong'];
    $luong = $_POST['luong'];

    // Cập nhật thông tin vào cơ sở dữ liệu
    $sql_update = "UPDATE NHANVIEN SET Ten_NV='$name', Phai='$gioiTinh', Noi_Sinh='$noiSinh', Ma_Phong='$maPhong', Luong='$luong' WHERE Ma_NV='$employeeId'";
    // Thực thi truy vấn cập nhật
    if ($conn->query($sql_update) === TRUE) {
        echo "Cập nhật thông tin nhân viên thành công";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>
