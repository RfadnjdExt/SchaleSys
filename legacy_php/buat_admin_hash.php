<?php
// Ganti 'password_admin_baru' dengan password yang Anda inginkan
$password_plain = 'password123'; 

$hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

echo "Username: admin<br>";
echo "Password Plain: " . htmlspecialchars($password_plain) . "<br>";
echo "Password Hash (salin ini): <br>";
echo "<textarea rows='3' cols='70' readonly>" . htmlspecialchars($hashed_password) . "</textarea>";
?>