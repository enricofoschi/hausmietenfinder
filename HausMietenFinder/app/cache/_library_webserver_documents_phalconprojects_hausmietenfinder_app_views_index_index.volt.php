<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HausMietenFinder</title>

    <!-- Bootstrap -->
    <?php echo $static_assets['headerCSS']; ?>
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script>
        (function(){
            var ef = function(){};
            window.console = window.console || {log:ef,warn:ef,error:ef,dir:ef};
        }());
    </script>
    <?php echo $static_assets['headerJSLTIE9']; ?>
    <![endif]-->
</head>
<body>
    
    <div class="main-container-outer">
        <div class="main-container-inner" id="main-content-container">
            
	<div class="loader">
        <i class="fa fa-gear fa-spin"></i>
    </div>

        </div>
    </div>

    <div id="modals-container"></div>

    <script type="text/javascript">
        var MainProperties = <?php echo $main_properties_json; ?>;
    </script>

    <?php echo $static_assets['footerJS']; ?>

</body>
</html>