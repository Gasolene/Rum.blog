
        <!-- Blog -->
        <div id="blog">
            <div class="container">                
                <div class="row">
                    <h2>From the <span>Blog</span></h2>
                    <div class="row bloginfo">
                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8 post1">
                            <img src="images/blogp_img1.jpg" alt="" />
                            <div>
                                <div class="postdate pull-left">
                                    <div class="month">August</div>
                                    <div class="day">10</div>
                                </div>
                                <div class="posttext pull-right">
                                    <h3>When Journalism Becomes a Game</h3>
                                    <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>

                                    <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                                    <div class="postbot">
                                        <div class="pull-left">
                                            <p class="author"><i class="fa fa-user"></i> Matthew Potter on 23/05/2013</p>
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
                                    <div class="pull-left"><h4>10 Comments</h4></div>
                                    <div class="pull-right"><i class="fa fa-share-square-o"></i> <a href="#">Share</a></div>
                                    <div class="clearfix"></div>
                                </div>

                                <hr />

                                <div class="comment">
                                    <div class="avatar pull-left">
                                        <img src="images/avatar.jpg" alt="" />
                                    </div>
                                    <div class="avatarright pull-right">

                                        <div class="heading">
                                            <div class="name pull-left">Alison Burgers</div>
                                            <div class="date pull-right">Feb 12, 2013</div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="text">
                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                        </div>
                                        <div class="reply">
                                            <i class="fa fa-reply"></i> <a href="#">Reply</a>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <hr />
								<?php $this->form->begin(array('class'=>'form-horizontal','role'=>'form')) ?>
                                    <div class="row leaveareply">
                                        <div class="col-md-3 col-lg-3 txt">
                                            Leave a reply
                                        </div>
                                        <div class="col-md-9 col-lg-9">
											<?php $this->form->author->render(array('class'=>'form-control','placeholder'=>'Name')) ?>
											<?php $this->form->body->render(array('class'=>'form-control','placeholder'=>'Comment','rows'=>'4')) ?>
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
                                <li><?=\Rum::link($entry["title"], 'entry', array('id'=>$entry["entry_id"])) ?></a></li>
								<?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Blog -->
