<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <style>
    body {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 400px;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      text-align: center;
    }

    h1 {
      color: #333;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: #f8d7da;
      color: #721c24;
      font-weight: bold;
      border: 1px solid #f5c6cb;
    }

    td {
      border: 1px solid #dee2e6;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 15px;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 15px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Sign in</h1>
    <?php if (isset($_GET['error'])): ?>
      <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>
    <form action="session.php" method="post">
      <table>
        <tr>
          <th colspan="2">Authentification</th>
        </tr>
        <tr>
          <td>Identifiant</td>
          <td><input type="text" name="identifiant" placeholder="Username ou Email" required></td>
        </tr>
        <tr>
          <td>Password</td>
          <td><input type="password" name="password" size="20" maxlength="15" required></td>
        </tr>
        <tr>
          <th colspan="2"><button type="submit">Sign in</button></th>
        </tr>
      </table>
    </form>
  </div>
</body>
</html>
