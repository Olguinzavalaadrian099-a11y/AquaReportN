<?php
$contrasena_plana = "admin123";
$hash = password_hash($contrasena_plana, PASSWORD_DEFAULT);

echo "El hash generado es: $2y$10$q1maT7K1XwJ7N7mWKtYKWumzaXMnBvrVAkZ.dq0DVvoSGrhsS63dy" . $hash;
?>