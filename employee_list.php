<?php
session_start();
// Kiểm tra nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra quyền của người dùng
$role = $_SESSION['role'];
$is_admin = ($role == 'admin');

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
function displayEmployees($page)
{
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
            echo "<td>";
            // Chèn hình ảnh tùy thuộc vào giới tính
            if ($row['Phai'] == 'NAM') {
                echo "<img src='images/man.png' alt='NAM' weight=115px height=115px/>";
            } elseif ($row['Phai'] == 'NU') {
                echo "<img src='images/woman.png' alt='NU' weight=115px height=115px/>";
            }
            echo "</td>";
            echo "<td>" . $row['Noi_Sinh'] . "</td>";
            echo "<td>" . $row['Ten_Phong'] . "</td>";
            echo "<td>" . $row['Luong'] . "</td>";
            if ($GLOBALS['is_admin']) {
                echo "<td><button onclick=\"editEmployee('" . $row['Ma_NV'] . "')\">Sửa</button> <button onclick=\"deleteEmployee('" . $row['Ma_NV'] . "')\">Xóa</button></td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>0 kết quả</td></tr>";
    }
}
$page = isset($_GET['page']) ? $_GET['page'] : 1;
// Tính tổng số nhân viên và số trang
$sql_count = "SELECT COUNT(*) AS total FROM NHANVIEN";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_employees = $row_count['total'];
$total_pages = ceil($total_employees / 5); // 5 là số lượng nhân viên trên mỗi trang

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhân sự</title>
    <script>
        function editEmployee(employeeId) {
            window.location.href = 'edit_employee.php?id=' + employeeId;
        }

        function deleteEmployee(employeeId) {
            var confirmDelete = confirm("Bạn có chắc muốn xóa nhân viên này?");
            if (confirmDelete) {
                window.location.href = 'process_delete_employee.php?id=' + employeeId;
            }
        }

        function addEmployee() {
            window.location.href = 'add_employee.php';
        }

        function goToPage(page) {
            window.location.href = 'employee_list.php?page=' + page;
        }

        function logout() {
            window.location.href = 'logout.php';
        }
    </script>
</head>

<body>
    <h1>Danh sách nhân viên</h1>
    <?php if ($is_admin) : ?>
        <button onclick="addEmployee()">Thêm nhân viên</button>
    <?php endif; ?>
    <table border="1">
        <thead>
            <tr>
                <th>Mã nhân viên</th>
                <th>Tên nhân viên</th>
                <th>Giới tính</th>
                <th>Nơi sinh</th>
                <th>Tên phòng</th>
                <th>Lương</th>
                <?php if ($is_admin) : ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Gọi hàm hiển thị thông tin nhân viên
            displayEmployees($page);
            ?>
        </tbody>
    </table>
    <?php if ($page > 1) : ?>
        <button onclick="goToPage(<?php echo $page - 1; ?>)">Trang trước</button>
    <?php endif; ?>
    <?php if ($page < $total_pages) : ?>
        <button onclick="goToNextPage()">Trang sau</button>
    <?php endif; ?>
    <button onclick="logout()">Đăng xuất</button>

    <!-- Thêm hidden input để truyền giá trị của $page -->
    <input type="hidden" id="currentPage" value="<?php echo $page; ?>">

    <script>
        function goToNextPage() {
            // Lấy giá trị của $page từ hidden input
            var currentPage = document.getElementById('currentPage').value;
            // Chuyển đến trang kế tiếp
            var nextPage = parseInt(currentPage) + 1;
            window.location.href = 'employee_list.php?page=' + nextPage;
        }

        function goToPage(page) {
            window.location.href = 'employee_list.php?page=' + page;
        }

        function logout() {
            window.location.href = 'logout.php';
        }
    </script>
</body>


</html>
