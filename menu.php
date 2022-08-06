<!-- refer to https://www.tutorialrepublic.com/twitter-bootstrap-tutorial/bootstrap-dropdowns.php -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="m-4">
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <div class="container-fluid">
            
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <!-- <li class="nav-item">
                        <a href="http://cei.bio.ed.ac.uk/motif/" class="nav-link">Search</a>
                    </li> -->
                    <li class="nav-item">
                        <a href="http://cei.bio.ed.ac.uk/motif/project.php" class="nav-link">Project Browser</a>
                    </li>
                  <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Sammple Browser</a>
                        <div class="dropdown-menu">
                            <a href="http://cei.bio.ed.ac.uk/motif/sample_sra.php" class="dropdown-item">SRA</a>
                            <a href="http://cei.bio.ed.ac.uk/motif/sample_tcga.php" class="dropdown-item">TCGA</a>
                            <a href="http://cei.bio.ed.ac.uk/motif/sample_gtex.php" class="dropdown-item">GTEx</a>
                    </li>
                  <li class="nav-item">
                        <a href="http://cei.bio.ed.ac.uk/motif/dea.php" class="nav-link">Analysis Result Summary</a>
                    <li class="nav-item">
                        <a href="http://cei.bio.ed.ac.uk/motif/Motif.php" class="nav-link">Motif Browser</a>
                    </li>
                  </li>
                </ul>
                
            </div>
        </div>
    </nav>    
</div>
</body>
</html>
