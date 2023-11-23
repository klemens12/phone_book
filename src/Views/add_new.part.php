<script id="addNewTemplate" type="text/x-jquery-tmpl">
   <form action="/createItem" method="post" enctype="multipart/form-data" id="create-new-item" class="create-item-form">
        <div class="card my-4 book-item">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-center card-content">
                    <div class="card-img-left">
                        <label for="uploadImage" class="form-label">${selectFile}</label>
                        <input class="form-control form-control-sm" accept="image/png, image/jpeg" id="uploadImage" name="user_picture" type="file">
                    </div>
                        <div class="content-left d-flex flex-column">
                            <div class="card-header">
                               <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <input type="text" placeholder="${firstName}" name="first_name" id="firstName" class="form-control" required/>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" placeholder="${lastName}" name="last_name" id="lastName" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6 phone-p">
                                        <input type="text" placeholder="${phone}" name="phone" id="phone" class="form-control" required/>
                                    </div>
                                    <div class="col-sm-6 email-p">
                                        <input type="email" placeholder="${email}" name="email" id="email" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 submit-p">
                                        <input type="submit" value="${storeItem}" title="${storeItem}" class="btn btn-primary js-event-save">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="remove-item">
                            <i class="fa-solid fa-trash-can remove-record js-event-remove-new-record"></i>
                        </div>
                    </div>
            </div>
        </div>
        <input type="hidden" name="csrf" value="${csrf}"/>
    </form>
</script>