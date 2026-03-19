<?php


class HelperTest extends \PHPUnit\Framework\TestCase
{
    public function testEnv()
    {
        putenv('var_true=true');
        putenv('var_false=false');

        $this->assertEquals(true, env('var_true'));
        $this->assertEquals(false, env('var_false'));
        $this->assertEquals('', env('var_empty'));
        $this->assertEquals(null, env('var_null'));

        $this->assertEquals(true, env('var_true_parentheses'));
        $this->assertEquals(false, env('var_false_parentheses'));
        $this->assertEquals('', env('var_empty_parentheses'));
        $this->assertEquals(null, env('var_null_parentheses'));

        putenv('var_quotes="quotes"');
        $this->assertEquals('quotes', env('var_quotes'));

        putenv('var_basic=basic_test');
        $this->assertEquals('basic_test', env('var_basic'));
    }
}
