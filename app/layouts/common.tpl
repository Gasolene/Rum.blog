<!DOCTYPE html>
<html lang="<?=Rum::app()->lang?>" >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="<?=\Rum::config()->themesURI?>/theme1/ico/favicon.png">

        <title>Blog</title>

        <!-- Bootstrap core CSS -->
        <link href="<?=\Rum::config()->themesURI?>/theme1/css/bootstrap.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?=\Rum::config()->themesURI?>/theme1/css/style.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->

        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?=\Rum::config()->themesURI?>/theme1/css/font-awesome.min.css">



    </head>
    <body data-spy="scroll" data-target=".navbar" data-offset="150" class="blog">

        <nav class="navbar navbar-default blog" role="navigation">
            <div class="container">
                <div class="row">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Blog <span>Demo</span></a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <ul class="nav navbar-nav">
                            <!--
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li><a href="#">Separated link</a></li>
                                    <li><a href="#">One more separated link</a></li>
                                </ul>
                            </li> 
                            -->
                        </ul>

                        <!--
        
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Link</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li><a href="#">Separated link</a></li>
                </ul>
            </li>
        </ul>
                        -->
                    </div><!-- /.navbar-collapse -->
                </div>
            </div>
        </nav>


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Ask for a <span>Quote</span></h4>
                    </div>
                    <form role="form">

                        <div class="modal-body">
                            <p>A big thank you for showing your interest, we usually reply to all emails within 24 hours.
                                Please wait a little more time if you don’t hear back back from us in that time frame.</p>
                            <input type="text" class="form-control" id="exampleInputNameQ" placeholder="Name"> 

                            <input type="text" class="form-control" id="exampleInputEmailQ" placeholder="Email" />
                            <input type="text" class="form-control" id="exampleInputCompanyQ" placeholder="Company" />
                            <textarea name="desc" id="desc" class="form-control" rows="3" placeholder="Project Description"></textarea>
                            <div class="attf">
                                <p>Attach files less than 25mb</p>
                                <button type="button" class="btn btn-transparent attachfile" id="attf">Attach file</button>
                                <div class="clearfix"></div>
                            </div>
                            <input type="text" class="form-control" id="exampleInputDeadlineQ" placeholder="Expected Project Deadline - DDMMYY" />
                            <input type="text" class="form-control" id="exampleInputSkypeQ" placeholder="Skype Name" />
                            <button type="submit" class="btn btn-transparent">Submit</button>

                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

		<?php $this->content()?>

        <div id="footer">
            <div class="container">                
                <div class="row botline">
                    Copyrights 2013 yourcompanyname.com
                </div>
            </div>
        </div>



        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?=\Rum::config()->themesURI?>/theme1/js/jquery-2.0.3.min.js"></script>
        <script src="<?=\Rum::config()->themesURI?>/theme1/js/bootstrap.min.js"></script>

        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "eda0e1f9-f28a-45d6-8b94-a79358066c40", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

        <!-- Template custom JS  -->
        <script type="text/javascript" src="<?=\Rum::config()->themesURI?>/theme1/js/template-blog.js"></script>

        <!-- Retina  -->
        <script src="<?=\Rum::config()->themesURI?>/theme1/js/retina.js"></script>


    </body>
</html>