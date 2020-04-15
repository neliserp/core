<?php

namespace Neliserp\Core\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

// TODO: This class should be located in 'tests/' folder,
// but class 'not found' when others package tryint to extend.
abstract class CrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Model class name.
     *
     * @var string
     */
    protected $model;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table;

    /**
     * API url
     *
     * @var string
     */
    protected $api_url;

    /**
     * Search 'q' fields
     *
     * @var array
     */
    protected $q_fields = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->initClassProperties();
    }

    // *** index ***

    /** @test */
    public function it_can_list_items()
    {
        $items = factory($this->model, 3)->create();

        $expectedData = $items->toArray();

        $this->json('GET', "{$this->api_url}?sort=id")
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedData,
                'links' => [
                    'first' => "http://localhost{$this->api_url}?page=1",
                    'last' => "http://localhost{$this->api_url}?page=1",
                    'prev' => null,
                    'next' => null
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'last_page' => 1,
                    'path' => "http://localhost{$this->api_url}",
                    'per_page' => 10,
                    'to' => $items->count(),
                    'total' => $items->count(),
                ],
            ]);
    }

    /** @test */
    public function it_can_search_items_by_q()
    {
        if (count($this->q_fields) == 0) {
            $this->markTestSkipped("Warning: $this->model has no q fields defind.");
            return;
        }

        $q = 'bbb';
        $q_field = Arr::random($this->q_fields);

        $item_1 = factory($this->model)->create([$q_field => 'aaa-1']);
        $item_2 = factory($this->model)->create([$q_field => 'bbb-1']);
        $item_3 = factory($this->model)->create([$q_field => 'bbb-2']);
        $item_4 = factory($this->model)->create([$q_field => 'ccc-1']);

        $expectedData = [
            [
                'id' => $item_2->id,
            ],
            [
                'id' => $item_3->id,
            ],
        ];

        $this->json('GET', "{$this->api_url}?q={$q}")
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => $expectedData,
            ]);
    }

    // *** show ***

    /** @test */
    public function not_found_items_return_404()
    {
        $this->json('GET', "{$this->api_url}/9999")
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_view_an_item()
    {
        $item = factory($this->model)->create();

        $expectedData = $item->toArray();

        $this->json('GET', "{$this->api_url}/{$item->id}")
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedData,
            ]);
    }

    // *** store ***
    /**  @test */
    public function create_an_item_requires_valid_fields()
    {
        $data = [];

        $this->json('POST', $this->api_url, $data)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    //'name' => [
                    //    'The name field is required.'
                    //],
                ],
            ]);
    }

    /** @test */
    public function it_can_create_an_item()
    {
        $data = factory($this->model)->raw();

        $this->json('POST', $this->api_url, $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas($this->table, $data);
    }

    // *** update ***

    /**  @test */
    public function update_an_item_requires_valid_fields()
    {
        $item = factory($this->model)->create();

        $data = [];

        $this->json('PUT', "{$this->api_url}/{$item->id}", $data)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    //'name' => [
                    //    'The name field is required.'
                    //],
                ],
            ]);
    }

    /** @test */
    public function update_not_found_items_return_404()
    {
        $data = factory($this->model)->raw();

        $this->json('PUT', "{$this->api_url}/9999", $data)
            ->assertStatus(404);
    }

    /** @test */
    public function user_can_submit_update_with_no_changes()
    {
        $item = factory($this->model)->create();

        $data = $item->toArray();

        $this->json('PUT', "{$this->api_url}/{$item->id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas($this->table, $data);
    }

    /** @test */
    public function it_can_update_an_item()
    {
        $item = factory($this->model)->create();

        $data = factory($this->model)->raw();

        $this->json('PUT', "{$this->api_url}/{$item->id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas($this->table, $data);
    }

    // *** destroy ***

    /** @test */
    public function delete_not_found_items_return_404()
    {
        $this->json('DELETE', "{$this->api_url}/9999")
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_an_item()
    {
        $item = factory($this->model)->create();

        $this->json('DELETE', "{$this->api_url}/{$item->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing($this->table, [
            'id' => $item->id,
        ]);
    }

    /**
     * Init class properties.
     *
     * @return void
     */
    protected function initClassProperties()
    {
        $reflection = new \ReflectionClass($this);
        $namespace_name = $reflection->getNamespaceName();  // Neliserp\Core\Tests\Feature
        $short_class_name = $reflection->getShortName();

        $package_name = str_replace('\Tests\Feature', '', $namespace_name);
        $model_name = str_replace('Test', '', $short_class_name);

        $this->model = "{$package_name}\\{$model_name}";
        $this->table = Str::of($model_name)->plural()->lower();
        $this->api_url = "/api/{$this->table}";
    }
}
