<?php
require_once __DIR__.'/cek-akses.php';
require_once __DIR__.'/i18n/translate.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/"><?php _t('Home');?></a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="keranjang.php"><?php _t('Keranjang');?></a>
            </li>
            <?php
            if (hasLogin()) {
            ?>
            <?php if (hasAccess('lihatDaftarPesanan')) {?>
            <li class="nav-item">
            <a class="nav-link" href="pesanan.php"><?php _t('Pesanan');?></a>
            </li>
            <?php }?>
            <li class="nav-item">
            <a class="nav-link" href="logout.php"><?php _t('Keluar');?></a>
            </li>
            <?php } else {?>
            <li class="nav-item">
            <a class="nav-link" href="login.php"><?php _t('Masuk');?></a>
            </li>
            <?php }?>
        </ul>
        <ul class="navbar-nav me-3">
            <li class="nav-item dropdown">
                <?php
                $languages = getLanguages();
                ?>
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><?php echo $languages[getCurrentLanguage()]['label'];?></a>
                <ul class="dropdown-menu">
                    <?php
                    foreach($languages as $locale => $language) {
                    ?>
                    <li>
                        <a href="?__lang=<?php echo $locale;?>" class="dropdown-item"><?php echo $language['title'];?></a>
                    </li>
                    <?php }?>
                </ul>
            </li>
        </ul>
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit"><?php _t('Cari');?></button>
        </form>
        </div>
    </div>
</nav>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>