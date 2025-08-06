<?php 

    include "../controller/auth.php";

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>The Kost | Login</title>
        <link rel="icon" href="/kost/assets/UI/Dashboards/assets/images/info-icon-03.png" type="image/x-icon">

        <!-- Bootsrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

        <!-- fontawesome CDN -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Login CSS -->
        <link rel="stylesheet" href="/kost/assets/UI/Dashboards/assets/css/login.css">

    </head>

    <body>

        <div class="container">
            <div class="row justify-content-center mt-3">
                <div class="col-md-6 col-lg-4">
                    <div class="card p-4">
                        <div class="card-body">

                            <div class="text-center mb-3">
                                <h3><span style="color: #8b0420;">The Kost</span> | Login</h3>
                                <p class="text-muted">Silakan masuk ke akun Anda</p>
                            </div>

                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                <div class="mb-3">

                                    <label for="username" class="form-label">Username</label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" name="username" id="username" class="form-control" required>

                                    </div>

                                </div>

                                <div class="mb-3">

                                    <label for="password" class="form-label">Password</label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>

                                        <input type="password" name="password" id="password" class="form-control" required>

                                        <span class="input-group-text" onclick="togglePassword()">
                                            <i class="fas fa-eye" id="toggleIcon"></i>
                                        </span>

                                    </div>

                                </div>

                                <div class="d-grid mb-3">

                                    <button type="submit" name="login" class="btn btn-primary mb-2">
                                        Login
                                    </button>

                                    <a href="/kost/" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>

                                </div>

                                <div class="text-center">
                                    <small>Belum punya akun? 
                                        <a href="/kost/views/dashboards/regis">Daftar di sini</a>
                                    </small>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Toggle Password -->
        <script>
            function togglePassword() {
                const input = document.getElementById("password");
                const icon = document.getElementById("toggleIcon");
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
        </script>

        <!-- SweetAlert2 Error Notification -->
        <?php if (isset($error_message)) : ?>
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '<?php echo $error_message; ?>',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = "/kost/views/dashboards/login";
                });
            </script>
            <?php unset($error_message); ?>
        <?php endif; ?>

    </body>

</html>