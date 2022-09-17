<?php
include("conexao.php");

if(isset($_GET['deletar'])) {
    $id = intval($_GET['deletar']);
    $sql_query = $mysqli->query("SELECT * FROM arquivos WHERE id = '$id'") or die($mysqli->error);
    $arquivo = $sql_query->fetch_assoc();
    if(unlink($arquivo['path'])) {
        $deu_certo = $sql_query = $mysqli->query("DELETE FROM arquivos WHERE id = '$id'") or die($mysqli->error);
        if($deu_certo)
        echo "<p>Arquivo excluído com sucesso.</p>";
    }
}

function enviarArquivo ($error, $size, $name, $tmp_name) {
    include("conexao.php");

    if($error)
    die('Falha ao enviar arquivo! Por favor, tente novamente.');

    if($size > 2097152)
    die('Arquivo muito grande! MAX: MB');
    
    $pasta = "arquivos/";
    $nomeDoArquivo = $name;
    $novoNomeDoArquivo = uniqid();
    $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

    if($extensao != "jpg" && $extensao != "png")
        die('Tipo de arquivo não aceito!');

    $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
    $deu_certo = move_uploaded_file($tmp_name, $path);

    if($deu_certo) {
        $mysqli->query("INSERT INTO arquivos (name_arquivo, path) VALUES('$nomeDoArquivo', '$path')") or die($mysqli->error);
        return true;
    } else
        return false;
}

if(isset($_FILES['arquivos'])) {
    $arquivos = $_FILES['arquivos'];
    $tudo_certo = true;
    foreach($arquivos['name'] as $index => $arq){
        $deu_certo = enviarArquivo ($arquivos['error'][$index], $arquivos['size'][$index], $arquivos['name'][$index], $arquivos["tmp_name"][$index]);
        if(!$deu_certo) {
            $tudo_certo = false;
        }
    }

    if($tudo_certo) {
        echo "<p>Todos os arquivos foram enviados com sucesso.</p>";
    }
    else {
        echo "<p>Falha ao enviar arquivos.</p>";
    }
}

$sql_query = $mysqli->query("SELECT * FROM arquivos") or die($mysqli->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css?v1">
    <!-- FONTS GOOGLE -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300&display=swap" rel="stylesheet">
    <title>upImagens</title> <!-- New title: Vision -->
</head>
<body>

    <nav class="main-nav">
    MENU
    </nav>

    <header class="main-header">
        <h1>Envie e compartilhe suas imagens de forma simples e direta.</h1>
        <h2>Crie álbuns e compartilhe de modo público ou privado, protegido por senha ou qualquer pessoa que tenha o link.</h2>
    </header>

    <main class="main">
        <form method="POST" enctype="multipart/form-data" action="">
            <label for="arquivo" class="label-bnt-file">Selecione o arquivo</label>
            <input multiple id="arquivo" class="bnt-file" name="arquivos[]" type="file">

            <button class="bnt-submit" name="upload" type="submit">Enviar Arquivos</button>
        </form>

        <h2>Lista de Arquivos</h2>
        <table border="1" cellpadding="10">
            <thead>
                <th>Visualizar</th>
                <th>Baixar arquivo</th>
                <th>Data do envio</th>
                <th>Deletar</th>
            </thead>

            <tbody>
                <?php while($arquivo = $sql_query->fetch_assoc()) { ?>
                <tr>
                    <th><a target="_blank" href="<?php echo $arquivo['path'] ?>"><img height="50" src="<?php echo $arquivo['path'] ?>" alt=""></a></th>
                    <td><a href="<?php echo $arquivo['path'] ?>" download="<?php $arquivo['name_arquivo']; ?>"><?php echo $arquivo['name_arquivo']; ?></a></td>
                    <td><?php echo date("d/m/Y H:i", strtotime($arquivo['data_upload'])); ?></td>
                    <td><a href="index.php?deletar=<?php echo $arquivo['id'] ?>">Deletar</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <footer>O uso do upImagens constitui a aceitação de nossos Termos de Serviço e Política de Privacidade.</footer>

</body>
</html>