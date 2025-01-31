<!-- Profile -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profile" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="profilePreview" class="active" data-tab="preview" name="profileViewForm"
                enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="RegistrationModalLongTitleYou">Your Profile</h5>
                    <h5 class="modal-title" id="RegistrationModalLongTitleOther">Member Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row text-left">
                    <div class="col-6 col-md-4">
                        <div class="user-info-col row full-name w-mv-49 d-mv-inline-block m-mv-0">
                            <div class="col">
                                <h6>Name:</h6>
                                <p class="loader"></p>
                                <p class="details ml-2"></p>
                            </div>
                        </div>
                        <div class="user-info-col row gender w-mv-49 d-mv-inline-block m-mv-0">
                            <div class="col">
                                <h6>Gender:</h6>
                                <p class="loader"></p>
                                <p class="details ml-2"></p>
                            </div>
                        </div>

                        <div class="user-info-col row email m-mv-0">
                            <div class="col">
                                <h6>Email:</h6>
                                <p class="loader"></p>
                                <p class="details ml-2">
                                    <span class="text"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="user-info-col row rank w-mv-49 d-mv-inline-block m-mv-0">
                            <div class="col">
                                <h6>Rank:</h6>
                                <p class="loader"></p>
                                <p class="details ml-2">
                                    <span>#</span><span class="text"></span>
                                </p>
                            </div>
                        </div>
                        <div class="user-info-col row score w-mv-49 d-mv-inline-block m-mv-0">
                            <div class="col">
                                <h6>Best score:</h6>
                                <p class="loader"></p>
                                <p class="details ml-2">
                                    <span class="text"></span>
                                </p>
                            </div>
                        </div>
                        <div class="user-info-col row registred m-mv-0">
                            <div class="col">
                                <h6>Registered at:</h6>
                                <p class="loader"></p>
                                <p class="details ml-2">
                                    <span class="text"></span>
                                </p>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-5">
                        <div class="row img d-block">
                            <div class="col text-center text-md-end">
                                <img src="<?= PathHelper::image('avatar-male.jpg') ?>" alt="Profile">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancel" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary edit">Edit</button>
                </div>
            </form>
            <form id="editUserForm" name="editUserForm" enctype="multipart/form-data" data-tab="edit">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLongTitle">Edit Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row text-left">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-sm firstname required">
                                    <label for="firstname">First Name <span class="text-danger"> *</span></label>
                                    <input class="form-control form-control-sm" type="text" name="first_name"
                                        required="required" aria-required="true" maxlength="15">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-sm lastname">
                                    <label for="lastname">Last Name</label>
                                    <input class="form-control form-control-sm" type="text" name="last_name"
                                        maxlength="15">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-sm gender">
                                    <label for="country">Your Gender </label>
                                    <select class="form-control form-control-sm" name="gender" name="gender"
                                        required>
                                        <option value="1">Male</i></option>
                                        <option value="2">Female</i></option>
                                        <option value="3">Other</i></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-sm replace-passwords required">
                                    <label for="email">Replace Password?</label>
                                    <select class="form-control form-control-sm" id="changePasswordSelect"
                                        name="change_password">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <fieldset class="passwords" disabled="disabled">
                            <div class="row passwords-row" expanded="false">
                                <div class="col-md-6">
                                    <div class="form-group-sm password required">
                                        <label for="password">New Password <span class="text-danger"> *</span></label>
                                        <input class="form-control form-control-sm" type="password" name="password"
                                            autocomplete="new-password" maxlength="25">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-sm repassword required">
                                        <label for="repassword">Confirm Password <span class="text-danger"> *</span>
                                        </label>
                                        <input class="form-control form-control-sm" type="password" name="confirm_password"
                                            autocomplete="off" maxlength="25">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group-sm image text-center">
                            <div class="image-wrapper">
                                <label for="image" class="d-block">Profile Image</label>
                                <input type="file" name="image" accept="image/*" hidden>
                                <button href="#" class="image-button btn btn-link p-0">
                                    <img src="<?= PathHelper::image('imageUpload.jpg'); ?>" class="img-thumbnail" width="100"
                                        height="100" alt="Your profile image">
                                </button>
                            </div>
                            <p class="alert-danger error p-1 mt-1 mb-0 opacity-0 text-center"></p>
                        </div>
                        <div class="form-group-sm submit mt-3">
                        </div>
                    </div>
                    <div class="col md-12">
                        <div id="BEstatus" class="pt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
