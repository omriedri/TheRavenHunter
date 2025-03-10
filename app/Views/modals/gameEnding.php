<div class="modal fade" id="GameEndingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="GameEndingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark shadow">
            <div class="modal-header d-none">
                <h1 class="modal-title fs-5" id="GameEndingModalLabel">Game Over</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h2>Congratulations!</h2>
                <img src="public/images/shooted.png" alt="Shooted Raven">
                <p>You have successfully defeated the raven!</p>
                <div class="d-flex flex-row-reverse justify-content-evenly align-items-center">
                    <div class="score">
                        <span class="h6">Your Score</span>
                        <h3 id="your-score" class="h2">0</h3>
                    </div>
                    <div class="time">
                        <span class="h6">Time Left</span>
                        <h3 id="your-time">00:00:00</h3>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-action="exit"    data-bs-dismiss="modal" style="width: 100px;">Exit</button>
                <button type="button" class="btn btn-primary"   data-action="restart" data-bs-dismiss="modal" style="width: 100px;">Try Again</button>
            </div>
        </div>
    </div>
</div>