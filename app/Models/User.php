<?php class User extends \Aternos\Model\GenericModel {

    protected static array $drivers = [\Aternos\Model\Driver\Mysqli\Mysqli::ID];
        
    // all public properties are database fields
    public mixed $id = null;
    public string $first_name;
    public string $last_name;
    public string $gender;
    public string $email;
    public string $password;
    public ?string $phone;
    public ?string $image;
    public ?string $csrf;
    public ?string $last_login;
    public string $created_at;
    public string $updated_at;


    public const VALIDATION_RULES = [
        'first_name'    => 'required|min:2|max:48',
        'last_name'     => 'required|min:2|max:48',
        'email'         => 'required|email:min:6|max:64',
        'password'      => 'required|min:8|max:32',
        'phone'         => 'min:10|max:16',
    ];

    public const AUTH_RULES = [
        'email'         => 'required|email',
        'password'      => 'required|min:8|max:32',
    ];

    protected const GENDER_UNKNOWN = 0;
    protected const GENDER_MALE    = 1;
    protected const GENDER_FEMALE  = 2;
    protected const GENDER_OTHER   = 3;

    

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
        return "users";
    }
}
