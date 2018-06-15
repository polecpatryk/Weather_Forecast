<?php
	
	include('assets/php/forecast.class.php');
	include('assets/php/database.class.php');
	include('assets/php/explodedate.function.php');
	
	$weather = new Forecast;
	$base = new Database;
			
	if(($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['search']))
	{
		$error = "";
		
		$city = preg_replace('/\d/', '', htmlspecialchars(trim($_POST['city'])));
		
		if(!empty($city))
		{
			$XML = $weather->Search($city);
			
			if($XML > 0) $error = 'Nie znaleziono miasta';
			
			else if(!empty($XML->location->name)) $base->Insert($XML->location->name, ExplodeDate($XML->forecast->time[0]['from']), ExplodeDate($XML->forecast->time[count($XML->forecast->time)-1]['to']));
		}
		else
			$error = 'Wpisz poprawną nazwę miasta';
	}
?>

<html>
    <head>
        <meta charset="UTF-8">
		<link rel="stylesheet" href="assets/css/global.css" type="text/css">
        <title>Prognoza pogody</title>
    </head>
    <body>
		<div class="form-search">
			<form action="index.php" method="POST">
				<input type="text" name="city" placeholder="Nazwa miasta" required>
				<input type="submit" name="search" value="Szukaj">
				<?php 
				
					if(!empty($error))
					{
						echo '<div>'.$error.'</div>';
					}
				?>
			</form>
		</div>
		
		<div class="content">
			<?php 
			
				if(empty($error) && isset($city))
				{
					echo '<div class="current-weather">
							<h1>Pięciodniowa prognoza pogody dla '.$XML->location->name.', '.$XML->location->country.'</h1>
							<div style="overflow:auto;">
							<table class="current-weather-table" width="15000px">
								<tr><th>Zakres</th>';
									foreach($XML->forecast->time as $value)
									{
										echo '<th>od '.ExplodeDate($value['from']).' do '.ExplodeDate($value['to']).'</th>';
									}
					echo       '</tr>
								<tr><th>Temperatura</th>';
									foreach($XML->forecast->time as $value)
									{
										echo '<td>'.round($value->temperature['value'], 1).' '.$value->temperature['unit'].'</td>';
									}
					echo       '</tr>
								<tr><th>Ciśnienie</th>';
									foreach($XML->forecast->time as $value)
									{
										echo '<td>'.$value->pressure['value'].' '.$value->pressure['unit'].'</td>';
									}
					echo       '</tr>
								<tr><th>Wilgotność</th>';
									foreach($XML->forecast->time as $value)
									{
										echo '<td>'.$value->humidity['value'].$value->humidity['unit'].'</td>';
									}
					echo       '</tr>
								<tr><th>Prędkość wiatru</th>';
									foreach($XML->forecast->time as $value)
									{
										echo '<td>'.$value->windSpeed['mps'].'m/s</td>';
									}
					echo       '</tr>
								<tr><th>Kierunek wiatru</th>';
									foreach($XML->forecast->time as $value)
									{
										echo '<td>'.$value->windDirection['name'].'</td>';
									}
					echo       '</tr>
								<tr><th>Zachmurzenie</th>';
									foreach($XML->forecast->time as $value)
									{
										echo '<td>'.$value->clouds['all'].$value->clouds['unit'].'</td>';
									}
					echo	   '</tr>		
							</table>
							</div>
						  </div>';
				}
				else
				{
					$result = $base->Select();
					
					echo '<div class="last-searched">
							<h1>Ostatnio wyszukiwane prognozy</h1>
							<table class="last-searched-table">
								<tr>
									<th>Miasto</th>	<th>Zakres dat</th>
								</tr>';
								
								foreach($result as $value)
								{
									echo '<tr><td>'.$value['city'].'</td><td>od '.$value['from_date'].' do '.$value['to_date'].'</td></tr>';
								}					

					echo	'</table>
					      </div>';
				}
			?>
		</div>
</html>