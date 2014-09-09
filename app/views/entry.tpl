
        <!-- Blog -->
        <div id="blog">
            <div class="container">                
                <div class="row">
                    <h2>From the <span>Blog</span></h2>
                    <div class="row bloginfo">
                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8 post1">
                            <div>
                                <div class="postdate">
                                    <div class="month"><?=date('F j, Y', strtotime($entry["datetime"]))?></div>
                                </div>
                                <div class="posttext">
                                    <h3><?=htmlentities($entry["title"])?></h3>
                                    <p><?=htmlentities($entry["body"])?></p>

                                    <div class="postbot">
                                        <div class="pull-left">
                                            <p class="author"><i class="fa fa-user"></i> on <?=date('y/m/d h:i:s', strtotime($entry["datetime"]))?></p>
                                        </div>
                                        <div class="shareit pull-right">
                                            <span class='st_fblike_hcount' displayText='Facebook Like'></span>
                                            <span class='st_twitter_hcount' displayText='Tweet'></span>
                                            <span class='st_plusone_hcount' displayText='Google +1'></span>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <hr />
                                <div class="numcomments">
                                    <div class="pull-left"><h4><?php $this->numcomments->render()?> Comments</h4></div>
                                    <div class="pull-right"><i class="fa fa-share-square-o"></i> <a href="#">Share</a></div>
                                    <div class="clearfix"></div>
                                </div>

                                <hr />

								<?php $this->comments->render() ?>
								<?php $this->form->begin(array('class'=>'form-horizontal','role'=>'form')) ?>
                                    <div class="row leaveareply">
                                        <div class="col-md-3 col-lg-3 txt">
                                            Leave a reply
                                        </div>
                                        <div class="col-md-9 col-lg-9">
											<?php $this->form->author->render(array('class'=>'form-control','placeholder'=>'Name')) ?>
											<?php $this->form->body->render(array('class'=>'form-control','placeholder'=>'Comment','rows'=>'4')) ?>
											<?php $this->form->submit->begin(array('class'=>'btn btn-default')) ?>
												Post Reply
											<?php $this->form->submit->end() ?>
                                        </div>
                                    </div>
                                <?php $this->form->end() ?>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-lg-4 col-md-4 moreblog">
                            <h3 class="arch">Recent Posts</h3>
                            <ul>
								<?php foreach($entries as $entry) : ?>
                                <li><?=\Rum::link($entry["title"], 'entry', array('id'=>$entry["entry_id"])) ?></a></li>
								<?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Blog -->
