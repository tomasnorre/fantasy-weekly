<?php
class ChartCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/chart');
    }

    public function iSeeChartLegend(AcceptanceTester $I)
    {
        $I->see('Morten');
        $I->see('Mathias');
        $I->see('Kasper');
        $I->see('Tomas');
    }
}
