<?php class UserRankResponse {

    public string   $score  = '0';
    public int      $rank   = 0;
    public string   $date   = '';

    /**
     * Fill the response by a score
     *
     * @param Score $Score
     * @param integer $index
     * @return void
     */
    public function fillByScore(Score $Score, int $index) {
        $this->score = number_format($Score->score);
        $this->rank = $index + 1;
        $this->date = date('d/m/Y', strtotime($Score->created_at));
    }
}