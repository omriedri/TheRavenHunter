<!-- Register -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="registerForm" class="active" name="registerForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalTitle">Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row text-left">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group-sm first_name required">
                                    <label for="firstname">First Name <span class="text-danger"> *</span></label>
                                    <input class="form-control form-control-sm" type="text" name="first_name"
                                        required="required" aria-required="true" maxlength="15">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group-sm last_name">
                                    <label for="lastname">Last Name</label>
                                    <input class="form-control form-control-sm" type="text" name="last_name"
                                        maxlength="15">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group-sm gender">
                                    <label for="country">Your Gender <span class="text-danger"> *</span></label>
                                    <select class="form-control form-control-sm" name="gender" name="gender"
                                        required>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                        <option value="3">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group-sm email required">
                                    <label for="email">Your Email <span class="text-danger"> *</span></label>
                                    <input class="form-control form-control-sm" type="email" name="email"
                                        required="required" aria-required="true" autocomplete="empty"
                                        maxlength="50">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group-sm password required">
                                    <label for="password">Password <span class="text-danger"> *</span></label>
                                    <input class="form-control form-control-sm" type="password" name="password"
                                        required="required" aria-required="true" autocomplete="new-password"
                                        maxlength="25">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group-sm password-retype required">
                                    <label for="repassword">Retype Password <span class="text-danger"> *</span>
                                    </label>
                                    <input class="form-control form-control-sm" type="password" name="password_confirm"
                                        required="required" aria-required="true" autocomplete="off" maxlength="25">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group-sm image text-center">
                            <label for="image">Profile Image</label>
                            <input type="file" name="image" accept="image/*" hidden>
                            <button href="#" class="image-button btn btn-link p-0">
                                <img src="<?= PathHelper::image('imageUpload.jpg') ?>" class="img-thumbnail" width="100" height="100"
                                    alt="Your profile image">
                            </button>
                            <p class="alert-danger error p-1 mt-1 mb-0 opacity-0 text-center"></p>
                        </div>
                        <div class="form-group-sm submit mt-3">
                        </div>
                    </div>
                    <div class="col md-12">
                        <div id="BEstatus" class="pt-2"></div>
                        <div id="privacyPolicyAcceptLine">By done the regiseration you agree to our <a
                                class="privacy-policy-trigger" href="#" role="button" aria-haspopup="true"
                                data-bs-toggle="modal" data-bs-target="#privacyPolicyModal">Privacy Policy</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancel d-mv-none"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Register</button>
                    <div id="googleRegisterBtn" class="g_id_signin"></div>
                </div>
            </form>
        </div>
    </div>
</div>