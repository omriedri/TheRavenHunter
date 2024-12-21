<?php class Score extends \Aternos\Model\GenericModel {

    protected static array $drivers = [\Aternos\Model\Driver\Mysqli\Mysqli::ID];
    
    public mixed $id = null;
    public int $user_id;
    public int $platform;
    public int $difficulty;
    public string $time;
    public int $score;
    public string $created_at;
    public string $updated_at;

    protected const DIFFICULTY_EASY     = 1;
    protected const DIFFICULTY_MEDIUM   = 2;
    protected const DIFFICULTY_HARD     = 3;
    protected const DIFFICULTY_INSANE   = 4;

    protected const PLATFORM_DESKTOP    = 1;
    protected const PLATFORM_MOBILE     = 2;

    public const VALIDATION_RULES = [
        'user_id'       => 'required|integer',
        'platform'      => 'required|integer|in:' . array_keys(self::getPlatforms()),
        'difficulty'    => 'required|integer|in:' . array_keys(self::getDifficulties()),
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
    protected static function getPlatforms() : array {
        return [
            self::PLATFORM_DESKTOP    => 'Desktop',
            self::PLATFORM_MOBILE     => 'Mobile',
        ];
    }
}
