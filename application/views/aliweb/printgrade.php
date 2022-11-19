<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <style type="text/css" media="screen">
        .PrintButton{
            display:block;
        }

        .table-condensed>thead>tr>th, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>tbody>tr>td, .table-condensed>tfoot>tr>td{
            padding: 5px;
            font-size: 11px;
        }
    </style>
    <style type="text/css" media="print">
        .PrintButton{
            display:none;
        }
    </style>
</head>
<body>

<div class="container">
    <div style="float:right;"><input type="button" onclick="window.print();" id="printButton" class="PrintButton" value="Print Page" /></div>
    <h5><?php echo $gpname;?> / <?php echo $classname;?></h5>
    <table class="table table-condensed table-bordered">
        <thead>
        <?php echo $tableheader;?>
        </thead>
        <tbody>
        <?php echo $tablebody;?>
        </tbody>
    </table>
</div>

</body>
</html>