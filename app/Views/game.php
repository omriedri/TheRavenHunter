<game class="page">
    <h5 class="text d-mv-none">Try catching the bird using the mouse</h5>

    <div class="frame default position-relative" id="cage" dir="rtl">
        <img class="onplay position-absolute noSelect" id="bird" src="public/images/bird.gif" alt="bird">
    </div>
    <div id="timer">
        <exit>
            <button>Exit</button>
        </exit>
        <Restart>
            <button>Reset</button>
        </Restart>
        <timer>
            <span id="min">00</span>
            <span>:</span>
            <span id="sec">00</span>
            <span>:</span>
            <span id="milisec">00</span>
        </timer>
    </div>
    <div id="birdTouchCounter" class="progress d-lg-none d-xl-none">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="75"
            aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
    </div>

</game>