<?php

namespace Tests\Feature;

use App\Models\PersonalRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonalRecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_personal_records(): void
    {
        $user = User::factory()->create();
        PersonalRecord::factory()->forUser($user)->count(3)->create();

        $response = $this->actingAs($user)->get(route('personal-records.index'));

        $response->assertOk();
    }

    public function test_user_can_create_personal_record(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('personal-records.store'), [
            'distance_label' => '10K',
            'distance_km' => 10.0,
            'time_seconds' => 2880,
            'date' => '2026-03-15',
            'location' => 'Madrid',
        ]);

        $response->assertRedirect(route('personal-records.index'));
        $this->assertDatabaseHas('personal_records', [
            'user_id' => $user->id,
            'distance_label' => '10K',
            'time_seconds' => 2880,
        ]);
    }

    public function test_personal_record_validation_requires_distance_label(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('personal-records.store'), [
            'time_seconds' => 2880,
            'date' => '2026-03-15',
        ]);

        $response->assertSessionHasErrors('distance_label');
    }

    public function test_personal_record_validation_requires_time_seconds(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('personal-records.store'), [
            'distance_label' => '5K',
            'date' => '2026-03-15',
        ]);

        $response->assertSessionHasErrors('time_seconds');
    }

    public function test_personal_record_formatted_time_without_hours(): void
    {
        $record = PersonalRecord::factory()->make(['time_seconds' => 1560]); // 26 minutes

        $this->assertEquals('26:00', $record->formatted_time);
    }

    public function test_personal_record_formatted_time_with_hours(): void
    {
        $record = PersonalRecord::factory()->make(['time_seconds' => 5400]); // 1h 30min

        $this->assertEquals('1:30:00', $record->formatted_time);
    }

    public function test_personal_record_pace_calculation(): void
    {
        $record = PersonalRecord::factory()->make([
            'time_seconds' => 3000, // 50 minutes
            'distance_km' => 10.0,
        ]);

        $this->assertEquals('5:00 /km', $record->pace);
    }

    public function test_personal_record_pace_is_null_without_distance(): void
    {
        $record = PersonalRecord::factory()->make([
            'time_seconds' => 3000,
            'distance_km' => null,
        ]);

        $this->assertNull($record->pace);
    }

    public function test_user_can_delete_personal_record(): void
    {
        $user = User::factory()->create();
        $record = PersonalRecord::factory()->forUser($user)->create();

        $response = $this->actingAs($user)->delete(route('personal-records.destroy', $record));

        $response->assertRedirect(route('personal-records.index'));
        $this->assertDatabaseMissing('personal_records', ['id' => $record->id]);
    }

    public function test_user_cannot_delete_other_users_personal_record(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $record = PersonalRecord::factory()->forUser($otherUser)->create();

        $response = $this->actingAs($user)->delete(route('personal-records.destroy', $record));

        $response->assertForbidden();
        $this->assertDatabaseHas('personal_records', ['id' => $record->id]);
    }

    public function test_unauthenticated_user_cannot_access_personal_records(): void
    {
        $response = $this->get(route('personal-records.index'));

        $response->assertRedirect(route('login'));
    }
}
