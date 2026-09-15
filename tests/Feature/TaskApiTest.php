<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can create a task', function () {
    $response = $this->actingAs($this->user)->postJson('/api/tasks', [
        'name' => 'Test task',
        'description' => 'Test description',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment(['name' => 'Test task']);

    $this->assertDatabaseHas('tasks', ['name' => 'Test task']);
});

it('can update a task', function () {
    $task = Task::create(['name' => 'Original name']);

    $response = $this->actingAs($this->user)->putJson("/api/tasks/{$task->id}", [
        'name' => 'Updated name',
        'completed' => true,
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => 'Updated name']);

    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'name' => 'Updated name']);
});

it('can delete a task', function () {
    $task = Task::create(['name' => 'To be deleted']);

    $response = $this->actingAs($this->user)->deleteJson("/api/tasks/{$task->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('rejects unauthenticated requests', function () {
    $response = $this->getJson('/api/tasks');

    $response->assertStatus(401);
});