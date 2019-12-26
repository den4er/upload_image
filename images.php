<?php
	try{
        $host = 'localhost';
        $db_name = 'host1762472_users';
        $login = 'host1762472';
        $password = '123456';

        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $login, $password);
    }catch(PDOException $e){
    	echo $e->getMessage();
    }

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_FILES['file_1']['name']) || empty($_FILES['file_2']['name']) || 													empty($_FILES['file_3']['name'])){
            echo '<h2>Выберите все файлы для загрузки</h2>';
        }else{
          
            $name_1 = $_FILES['file_1']['name']; // имя первого файла
            $name_2 = $_FILES['file_2']['name']; // имя второго файла
            $name_3 = $_FILES['file_3']['name']; // имя третьего файла

            $temp_name_1 = $_FILES['file_1']['tmp_name']; // временное имя первого файла
            $temp_name_2 = $_FILES['file_2']['tmp_name']; // временное имя второго файла
            $temp_name_3 = $_FILES['file_3']['tmp_name']; // временное имя третьего файла  
   
            move_uploaded_file($temp_name_1, 'images/' . $name_1); // перемещаем первый файл
            move_uploaded_file($temp_name_2, 'images/' . $name_2); // перемещаем второй файл
            move_uploaded_file($temp_name_3, 'images/' . $name_3); // перемещаем третий файл
          
          	$names = array();	// объявляем массив для путей файлов
          	$names[1] = 'images/' . $name_1;	// путь к файлу 1
          	$names[2] = 'images/' . $name_2;	// путь к файлу 2
          	$names[3] = 'images/' . $name_3;	// путь к файлу 3
          	
          	try{
            	$query = 'INSERT INTO files VALUES(NULL, ?)';// пишем шаблон запроса
              	$result = $pdo->prepare($query);	// подготавливаем
              	for($i = 1; $i < 4; $i++){	// производим перебор ключей массива в цикле
                	$result->execute(array($names[$i])); //подставляем значение каждого элемента
                  	if(!$result){ // если запрос вернул FALSE
                    	echo 'Произошла ошибка при добавлении в таблицу';
                    }
                }
            }catch(PDOException $e){
            	echo $e->getMessage();
            }
          	
        }  
	} // конец блока обработки формы
	
	try{
        $query = 'SELECT file_name FROM files';
        $result = $pdo->query($query);

        echo '<div class="container">';
        while($file = $result->fetch()){
            echo '<div class="box">';
            echo '<img src="' . $file['file_name'] . '">';
            echo '</div>';
        }
        echo '</div>';    
    }catch(PDOException $e){
    	echo $e->getMessage();
    }
  

?>
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<title>Загрузка файлов</title>
  	  	<style>
 	    .container{
  	        display: flex;
  	        flex-wrap: wrap;
  	    }
  	    .box{
  	        margin: 3px;
  	    }
  	    .box img{
  	        width: 150px;
  	        height: 150px;
  	    }
  	</style>
</head>
<body>
	<form enctype="multipart/form-data" method="POST" action="">
        <label for="file_1">Первый файл</label>
        <input type="file" name="file_1"><br>

      	<label for="file_2">Второй файл</label>
        <input type="file" name="file_2"><br>
      
      	<label for="file_3">Третий файл</label>
        <input type="file" name="file_3"><br>
      
        <input type="submit" name="action" value="Загрузить файлы">
	</form>
</body>  
</html>