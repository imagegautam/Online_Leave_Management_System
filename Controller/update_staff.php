<?php
include '../controller/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    $sql = "UPDATE users SET username = ?, password = ?, role = ? WHERE user_id = ? AND role = 'staff'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $password, $role, $user_id);

    if ($stmt->execute()) {
		echo "<script>alert('Staff details updated successfully.'); window.location.href='../view/view_all_staff.php';</script>";
    } else {
        echo "<script>alert('Failed to Update staff Details !!!..'); window.location.href='../controller/update_staff.php';</script>";
    }

    $stmt->close();
    $conn->close();
    exit();
}

if (isset($_GET['id'])) {
    $staff_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE user_id = ? AND role = 'staff'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
    } else {
        echo "No staff found with the given ID.";
        exit();
    }

    $stmt->close();
} else {
    echo "No staff ID provided.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Staff - Leave Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .header {
            background-color: #8FBC8F;
            color: white;
            padding: 2px;
            display: flex;
            justify-content: space-between;
            align-items: center;
			background-image: url('../images/sidebar.jpg');
        }
        .header h1 {
            margin: 0;
        }
        .user-info {
            font-size: 14px;
			margin-right: 10px;
        }
        .container {
            display: flex;
            margin: 20px;
			width: 1200px;
        }
        .sidebar {
            width: 200px;
            background-color: #8FBC8F;
            padding: 20px;
			background-image: url('../images/sidebar.jpg');
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 5px;
        }
        .sidebar a:hover {
            background-color: #465669;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: white;
            margin-left: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background-color: #FF8C00;
            color: white;
            padding: 10px 40px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #8FBC8F;
        }
        .footer {
            background-color: #8FBC8F;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
			background-image: url('../images/sidebar.jpg');
        }
		.imagesrc{
			height: 70px;
			width:120px;
			margin-left: 0px;
		}
    </style>
</head>
<body>
    <div class="header">
		<img class = "imagesrc" src="../images/logo4.png" alt = "" />
        <h1>TechS Inc Leave Management System</h1>
        <div class="user-info">
            <span><a href="../controller/admin_dashboard.php" style="color: white; text-decoration: none;">Home</a></span> | 
			<span><a href="../index.php" style="color: white; text-decoration: none;">Logout</a></span> | 
            <span>Hi admin</span>
        </div>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <a href="../controller/admin_dashboard.php">Add Staff</a>
			<a href="../view/leave_requests.php">View Leave Requests</a>
            <a href="../view/view_all_staff.php">View All Staff</a>
            <a href="../controller/add_leave.php">Add Leave Type</a>
            <a href="../controller/delete_leave.php">Delete Leave Type</a>
			<a href="../controller/add_compoff_leave.php">Add Comp-Off Leave</a>
        </div>
        
        <div class="content">
            <h2>Update Staff</h2>
            <form action="../controller/update_staff.php" method="post">
                <div class="form-group">
                    <label>ID</label>
                    <input type="text" placeholder="Staff ID" name="user_id" value="<?php echo $staff['user_id']; ?>" required readonly>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" placeholder="Staff Name" name="username" value="<?php echo $staff['username']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="text" id="password" name="password" value="<?php echo $staff['password']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="staff" <?php if($staff['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                        <option value="admin" <?php if($staff['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn">Update</button>
            </form>
        </div>
    </div>
    
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
</body>
</html>
