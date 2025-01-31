<?php require_once __DIR__ . '/../Models/Score.php';

class GameService {

    private const GAME_SESSION = 'game_session';
    private const SAVED_SESSION = 'saved_session';
    private const BEST_SCORE = 'best_score';

        /**
     * Get the logged in user's game session
     * @return Score
     */
    public static function getPendingGameSession(): ?Score {
        return isset($_SESSION[self::GAME_SESSION]) ? unserialize($_SESSION[self::GAME_SESSION]) : null;
    }

    /**
     * Set the logged in user's game session
     * @param Score $Score
     * @return boolean
     */
    public static function setPendingGameSession(Score $Score): bool {
        return (bool) $_SESSION[self::GAME_SESSION] = serialize($Score);
        
    }

    /**
     * Unset the logged in user's game session
     * @return void
     */
    public static function unsetPendingGameSession(): void {
        unset($_SESSION[self::GAME_SESSION]);
    }

    /**
     * Set the last saved game session
     * @return Score|null
     */
    public static function getBestScoreGameSession(): ?Score {
        return isset($_SESSION[self::SAVED_SESSION]) ? unserialize($_SESSION[self::SAVED_SESSION]) : null;
    }

    /**
     * Set the last saved game session
     * @param Score|null $Score
     * @return boolean
     */
    public static function setBestScoreGameSession(?Score $Score = null): bool {
        if($Score === null) {
            unset($_SESSION[self::SAVED_SESSION]);
            return true;
        }
        if(!empty($_SESSION[self::SAVED_SESSION]) && unserialize($_SESSION[self::SAVED_SESSION])->score > $Score->score) {
            return false;
        }
        return (bool) $_SESSION[self::SAVED_SESSION] = serialize($Score);
    }


    /**
     * Get the best score of the logged in user
     * @param integer $platform
     * @return integer
     */
    public static function getUserBestScore(int $platform = Score::PLATFORM_DESKTOP): int {
        $userId = AuthService::user()->id;
        $bestScore = isset($_SESSION[self::BEST_SCORE]) ? $_SESSION[self::BEST_SCORE] : null;
        if(!isset($bestScore)) {
            $bestScore = Score::getUserBestScore($userId, $platform) ?? 0;
        }
        if($bestScore < self::getBestScoreGameSession()->score) {
            $_SESSION[self::BEST_SCORE] = self::getBestScoreGameSession()->score;
        }
        return $_SESSION[self::BEST_SCORE];
    }

    /**
     * Set the best score of the logged in user
     * @param integer $score
     * @return void
     */
    public static function setUserBestScore(int $score): void {
        if(!isset($_SESSION[self::BEST_SCORE])) {
            $_SESSION[self::BEST_SCORE] = $score;
            return;
        }
        if($score > ($_SESSION[self::BEST_SCORE] ?? 0)) {
            $_SESSION[self::BEST_SCORE] = $score;
        }
    }
}
