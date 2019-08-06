<?php


namespace App\Modules\Core\Tests;

use App\Modules\Core\Role;
use Tests\CreatesApplication;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getManyTest($class, $route, $user = null, $count = 0): void
    {
        $count = $count !== 0 ? $count : random_int(1, 10);

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

    protected function getPaginateTest($class, $route, $user): void
    {
        $count = random_int(1, 10);

        for ($i = 0; $i < $count; $i++) {
            factory($class)->create();
        }

        $route .= '?per-page=' . $count;

        if ($user) {
            $response = $this->actingAs($user)->getJson($route);
        } else {
            $response = $this->getJson($route);
        }

        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);

        $this->assertCount($count, $data['data']);
    }

    protected function getOneTest($class, $route, $primaryKey, $user = null): void
    {
        $model = factory($class)->create();

        if ($user) {
            $response = $this->actingAs($user)->getJson($route . $model[$primaryKey]);
        } else {
            $response = $this->getJson($route . $model[$primaryKey]);
        }

        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);

        $this->assertSame($data[$primaryKey], $model[$primaryKey]);
    }

    protected function postTest($class, $route, $user = null, $merge = [], $defaults = []): void
    {
        $model = factory($class)->make();

        $input = $model->toArray();

        if ($user) {
            $this->actingAs($user)->json('POST', $route, array_merge($input, $merge))->assertStatus(200);
        } else {
            $this->json('POST', $route, array_merge($input, $merge))->assertStatus(200);
        }

        foreach ($input as $key => $value) {
            if (in_array($key, $defaults, true)) {
                unset($input[$key]);
                continue;
            }

            if (is_array($value)) {
                $input[$key] = json_encode($value);
            }
        }

        $this->assertDatabaseHas($model->getTable(), $input);
    }

    protected function putTest($class, $route, $primaryKey, $user = null, $merge = [], $notUpdate = []): void
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
            if (in_array($key, $notUpdate, true)) {
                unset($input[$key]);
                continue;
            }

            if (is_array($value)) {
                $input[$key] = json_encode($value);
            }
        }

        $this->assertDatabaseHas($model->getTable(), $input);
    }

    protected function deleteTest($class, $route, $primaryKey, $user = null): void
    {
        $model = factory($class)->create();

        if ($user) {
            $this->actingAs($user)->delete($route . $model[$primaryKey])->assertStatus(200);
        } else {
            $this->json('DELETE', $route . $model[$primaryKey])->assertStatus(200);
        }

        $input = $model->toArray();

        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model), true)) {
            $input['deleted_at'] = null;
        }

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = json_encode($value);
            }
        }

        $this->assertDatabaseMissing($model->getTable(), $input);
    }

    protected function routeTest($route, $method, $status): void
    {
        $response = null;

        switch ($method) {
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
        if (strlen($route) > 1 && $route[0] === '/') {
            $route = substr($route, 1);
        }
        $routes = \Route::getRoutes()->getRoutes();
        foreach ($routes as $r) {
            /** @var \Route $r */
            if ($r->uri === $route) {
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
