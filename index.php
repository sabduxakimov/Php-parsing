<?php 
	
	header("Content-Type: text/html; charset=utf-8");
	require_once 'includes/simple_html_dom.php';
	require_once 'includes/db_connect.php';
	require_once 'includes/db_object.php';
	$message = '';
	$notFound = '';
	if($_POST['submit']){
		$html = file_get_html('http://www.pc.uz/trade/orgs/cat1013');
		$flag = true;
		$isReady = false;
		$lineInfo = array('address', 'email', 'phone', 'website');
		foreach($html->find("table[width=98%] tbody tr") as $element){

			if(!empty($element->getElementByTagName("strong")->plaintext)){
				$company['name'] = $element->getElementByTagName("strong")->plaintext;
			}

			$counter = 0;
			foreach ($element->find("td[class=line_about] div") as $info) {
				if($flag && !empty($info->plaintext)){
					$company['description'] = $info->plaintext;
					$flag = false;
					$isReady = true;
				}
				if(!empty($info->getElementByTagName("span")->plaintext)){
					$company[$lineInfo[$counter]] = $info->getElementByTagName("span")->plaintext;
					$counter++;			
				}

			}
			if($isReady){
				$companies[] = $company;
				$company = null;			
			}
			$flag = true;
			$isReady = false;
		}
		$qwer= '';

		for ($i=0; $i < count($companies); $i++) { 
			unset($success);
			$qwer="INSERT INTO companies (name, description, address, phone, email, website) VALUES ('".$companies[$i][name]."', '".$companies[$i][description]."', '".$companies[$i][address]."', '".$companies[$i][email]."', '".$companies[$i][phone]."', '".$companies[$i][website]."');";		
			$success = $mySqli->query($qwer);
		}
		if($success){
			$message = "Данные записаны успешно!";
			$result = DatabaseObject::search();
		}
	}

	if($_POST['search']){
		if(empty($_POST['keyword'])){
			$result = DatabaseObject::search();
		} else{
			$result = DatabaseObject::search($_POST['keyword']);
		}
		if(!$result){
			$notFound = "По вашему запросу ничего не найдено!";
		}
		
	}
	
	$mySqli->close();
?>
	<html>
	<head>
		<title>Парсинг...</title>
		<link rel="stylesheet" type="text/css" media="all" href="style/bootstrap.min.css" />
	</head>
	<body>
		<div class="container">
	      <!-- Static navbar -->
	      <nav class="navbar navbar-inverse">
	        <div class="container-fluid">
	          <div class="navbar-header">
	            <a class="navbar-brand" href="#">Парсинг</a>
	          </div>
	        </div>
	      </nav>

      	<!-- Main component for a primary marketing message or call to action -->
	      <div class="jumbotron">
	        <h1>Парсинг</h1>
	        <p>При нажатии кнопки произойдет парсинг страницы <a href="http://www.pc.uz/trade/orgs/cat1013" alt="pc.uz" title="pc.uz">pc.uz</a></p>
	        <form action="index.php" method="POST" name="form">
				<p><input type="submit" class="btn btn-success" value="Пропарсит страницу!" name="submit">
				<?php echo $message;?></p>
			</form>
	      </div>
	      <div class="well">
	      	<form action="index.php" method="post" name="form2">
	      		<div class="row">
		      		<div class="col-xs-10">
		      			<input type="text" id="keyword" class="form-control" name="keyword" placeholder="Введите название, адрес или телефон компании">
		      		</div>
	      			<div class="col-xs-2">
	      				<input type="submit" id="search" class="form-control btn btn-success pull-left" value="Поиск" name="search">
	      			</div>
	      		</div>
	      		<div class="row">
	      			<div class="col-xs-12">
	      				<br>
	      				<p style="color:red"><?php echo $notFound;?></p>
	      			</div>
	      		</div>
	      	</form>
	      </div>
	    <?php
	      	if($result){
	    ?>
			<div class="well">
	      		<table class="table table-condensed table-responsive">
	      			<thead>
	      				<th>N/N</th>
	      				<th>Название</th>
	      				<th>Описание</th>
	      				<th>Адрес</th>
	      				<th>Тел</th>
	      				<th>Емайл</th>
	      				<th>Сайт</th>
	      			</thead>
	      			<tbody>
	      	<?php	
			foreach ($result as $object) {
				echo "<tr>
						<td>".++$counter."</td>
						<td>".$object->get_name()."</td>
						<td>".$object->get_description()."</td>
						<td>".$object->get_address()."</td>
						<td>".$object->get_phone()."</td>
						<td>".$object->get_email()."</td>
						<td>".$object->get_website()."</td>
					</tr>";
			}

			?>
      				</tbody>
	      		</table>
	      	</div>
		<?php
			}
	    ?>
	      

    	</div>
		<script type="text/javascript" src="scripts/jquery.min.js"></script>
		<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
	</body>
	</html>


