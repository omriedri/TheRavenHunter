<game class="page">
    <div class="frame default position-relative" id="cage" dir="rtl">
        <img class="onplay position-absolute noSelect" id="bird" src="public/images/bird.gif" alt="bird">
    </div>
    <div id="game-control-btns" class="btn-group position-fixed border border-dark shadow" role="group" aria-label="Game Controllers">
        <button type="button" action="exit" class="btn btn-primary">Exit</button>
        <button type="button" action="restart" class="btn btn-primary">Restart</button>
        <timer class="btn btn-light">
            <span id="min">00</span>
            <span>:</span>
            <span id="sec">00</span>
            <span>:</span>
            <span id="milisec">00</span>
        </timer>
    </div>
    <div id="life" class="position-fixed p-3">
        <div class="life-wrapper d-flex">
            <img src="public/images/bird-side-icon.png" alt="Raven" class="mx-2">
            <div id="life-bar" class="progress">
                <div class="progress-bar progress-bar-animated bg-success" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                </div>
            </div>
        </div>
    </div>
</game>