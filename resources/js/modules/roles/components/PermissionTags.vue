<template>
  <div class="flex flex-wrap gap-1">
    <template v-if="value && value.length">
      <!-- Show first 5 tags -->
      <span 
        v-for="permission in visiblePermissions" 
        :key="permission.id" 
        class="px-2 py-0.5 text-xs rounded-full bg-cyan-100 text-cyan-800"
      >
        {{ $t(`permissions.${permission.slug}`) }}
      </span>
      
      <!-- Show more button if there are more than 5 permissions -->
      <div v-if="hasMorePermissions" class="relative">
        <button 
          ref="buttonRef"
          @click="togglePopover" 
          class="px-2 py-0.5 text-xs rounded-full bg-cyan-600 text-white hover:bg-cyan-700 transition-colors"
        >
          +{{ value.length - maxVisibleTags }} more
        </button>
        
        <!-- Popover with all permissions -->
        <div 
          v-if="showPopover" 
          ref="popoverRef"
          class="absolute z-50 top-full mt-1 left-0 bg-white shadow-lg rounded-md p-2 border border-gray-200 w-96"
        >
          <div class="flex flex-wrap gap-1 max-h-48 overflow-y-auto">
            <span 
              v-for="permission in value" 
              :key="permission.id" 
              class="px-2 py-0.5 text-xs rounded-full bg-cyan-100 text-cyan-800 mb-1"
            >
              {{ $t(`permissions.${permission.slug}`) }}
            </span>
          </div>
          <div class="mt-2 text-right">
            <button 
              @click="togglePopover" 
              class="text-xs text-gray-600 hover:text-gray-800"
            >
              {{ $t('global.actions.close') }}
            </button>
          </div>
        </div>
      </div>
    </template>
    <span v-else class="text-gray-500 text-sm">{{ $t('roles.noPermissions') }}</span>
  </div>
</template>

<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

interface Permission {
  id: number;
  name: string;
  slug: string;
}

const props = defineProps<{
  value: Permission[];
}>();

const { t } = useI18n();
const showPopover = ref(false);
const maxVisibleTags = 5;
const popoverRef = ref<HTMLElement | null>(null);
const buttonRef = ref<HTMLButtonElement | null>(null);
const dialogRef = ref<HTMLElement | null>(null);

// Compute visible permissions (max 5)
const visiblePermissions = computed(() => {
  if (!props.value) return [];
  return props.value.slice(0, maxVisibleTags);
});

// Check if there are more permissions than the visible limit
const hasMorePermissions = computed(() => {
  return props.value && props.value.length > maxVisibleTags;
});

// Toggle popover visibility
const togglePopover = (event: MouseEvent) => {
  event.stopPropagation();
  showPopover.value = !showPopover.value;
};

// Close popover when clicking outside
const handleClickOutside = (event: MouseEvent) => {
  if (showPopover.value) {
    // Check if click is outside both the popover and the button
    if (
      popoverRef.value && 
      !popoverRef.value.contains(event.target as Node) &&
      buttonRef.value && 
      !buttonRef.value.contains(event.target as Node)
    ) {
      showPopover.value = false;
    }
  }
};

// Add and remove event listeners
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
