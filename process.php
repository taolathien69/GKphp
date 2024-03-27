<?php
// Kết nối đến MySQL
$servername = "localhost";
$username = "root";
$password = "";
$database = "ql_nhansu";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}

// Chức năng hiển thị thông tin nhân viên và phân trang
function displayEmployees($page) {
    global $conn;
    $results_per_page = 5;
    $start_from = ($page - 1) * $results_per_page;

    $sql = "SELECT NHANVIEN.Ma_NV, Ten_NV, Phai, Noi_Sinh, Ten_Phong, Luong
            FROM NHANVIEN
            INNER JOIN PHONGBAN ON NHANVIEN.Ma_Phong = PHONGBAN.Ma_Phong
            LIMIT $start_from, $results_per_page";
    $result = $conn->query($sql);

    // Hiển thị dữ liệu và ảnh giới tính
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Ma_NV'] . "</td>";
            echo "<td>" . $row['Ten_NV'] . "</td>";
            echo "<td>" . $row['Phai'] . "</td>";
            echo "<td>" . $row['Noi_Sinh'] . "</td>";
            echo "<td>" . $row['Ten_Phong'] . "</td>";
            echo "<td>" . $row['Luong'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "0 kết quả";
    }
}

// Chức năng đăng nhập
function login($username, $password) {
    global $conn;
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row['role'];
    } else {
        return false;
    }
}

// Chức năng kiểm tra vai trò
function checkRole($role) {
    if ($role == 'admin') {
        return true;
    } else {
        return false;
    }
}

// Chức năng thêm nhân viên
function addEmployee($maNV, $tenNV, $phai, $noiSinh, $maPhong, $luong) {
    global $conn;
    $sql = "INSERT INTO NHANVIEN (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) 
            VALUES ('$maNV', '$tenNV', '$phai', '$noiSinh', '$maPhong', $luong)";
    if ($conn->query($sql) === TRUE) {
        echo "Thêm nhân viên thành công";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}


// Chức năng sửa nhân viên
function editEmployee($maNV, $tenNV, $phai, $noiSinh, $maPhong, $luong) {
    global $conn;
    $sql = "UPDATE NHANVIEN 
            SET Ten_NV='$tenNV', Phai='$phai', Noi_Sinh='$noiSinh', Ma_Phong='$maPhong', Luong=$luong 
            WHERE Ma_NV='$maNV'";
    if ($conn->query($sql) === TRUE) {
        echo "Cập nhật thông tin nhân viên thành công";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}


// Chức năng xóa nhân viên
function deleteEmployee($maNV) {
    global $conn;
    $sql = "DELETE FROM NHANVIEN WHERE Ma_NV='$maNV'";
    if ($conn->query($sql) === TRUE) {
        echo "Xóa nhân viên thành công";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

?>
