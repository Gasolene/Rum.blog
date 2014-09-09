
        <!-- Blog -->
        <div id="blog">
            <div class="container">                
                <div class="row">
                    <h2>From the <span>Blog</span></h2>
                    <div class="row bloginfo">
                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8 post1">
                            <div>
								<?php foreach($entries as $entry) : ?>
                                <div class="postdate pull-left">
                                    <div class="month"><?=date('F j, Y', strtotime($entry["datetime"]))?></div>
                                </div>
                                <div class="posttext pull-right">
                                    <h3><?=htmlentities($entry["title"])?></h3>
                                    <p>
										<?=nl2br(htmlentities($entry["body"]))?>
									</p>
									<p> - See more at: <?=\Rum::link(Rum::url('entry', array('id'=>$entry["entry_id"])), 'entry', array('id'=>$entry["entry_id"])) ?></p>
                                    <div class="postbot">
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
							<?php endforeach; ?>
							<?php $this->form->begin(array('class'=>'form-horizontal','role'=>'form')) ?>
								<div class="row leaveareply">
									<div class="col-md-3 col-lg-3 txt">
										New blog entry
									</div>
									<div class="col-md-9 col-lg-9">
										<?php $this->form->title->render(array('class'=>'form-control','placeholder'=>'Title')) ?>
										<?php $this->form->body->render(array('class'=>'form-control','placeholder'=>'Body','rows'=>'4')) ?>
										<?php $this->form->submit->begin(array('class'=>'btn btn-default')) ?>
											Post Blog Entry
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
                                <li><?=\Rum::link($entry["title"], 'entry', array('id'=>$entry["entry_id"])) ?></li>
								<?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Blog -->
