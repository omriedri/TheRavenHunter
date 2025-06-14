<settings class="page">
    <div class="container content">
        <h3 class="heading">Settings</h3>

        <div class="row">
            <div class="col-lg-4">
                <div id="difficultySelector" class="wrappers">
                    <h4>Difficulty</h4>
                    <p>
                        <input type="radio" id="difficulty-easy" disable="mobile" name="difficulty" value="1" checked>
                        <label for="difficulty-easy">Easy</label>
                    </p>
                    <p>
                        <input type="radio" id="difficulty-medium" name="difficulty" value="2">
                        <label for="difficulty-medium">Medium</label>

                    </p>
                    <p>
                        <input type="radio" id="difficulty-hard" name="difficulty" value="3">
                        <label for="difficulty-hard">Hard</label>

                    </p>
                    <p>
                        <input type="radio" id="difficulty-insane" disable="mobile" name="difficulty" value="4">
                        <label for="difficulty-insane">Insane</label>
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="appearanceSelector" class="wrappers">
                    <h4>Appearance</h4>
                    <p>
                        <input type="radio" id="appearance-default" name="appearance" value="1" checked>
                        <label for="appearance-default">Default</label>
                    </p>
                    <p>
                        <input type="radio" id="appearance-opensky" name="appearance" value="2">
                        <label for="appearance-opensky">Open Sky</label>
                    </p>
                    <p>
                        <input type="radio" id="appearance-beach" name="appearance" value="3">
                        <label for="appearance-beach">On the beach</label>
                    </p>
                    <p>
                        <input type="radio" id="appearance-mountainsvalley" name="appearance" value="4">
                        <label for="appearance-mountainsvalley">Mountains valley</label>

                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="extraOptions" class="wrappers">
                    <h4>Extras</h4>
                    <p class="d-none d-lg-block">
                        <input type="checkbox" id="extra-options-fullscreen" name="full_screen">
                        <label for="extra-options-fullscreen">Full Screen</label>
                        <span class="settingDetails">Strech to full screen</span>
                    </p>
                    <p>
                        <input type="checkbox" id="extra-options-sounds" name="sounds">
                        <label for="extra-options-sounds">Game Sounds</label>
                        <span class="settingDetails">enable the game sounds</span>
                    </p>
                </div>
            </div>

        </div>
    </div>
</settings>