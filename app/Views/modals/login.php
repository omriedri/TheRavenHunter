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
                            <a class="login-dialog-tab-control float-right mt-2" href="#" data-tab="forget"
                                role="button">Forget Password?</a>
                            <input class="form-control form-control-sm" type="password" name="password"
                                required="required" aria-required="true" maxlength="25">
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
                    <div class="col-sm-12">
                        <div class="launch-full-screen">
                            <input type="checkbox" class="mr-1 ml-1" id="login-fullscreen-toggle">
                            <label for="launch-full-screen">Full-Screen Mode</label>
                        </div>
                    </div>
                    <div class="col md-12">
                        <div id="BEstatus" class="pt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="loginData">
                    <button type="button" class="btn btn-secondary cancel d-mv-none"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Log-In</button>
                </div>
            </form>
            <form id="forgetPasswordForm" name="forgetPassword">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgetPasswordModalLongTitle">Forget Your Password</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row text-left">
                    <div class="col-md-9">
                        <div class="form-group-sm email required">
                            <label for="email">Your Email <span class="text-danger"> *</span></label>
                            <input class="form-control form-control-sm" type="email" name="email"
                                required="required" aria-required="true" maxlength="50">
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col md-12">
                        <div id="BEstatus" class="pt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="forgetPassword">
                    <button type="button" class="btn btn-secondary cancel login-dialog-tab-control"
                        data-tab="login">Cancel</button>
                    <button type="submit" class="btn btn-primary resetPassword">Continue</button>
                </div>
            </form>
            <form id="resetPasswordForm" name="resetPassword">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLongTitle">Create New Password</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row text-left">
                    <div class="col-md-9">
                        <div class="form-group-sm email required">
                            <label for="secret">Validation Key </label>
                            <span class="text-muted float-right mt-2"> (sent to your email)</span>
                            <input class="form-control form-control-sm" type="text" name="validationKey"
                                required="required" aria-required="true" autocomplete="emply" maxlength="6">
                        </div>
                        <div class="form-group-sm email required">
                            <label for="password">New Password <span class="text-danger"> *</span></label>
                            <input class="form-control form-control-sm" type="password" name="newPassword"
                                required="required" aria-required="true" autocomplete="empty" maxlength="25">
                        </div>
                        <div class="form-group-sm password required">
                            <label for="password">Retype Password <span class="text-danger"> *</span></label>
                            <input class="form-control form-control-sm" type="password" name="newRepassword"
                                required="required" aria-required="true" autocomplete="empty" maxlength="25">
                        </div>

                    </div>
                    <div class="col-md-3"> </div>
                    <div class="col md-12">
                        <div id="BEstatus" class="pt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="resetPassword">
                    <button type="button" class="btn btn-secondary cancel login-dialog-tab-control"
                        data-tab="login">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>