<?php


namespace App\Modules\Core\Tests\Feature;


use App\Modules\Core\Traits\MenuTrait;
use Tests\TestCase;

class AdminModuleTest extends TestCase
{
    use MenuTrait;

    public function testPutChildrenIntoParents(): void
    {
        //Empty
        $this->assertEquals($this->putChildrenIntoParents([]), []);

        //One depth, Mixed ordered
        $this->assertEquals($this->putChildrenIntoParents([
            ['id' => 2, 'weight' => 1, 'parent' => 0, 'children' => []],
            ['id' => 3, 'weight' => 2, 'parent' => 2, 'children' => []],
            ['id' => 1, 'weight' => 1, 'parent' => 0, 'children' => []],
        ]), [
            ['id' => 2, 'weight' => 1, 'parent' => 0, 'children' => [
                ['id' => 3, 'weight' => 2, 'parent' => 2, 'children' => []],
            ]],
            ['id' => 1, 'weight' => 1, 'parent' => 0, 'children' => []],
        ]);

        //Two depth, Mixed Order
        $this->assertEquals($this->putChildrenIntoParents([
            ['id' => 2, 'weight' => 1, 'parent' => 0, 'children' => []],
            ['id' => 3, 'weight' => 2, 'parent' => 2, 'children' => []],
            ['id' => 1, 'weight' => 1, 'parent' => 0, 'children' => []],
            ['id' => 5, 'weight' => -1, 'parent' => 0, 'children' => []],
            ['id' => 4, 'weight' => 6, 'parent' => 3, 'children' => []],
        ]), [
            ['id' => 5, 'weight' => -1, 'parent' => 0, 'children' => []],
            ['id' => 2, 'weight' => 1, 'parent' => 0, 'children' => [
                ['id' => 3, 'weight' => 2, 'parent' => 2, 'children' => [
                    ['id' => 4, 'weight' => 6, 'parent' => 3, 'children' => []],
                ]],
            ]],
            ['id' => 1, 'weight' => 1, 'parent' => 0, 'children' => []],
        ]);
    }

}
