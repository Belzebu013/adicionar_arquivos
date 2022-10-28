<a href="index.php">Ver Cadastros</a>
<hr/>
<form action="cadastro.php" method="post"
enctype="multipart/form-data">
<input type="text" name="nome" 
placeholder="nome"/><br/>
<input type="file" name="arquivo"/><br/>
<input type="submit" name="upload" value="Gravar"/>
</form>
<?php
if(isset($_POST['upload'])){
    $nome=$_POST['nome'];
    $_UP['pasta']="arquivos/";
    $_UP['tamanho']=1024*1024*2; //2mb
    $_UP['extensao']=array('jpg','png','jpeg');
    $_UP['renomear']=true;

    //validação de extenção
    $explode=explode('.',$_FILES['arquivo']['name']);
    $aponta=end($explode);
    $extensao=strtolower($aponta);
    if(array_search($extensao,$_UP['extensao'])
    ===false){
        echo "Extensão não aceita";
        exit();
    }

    //validação de tamanho de arquivo
    if($_UP['tamanho']<=$_FILES['arquivo']['size']){
        echo "Arquivo muito grande";
        exit();
    }

    //validação de nome (renomear)
    if($_UP['renomear']===true){
        $nome_final=md5(time()).".$extensao";
    }else{
        $nome_final=$_FILES['arquivo']['name'];
    }

    if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'].$nome_final)){
        include 'conn.php';
        $url = $_UP['pasta'].$nome_final;
        $grava = $conn->prepare('INSERT INTO arquivos (id_arq, nome_arq, url_arq) VALUES (NULL, ?, ?)');
        $grava -> bindValue(1, $nome);
        $grava -> bindValue(2, $url);
        $grava -> execute();
        echo "Gravado com Sucesso";
        
    }else{
        echo "Algo deu errado";
    }

}
?>