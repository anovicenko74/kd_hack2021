<?php

	if ($_POST['login']=='' || $_POST['password']=='') echo 
		'<!DOCTYPE html>
			<html lang="ru">
			<head>
			    <meta charset="UTF-8">
			    <title>Регистрация</title>
			</head>
			<body>
			 
				<form action="registration" method="POST">
					<p>логин
				    <input type="text" name="login" id="login"></p>
				    <p>пароль
				    <input type="password" name="password" id="password"></p>
				    <input type="submit" id="button" value="Зарегистрироваться">
				</form>
				<p><a href="login">Авторизация</a> </p>
			                
			</body>
			</html>';
	else{
		//вот так данные можно отправлять без проверки вовсе, ни чего очень плохого случиться не должно. 
		//PDO все заэкранирует и превратит в текст. 
		//Можно разве что проверять длинну текста и какие то специфическиие данные
		try{
			$sql = "INSERT INTO  `users` (`id` ,`login` ,`password`)
					VALUES (
					NULL ,  :login,  :pass
					);";//Формируем запрос без данных
			$result = $pdo->prepare($sql);
			$result->bindvalue(':login', $_POST['login']);	//Заполняем данные
			$result->bindvalue(':pass', md5(md5($_POST['password'])));	//Заполняем данные. Пароли хранить в открытом виде, дело такое. Поэтому двойной md5)
			$result->execute( );							//Выполняем запросы

			echo '<meta charset="UTF-8">Регистрация успешна!';
		}catch(PDOException $e){
			$Log_File = "log.txt";
			file_put_contents($Log_File, date("Y-m-d H:i:s")." -//- ".$e->getMessage().PHP_EOL, FILE_APPEND | LOCK_EX);				
			echo '<meta charset="UTF-8">Ошибка регистрации';
		}

			
		}
?>