<?php require_once __DIR__ . '/Score.php';

class Settings extends \Aternos\Model\GenericModel {

    protected static array $drivers = [\Aternos\Model\Driver\Mysqli\Mysqli::ID];

    // all public properties are database fields
    public mixed $id = null;
    public int $user_id = 0;
    public int $difficulty = 1;
    public int $appearance = 1;
    public int $full_screen = 0;
    public int $sounds = 0;
    public int $coordinates = 0;
    public string $created_at;
    public string $updated_at;

    public const DIFFICULTY_EASY     = 1;
    public const DIFFICULTY_MEDIUM   = 2;
    public const DIFFICULTY_HARD     = 3;
    public const DIFFICULTY_INSANE   = 4;


    // the name of your model (and table)
    public static function getName() : string {
        return "settings";
    }

    public const VALIDATION_RULES = [
        'user_id'       => 'integer',
        'difficulty'    => 'integer|in:1,2,3,4',
        'appearance'    => 'integer|in:1,2,3,4',
        'full_screen'   => 'integer|in:0,1',
        'sounds'        => 'integer|in:0,1',
        'coordinates'   => 'integer|in:0,1',
    ];

    /**
     * Settings constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }

    /**
     * Get/Create+Get the settings of a user
     *
     * @param integer $userId
     * @param boolean $preventRecursion
     * @return self
     */
    public static function getSettings(int $userId, $preventRecursion = false): self {
        $Settings = self::select(['user_id' => $userId], null, null, 1)[0] ?? null;
        if(!$Settings && !$preventRecursion) {
            $Settings = new self();
            $Settings->user_id = $userId;
            $Settings->save();
            $Settings = self::getSettings($userId, true);
            
        }
        return $Settings;
    }

    /**
     * Get the public data of the settings
     *
     * @return array
     */
    public function getPublicData(): array {
        return [
            'difficulty'    => $this->difficulty,
            'appearance'    => $this->appearance,
            'full_screen'   => $this->full_screen,
            'sounds'        => $this->sounds,
            'coordinates'   => $this->coordinates,
        ];
    }

}
