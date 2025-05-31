<?php 
require_once __DIR__ . '/../Services/FileUploadService.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Helpers/PathHelper.php';
require_once __DIR__ . '/Settings.php';
require_once __DIR__ . '/Score.php';

class User extends \Aternos\Model\GenericModel {

    protected static array $drivers = [\Aternos\Model\Driver\Mysqli\Mysqli::ID];

    // all public properties are database fields
    public mixed $id = null;
    public string $first_name;
    public string $last_name;
    public string $gender;
    public string $email;
    public ?string $password;
    public ?string $phone;
    public ?string $image;
    public ?string $csrf;
    public ?string $last_login;
    public string $created_at;
    public string $updated_at;

    // the name of your model (and table)
    public static function getName() : string {
        return "users";
    }

    public const VALIDATION_RULES = [
        'first_name'        => 'required|min:2|max:48',
        'last_name'         => 'min:2|max:48',
        'email'             => 'required|email:min:6|max:64',
        'password'          => 'required|min:8|max:32',
        'phone'             => 'min:10|max:16',
    ];

    public const REGISTER_RULES = [
        'first_name'        => 'required|min:2|max:48',
        'last_name'         => 'min:2|max:48',
        'email'             => 'required|email|min:6|max:64',
        'phone'             => 'min:10|max:16',
        'password'          => 'required|min:8|max:32',
        'password_confirm'  => 'required|same:password',
        'images.image'      => 'uploaded_file|max:5M|mimes:jpeg,png',
    ];

    public const UPDATE_RULES = [
        'id'                => 'required|integer',
        'first_name'        => 'min:2|max:48',
        'last_name'         => 'min:2|max:48',
        'gender'            => 'in:0,1,2,3',
        'change_password'   => 'integer|in:0,1',
        'password'          => 'required_if:change_password,1|min:8|max:32',
        'confirm_password'  => 'required_if:change_password,1|same:password',
        'images.image'      => 'uploaded_file|max:5M|mimes:jpeg,png',
    ];

    public const AUTH_RULES = [
        'email'             => 'required|email',
        'password'          => 'required|min:8|max:32',
    ];

    public const RESET_PASSWORD_RULES = [
        'email'             => 'required|email',
        'password'          => 'required|min:8|max:32',
        'password_confirm'  => 'required|same:password',
        'verification_code' => 'required|min:12|max:32',
    ];

    public const GENDER_UNKNOWN = 0;
    public const GENDER_MALE    = 1;
    public const GENDER_FEMALE  = 2;
    public const GENDER_OTHER   = 3;

    

    /**
     * User constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }

    /**
     * get the user brief data
     *
     * @return array
     */
    public function getUserInfo(): array {
        return [
            'id'    => $this->id,
            'fname' => $this->first_name,
            'lname' => $this->last_name,
            'email' => $this->isLoggedUser() ? $this->email : 'Private',
            'phone' => $this->isLoggedUser() ? $this->phone : 'Private',
            'gender'=> $this->gender,
            'image' => $this->getImage(),
            'since' => date('d/m/Y', strtotime($this->created_at)),
        ];
    }

    /**
     * get the user settings
     *
     * @return Settings
     */
    public function getSettings(): Settings {
        return Settings::getSettings($this->id);
    }

    /**
     * get the user rank details
     *
     * @return UserRankResponse
     */
    public function getUserRankDetails(): UserRankResponse {
        return Score::getUserRankDetails($this->id);
    }

    /**
     * get the user image
     *
     * @return string
     */
    public function getImage(): string {
        if(!empty($this->image)) {
            return $this->image;
        } elseif((int)$this->gender === self::GENDER_FEMALE) {
            return PathHelper::image('avatar-female.jpg');
        } else {
            return PathHelper::image('avatar-male.jpg');
        }
    }

    /**
     * update the user in the session
     *
     * @return bool
     */
    public function updateSession(): bool {
        return AuthService::setUserSession($this);
    }

    /**
     * check if the user is the logged in user
     *
     * @return boolean
     */
    public function isLoggedUser(): bool {
        return AuthService::user()->id ?? 0 === $this->id ?? 0;
    }

    /**
     * Upload the user image
     *
     * @param array $file
     * @param bool $save
     * @return string
     * @throws \Exception
     */
    public function uploadImage(array $file, $save = true): string {
        $uploadResponse = FileUploadService::uploadCompressUsingTinify($file);
        if($uploadResponse->status) {
            $this->image = PathHelper::UPLOADS_PATH . $uploadResponse->data['filename'];
            if($save) $this->save();
        } else {
            throw new \Exception($uploadResponse->message);
        }
        return $this->image;
    }
}
