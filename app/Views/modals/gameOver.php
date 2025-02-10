<div class="modal fade" id="GameOverModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="GameOverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark shadow">
            <div class="modal-header d-none">
                <h1 class="modal-title fs-5" id="GameOverModalLabel">Game Over</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-4">
                <h2 class="mb-3">Game Over</h2>
                <img src="public/images/escaped.png" alt="Jokking Raven">
                <p class="mt-3 mb-0">The time is over, try better next time!</p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-action="exit"    data-bs-dismiss="modal" style="width: 100px;">Exit</button>
                <button type="button" class="btn btn-primary"   data-action="restart" data-bs-dismiss="modal" style="width: 100px;">Try Again</button>
            </div>
        </div>
    </div>
</div>