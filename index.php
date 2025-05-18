<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    include_once 'Controller.php';
    $controller = null;
    if ($_POST != null) {
    $controller =  new Controller();
   
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
    <link rel="stylesheet"  type="text/css" href="style.css">
   	
</head>
<body>
    <h1>Formulario de Contacto</h1>
    
   <?php if ($controller && !empty($controller->errors)): ?>
    <ul class="errors">
        <?php foreach ($controller->errors as $mensaje): ?>
            <li><?php echo htmlspecialchars($mensaje); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
   
    
    
    <form action="index.php" method="post">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" 
        value="<?=  empty($_POST['name']) ? "": htmlspecialchars($_POST['name'])  ?>" > 

        <label for="email">Correo Electrónico:</label>
        <input type="text" id="email" name="email"  
        value="<?=  empty($_POST['email']) ? "": htmlspecialchars($_POST['email'])  ?>" >

        <label for="message">Mensaje:</label>
        <textarea id="message" name="message" rows="5" ><?=  empty($_POST['message']) ? "": htmlspecialchars($_POST['message'])  ?></textarea>

        <button type="submit">Enviar</button>
    </form>
</body>
</html>