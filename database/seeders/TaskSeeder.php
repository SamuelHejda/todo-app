<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Tag;

class TaskSeeder extends Seeder
{
    public function run(): void
{
    $urgent = Tag::firstOrCreate(['name' => 'urgentné']);
    $home = Tag::firstOrCreate(['name' => 'domov']);
    $work = Tag::firstOrCreate(['name' => 'práca']);

    $task1 = Task::create([
        'name' => 'Kúpiť mlieko',
        'description' => 'V obchode pri práci',
        'completed' => false,
    ]);
    $task1->tags()->attach($home->id);

    $task2 = Task::create([
        'name' => 'Dokončiť Laravel zadanie',
        'description' => 'ToDo aplikácia pre WAME',
        'completed' => false,
    ]);
    $task2->tags()->attach([$urgent->id, $work->id]);

    $task3 = Task::create([
        'name' => 'Zavolať doktorovi',
        'description' => null,
        'completed' => true,
    ]);
    $task3->tags()->attach($urgent->id);

    Task::create([
        'name' => 'Upratať izbu',
        'description' => 'Cez víkend',
        'completed' => false,
    ]);
}
}
