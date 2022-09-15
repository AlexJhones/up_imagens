<?php
if(isset($_FILES['arquivo'])) {
    $arquivo = $_FILES['arquivo'];

    if($arquivo['error'])
    die('Falha ao enviar arquivo! Por favor, tente novamente.');

    if($arquivo['size'] > 2097152)
    die('Arquivo muito grande! MAX: MB');
    
    $pasta = "arquivos/";
    $nomeDoArquivo = $arquivo['name'];
    $novoNomeDoArquivo = uniqid();
    $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

    if($extensao != "jpg" && $extensao != "png")
        die('Tipo de arquivo não aceito!');

    $deuCerto = move_uploaded_file($arquivo["tmp_name"], $pasta . $novoNomeDoArquivo . "." . $extensao);

    if($deuCerto)
        echo "<p>Arquivo enviado com sucesso. Para acessá-lo, <a target=\"_blank\" href=\"arquivos/$novoNomeDoArquivo.$extensao\">clique aqui.</a></p>";
    else
        echo '<p>Falha ao enviar arquivo!</p>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>upImagens</title>
</head>
<body>

    <form method="POST" enctype="multipart/form-data" action="">
        <p><label for="">Selecione o arquivo</label>
        <input name="arquivo" type="file"></p>
        <button name="upload" type="submit">Enviar arquivo</button>
    </form>
    
</body>
</html>