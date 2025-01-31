<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Services/GameService.php';
require_once __DIR__ . '/../Responses/BaseResponse.php';
require_once __DIR__ . '/../Responses/DataResponse.php';
require_once __DIR__ . '/../Helpers/ArrayHelper.php';
require_once __DIR__ . '/../Enums/HttpStatus.php';
require_once __DIR__ . '/../Exceptions/DataValidationException.php';
require_once __DIR__ . '/../Models/Score.php';
require_once __DIR__ . '/../Models/User.php';

use Aternos\Model\Query\WhereCondition as Where;
use Aternos\Model\Query\Limit;

class ScoresController extends BaseController {

    private const GET__ALL = 0;
    private const GET__TOP_15 = 1;
    private const GET__FASTEST_15 = 2;
    private const GET__LAST_7_DAYS = 3;
    private const GET__LAST_30_DAYS = 4;

    public static function get(int $option = self::GET__TOP_15) {
        $Response = new DataResponse();
        try {
            switch ($option) {
                case self::GET__TOP_15:
                    $scores = Score::select(null, ['score' => 'DESC'], null, new Limit(15));
                    break;
                case self::GET__FASTEST_15:
                    $scores = Score::select(null, ['time' => 'ASC'], null, new Limit(15));
                    break;
                case self::GET__LAST_7_DAYS:
                case self::GET__LAST_30_DAYS:
                    switch ($option) {
                        case self::GET__LAST_7_DAYS:    $date = date('Y-m-d H:i:s', strtotime('-7 days'));
                        case self::GET__LAST_30_DAYS:   $date = date('Y-m-d H:i:s', strtotime('-30 days'));
                    }
                    $scores = Score::select(new Where('created_at', $date, '>='), ['created_at' => 'DESC']);
                    break;
                default:
                    $scores = Score::select();
                    break;  
            }
            $data = [];
            foreach ($scores as $Score) {
                $User = User::get($Score->user_id);
                $data[] = [
                    'player' => [
                        'id' => $User->id,
                        'name' => $User->first_name . ' ' . $User->last_name,
                        'image' => $User->getImage(),
                    ],
                    'hour' => date('H:i', strtotime($Score->created_at)),
                    'date' => date('d/m/Y', strtotime($Score->created_at)),
                    'platform' => $Score->getPlatform(),
                    'difficulty' => $Score->getDifficulty(),
                    'score' => number_format($Score->score),
                    'time' => $Score->time,
                ];
            }
            $Response->setData($data)->setSuccess('Scores fetched successfully');
        } catch (\Throwable $th) {
            $Response->setError($th->getMessage(), HttpStatus::INTERNAL_SERVER_ERROR);
        }
        $Response->output();
    }

    /**
     * Set new game session (start game)
     * @return void
     */
    public static function startGameSession() {
        $Response = new DataResponse();
        try {
            if(AuthService::guest()) throw new AuthException();
            $GameSession = new Score();
            $User = AuthService::user();
            $platform = self::getReadyData(['platform' => 'required|integer|in:1,2'])['platform'];
            GameService::setPendingGameSession($GameSession->startGameSession($User, $platform));
            $SavedGameSession = GameService::getBestScoreGameSession();
            if(!($SavedGameSession instanceof Score)) {
                $SavedGameSession = Score::getUserScoreInstance($User->id, $platform);
            }
            GameService::setBestScoreGameSession($SavedGameSession);
            GameService::setUserBestScore($SavedGameSession->score ?? 0);
            $Response->setData($GameSession)->setSuccess('Game session started successfully');
        } catch (\Throwable $th) {
            $Response->setError($th->getMessage(), HttpStatus::INTERNAL_SERVER_ERROR);
        }
        $Response->output();
    }

    /**
     * End game session (end game)
     * @return void
     */
    public static function endGameSession() {
        $Response = new DataResponse();
        try {
            if(AuthService::guest()) throw new AuthException();
            $GameSession = GameService::getPendingGameSession();
            if(!($GameSession instanceof Score)) {
                throw new \Exception('Game session not found');
            }
            $GameSession = $GameSession->endGameSession(GameService::getBestScoreGameSession());
            GameService::setBestScoreGameSession($GameSession);
            GameService::setUserBestScore($GameSession->score);
            $data = array_merge((array)$GameSession, ['best_score' => GameService::getUserBestScore()]);
            $data['score'] = number_format($data['score']);
            $Response->setData($data)->setSuccess('Game session ended successfully');
        } catch (\Throwable $th) {
            $Response->setError($th->getMessage(), HttpStatus::INTERNAL_SERVER_ERROR);
        }
        $Response->output();
    }

    /**
     * Forget game session
     * @return void
     */
    public static function forgetGameSession() {
        $Response = new DataResponse();
        try {
            if(AuthService::guest()) throw new AuthException();
            GameService::unsetPendingGameSession();
            $Response->setSuccess('Game session forgotten successfully');
        } catch (\Throwable $th) {
            $Response->setError($th->getMessage(), HttpStatus::INTERNAL_SERVER_ERROR);
        }
        $Response->output();
    }
}
