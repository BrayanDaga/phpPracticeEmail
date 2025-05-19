<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'Controller.php';
$controller = null;
if ($_POST != null) {
    $controller = new Controller();
    $errors = $controller->getErrors();
    $msg = $controller->getMsg();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulario de Contacto</title>
<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
	<h1>Formulario de Contacto</h1>
    
<?php
if ($controller) :
    // Mostrar errores si existen
    if (!empty($errors)) : ?>
        <ul class="errors">
            <?php foreach ($errors as $mensaje): ?>
                <li><?= htmlspecialchars($mensaje); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($msg)) : ?>
        <?php if (!empty($msg['error'])) : ?>
            <div class="errors">
                <p><?= htmlspecialchars($msg['error']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($msg['success'])) : ?>
            <div class="success">
                <p><?= htmlspecialchars($msg['success']); ?></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

   
 
   
    
    
    <form action="index.php" method="post">
		<label for="name">Nombre:</label> <input type="text" id="name"
			name="name"
			value="<?=  empty($_POST['name']) ? "": htmlspecialchars($_POST['name'])  ?>">

		<label for="email">Correo Electrónico:</label> <input type="text"
			id="email" name="email"
			value="<?=  empty($_POST['email']) ? "": htmlspecialchars($_POST['email'])  ?>">

		<label for="body">Mensaje:</label>
		<textarea id="body" name="body" rows="5"><?=  empty($_POST['body']) ? "": htmlspecialchars($_POST['body'])  ?></textarea>

		<button type="submit">Enviar</button>
	</form>
</body>
</html>