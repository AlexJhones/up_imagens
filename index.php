<?php

include("conexao.php");

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

    $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
    $deuCerto = move_uploaded_file($arquivo["tmp_name"], $path);

    if($deuCerto) {
        $mysqli->query("INSERT INTO arquivos (name_arquivo, path) VALUES('$nomeDoArquivo', '$path')") or die($mysqli->error);
        echo "<p>Arquivo enviado com sucesso. Para acessá-lo, <a target=\"_blank\" href=\"arquivos/$novoNomeDoArquivo.$extensao\">clique aqui.</a></p>";
    } else
        echo '<p>Falha ao enviar arquivo!</p>';

}
$sql_query = $mysqli->query("SELECT * FROM arquivos") or die($mysqli->error);
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

    <h1>Lista de Arquivos</h1>
    <table border="1" cellpadding="10">
        <thead>
            <th>Preview</th>
            <th>Arquivo</th>
            <th>Data de Envio</th>
        </thead>

        <tbody>
            <?php while($arquivo = $sql_query->fetch_assoc()) { ?>
            <tr>
                <th><img height="50" src="<?php echo $arquivo['path'] ?>" alt=""></th>
                <td><a target="_blank" href="<?php echo $arquivo['path'] ?>"><?php echo $arquivo['name_arquivo']; ?></a></td>
                <td><?php echo date("d/m/Y H:i", strtotime($arquivo['data_upload'])); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    
</body>
</html>