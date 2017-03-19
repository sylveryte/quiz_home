<?php
session_start();
  require_once('../mysqli_connect.php');

   $_POST = array_map("trim", $_POST);

  $quiz_code=$_POST["quiz_code"];
  if($quiz_code)
    $_SESSION["quiz_code_for_check"]=$quiz_code;
  // echo $quiz_code."<br>";
  // echo $_SESSION["quiz_code_for_check"];

  if(isset($_POST["endtest"]))
    $quiz_code=$_SESSION["quiz_code_for_check"];

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
			<p>Quiz HOME</p>
		</header>

		<div id="tablecontainer">
		<div id="tablerow">

		<section id="main" class="sectionbox">

      <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <?php
      //get quetions Added
      $query="SELECT * from bucket WHERE quiz_id=\"$quiz_code\" ORDER BY rand()";
      //echo $query;
      $result=mysqli_query($dbc, $query);
      if(!isset($_POST["endtest"]))
      {
      $noq=mysqli_num_rows($result);
        if($result)
        {
          while ($question_row=mysqli_fetch_array($result)) {
            # code...
            //echo "<br>".$question_row['wrong1'];
            // for ($i=0; $i < count($question_row) ; $i++) {
            //   # code...
            //   echo $i . "  $question_row[$i]<br>";
            // }
            $qid=$question_row["qid"];
            ?>
            <article class="qcontainer">
              <div class="leftfloater">
              </div>
            <div class="inqcontainer">
              <div class="qbox">
                <?php echo $question_row['questions'] ?>
              </div>

              <!-- options bharing -->

            	<?php
              $row_numbers=range(3,6);
              shuffle($row_numbers);
              // print_r($row_numbers);
              for ($i=0; $i < 4; $i++) {
            		# code...
                if($row_numbers[$i]==3){
                  $option_id=$qid.'r';
                }
                if($row_numbers[$i]==4){
                  $option_id=$qid.'w1';
                }
                if($row_numbers[$i]==5){
                  $option_id=$qid.'w2';
                }
                if($row_numbers[$i]==6){
                  $option_id=$qid.'w3';
                }
                  //echo $option_id.'<br>';

            	 ?>

              <div class="optionbox">
                <?php //echo $option_id;?>
                <input  type="radio"
                        name=<?php echo "$qid";?>
                        value=<?php echo $option_id;?>>
                <?php echo $question_row[$row_numbers[$i]];?>
              </div>


              	<?php
             	  }
              	?>


            </div>
        </article>
            <?php
          }
        }?>

        <article class="sixty">
          <table cellspacing='10'>
          <tr><td align='right'>Name</td><td>
          <input type="text" placeholder="eg Makoto Kowata" name="stud_name" />
        </td><tr><td align='right'>Id</td>
          <td><input type="text" placeholder="eg d7a-21" name="stud_id" required="enter name"/></td></tr>
          <tr><td></td><td><input type="submit" name="endtest" value="Finish Test" required="enter id"></td></tr>
        </table>
        </article>
      </form>
        <?php
      }//end of not endtest
      else{
        //if endtest clicked

        if($result)
        {
          $total_question_count=mysqli_num_rows($result);
          $correct_ans_count=0;
          $wrong_ans_count=0;
          $attempted_question_count=$total_question_count;
          while ($question_row=mysqli_fetch_array($result)) {
            # code...
            //echo "<br>".$question_row['wrong1'];
            // for ($i=0; $i < count($question_row) ; $i++) {
            //   # code...
            //   echo $i . "  $question_row[$i]<br>";
            // }
            // echo $quiz_code;
            $qid=$question_row["qid"];
            if($response=$_POST["$qid"])
            {
              if(substr($response,-1)=='r')
                $correct_ans_count++;
                else
                $wrong_ans_count++;
            }else {
              $attempted_question_count--;
            }
          //  echo $_POST ["$qid"]."<br>";
          }//end of while
          $stud_name=$_POST["stud_name"];
          $stud_id=$_POST["stud_id"];

          echo "<article class='sixty'>$stud_name<br>$stud_id</article>";

          // $wrong_ans_count=$total_question_count-$attempted_question_count;
          echo "<article class='sixty'><table cellspacing='10'>";
          echo "<tr><td align='right'>Total question </td><td>|</td><td> $total_question_count</td></tr>";
          echo "<tr><td align='right'>Attempted question </td><td>|</td><td> $attempted_question_count</td></tr>";
          echo "<tr><td align='right'>Wrong Answered </td><td>|</td><td> $wrong_ans_count</td></tr>";
          echo "<tr><td align='right'>Correctly Answered </td><td>|</td><td> $correct_ans_count</td></tr>";
          echo "<tr><td align='right'>Marks </td><td>|</td><td> $correct_ans_count / $total_question_count</td></tr>";
          $per=100*($correct_ans_count/$total_question_count);
          $perc=round($per,2);
          echo "<tr><td align='right'>Percentage </td><td>|</td><td> $perc</td></tr>";
          echo "</table></article>";

          $messageIdent = md5($_POST['stud_id'] . $_POST['stud_name'] . $quiz_code);

                              //and check it against the stored value:

                          $sessionMessageIdent = isset($_SESSION['messageIdentl'])?$_SESSION['messageIdentl']:'';

          if ($messageIdent!=$sessionMessageIdent)

            {
              $_SESSION['messageIdentl'] = $messageIdent;
            //insert results into result code

            $quer="SELECT result_id FROM mera WHERE quiz_id='$quiz_code' limit 1";
            //        echo $quer;
            $resp=mysqli_query($dbc,$quer);
            $rc=mysqli_fetch_object($resp);
            $result_code=$rc->result_id;

            // echo $result_code;


            $query="INSERT INTO results VALUES
            ('$stud_name',
              '$stud_id',
              '$result_code',
              $total_question_count,
              $attempted_question_count,
            $correct_ans_count,
          $wrong_ans_count,
        NULL)";

        // echo $query;
        mysqli_query($dbc,$query);
        }

        }
        ?>

        <article class="sixty">
          <h1>Back to Home</h1>
          <a href="index.html"><p>Click here</p></a>
        </article>

        <?php

      }//end of else endtest
       ?>

		</section>
		</div>
		</div>

		<footer class="headfoot">
			<p>Copyright © 2017 VESP GROUP PROJECT. P-K-S-V </p>
		</footer>

		</div>

	</body>
</html>
<?php mysqli_close($dbc); ?>
