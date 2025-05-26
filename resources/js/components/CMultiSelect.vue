<template>
  <div class="form-group">
    <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
    <div
      class="relative"
      @click.stop="toggleDropdown"
      @keydown.esc="isOpen = false"
      @keydown.down="onArrowDown"
      @keydown.up="onArrowUp"
      @keydown.enter="selectOption(highlightedIndex)"
    >
      <div
        class="form-control min-h-[42px] w-full px-3 py-2 bg-white border rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
        :class="{ 'border-red-500': hasError }"
      >
        <div class="flex flex-wrap gap-1">
          <div
            v-for="option in selectedOptions"
            :key="getOptionValue(option)"
            class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-sm flex items-center"
          >
            {{ getOptionLabel(option) }}
            <button
              type="button"
              class="mr-1 ltr:ml-1 ltr:mr-0 text-blue-500 hover:text-blue-700 focus:outline-none"
              @click.stop="removeOption(option)"
            >
              &times;
            </button>
          </div>
          <div v-if="selectedOptions.length === 0" class="text-gray-400">
            {{ placeholder || 'Select options' }}
          </div>
        </div>
      </div>
      <div v-if="isOpen" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
        <div v-if="filteredOptions.length === 0" class="px-4 py-2 text-gray-500">
          No options available
        </div>
        <div
          v-for="(option, index) in filteredOptions"
          :key="getOptionValue(option)"
          class="px-4 py-2 cursor-pointer hover:bg-blue-50"
          :class="{ 'bg-blue-50': index === highlightedIndex }"
          @click.stop="selectOption(index)"
        >
          {{ getOptionLabel(option) }}
        </div>
      </div>
    </div>
    <div v-if="hasError" class="mt-1 text-sm text-red-600">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

interface Props {
  options: any[];
  label?: string;
  placeholder?: string;
  optionLabel?: string | ((option: any) => string);
  optionValue?: string | ((option: any) => any);
  errors?: Record<string, any>;
  name?: string;
}

const props = withDefaults(defineProps<Props>(), {
  options: () => [],
  optionLabel: 'label',
  optionValue: 'value',
  errors: () => ({}),
  name: '',
});

const model = defineModel<any[]>({ default: [] });

// Using defineModel instead of emits

const isOpen = ref(false);
const highlightedIndex = ref(0);

const selectedOptions = computed(() => {
  return props.options.filter(option => model.value.includes(getOptionValue(option)));
});

const filteredOptions = computed(() => {
  return props.options.filter(option => 
    !model.value.includes(getOptionValue(option))
  );
});

const hasError = computed(() => {
  return props.name && props.errors && props.errors[props.name];
});

const errorMessage = computed(() => {
  if (hasError.value) {
    return props.errors[props.name];
  }
  return '';
});

function getOptionLabel(option: any): string {
  if (typeof option === 'string') return option;
  
  // Handle case where optionLabel is a function
  if (typeof props.optionLabel === 'function') {
    return props.optionLabel(option);
  }
  
  return option[props.optionLabel as string] || '';
}

function getOptionValue(option: any): any {
  if (typeof option === 'string') return option;
  
  // Handle case where optionValue is a function
  if (typeof props.optionValue === 'function') {
    return props.optionValue(option);
  }
  
  return option[props.optionValue];
}

function toggleDropdown() {
  isOpen.value = !isOpen.value;
  if (isOpen.value) {
    highlightedIndex.value = 0;
  }
}

function selectOption(index: number) {
  if (index >= 0 && index < filteredOptions.value.length) {
    const option = filteredOptions.value[index];
    model.value = [...model.value, getOptionValue(option)];
  }
}

function removeOption(option: any) {
  const value = getOptionValue(option);
  model.value = model.value.filter(v => v !== value);
}

function onArrowDown() {
  if (!isOpen.value) {
    isOpen.value = true;
    return;
  }
  
  highlightedIndex.value = (highlightedIndex.value + 1) % filteredOptions.value.length;
}

function onArrowUp() {
  if (!isOpen.value) {
    isOpen.value = true;
    return;
  }
  
  highlightedIndex.value = (highlightedIndex.value - 1 + filteredOptions.value.length) % filteredOptions.value.length;
}

// Close dropdown when clicking outside
function handleClickOutside(event: MouseEvent) {
  const target = event.target as HTMLElement;
  if (target && !target.closest('.form-group')) {
    isOpen.value = false;
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
