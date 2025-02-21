<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <!-- <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Home</a>
        </li> -->
    </ul>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item">
            <a class="nav-link">
                <?php
                if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan' || $this->session->userdata('bagian') == 'Auditor') {
                    echo '<h5 class="font-weight-bold">Asset,Penyusutan & Evkin</h5>';
                } else {
                    echo '<h5 class="font-weight-bold">Evaluasi Kinerja</h5>';
                }
                ?>
            </a>
        </li>
        <li class="nav-item" data-toggle="modal" data-target="#logoutModal">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>