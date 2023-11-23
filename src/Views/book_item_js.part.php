<script id="itemTemplate" type="text/x-jquery-tmpl">
        <div class="card my-4 book-item" data-item-id="${itemId}">
            <div class="card-body">
                <div class="d-flex align-items-center card-content">
                    <div class="card-img-left">
                        <img src="${picture}" class="img-fluid" alt="">
                    </div>
                    <div class="content-left d-flex flex-column">
                        <h5 class="card-header">
                            <strong>${firstName}</strong>
                            <strong>${lastName}</strong>
                        </h5>
                        <div class="card-text middle-text">
                            <p class="phone-p"><a class="phone-link" href="tel: ${phone}">${phone}</a></p>
                            <p class="email-p"><a class="email-link" href="mailto: ${email}">${email}</a></p>
                        </div>   
                    </div>
                    <div class="remove-item">
                        <i class="fa-solid fa-trash-can remove-record js-event-remove"></i>
                    </div>
                </div>
            </div>
        </div>
</script>