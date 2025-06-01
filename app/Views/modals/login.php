<!-- Login -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="Log-in" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="loginForm" name="loginForm" class="active">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLongTitle">Log-in</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row text-left">
                    <div class="col-md-9">
                        <div class="form-group-sm email required">
                            <label for="email">Email <span class="text-danger"> *</span></label>
                            <input class="form-control form-control-sm" type="email" name="email"
                                required="required" aria-required="true" maxlength="50">
                        </div>
                        <div class="form-group-sm password required">
                            <label for="password">Password <span class="text-danger"> *</span></label>
                            <input class="form-control form-control-sm" type="password" name="password"
                                required="required" aria-required="true" maxlength="25">
                        </div>
                        <div class="password-forgot my-2">
                            <a class="float-right" href="#" data-form-switch="FORGOT_PASSWORD" role="button">Forgot Password?</a>
                        </div>
                    </div>
                    <div class="col-md-3 d-none d-md-block">
                        <div class="form-group-sm image d-mv-none">
                            <label for="image"> </label>
                            <input type="file" name="image" accept="image/*" hidden>
                            <div href="#" class="image-button btn btn-link d-block p-0">
                                <img src="<?= PathHelper::image('imageUpload.jpg'); ?>" class="img-thumbnail" width="100" height="100"
                                    alt="Your profile image">
                            </div>
                            <p class="alert-danger error p-1 mt-1 mb-0 opacity-0 text-center"></p>
                        </div>
                        <div class="form-group-sm submit mt-3"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="loginData">
                    <button type="button" class="btn btn-secondary cancel d-mv-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Log-In</button>
                    <div id="googleSignInBtn" class="g_id_signin"></div>
                </div>
            </form>
            <form id="forgetPasswordForm" name="forgetPassword">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgetPasswordModalLongTitle">Forget Your Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row text-left">
                    <div class="col-12">
                        <div class="form-group-sm email required">
                            <label for="email">Your Email <span class="text-danger"> *</span></label>
                            <input class="form-control form-control-sm" type="email" name="email"
                                required="required" aria-required="true" maxlength="50">
                        </div>

                    </div>
                    <div class="col-12 mt-2">
                        <small>We will send you an email with a verification code to reset your password.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="forgetPassword">
                    <button type="button" class="btn btn-secondary" data-form-switch="LOGIN">Back</button>
                    <button type="submit" class="btn btn-primary">Continue</button>
                </div>
            </form>
            <form id="resetPasswordForm" name="resetPassword">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLongTitle">Create New Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row text-left">
                    <div class="col-md-9">
                        <div class="form-group-sm email required">
                            <label for="secret">verification code </label>
                            <span class="text-muted float-end mt-2"> (sent to your email)</span>
                            <input class="form-control form-control-sm" type="text" name="verification_code"
                                required="required" aria-required="true" autocomplete="empty" maxlength="32">
                        </div>
                        <div class="form-group-sm email required">
                            <label for="password">New Password <span class="text-danger"> *</span></label>
                            <input class="form-control form-control-sm" type="password" name="password"
                                required="required" aria-required="true" autocomplete="empty" minlength="8" maxlength="32">
                        </div>
                        <div class="form-group-sm password required">
                            <label for="password">Retype Password <span class="text-danger"> *</span></label>
                            <input class="form-control form-control-sm" type="password" name="password_confirm"
                                required="required" aria-required="true" autocomplete="empty" minlength="8" maxlength="32">
                        </div>

                    </div>
                    <div class="col-md-3"> </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancel" data-form-switch="FORGOT_PASSWORD">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>