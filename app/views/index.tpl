
        <!-- Blog -->
        <div id="blog">
            <div class="container">                
                <div class="row">
                    <h2>From the <span>Blog</span></h2>
                    <div class="row bloginfo">
                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8 post1">
                            <div>
								<?php $this->entries->render() ?>

								<?php foreach($entries as $entry) : ?>
                                
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
