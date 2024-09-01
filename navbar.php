<nav class="navbar fixed-top navbar-expand-lg bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="lobby.php" style="color: #DC3545;">VALORANT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="background-color: white;">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="waiting-room.php" style="color: white;">PLAY</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="career.php" style="color: white;">CAREER</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" style="color: gray;">BATTLEPASS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" style="color: gray;">COLLECTION</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="agents.php" style="color: white;">AGENTS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" style="color: gray;">STORE</a>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #DC3545;">
                <?php echo $_SESSION["account"]["USERNAME"]; ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item disabled" href="#">SETTINGS</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">LOGOUT</a></li>
          </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>