<?php self::render('header.part');?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="d-flex justify-content-end">
                    <?php if($is_auth === false):?>
                        <div>
                            <a class="btn btn-outline-primary" href="/registration" role="button"><?= $translates['register_btn'] ?></a>
                        </div>
                    <?php endif; ?>
                    <?php if($is_auth === true):?>
                        <div class="d-flex w-100 justify-content-between">
                            <button class="btn btn-outline-secondary js-event-show-form"><?= $translates['add_new_btn'] ?></button>
                            <a class="btn btn-outline-info" href="/logout" role="button"><?= $translates['logout_btn'] ?></a>
                        </div>
                    <?php endif;?>
                </div>
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
                
                    $is_auth === false ? self::render('login_form.part') : self::render('book.part');
                ?>
            </div>
        </div>
    </div>
<?php self::render('footer.part');?>
   