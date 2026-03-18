<?php

namespace Tests\Feature;

use App\Models\Gear;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GearUsageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_gear_index(): void
    {
        $user = User::factory()->create();
        Gear::factory()->forUser($user)->count(3)->shoes()->create();

        $response = $this->actingAs($user)->get(route('gear.index'));

        $response->assertOk();
    }

    public function test_user_can_create_gear(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('gear.store'), [
            'brand' => 'Nike',
            'model' => 'Vaporfly 3',
            'type' => 'shoes',
            'current_km' => 0,
            'max_km' => 700,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('gear.index'));
        $this->assertDatabaseHas('gears', [
            'user_id' => $user->id,
            'brand' => 'Nike',
            'model' => 'Vaporfly 3',
        ]);
    }

    public function test_gear_validation_requires_brand(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('gear.store'), [
            'model' => 'Vaporfly',
            'type' => 'shoes',
        ]);

        $response->assertSessionHasErrors('brand');
    }

    public function test_gear_validation_requires_valid_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('gear.store'), [
            'brand' => 'Nike',
            'model' => 'Test',
            'type' => 'invalid_type',
        ]);

        $response->assertSessionHasErrors('type');
    }

    public function test_gear_usage_percentage_calculation(): void
    {
        $gear = Gear::factory()->make(['current_km' => 350, 'max_km' => 700]);

        $this->assertEquals(50.0, $gear->usage_percentage);
    }

    public function test_gear_usage_percentage_is_null_without_max_km(): void
    {
        $gear = Gear::factory()->make(['current_km' => 350, 'max_km' => null]);

        $this->assertNull($gear->usage_percentage);
    }

    public function test_gear_usage_percentage_caps_at_100(): void
    {
        $gear = Gear::factory()->make(['current_km' => 800, 'max_km' => 700]);

        $this->assertEquals(100.0, $gear->usage_percentage);
    }

    public function test_gear_remaining_km_calculation(): void
    {
        $gear = Gear::factory()->make(['current_km' => 300, 'max_km' => 700]);

        $this->assertEquals(400.0, $gear->remaining_km);
    }

    public function test_gear_remaining_km_is_null_without_max_km(): void
    {
        $gear = Gear::factory()->make(['current_km' => 300, 'max_km' => null]);

        $this->assertNull($gear->remaining_km);
    }

    public function test_gear_remaining_km_does_not_go_negative(): void
    {
        $gear = Gear::factory()->make(['current_km' => 900, 'max_km' => 700]);

        $this->assertEquals(0.0, $gear->remaining_km);
    }

    public function test_user_can_update_gear(): void
    {
        $user = User::factory()->create();
        $gear = Gear::factory()->forUser($user)->create(['current_km' => 100]);

        $response = $this->actingAs($user)->patch(route('gear.update', $gear), [
            'brand' => 'Nike',
            'model' => 'Vaporfly 3',
            'type' => 'shoes',
            'current_km' => 250,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('gear.index'));
        $this->assertDatabaseHas('gears', ['id' => $gear->id, 'current_km' => 250]);
    }

    public function test_user_cannot_update_other_users_gear(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $gear = Gear::factory()->forUser($otherUser)->create();

        $response = $this->actingAs($user)->patch(route('gear.update', $gear), [
            'brand' => 'Adidas',
            'model' => 'Fake',
            'type' => 'shoes',
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_delete_gear(): void
    {
        $user = User::factory()->create();
        $gear = Gear::factory()->forUser($user)->create();

        $response = $this->actingAs($user)->delete(route('gear.destroy', $gear));

        $response->assertRedirect(route('gear.index'));
        $this->assertDatabaseMissing('gears', ['id' => $gear->id]);
    }

    public function test_unauthenticated_user_cannot_access_gear(): void
    {
        $response = $this->get(route('gear.index'));

        $response->assertRedirect(route('login'));
    }
}
