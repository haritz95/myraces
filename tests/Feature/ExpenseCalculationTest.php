<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\Race;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_expenses_index(): void
    {
        $user = User::factory()->create();
        Expense::factory()->forUser($user)->count(3)->create();

        $response = $this->actingAs($user)->get(route('expenses.index'));

        $response->assertOk();
    }

    public function test_user_can_create_expense(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('expenses.store'), [
            'amount' => 45.00,
            'currency' => 'EUR',
            'category' => 'registration',
            'description' => 'Carrera popular Madrid',
            'date' => '2026-03-15',
        ]);

        $response->assertRedirect(route('expenses.index'));
        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'amount' => 45.00,
            'category' => 'registration',
        ]);
    }

    public function test_expense_validation_requires_amount(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('expenses.store'), [
            'currency' => 'EUR',
            'category' => 'registration',
            'date' => '2026-03-15',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_expense_validation_requires_positive_amount(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('expenses.store'), [
            'amount' => -10,
            'currency' => 'EUR',
            'category' => 'registration',
            'date' => '2026-03-15',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_expense_validation_requires_valid_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('expenses.store'), [
            'amount' => 20.00,
            'currency' => 'EUR',
            'category' => 'invalid_category',
            'date' => '2026-03-15',
        ]);

        $response->assertSessionHasErrors('category');
    }

    public function test_user_can_update_expense(): void
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->forUser($user)->create(['amount' => 30.00]);

        $response = $this->actingAs($user)->patch(route('expenses.update', $expense), [
            'amount' => 55.00,
            'currency' => 'EUR',
            'category' => 'travel',
            'date' => '2026-03-15',
        ]);

        $response->assertRedirect(route('expenses.index'));
        $this->assertDatabaseHas('expenses', ['id' => $expense->id, 'amount' => 55.00]);
    }

    public function test_user_cannot_update_other_users_expense(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $expense = Expense::factory()->forUser($otherUser)->create();

        $response = $this->actingAs($user)->patch(route('expenses.update', $expense), [
            'amount' => 999.00,
            'currency' => 'EUR',
            'category' => 'travel',
            'date' => '2026-03-15',
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_delete_expense(): void
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->forUser($user)->create();

        $response = $this->actingAs($user)->delete(route('expenses.destroy', $expense));

        $response->assertRedirect(route('expenses.index'));
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

    public function test_user_cannot_delete_other_users_expense(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $expense = Expense::factory()->forUser($otherUser)->create();

        $response = $this->actingAs($user)->delete(route('expenses.destroy', $expense));

        $response->assertForbidden();
        $this->assertDatabaseHas('expenses', ['id' => $expense->id]);
    }

    public function test_yearly_total_calculation_is_correct(): void
    {
        $user = User::factory()->create();

        Expense::factory()->forUser($user)->create(['amount' => 45.00, 'date' => now()->format('Y-m-d')]);
        Expense::factory()->forUser($user)->create(['amount' => 120.50, 'date' => now()->format('Y-m-d')]);
        Expense::factory()->forUser($user)->create(['amount' => 30.00, 'date' => now()->subYear()->format('Y-m-d')]);

        $yearlyTotal = $user->expenses()->whereYear('date', now()->year)->sum('amount');

        $this->assertEquals(165.50, (float) $yearlyTotal);
    }

    public function test_expense_can_be_linked_to_race(): void
    {
        $user = User::factory()->create();
        $race = Race::factory()->for($user)->create();

        $response = $this->actingAs($user)->post(route('expenses.store'), [
            'amount' => 45.00,
            'currency' => 'EUR',
            'category' => 'registration',
            'date' => '2026-03-15',
            'race_id' => $race->id,
        ]);

        $response->assertRedirect(route('expenses.index'));
        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'race_id' => $race->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_expenses(): void
    {
        $response = $this->get(route('expenses.index'));

        $response->assertRedirect(route('login'));
    }
}
