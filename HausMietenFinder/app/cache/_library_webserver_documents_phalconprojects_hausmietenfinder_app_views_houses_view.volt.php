<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <?php echo $this->assets->outputCss('headerCSS'); ?>
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script>
        (function(){
            var ef = function(){};
            window.console = window.console || {log:ef,warn:ef,error:ef,dir:ef};
        }());
    </script>
    <?php echo $this->assets->outputJs('headerJSLTIE9'); ?>
    <![endif]-->
</head>
<body class="view_<?php echo $properties; ?>">
    

<div class="container">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Picture</th>
                <th>Transit Time</th>
                <th>Address precise</th>
                <th>Price</th>
                <th>Living Space</th>
                <th>Private Offer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($houses as $house) { ?>
                <tr class="<?php echo ($house->getStatus() == 1 ? 'success' : (($house->getStatus() == 2 ? 'danger' : ''))); ?>" data-id="<?php echo $house->getId(); ?>">
                    <td><a href="<?php echo $house->getUrl(); ?>" target="_blank"><img width="100" src="<?php echo $house->getPictureUrl(); ?>" /></a></td>
                    <td><?php echo $house->getTransitTime(); ?></td>
                    <td><?php echo $house->isAddressPrecise(); ?></td>
                    <td><?php echo $house->getWarmMiete(); ?></td>
                    <td><?php echo $house->getLivingSpace(); ?></td>
                    <td><?php echo $house->isPrivateOffer(); ?></td>
                    <td>
                        <a class="btn btn-default btn-block" target="_blank" href="<?php echo $house->getUrl(); ?>">View</a>
                        <a class="btn btn-default btn-block action update-status" data-status="0">Reset</a>
                        <a class="btn btn-success btn-block action update-status" data-status="1">Shortlist</a>
                        <a class="btn btn-danger btn-block action update-status" data-status="2">Blacklist</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


    <script type="text/javascript">
        var MainProperties = <?php echo $properties; ?>;
    </script>
    <?php echo $this->assets->outputJs('footerJS'); ?>
</body>
</html>