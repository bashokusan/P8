<?php


namespace App\Tests\Utils;

use App\Utils\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    /**
     * @dataProvider getSlugs
     */
    public function testSlug(string $string, string $slug){
        $this->assertSame($slug, Slugger::slugify($string));
    }

    public function getSlugs()
    {
        yield ['Lorem Ipsum', 'lorem-ipsum'];
        yield ['Lorem Ipsum', 'lorem-ipsum'];
        yield ['L\'orem Ipsum', 'l-orem-ipsum'];
        yield ['Lôrém ïpsùm', 'lorem-ipsum'];
        yield ['  Lorem Ipsum  ', 'lorem-ipsum'];
        yield [' lOrEm  iPsUm  ', 'lorem-ipsum'];
        yield ['!Lorem Ipsum!', '!lorem-ipsum!'];
        yield ['lorem-ipsum', 'lorem-ipsum'];
    }
}
