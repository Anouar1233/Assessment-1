<?php

//$hashed_password = password_hash("123", PASSWORD_DEFAULT);
//echo $hashed_password;

//username: anouar 
//password : hallo
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Secure Login System PHP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="d-flex justify-content-center align-items-center min-vh-100">
    <form class="p-5 rounded shadow" action="authenticate.php" method="post" style="width: 30rem">
      <h1 class="text-center pb-5 display-4">Login</h1>
      <?php if (isset($_GET['error'])) { ?>
      <div class="alert alert-danger" role="alert">
        <?=htmlspecialchars($_GET['error'])?>
      </div>
      <?php } ?>
      <div class="mb-3">
        <label for="exampleInputUsername" class="form-label">Username</label>
        <input type="text" name="username" value="<?php if(isset($_GET['username']))echo(htmlspecialchars($_GET['username'])) ?>" class="form-control" id="exampleInputUsername" aria-describedby="usernameHelp">
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="exampleInputPassword">
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
</body>
</html>
