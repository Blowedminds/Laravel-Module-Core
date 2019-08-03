<?php


namespace App\Modules\Core\Tests;

use Tests\CreatesApplication;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getTest($class, $count, $route, $user = null)
    {
        for ($i = 0; $i < $count; $i++) {
            factory($class)->create();
        }
        if ($user) {
            $response = $this->actingAs($user)->getJson($route);
        } else {
            $response = $this->getJson($route);
        }
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);

        $this->assertCount($count, $data);
    }

    protected function postTest($class, $route, $user = null, $merge = [])
    {
        $model = factory($class)->make();

        $input = $model->toArray();

        if ($user) {
            $this->actingAs($user)->json('POST', $route, array_merge($input, $merge))->assertStatus(200);
        } else {
            $this->json('POST', $route, array_merge($input, $merge))->assertStatus(200);
        }

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = json_encode($value);
            }
        }

        $this->assertDatabaseHas($model->getTable(), $input);
    }

    protected function putTest($class, $route, $primaryKey, $user = null, $merge = [])
    {
        $model = factory($class)->create();

        $model1 = factory($class)->make();

        $input = $model1->toArray();

        if ($user) {
            $this->actingAs($user)->json('PUT', $route . $model[$primaryKey], array_merge($input, $merge))->assertStatus(200);
        } else {
            $this->json('PUT', $route . $model[$primaryKey], array_merge($input, $merge))->assertStatus(200);
        }

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = json_encode($value);
            }
        }

        $this->assertDatabaseHas($model->getTable(), array_merge($input, [$primaryKey => $model[$primaryKey]]));
    }

    protected function deleteTest($class, $route, $primaryKey, $user = null)
    {
        $model = factory($class)->create();

        $this->actingAs($user)->delete($route . $model[$primaryKey])->assertStatus(200);

        $input = $model->toArray();

        if(in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model), true)) {
            $input['deleted_at'] = null;
        }

        $this->assertDatabaseMissing($model->getTable(), $input);
    }

    protected function routeTest($route, $type, $status): void
    {
        $response = null;

        switch ($type) {
            case 'get':
                $response = $this->get($route);
                break;
        }

        if ($response) {
            $response->assertStatus($status);
        }
    }

    protected function checkRoute($route, $method = 'get'): bool
    {
        if ($route[0] === "/") {
            $route = substr($route, 1);
        }
        $routes = \Route::getRoutes()->getRoutes();
        foreach ($routes as $r) {
            /** @var \Route $r */
            if ($r->uri == $route) {
                foreach ($r->methods as $m) {
                    if (strtolower($m) === strtolower($method)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
