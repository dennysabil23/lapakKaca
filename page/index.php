<?php
session_start();
include '../koneksi.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Menggunakan Icon dari Bootstrap dan Fontawesome  -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="shortcut icon" href="../asset/favicon.png" type="image/x-icon">
  <script src="https://kit.fontawesome.com/35cb4de772.js" crossorigin="anonymous"></script>
  <title>Lapak Kaca</title>
  <link rel="stylesheet" href="../css/styles.css" />
</head>

<body>
  <header>
    <!-- Header untuk Logo dan Cari Barang sudah finish PROGRES 100% -->
    <div class="header-container">
      <div class="logo" onclick="window.location.href='index.php'">
        <img src="../asset/lapakkaca-logo.png" alt="Lapak Kaca Logo" />
      </div>
      <div class="search-container">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
      </div>
      <div class="cariEtalase">
        <input type="text" autocomplete="off" id="searchInput" oninput="searchEtalase(this.value)" placeholder="Cari Etalase..">
        <div id="searchResults" class="search-results"></div>
      </div>
      <div class="icon-and-account">
        <div class="cart">
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="cart.php">
              <i class="icon-cart fas fa-shopping-cart"></i>
            </a>
          <?php else: ?>
            <a href="masuk.php">
              <i class="icon-cart fas fa-shopping-cart"></i>
            </a>
          <?php endif; ?>
        </div>
        <div class="akun">
          <?php if (isset($_SESSION['user_id'])): ?>
            <div class="profile-icon">
              <a href="user.php?section=profile">
                <?php
                $user_id = $_SESSION['user_id'];
                $query = "SELECT profile_picture FROM users WHERE id_user = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                $profile_picture = $user['profile_picture'] ? '../uploads/' . $user['profile_picture'] : '../asset/default-pic.jpg';
                ?>
                <img class="img-profile" src="<?php echo htmlspecialchars($profile_picture); ?>">
              </a>
            </div>
            <div class="username">
              <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
            <a class="logout" href="../logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a>
          <?php else: ?>
            <button class="masuk" onclick="window.location.href='masuk.php'"><i class="bi bi-person-circle"></i>Masuk</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="slideshow-container">
      <div class="mySlides fade">
        <img src="../asset/banner.jpg" style="width:100%">
      </div>

      <div class="mySlides fade">
        <img src="../asset/banner2.jpg" style="width:100%">
      </div>
    </div>
    <div class="setdot">
      <span class="dot"></span>
      <span class="dot"></span>
    </div>
  </header>
  <main>
    <!-- Fitur menu sudah aktif PROGRES 90%-->
    <div class="fiturMenu">
      <div class="fitur">
        <div class="menuOrder">
          <a href="user.php?section=orders">
            <i class="fa-solid fa-receipt"></i>
            <span>Status Pesanan</span>
          </a>
        </div>
        <div class="menuAlamat">
          <a href="user.php?section=alamat">
            <i class="fa-solid fa-map-marker-alt"></i>
            <span>Alamat</span>
          </a>
        </div>
        <div class="menuPembayaran">
          <a href="galeri.html">
            <i class="bi bi-image-fill"></i>
            <span>Galeri</span>
          </a>
        </div>
        <div class="menuChat">
          <a href="../chat/index.php">
            <i class="fa-solid fa-comments"></i>
            <span>Chat</span>
          </a>
        </div>
        <div class="menuRiwayat">
          <a href="user.php?section=riwayat">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Riwayat Pembelian</span>
          </a>
        </div>
        <div class="menuProfile">
          <a href="user.php?section=profile">
            <i class="fa-solid fa-user"></i>
            <span>Profile</span>
          </a>
        </div>
      </div>
    </div>

    <section class="produk-section">
      <!-- Area sensitif Hati-hati! PROGRES 90% -->
      <!-- Ratting belum berfungsi -->
      <div class="title1">
        <h3>Produk Unggulan</h3>
      </div>
      <div class="productArea">
        <?php
        $query = "SELECT id_product, name, price, image FROM products";
        $result = $conn->query($query);
        while ($produk = $result->fetch_assoc()): ?>
          <div class="product">
            <a href="produk.php?id=<?php echo $produk['id_product']; ?>">
              <img src="../asset/gambar-produk/<?php echo $produk['image']; ?>" alt="<?php echo htmlspecialchars($produk['name']); ?>" />
            </a>
            <div class="desc">
              <h4><?php echo htmlspecialchars($produk['name']); ?></h4>
              <span class="rp">Rp.</span>
              <span class="harga"><?php echo number_format($produk['price'], 0, ',', '.'); ?></span>
              <div class="ratingMarket">
                <i class="bi bi-star-fill rating"></i>
                <span>0/5</span>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

      <!-- Semua Produk -->

      <div class="title2">
        <h3>Lihat Semua Etalase</h3>
      </div>
      <div class="produk-pajangan">
        <?php
        for ($i = 0; $i < 10; $i++) {
          echo '
              
                  <div class="product">
                    <a href="#">
                      <img src="../asset/gambar-produk/sample-etalase.png" alt="Etalase Berkualitas"/>
                    </a>
                    <div class="desc desc-pajangan">
                      <h4>Etalase Berkualitas</h4>
                      <span> Almunium Silver </span>
                      <div class="btn-pajangan">
                        <button class="ToCart btn-buy">
                          <span>Beli Sekarang</span>
                        </button>
                      </div>
                    </div>
                  </div>
              ';
        }
        ?>
      </div>
    </section>
    <footer>
      <!-- Bagian footer PROGRES 80% -->
      <!-- Tombol belum berfungsi -->
      <div class="footer-container">
        <div class="footer-detail">
          <h4>Tentang Kami</h4><br>
          <p>
            Toko Etalase kami menyediakan berbagai macam etalase dengan
            kualitas kaca terbaik yang dapat memenuhi kebutuhan bisnis
            Anda. Kami melayani pengiriman ke seluruh daerah di Pulau
            Jawa dengan harga yang sudah termasuk ongkos kirim, sehingga
            Anda tidak perlu khawatir dengan biaya tambahan. Proses
            pemesanan dimulai dengan DP (Down Payment) terlebih dahulu,
            dan kami juga menyediakan opsi COD (Cash on Delivery) dengan
            syarat foto dokumen seperti KTP atau identitas lain sebagai
            jaminan. Pengiriman dilakukan dalam 1 hari setelah proses
            produksi selesai, yang memakan waktu sekitar 7 hari kerja.
            Anda juga dapat melakukan request desain sesuai kebutuhan
            dengan menghubungi penjual atau tim pemasaran kami.
          </p>
        </div>
        <div class="footer-link">
          <h5>Informasi Lain</h5><br>
          <ul>
            <li><a href="#">Cara Pesan</a></li>
            <li><a href="#">Kebijakan Privasi</a></li>
            <li><a href="#">Syarat dan Ketentuan</a></li>
          </ul>
        </div>
        <div class="footer-maps">
          <h5>Lokasi</h5>
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d247.92814559913492!2d107.24697305339133!3d-6.15079330363097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6981f07273ae9b%3A0x307b6b922cdcbe56!2sCV%20etalase%20glass!5e0!3m2!1sid!2sid!4v1729956571040!5m2!1sid!2sid"
            width="300"
            height="200"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
          </iframe>
        </div>
        <div class="footer-social">
          <h5>Ikuti Kami</h5><br>
          <ul class="linkSosmed">
            <li><a href="#"><i class="bi bi-facebook"></i></a></li>
            <li><a href="#"><i class="bi bi-instagram"></i></a></li>
            <li><a href="#"><i class="bi bi-twitter"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="hakcipta">
        <p>&copy; 2024 <a href="">Lapak Kaca.</a> All Rights Reserved.</p>
      </div>
    </footer>
  </main>
  <script src="../js/main.js"></script>
</body>

</html>