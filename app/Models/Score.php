<?php 
require_once __DIR__ . '/Settings.php';
require_once __DIR__ . '/../Responses/UserRankResponse.php';
require_once __DIR__ . '/../Helpers/TimeHelper.php';

use Aternos\Model\Query\Limit;

class Score extends \Aternos\Model\GenericModel {

    protected static array $drivers = [\Aternos\Model\Driver\Mysqli\Mysqli::ID];
    
    public mixed $id = null;
    public int $user_id;
    public int $status = self::STATUS__PENDING;
    public int $platform;
    public int $difficulty;
    public float $time_diff;
    public string $time;
    public int $score;
    public string $created_at;
    public string $updated_at;

    public const STATUS__PENDING = 0;
    public const STATUS__COMPLETE = 1;

    public const DIFFICULTY_EASY        = Settings::DIFFICULTY_EASY;
    public const DIFFICULTY_MEDIUM      = Settings::DIFFICULTY_MEDIUM;
    public const DIFFICULTY_HARD        = Settings::DIFFICULTY_HARD;
    public const DIFFICULTY_INSANE      = Settings::DIFFICULTY_INSANE;

    public const PLATFORM_DESKTOP    = 1;
    public const PLATFORM_MOBILE     = 2;

    public const VALIDATION_RULES = [
        'user_id'       => 'required|integer',
        'status'        => 'required|integer|in:' . 
            self::STATUS__PENDING . ',' . 
            self::STATUS__COMPLETE,
        'platform'      => 'required|integer|in:' . 
            self::PLATFORM_DESKTOP . ',' . 
            self::PLATFORM_MOBILE,
        'difficulty'    => 'required|integer|in:' . 
            self::DIFFICULTY_EASY . ',' . 
            self::DIFFICULTY_MEDIUM . ',' . 
            self::DIFFICULTY_HARD . ',' . 
            self::DIFFICULTY_INSANE,
        'time'          => 'required',
        'score'         => 'required|integer',
    ];

    /**
     * User constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }

    // the name of your model (and table)
    public static function getName() : string {
        return "scores";
    }

    /**
     * Get the game difficulties
     *
     * @return array
     */
    protected static function getDifficulties() : array {
        return [
            self::DIFFICULTY_EASY     => 'Easy',
            self::DIFFICULTY_MEDIUM   => 'Medium',
            self::DIFFICULTY_HARD     => 'Hard',
            self::DIFFICULTY_INSANE   => 'Insane',
        ];
    }

    /**
     * Get the platforms
     *
     * @return array
     */
    public static function getPlatforms() : array {
        return [
            self::PLATFORM_DESKTOP    => 'Desktop',
            self::PLATFORM_MOBILE     => 'Mobile',
        ];
    }

    /**
     * Get a user's best score & rating
     *
     * @param integer $userId
     * @return UserRankResponse
     */
    public static function getUserRankDetails(int $userId = 0): UserRankResponse {
        $Response = new UserRankResponse();
        $scores = self::select([],['score' => 'ASC']);
        foreach ($scores as $index => $Score) {
            if($Score->user_id == $userId) {
                $Response->fillByScore($Score, $index);
                break;
            }
        }
        return $Response;
    }

    /**
     * Get the user's score instance
     *
     * @param integer $userId
     * @param integer $platform
     * @return self|null
     */
    public static function getUserScoreInstance(int $userId, int $platform): ?self {
        $scores = self::select([
            'user_id' => $userId, 
            'platform' => $platform
        ], ['score' => 'DESC'], null, new Limit(1));
        return $scores[0] ?? null;
    }

    /**
     * Get the user's best score
     *
     * @param integer $userId
     * @param integer $platform
     * @return integer
     */
    public static function getUserBestScore(int $userId, int $platform): int {
        return self::getUserScoreInstance($userId, $platform)->score ?? 0;
    }

    public function getUserDetails() {
        return User::get($this->user_id)->getUserInfo() ?? [];
    }

    public function getPlatform() {
        return self::getPlatforms()[$this->platform] ?? '';
    }

    public function getDifficulty() {
        return self::getDifficulties()[$this->difficulty] ?? '';
    }

    /**
     * Start a new game session
     *
     * @param User $User
     * @param integer $platform
     * @return self
     */
    public function startGameSession(User $User, int $platform = self::PLATFORM_DESKTOP): self {
        $Settings = $User->getSettings();
        $this->user_id = $User->id;
        $this->platform = $platform;
        $this->difficulty = $Settings->difficulty;
        $this->time_diff = microtime(true);
        $this->time = '00:00:00';
        $this->score = 0;
        $this->status = self::STATUS__PENDING;
        return $this;
    }

    /**
     * End the game session
     *
     * @param Score|null $SavedSession
     * @return self
     */
    public function endGameSession(?Score $SavedSession = null): self {
        $InstanceToReturn = $this;
        $time_diff = (microtime(true) - $this->time_diff);
        $miliseconds = (int) ($time_diff * 1000);
        $data = [
            'time_diff'  => $time_diff,
            'time' => TimeHelper::milisecondsToTime($miliseconds),
            'score' => $this->getCalculateScore($miliseconds),
        ];
        if(!$SavedSession) {
            $this->fill($data);
            $this->status = self::STATUS__COMPLETE;
            $this->save();
            $InstanceToReturn = Score::getUserScoreInstance($this->user_id, $this->platform);
        } elseif($SavedSession->score < $data['score']) {
            $SavedSession->fill($data);
            $SavedSession->save();
            $InstanceToReturn = $SavedSession;
        } else {
            $this->fill($data);
            $InstanceToReturn = $this;
        }
        return $InstanceToReturn;

    }

    /**
     * Calculate the score of a game session and set it
     *
     * @param int $time
     * @return int
     */
    private function getCalculateScore(float $miliSeconds): int {
        $score = 0;
        switch ($this->difficulty) {
            case self::DIFFICULTY_EASY:
                $score = 20000;
                break;
            case self::DIFFICULTY_MEDIUM:
                $score = 50000;
                break;
            case self::DIFFICULTY_HARD:
                $score = 70000;
                break;
            case self::DIFFICULTY_INSANE:
                $score = 10000;
                break;
        }
        $score = (int) ($score - ($miliSeconds * 0.75));
        if($score < 0) $score = 0;
        return $score;
    }
}
