<?php
    session_start();

    //fFININFSHED AND SAVE PROBABLY SEND EMAIL
    if(isset($_POST['save']))
    {
        //here default web
        $title=$_SESSION["title"];
        $result_code=$_SESSION["result_code"];
        $quiz_code=$_SESSION["quiz_code"];
        unset($_POST);
        unset($_SESSION);
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

              <article>
                <p>Please note down codes.</p>
              </article>

              <article>
      					<?php echo "<table class='comme' cellpadding='8'>
      					<tr><td> $title </td></tr>
      					</table>";
                          ?>
      				</article>

      				<article class="codes">
      					<p>
      						<?php
                  echo "<table cellpadding='8'>
      						<tr><td align='right'> Result Code : </td>
      						<td> $result_code </td></tr>
      						<tr><td align='right'> Quiz Code : </td>
      						<td> $quiz_code</td></tr>
      						</table>";
                       ?>
      					 </p>
      				</article>
              <article>
                <br><a href="index.html"><p>Click here to exit.</p></a>
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


        <?php
    }else{

        require_once('../mysqli_connect.php');

    if (!isset($_POST["addq"])&&isset($_POST["submit"])) {
        $_SESSION["title"]=$_POST["title"];
        $_SESSION["result_code"]=substr(md5(microtime()), rand(0, 26), 6);
        $_SESSION["quiz_code"]=substr(md5(microtime()), rand(0, 26), 6);
                //storing into database

        // $strquery='INSERT INTO
				// mera (result_id,title,date,quiz_id)
				// 		VALUES("'
        //                 . $_SESSION["result_code"]
        //                 . '","'
        //                 . $_POST["title"]
        //                 . '",null,"'
        //                 . $_SESSION["quiz_code"]
        //                 . '")';

                        $lresult_code=$_SESSION["result_code"];
                        $ltitle=$_POST["title"];
                        $lquiz_code=$_SESSION["quiz_code"];

                        $stquery="INSERT INTO mera values
                        ('$lresult_code',
                          '$ltitle',
                          NULL,
                          '$lquiz_code')";

                        // echo $stquery;

      //  echo $strquery;

                /*
                            $queryi="INSERT INTO mera (result_id,title,date,quiz_id,noq) VALUES (?,?,?,?,?)";
                            $stmtt=mysqli_prepare($dbc,$queryi);
                            mysqli_stmt_bind_param($stmtt,"ssssi",$_SESSION["result_code"],$_SESSION["stitle"],null,$_SESSION["quiz_code"],$noq);
                            mysqli_stmt_execute($stmtt);
                */

                mysqli_query($dbc, $stquery)
                                                        or die(mysqli_error($dbc));

        //mysqli_stmt_close($stmtt);
    }


            //setting common vars
            $title=$_SESSION["title"];
            $result_code=$_SESSION["result_code"];
            $quiz_code=$_SESSION["quiz_code"];




                //db is open handle question add
                if (isset($_POST['addq'])) {
                    $data_missing=[];

                    if (empty($_POST['question'])) {
                        $data_missing[]='question';
                    } else {
                        $question=trim($_POST['question']);
                    }

                    if (empty($_POST['right_ans'])) {
                        $data_missing[]='Right ans';
                    } else {
                        $right_ans=trim($_POST['right_ans']);
                    }

                    if (empty($_POST['wrong1'])) {
                        $data_missing[]='wrong1';
                    } else {
                        $wrong1=$_POST['wrong1'];
                    }
                    if (empty($_POST['wrong2'])) {
                        $data_missing[]='wrong2';
                    } else {
                        $wrong2=$_POST['wrong2'];
                    }
                    if (empty($_POST['wrong3'])) {
                        $data_missing[]='wrong3';
                    } else {
                        $wrong3=$_POST['wrong3'];
                    }

                    $messageIdent = md5($_POST['question'] . $_POST['right_ans'] . $_POST['wrong1'] . $_POST['wrong2'] . $_POST['wrong3']);

                                        //and check it against the stored value:

                                    $sessionMessageIdent = isset($_SESSION['messageIdent'])?$_SESSION['messageIdent']:'';

                    if ($messageIdent!=$sessionMessageIdent) {
                        //if its different:
                                //save the session var:
                            $_SESSION['messageIdent'] = $messageIdent;
                                //and...do things

                                        if (empty($data_missing)) {
                                          /*  echo "<p>Question : $question <br>
																						Right ans $right_ans <br />
																						Wrong ans1 $wrong1 <br />
																						Wrong ans2 $wrong2 <br />
																						Wrong ans3 $wrong3 <br />
																						</p></br>";
																						*/

                                            $query="INSERT INTO bucket (quiz_id,questions,right_ans,wrong1,wrong2,wrong3) VALUES (?,?,?,?,?,?)";
                                            $stmt=mysqli_prepare($dbc, $query);

                                            mysqli_stmt_bind_param($stmt, "ssssss", $quiz_code, $question, $right_ans, $wrong1, $wrong2, $wrong3);

                                            mysqli_stmt_execute($stmt);

                                            $affected_rows = mysqli_stmt_affected_rows($stmt);

                                            if ($affected_rows==1) {
                                                //echo 'question added';
                                            } else {
                                                echo 'error occured<br>';
                                                echo mysqli_error();
                                                echo mysqli_errno();
                                            }

                                            mysqli_stmt_close($stmt);
                                        } else {
                                            echo '<p class="redd">you need to enter the following data<br>';
                                            foreach ($data_missing as $missing) {
                                                echo "$missing". " ";
                                            }
                                            echo "</p>";
                                        }
                    } else {
                        //you've sent this already!
                    }
                }

//deleting deleting elements
                if(isset($_POST["delete"]))
                {
                    foreach ($_POST['todelete'] as $key) {
                    # code...
                    //delte from bucket where qid=13 and quiz_id='adsf';
                    $query="DELETE FROM bucket WHERE quiz_id=\"$quiz_code\" AND qid=$key";
                    //echo $query;
                    mysqli_query($dbc, $query);
                  //  echo "<br>".$key;
                  }
                }

    //get quetions Added
    $query="SELECT * from bucket WHERE quiz_id=\"$quiz_code\" ORDER BY qid DESC";
    //echo $query;
    $result=mysqli_query($dbc, $query);
    $noq=mysqli_num_rows($result);
    //echo $noq . "rows";

        //here showing added Questions
        //actually it's down there

  //$fetched_questions=mysqli_fetch_array($result);

//  mysqli_free_result($result);

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf8"/>
		<title>Create Quiz <?php echo $title;  ?></title>
		<link rel="stylesheet" href="defstyle.css"/>
	</head>
	<body>

	<div id="allcontainer">
		<header class="headfoot">
			<p>Quiz <a href="index.html">HOME</a></p>
		</header>

		<div id="tablecontainer">
		<div id="tablerow">

		<section id="main" class="sectionbox">

		<article  id="question_maker">
			<form  method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

			<label for="Question">Quetion : </label>
			<input type="text" id="question" name="question" required="question"></textarea><br>

			<label for="Right ans">Right Ans : </label>
			<input type="text" id="right_ans" name="right_ans" required="right_ans"/><br>

			<label for="wrong1">Wrong Ans1 : </label>
			<input type="text" id="wrong1" name="wrong1" required="wrong1"/><br>
			<label for="wrong2">Wrong Ans2 : </label>
			<input type="text" id="wrong2" name="wrong2" required="wrong2"/><br>
			<label for="wrong3">Wrong Ans3 : </label>
			<input type="text" id="wrong3" name="wrong3" required="wrong3"/><br>

			<input type="submit" value="Add question " name="addq"/><br>
			</form>
		</article>

    <!--questions here to be diplayed added one-->

    <form method="POST"action="<?php echo $_SERVER['PHP_SELF']; ?>">

<article><p>Questions<br>


</p>
  <input type="submit" name="delete" value="Delete Selected Questions">
  <input type="submit" name="save" value="Save Quiz">
</article>
    <?php
      if($result)
      {
        while ($question_row=mysqli_fetch_array($result)) {
          # code...
          //echo "<br>".$question_row['wrong1'];
          ?>
          <article class="qcontainer">
            <div class="leftfloater">
              <input  type="checkbox" name="todelete[]" value=<?php echo "'" . $question_row["qid"] . "'" ?>>
            </div>
          <div class="inqcontainer">
            <div class="qbox">
              <?php echo $question_row['questions'] ?>
            </div>
            <div class="optionbox">
              <?php echo $question_row['right_ans'] ?>
            </div>
            <div class="optionbox">
              <?php echo $question_row['wrong1'] ?>
            </div>
            <div class="optionbox">
              <?php echo $question_row['wrong2'] ?>
            </div>
            <div class="optionbox">
              <?php echo $question_row['wrong3'] ?>
            </div>
          </div>
      </article>
          <?php
        }
      }
     ?>

   </form>

  </section>

		<aside class="sectionbox">
				<article class="codes">
					<?php echo "<table class='comme' cellpadding='8'>
					<tr><td> $title </td></tr>
					</table>";
                    ?>
				</article>

				<article class="codes">
					<p>
						<?php
            echo "<table cellpadding='8'>
						<tr><td align='right'> Result Code : </td>
						<td> $result_code </td></tr>
						<tr><td align='right'> Quiz Code : </td>
						<td> $quiz_code</td></tr>
						<tr><td align='right'> Questions Added : </td>
						<td> $noq </td></tr></table>";
                 ?>
					 </p>
				</article>


            <?php
            if(isset($_POST["delete"]))
            {

              unset($_POST["delete"]);
                echo '<article class="codes"><p>';
              echo count($_POST['todelete']) . " Questions deleted <br>";
              echo '</p></article>';
            }
            ?>

		</aside>

		</div>
		</div>

		<footer class="headfoot">
			<p>Copyright © 2017 VESP GROUP PROJECT. P-K-S-V </p>
		</footer>

		</div>
<?php mysqli_close($dbc); }?>
	</body>
</html>
