<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php?action=visit">Site de Recettes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php?action=home">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?action=contact">Contact</a>
        </li>
        <?php if(!empty($_SESSION['LOGGED_USER'])) : ?>
        <li class="nav-item">
          <a class="nav-link" href="index.php?action=add">Ajoutez une recette !</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
    <?php if(!empty($_SESSION['LOGGED_USER'])): ?>
      <button><a href="logout.php">LOGOUT</a></button>
    <?php endif; ?>
  </div>
</nav>