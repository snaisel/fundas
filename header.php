<header class=" justify-content-center mb-4 shadow-sm">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand">
                <span class="fs-4">Fundas</span>
                <svg class="bi me-2" width="40" height="32">
                    <use xlink:href="index.php"></use>
                </svg>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto nav-fill mb-2 mb-lg-0" style="width: 80%;   ">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link" aria-current="page"><i class="bi bi-house-door"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="opciones.php" class="nav-link"><i class="bi bi-card-list"></i> Opciones</a>
                    </li>
                    <li class="nav-item">
                        <a href="acciones.php" class="nav-link"><i class="bi bi-box"></i> Fundas</a>
                    </li>
                    <li class="nav-item">
                        <a href="modelos.php" class="nav-link"><i class="bi bi-phone"></i> Modelos</a>
                    </li>
                    <li class="nav-item">
                        <a href="sumar.php" class="nav-link"><i class="bi bi-node-plus"></i> Sumar</a>
                    </li>
                    <li class="nav-item">
                        <a href="restar.php" class="nav-link"><i class="bi bi-node-minus"></i> Restar</a>
                    </li>
                    <li class="nav-item">
                        <a href="admin_config.php" class="nav-link"><i class="bi bi-gear"></i> Administrar</a>
                    </li>
                    <li class="nav-item">
                        <a href="doc.php" class="nav-link"><i class="bi bi-question-circle"></i> Ayuda</a>
                    </li>
                </ul>
                <form method="post" action="index.php" class="d-flex mb-0">
                    <input type="hidden" name="logout" value="1">
                    <button type="submit" class="btn btn-outline-danger me-2">Cerrar sesión</button>
                </form>

            </div>
        </div>
    </nav>
</header>

