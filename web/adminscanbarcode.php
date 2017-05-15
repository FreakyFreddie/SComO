<?php
//this page is created using the Lumino bootstrap dashboard template
//The functionality is limited to the basics, but can be expanded if needed
//template can be found here: http://medialoot.com/item/lumino-admin-bootstrap-template/

//set page var in order to adapt navbar and functions
$GLOBALS['page'] = "adminpanel";
$GLOBALS['adminpage'] = "adminbestellingen";

//include header
require '../templates/header.php';

//redirect if user is not logged in
if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
{
    header("location: index.php");
}

?>

<link href="css/Lumino/datepicker3.css" rel="stylesheet">
<link href="css/Lumino/bootstrap-table.css" rel="stylesheet">
<link href="css/Lumino/styles.css" rel="stylesheet">

<!--Icons-->
<script src="js/Lumino/lumino.glyphs.js"></script>

<!--[if lt IE 9]>
<script src="js/Lumino/html5shiv.min.js"></script>
<script src="js/Lumino/respond.min.js"></script>
<![endif]-->

<script src="js/adminScanBarcode.js"></script>

</head>

<body>
<?php
//include navbar
require $GLOBALS['settings']->Folders['root'].'/templates/navbar.php';
?>

<noscript>
    <div class="alert alert-warning alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
    </div>
</noscript>

<?php
//include admin dashboard navbar
require $GLOBALS['settings']->Folders['root'].'/templates/adminnavbar.php';
?>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default" id="finalorderlist">
                <div class="panel-heading">Barcode scannen</div>
                <div class="panel-body">
                    <form method="POST" action="adminscanbarcode.php">
                        <div class="form-group">
                            <label class="sr-only" for="barcode">Barcode</label>
                            <input class="form-control" type="text" id="barcode" name="barcode"  autofocus="autofocus" />
                        </div>
                        <input type="submit" class="btn btn-primary" value="Afgehaald" />
                    </form>
                </div>
            </div>
        </div>
    </div><!--/.row-->
    <?php
        if(isset($_POST['barcode']) && !empty($_POST['barcode']))
        {
            $dal = new DAL();

            $ordernumber = validateInput($_POST['barcode']);
            $ordernumber = (int) mysqli_real_escape_string($dal->getConn(), $ordernumber);

            //update status to delivered
            //create array of parameters
            //first item = parameter types
            //i = integer
            //d = double
            //b = blob
            //s = string
            $parameters[0] = "i";
            $parameters[1] = $ordernumber;

            //prepare statement
            //update status of approved orders to "ordered" = 3
            $dal->setStatement("UPDATE bestelling
				SET bestelling.status = '5'
				WHERE bestelling.status = '4' AND bestelling.bestelnummer=?");
            $dal->writeDB($parameters);
            unset($parameters);

            echo '<div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Barcode scannen</div>
                            <div class="panel-body">
                                <div class="row">
                                    <table class="table table-hover">
                                        <tbody>
                                        <tr>
                                            <th>
                                                bestelnummer
                                            </th>
                                            <td id="bestelnummer" class="text-right">';
                                                echo $ordernumber;
                                        echo '</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Rnummer
                                            </th>
                                            <td id="rnummer" class="text-right">';
                                                //create array of parameters
                                                //first item = parameter types
                                                //i = integer
                                                //d = double
                                                //b = blob
                                                //s = string
                                                $parameters[0] = "i";
                                                $parameters[1] = $ordernumber;

                                                //prepare statement
                                                $dal->setStatement("SELECT bestelling.rnummer
                                                FROM bestelling
                                                WHERE bestelling.bestelnummer=?");

                                                $records = $dal->queryDB($parameters);
                                                unset($parameters);

                                                echo $records[0]->rnummer;
                                        echo '</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Project
                                            </th>
                                            <td id="project" class="text-right">';
                                                //create array of parameters
                                                //first item = parameter types
                                                //i = integer
                                                //d = double
                                                //b = blob
                                                //s = string
                                                $parameters[0] = "i";
                                                $parameters[1] = $ordernumber;

                                                //prepare statement
                                                $dal->setStatement("SELECT project.titel
                                                                    FROM project
                                                                    INNER JOIN bestelling
                                                                    ON bestelling.idproject=project.idproject
                                                                    WHERE bestelling.bestelnummer=?");

                                                $records = $dal->queryDB($parameters);
                                                unset($parameters);

                                                if(empty($records[0]->titel))
                                                {
                                                    $records[0] = new stdClass();
                                                    $records[0]->titel = "N/A";
                                                }

                                                echo $records[0]->titel;

                                                $dal->closeConn();
                                        echo '</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-hover">
                                        <tbody>
                                        
                                        </tbody>
                                    </table>
							    </div>
							    <div class="row">
							        <table id="displayorderproducts" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
                                        <thead>
                                        <tr>
                                            <th data-field="productafbeelding" data-sortable="true">afbeelding</th>
                                            <th data-field="idproduct" data-sortable="true">id</th>
                                            <th data-field="productnaam" data-sortable="true">naam</th>
                                            <th data-field="leverancier" data-sortable="true">leverancier</th>
                                            <th data-field="productverkoper"  data-sortable="true">verkoper</th>
                                            <th data-field="productdatasheet" data-sortable="true">datasheet</th>
                                            <th data-field="prijs" data-sortable="true">prijs</th>
                                            <th data-field="aantal" data-sortable="true">aantal</th>
                                        </tr>
                                        </thead>
                                    </table>
							    </div>
                            </div>
                        </div>
                    </div>
                </div><!--/.row-->';
        }
    ?>
</div><!--/.main-->


<script src="js/Lumino/bootstrap-datepicker.js"></script>
<script src="js/Lumino/bootstrap-table.js"></script>
<script src="js/adminOrderArrived.js"></script>
<script>
    !function ($) {
        $(document).on("click","ul.nav li.parent > a > span.icon", function(){
            $(this).find('em:first').toggleClass("glyphicon-minus");
        });
        $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
    }(window.jQuery);

    $(window).on('resize', function () {
        if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
    })
    $(window).on('resize', function () {
        if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
    })
</script>
<!-- footer -->
<?php require $GLOBALS['settings']->Folders['root'].'/templates/footer.php'; ?>

