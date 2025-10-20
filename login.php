<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <form action="loginValidation.php" method="post">
        <label for="name">Имя</label>
        <input type="text" name="name">
        <label for="surname">Фамилия</label>
        <input type="text" name="surname">
        <label for="password">Пароль</label>
        <input type="text" name="password">
        <button type="submit">Войти</button>
    </form>
</body>
</html>