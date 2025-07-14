<?php

namespace Tests\Feature\Http\Controllers\Api\V2;


use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $recipes = Recipe::factory(5)->create();

        $response = $this->getJson('/api/v2/recipes');
        $response->assertStatus(Response::HTTP_OK)->
        assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data'  =>[],
            'links' =>[],
            'meta'  =>[],
        ]);
    }


}
