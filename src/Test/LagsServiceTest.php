<?php

namespace Octo\Lags\Test;

use Octo\Lags\LagsService;

class LagsServiceTest extends \PHPUnit\Framework\TestCase
{

    private $orders = 
            'DONALD;2015001;006;10000.00' . PHP_EOL
            . 'DAISY;2015003;002;4000.00' . PHP_EOL
            . 'PICSOU;2015007;007;8000.00' . PHP_EOL
            . 'MICKEY;2015008;007;9000.00' . PHP_EOL;

    private $testFile = './test.csv';

    public function setUp()
    {
        file_put_contents($this->testFile, $this->orders);

        $this->lagsService = new LagsService;
        $this->lagsService->getFichierOrder($this->testFile);
    }

    public function testCA()
    {
        ob_start();
        $this->lagsService->calculerLeCA(false);
        $stdout = ob_get_clean();

        $expectedResult = 'CALCUL CA..' . "\r\n"
            . '........CA: 19,000.00' . "\r\n";

        $this->assertEquals($expectedResult, $stdout);
    }

    public function tearDown()
    {
        unlink($this->testFile);
    }
}
