<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use Laravel\Sanctum\Sanctum;


class RecipeTest extends TestCase
{
    use RefreshDatabase;
    public function test_index(): void{
        Sanctum::actingAs(User::factory()->create());
        Category::factory()->create();
        $recipes = Recipe::factory(2)->create();

        $response = $this->getJson('/api/recipes');
        $response->assertStatus(Response::HTTP_OK)->
        assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' =>[
                [
                    'id',
                    'type',
                    'attributes' =>[
                        "category",
                        "author",
                        "title",
                        "description",
                        "ingredients",
                        "instructions",
                        "image",
                        "tags",
                    ],
                ]
            ]
        ]);
    }

    public function test_show(): void{
        Sanctum::actingAs(User::factory()->create());
        Category::factory()->create();


        $recipe = Recipe::factory()->create();

        $response = $this->getJson('/api/recipes/' . $recipe->id);
        $response->assertStatus(Response::HTTP_OK) //200
        ->assertJsonStructure([
            'data' =>[
                'id',
                'type',
                'attributes' =>[
                    "category",
                        "author",
                        "title",
                        "description",
                        "ingredients",
                        "instructions",
                        "image",
                        "tags",
                ],
            ]
        ]);
    }

    public function test_destroy(): void{
        Sanctum::actingAs(User::factory()->create());
        Category::factory()->create();


        $recipe = Recipe::factory()->create();

        $response = $this->deleteJson('/api/recipes/' . $recipe->id);
        $response->assertStatus(Response::HTTP_NO_CONTENT); //200;
    }
}
