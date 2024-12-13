<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/daftar.css">
    <link rel="shortcut icon" href="../asset/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="../asset/favicon.png" type="image/x-icon">
    <title>Daftar</title>
</head>

<body>
    <div class="container-header">
        <div class="header-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <img src="../asset/lapakkaca-logo.png" />
                <h2>Daftar</h2>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="register-container">

            <h2>Daftar</h2>

            <!-- Error and Success Messages (Optional) -->
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php elseif (isset($_GET['success'])): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
            <?php endif; ?>

            <!-- Step 1: Email, Password, and Full Name Form -->
            <form id="step1" onsubmit="event.preventDefault(); showStep2();">
                <table>
                    <tr>
                        <td>
                            <div class="floating-label">
                                <input type="email" id="email" name="email" placeholder=" " required>
                                <label for="email">Email</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="floating-label">
                                <input type="password" id="password" name="password" placeholder=" " required
                                    pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and contain both letters and numbers">
                                <label for="password">Password</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="floating-label">
                                <input type="password" id="confirm_password" name="confirm_password" placeholder=" " required>
                                <label for="confirm_password">Konfirmasi Password</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="floating-label">
                                <input type="text" id="long_name" name="long_name" placeholder=" " required>
                                <label for="long_name">Nama Lengkap</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><button type="submit">Lanjut</button></td>
                    </tr>
                </table>
            </form>

            <!-- Step 2: Additional Information Form -->
            <form id="step2" action="../proses-daftar.php" method="post" enctype="multipart/form-data" style="display: none;">
                <div class="form-wrapper">
                    <table>
                        <input type="hidden" id="hidden_email" name="email">
                        <input type="hidden" id="hidden_password" name="password">
                        <input type="hidden" id="hidden_long_name" name="long_name">

                        <tr>
                            <td>
                                <div class="floating-label">
                                    <input type="number" name="nomor_telpon" placeholder=" " required>
                                    <label for="nomor_telpon">Nomor Telepon</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="floating-label">
                                    <input type="text" id="pekerjaan" name="pekerjaan" placeholder=" " required>
                                    <label for="pekerjaan">Pekerjaan</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="floating-label">
                                    <input type="number" id="umur" name="umur" placeholder=" " required>
                                    <label for="umur">Umur</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="inputLeft gender">
                                    <label for="gender" class="left-label">Jenis Kelamin</label>
                                    <select id="gender" name="gender" class="left-input" required>
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="inputLeft">
                                    <input type="file" id="profile_picture" accept="image/*" name="profile_picture" class="left-input" required
                                        style="display: none;" onchange="previewProfileImage(event)">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><button type="submit">Daftar</button></td>
                        </tr>
                    </table>

                    <div class="priview">
                        <div class="update-pic-profile">
                            <img id="previewImage" src="../asset/default-picture.jpg" alt="Klik untuk memilih foto"
                                onclick="document.getElementById('profile_picture').click()"
                                style="cursor: pointer; display: block; margin: auto; max-width: 150px;">
                        </div>
                        <i class="bi bi-pencil-fill edit-icon" onclick="document.getElementById('profile_picture').click()"></i>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="../js/daftar.js"></script>
</body>

</html>