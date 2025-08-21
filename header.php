<header class="d-flex flex-wrap justify-content-center mb-4 border-bottom">
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand">
                <svg class="bi me-2" width="40" height="32">
                    <use xlink:href="index.php"></use>
                </svg>
                <span class="fs-4">Fundas</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link" aria-current="page">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="opciones.php" class="nav-link">Opciones</a>
                    </li>
                    <li class="nav-item">
                        <a href="acciones.php" class="nav-link">Fundas</a>
                    </li>
                    <li class="nav-item">
                        <a href="modelos.php" class="nav-link">Modelos</a>
                    </li>
                    <li class="nav-item">
                        <a href="sumar.php" class="nav-link">Sumar</a>
                    </li>
                    <li class="nav-item">
                        <a href="restar.php" class="nav-link">Restar</a>
                    </li>
                    <li class="nav-item">
                        <a href="admin_config.php" class="nav-link">Administrar</a>
                    </li>
                    <li class="nav-item">
                        <a href="doc.php" class="nav-link">Ayuda</a>
                    </li>
                    <li class="nav-item">
                        <form method="post" action="index.php" class="d-flex">
                            <input type="hidden" name="logout" value="1">
                            <button type="submit" class="btn btn-outline-danger">Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>