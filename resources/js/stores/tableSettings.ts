import { defineStore } from 'pinia';
import { ref, watch } from 'vue';
import { useLocalStorage } from '@vueuse/core';

export const useTableSettingsStore = defineStore('tableSettings', () => {
    // Use local storage to persist settings
    const tableStates = useLocalStorage<Record<string, {
        visibleColumns: string[];
        filters: Record<string, any>;
    }>>('table-settings', {});

    // Get saved state for a specific table
    const getTableState = (tableId: string) => {
        if (!tableStates.value[tableId]) {
            tableStates.value[tableId] = {
                visibleColumns: [],
                filters: {}
            };
        }
        return tableStates.value[tableId];
    };

    // Update visible columns for a table
    const setVisibleColumns = (tableId: string, columns: string[]) => {
        const state = getTableState(tableId);
        state.visibleColumns = columns;
        tableStates.value[tableId] = state;
    };

    // Save filter state for a table
    const saveFilters = (tableId: string, filters: Record<string, any>) => {
        const state = getTableState(tableId);
        state.filters = { ...state.filters, ...filters };
        tableStates.value[tableId] = state;
    };

    // Save current table state as a bookmark
    const saveBookmark = (tableId: string, bookmarkName: string, state: any) => {
        const bookmarks = JSON.parse(localStorage.getItem('table-bookmarks') || '{}');
        if (!bookmarks[tableId]) {
            bookmarks[tableId] = {};
        }
        bookmarks[tableId][bookmarkName] = state;
        localStorage.setItem('table-bookmarks', JSON.stringify(bookmarks));
    };

    // Get all bookmarks for a table
    const getBookmarks = (tableId: string) => {
        const bookmarks = JSON.parse(localStorage.getItem('table-bookmarks') || '{}');
        return bookmarks[tableId] || {};
    };

    // Apply a bookmark
    const applyBookmark = (tableId: string, bookmarkName: string) => {
        const bookmarks = JSON.parse(localStorage.getItem('table-bookmarks') || '{}');
        const bookmark = bookmarks[tableId]?.[bookmarkName];
        if (bookmark) {
            tableStates.value[tableId] = bookmark;
            return bookmark;
        }
        return null;
    };

    // Delete a bookmark
    const deleteBookmark = (tableId: string, bookmarkName: string) => {
        const bookmarks = JSON.parse(localStorage.getItem('table-bookmarks') || '{}');
        if (bookmarks[tableId]?.[bookmarkName]) {
            delete bookmarks[tableId][bookmarkName];
            localStorage.setItem('table-bookmarks', JSON.stringify(bookmarks));
            return true;
        }
        return false;
    };

    return {
        tableStates,
        getTableState,
        setVisibleColumns,
        saveFilters,
        saveBookmark,
        getBookmarks,
        applyBookmark,
        deleteBookmark,
    };
});
