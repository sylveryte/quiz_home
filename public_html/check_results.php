<?php
  require_once('../mysqli_connect.php');
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf8"/>
		<title>Home Quiz</title>
		<link rel="stylesheet" href="defstyle.css"/>
	</head>
	<body>

	<div id="allcontainer">
		<header class="headfoot">
			<h1>Quiz Results</h1>
      <p>
      <?php
      $_POST = array_map("trim", $_POST);
      if(isset($_POST['check_results']))
      {
				$result_code=$_POST["result_code"];

				$quer="SELECT title FROM mera WHERE result_id='$result_code' limit 1";
				      //  echo $quer;
				$resp=mysqli_query($dbc,$quer);
				$rc=mysqli_fetch_object($resp);
				$title=$rc->title;
				echo $title;

				// echo $result_code;

				$query="SELECT * FROM results WHERE result_code='$result_code'";

				$response=mysqli_query($dbc,$query);

      ?>
    </p>
		</header>

		<div id="tablecontainer">
		<div id="tablerow">

		<section id="main" class="sectionbox">

<article class="sixty">
			<table class="tg">
			  <tr>
			    <th class="tg-031e">Name</th>
			    <th class="tg-031e">ID</th>
			    <!-- <th class="tg-031e">Attempted</th> -->
			    <th class="tg-031e">Right</th>
			    <th class="tg-031e">Percentage</th>
			    <th class="tg-031e">Date-Time</th>
			  </tr>

				<?php

				if($response)
				{
					$i=0;
					while ($row=mysqli_fetch_array($response)) {
						# code...
				//		print_r($row);
					//	echo "<br>";
					if($i%2==0)
					{
						$cs="tg-z2zr";
					}else {
						$cs="tg-031e";
					}
					$i++;
				echo "<tr>";
					echo "<td class='$cs'>".$row['stud_name']."</td>";
					echo "<td class='$cs'>".$row['stud_id']."</td>";
					// echo "<td class='$cs'>".$row['attempted']."</td>";
					echo "<td class='$cs'>".$row['correct']."/".$row['total_question']."</td>";
					$per=100*($row['correct']/$row['total_question']);
					$perc=round($per,2);
					echo "<td class='$cs'>".$perc."</td>";
					echo "<td class='$cs'>".$row['time']."</td>";
					echo	"</tr>";






				}//end loop
			}//end if

				 ?>

</table>
</article>
<article class="sixty">
	<h1>Back to Home</h1>
	<a href="index.html"><p>Click here</p></a>
</article>
		</section>


		</div>
		</div>

		<footer class="headfoot">
			<p>Copyright © 2017 VESP GROUP PROJECT. P-K-S-V </p>
		</footer>

		</div>

	</body>
</html>
<?php } ?>
