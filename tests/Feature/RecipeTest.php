<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Tag;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;


class RecipeTest extends TestCase
{
    use RefreshDatabase,WithFaker;
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

    // public function test_store(): void {
    //     Sanctum::actingAs(User::factory()->create());
    //     $category = Category::factory()->create();
    //     $tag = Tag::factory()->create();
    
    //     $data = [
    //         'category_id' => $category->id,
    //         'title' => $this->faker->sentence,
    //         'description' => $this->faker->paragraph,
    //         'ingredients' => $this->faker->text,
    //         'instructions' => $this->faker->text,
    //         'tags' => $tag->id,
    //         'image' => UploadedFile::fake()->image('recipe.png'),
    //     ];
    
    //     $response = $this->postJson('/api/recipes/' . $data);
    //     $response->assertStatus(Response::HTTP_CREATED);
    // }

    public function test_store(): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();
    
        $data = [
            'category_id' => $category->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'ingredients' => $this->faker->text,
            'instructions' => $this->faker->text,
            'tags' => json_encode([$tag->id]), // Convertido a JSON porque daba error 
            'image' => UploadedFile::fake()->image('recipe.png'),
        ];
    
        $response = $this->postJson('/api/recipes', $data); // Corregida la URL
        $response->assertStatus(Response::HTTP_CREATED);
    
        // opcional pero es para una prueba buena
        // Verificar que la receta se guardó
        $this->assertDatabaseHas('recipes', [
            'category_id' => $category->id,
            'title' => $data['title'],
            'user_id' => $user->id,
        ]);
    
        // Verificar que la relación con tags se guardó
        $this->assertDatabaseHas('recipe_tag', [
            'tag_id' => $tag->id,
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

    public function test_update(): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $category = Category::factory()->create();
        $recipe = Recipe::factory()->create();
        $data = [
            'category_id' => $category->id,
            'title' => 'updated title',
            'description' => 'updated descrip',
            'ingredients' => $this->faker->text,
            'instructions' => $this->faker->text,

        ];
    
        $response = $this->putJson('/api/recipes/' .$recipe->id, $data); // Corregida la URL
        $response->assertStatus(Response::HTTP_OK);
    
        // opcional pero es para una prueba buena
        // Verificar que la receta se guardó
        $this->assertDatabaseHas('recipes', [
            'title' => 'updated title',
            'description' => 'updated descrip',
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
