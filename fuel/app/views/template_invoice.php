<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <?php echo Asset::css('bootstrap.css'); ?>
        <?php echo Asset::css('template.css'); ?>
        <?php echo Asset::css('menu.css'); ?>
        <?php echo Asset::js('jquery.js'); ?>
        <?php echo Asset::js('bootstrap.js'); ?>
        <?php echo Asset::js('menu.js'); ?>
        <?php echo Asset::js('template.js'); ?>
    </head>

    <body onload="ShowCurrentTime()">

        <!-- Header  -->
        <div class="top" >
            <div class=" logo pull-right" ><?php echo Asset::img('home.jpg', array('style' => 'height:60px')); ?></div>
            <center>
                <div id="title" class="span10 offset1">
                    <h3> INVOICE MANAGEMENT SYSTEM ADMIN PANEL</h3>
                </div>
            </center>
            <div class="login_info pull-right">
                <?php
                if ($user = Session::get('user')) {
                    echo "Logged in as " . $user->name;
                    ?> <a class="btn btn-danger" href="/login/lougout">Logout</a>
                    <?php
                } else {
                    echo "not logged ";
                }
                ?> 
            </div>
        </div>
        <div class="container">
            <div class="span12">
                <h1><?php echo $title; ?></h1>
                <hr />
                <?php if (Session::get_flash('success')): ?>
                    <div class="alert alert-success">
                        <strong>Success</strong>
                        <p>
                            <?php echo implode('</p><p>', e((array) Session::get_flash('success'))); ?>
                        </p>
                    </div>
                <?php endif; ?>
                <?php if (Session::get_flash('error')): ?>
                    <div class="alert alert-error">
                        <strong>Error</strong>
                        <p>
                            <?php echo implode('</p><p>', e((array) Session::get_flash('error'))); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div id="time"></div>
        <!-- Side menu bar  -->

        <div class="left-sidebar" >
            <div  style="margin-top:100px;">
                <ul class="menu">
                    <li class="item1"><a href="#">Invoice  </a>
                        <ul>
                            <li class="subitem1"><?php echo Html::anchor('invoice/single', 'Single'); ?></li>
                            <li class="subitem2"><?php echo Html::anchor('invoice/monthly', 'Monthly'); ?></li>

                        </ul>
                    </li>
                    <li class="item2"><a href="#">Panels</a>
                        <ul>
                            <li class="subitem1"><a href="#">Pricing </a></li>
                            <li class="subitem2"><a href="#">Add</a></li>

                        </ul>
                    </li>
                    <li class="item3"><?php echo Html::anchor('archive', 'Archive'); ?>
                    </li>
                    <li class="item4"><a href="#">Users</a>
                        <ul>
                            <li class="subitem1"><a href="#">List All Users</a></li>
                            <li class="subitem2"><a href="#">Create</a></li>

                        </ul>
                    </li>
                    <li class="item5"><a href="#">System Log</a>

                    </li>
                </ul>
            </div>
        </div>
        <div class="main well" >
            <div class="row">
                <div class="span3 pull-left">
                    <h2>Invoicing</h2>
                </div>
                <div class="span3 ">
                    <span class="pull-left"><p><?php echo Html::anchor('../monthly', 'Single', array("class" => "btn btn-large btn-success")); ?></p></span>
                    <span class="pull-right"><p><?php echo Html::anchor('../monthly', 'Monthly', array("class" => "btn btn-large btn-success")); ?></p></span>
                </div>
                <div class="span4 pull-right">
                    Date: 
                    <br>Invoice No: 
                    <br>Billing Period: 
                    <br>PAN:
                    <br>TIN:
                    <br>FP NO: 

                </div>
            </div> 
            <?php echo $content; ?> 
        </div> 
        <div class="right-sidebar" >
            <div class="instructions"><h5>Instructions: </h5><hr /></div>

            <div class="errors"><h5>Errors* :</h5><hr /></div>
        </div>

        <div class="footer" >
            <div class="" >
                <div class="span10 offset1 " style="padding-top: 10px">
                </div>
            </div>
            <footer>

            </footer>
        </div>
    </body>
</html>