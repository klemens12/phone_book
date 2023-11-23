<?php self::render('header.part');?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php 
                    if(!empty($messages)):
                        foreach($messages as $message): ?>
                            <div class="alert alert-danger alert-dismissible fade show custom-margin" role="alert">
                                <strong><?=$message?></strong> 
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                    <?php
                        endforeach;
                    endif; 
                
                  self::render('registration_form.part');
                ?>
            </div>
        </div>
    </div>
<?php self::render('footer.part');?>
   