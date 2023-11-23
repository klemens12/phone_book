<div class="phone-book js-event-put-template-here">
   <?php 
        if(empty($userBook)):?>
            <div class="alert alert-secondary text-center custom-margin js-event-hide-allert" role="alert">
                <strong><?=$noItems?></strong> 
            </div>
   <?php            
        endif; 
     foreach ($userBook as $bookRecord): ?>
        <div class="card my-4 book-item" data-item-id="<?=$bookRecord->id?>">
            <div class="card-body">
                <div class="d-flex align-items-center card-content">
                    <div class="card-img-left">
                        <img src="<?php echo (empty($bookRecord->user_picture)) ? '/img/user_default_picture.png' : $bookRecord->user_picture ?>" class="img-fluid" alt="">
                    </div>
                    <div class="content-left d-flex flex-column">
                        <h5 class="card-header">
                            <strong><?= $bookRecord->user_fname ?></strong>
                            <strong><?= $bookRecord->user_lname ?></strong>
                        </h5>
                        <div class="card-text middle-text">
                            <p class="phone-p"><a class="phone-link" href="tel: <?= $bookRecord->user_phone ?>"><?= $bookRecord->user_phone ?></a></p>
                            <p class="email-p"><a class="email-link" href="mailto: <?= $bookRecord->user_email ?>"><?= $bookRecord->user_email ?></a></p>
                        </div>   
                    </div>
                    <div class="remove-item">
                        <i class="fa-solid fa-trash-can remove-record js-event-remove"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php self::render('add_new.part');?>
    <?php self::render('book_item_js.part');?>
</div>
