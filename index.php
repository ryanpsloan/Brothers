<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Upload</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/css.css">
</head>
<body>
<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Home</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>

                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"></a></li>

                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>
<main>

    <div class="container-fluid">
        <div class="row center">
            <p>Upload .csv only - Did you save the upload to a .csv format before attempting the upload? </p>
            <form action="processor.php" method="POST" enctype="multipart/form-data">
                <table id="fileUpload" class="border">

                    <tr><td><label for="file">Brothers Apparel File Creator</label></td></tr>
                    <tr><td><input type="file" id="file" name="file"></td></tr>
                    <tr><td><hr/></td></tr>
                    <tr><td><input type="submit" value="Process File" id="submit" name="submit"></td></tr>
                </table>
            </form>
        </div>
        <div class="row center">
            <div><?php if(isset($_SESSION['output'])){echo $_SESSION['output']."<br>"; $_SESSION['output'] = "";}
                if(isset($_SESSION['fileName'])){ echo "<a href='download.php'>Download</a> | ";
                    echo "<a href='clear.php'>Clear Files</a><br>"; }?>
            </div>
            <div><?php if(isset($_SESSION['exception'])){echo $_SESSION['exception']. "<br>"; $_SESSION['exception'] = "";}
                if(isset($_SESSION['exceptionFileName'])){echo "<a href='downloadExceptions.php'>Download Exceptions</a>";}?>

         </div>
        </div>
    </div>
</main>
</body>
</html>