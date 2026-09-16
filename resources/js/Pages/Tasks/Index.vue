<script setup>
import { reactive } from 'vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps({
  tasks: Array,
});

const form = useForm({
  name: '',
  description: '',
});

function submit() {
  form.post('/tasks', {
    onSuccess: () => form.reset(),
  });
}

function toggleCompleted(task) {
  router.patch(`/tasks/${task.id}`, {
    completed: !task.completed,
  }, { preserveScroll: true });
}

function deleteTask(task) {
  if (confirm(`Naozaj vymazať úlohu "${task.name}"?`)) {
    router.delete(`/tasks/${task.id}`, { preserveScroll: true });
  }
}

const tagInputs = reactive({});

function addTag(task) {
  const tagName = tagInputs[task.id];
  if (!tagName) return;

  router.post(`/tasks/${task.id}/tags`, { tag: tagName }, {
    preserveScroll: true,
    onSuccess: () => { tagInputs[task.id] = ''; },
  });
}

function removeTag(task, tagName) {
  router.delete(`/tasks/${task.id}/tags`, {
    data: { tag: tagName },
    preserveScroll: true,
  });
}
</script>

<template>
  <div style="max-width: 700px; margin: 40px auto; font-family: sans-serif;">
    <h1>ToDo aplikácia</h1>

    <form @submit.prevent="submit" style="margin-bottom: 24px; display: flex; gap: 8px;">
      <input v-model="form.name" placeholder="Názov úlohy" required />
      <input v-model="form.description" placeholder="Popis (voliteľné)" />
      <button type="submit" :disabled="form.processing">Pridať úlohu</button>
    </form>
    <div v-if="form.errors.name" style="color: red; margin-top: -16px; margin-bottom: 16px;">{{ form.errors.name }}</div>

    <ul style="list-style: none; padding: 0;">
      <li v-for="task in tasks" :key="task.id" style="border: 1px solid #ddd; padding: 12px; margin-bottom: 8px; border-radius: 6px;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <input type="checkbox" :checked="task.completed" @change="toggleCompleted(task)" />
          <strong :style="{ textDecoration: task.completed ? 'line-through' : 'none' }">{{ task.name }}</strong>
          <button @click="deleteTask(task)" style="margin-left: auto;">Vymazať</button>
        </div>
        <p v-if="task.description" style="margin: 4px 0 0 26px; color: #555;">{{ task.description }}</p>

        <div style="margin: 8px 0 0 26px;">
          <span
            v-for="tag in task.tags"
            :key="tag.id"
            style="display: inline-block; background: #eee; border-radius: 12px; padding: 2px 10px; margin-right: 6px; font-size: 0.85em;"
          >
            {{ tag.name }}
            <button @click="removeTag(task, tag.name)" style="border: none; background: none; cursor: pointer;">×</button>
          </span>

          <input
            v-model="tagInputs[task.id]"
            placeholder="Nový tag"
            style="font-size: 0.85em; width: 100px;"
            @keyup.enter="addTag(task)"
          />
          <button @click="addTag(task)" style="font-size: 0.85em;">+ tag</button>
        </div>
      </li>
    </ul>

    <p v-if="tasks.length === 0">Zatiaľ žiadne úlohy.</p>
  </div>
</template>