<?php
class LeaderboardCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/');
    }

    public function iSeeTableHeaders(AcceptanceTester $I)
    {
        $I->see('#');
        $I->see('Player');
        $I->see('Today');
        $I->see('Score');
        $I->see('Teams');
    }

    public function iSeeLeadingScore(AcceptanceTester $I)
    {
        $I->see('Leading Score');
    }

    public function iSeeScoreCard(AcceptanceTester $I)
    {
        $I->click('tr.player');
        $I->see('Hole');
        $I->see('Par');
    }
}
