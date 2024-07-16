<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/index.css">
    <link rel="stylesheet" href="../style/navbar.css">

    <title>Login</title>
</head>
<body>
    <div class="login-container">

        
        <div class="container">
      <div class="sign-up-form">
        <form action="../scripts/login.php" method="post" class="form-content">
          <div class="input-wrap">
            <div class="input">
              <input type="text" id="username" placeholder=" " name="username"/>
              <div class="label">
                <label for="username">Username</label>
              </div>
            </div>
            <div class="input">
              <input type="password" id="password" name="password" placeholder=" " />
              <div class="label">
                <label for="password">Password</label>
              </div>
            </div>
            <button type="submit">Login</button>
          </div>
        </form>
      </div>
    </div>

    </div>
</body>
</html>
