<home class="page active">
    <img id="bird" class="text-center margin-auto" src="<?= PathHelper::image('bird.gif') ?>" alt="Flying bird animation">

    <div class="loader text-center position-relative" width="100" height="100">
        <div id="registerLoading" class="loadingio-spinner-spinner-t4i2yjwkfx active">
            <div class="ldio-bsei7j58pwk">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <!-- <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div> -->
    </div>
    <div class="logged-in-section d-none">
        <div class="loggedUser text-center">
            <p class="text-center">Welcome back, <span data-user-name></span></p>
            <img class="border border-dark rounded-circle" style="width: 100px;" src="" alt="" data-user-image>
            <p>Thanks for logging in, <span data-user-name></span></p>
        </div>
        <div class="login-register-section text-center">
            <button id="start" class="show">Click To Start</button>
        </div>
        <button id="start" class="d-none">Click To Start</button>
    </div>
    <div class="logged-out-section d-none">
        <p class="text-center">Welcome guest, please log-in or register to continue</p>
        <div class="login-register-section text-center">
            <div class="button_cont">
                <button class="cta-btn" data-bs-toggle="modal" data-bs-target="#loginModal" rel="nofollow noopener">Log-In</button>
            </div>
            <div class="button_cont">
                <button class="cta-btn" data-bs-toggle="modal" data-bs-target="#regiserationModal">Register</button>
            </div>
        </div>
    </div>
</home>