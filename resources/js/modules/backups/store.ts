import { acceptHMRUpdate } from "pinia"
import { defineEntryListStore } from "@/store/factories/entryListStore"
import { api } from "@/logic/api"

export interface BackupEntry {
    filename: string
    size: string
    created_at: string
}

export const useBackupsStore = defineEntryListStore("backups-store", {
    state: () => {
        return {
            entries: null as null | BackupEntry[],
            isLoading: true,
            query: "",
            pagination: {
                page: 1,
                last_page: 1,
                total: 0,
                per_page: 15,
            },
            order: {
                by: "created_at",
                desc: true,
            },
            dataLoadedCallbacks: [],
            isCreatingBackup: false,
            isRestoringBackup: false,
            restoringFilename: '',
        }
    },
    getters: {
        configWatcher(): any[] {
            return [this.pagination.page, this.pagination.per_page, this.query, this.order.by, this.order.desc]
        },
        entryListRequestParams(): Record<string, any> {
            return {
                query: this.query,
                page: this.pagination.page,
                per_page: this.pagination.per_page,
                order: this.order,
            }
        },
    },
    actions: {
        async createBackup() {
            try {
                this.isCreatingBackup = true
                const response = await api.post('/backups/create')
                return response.data
            } catch (error) {
                throw error
            } finally {
                this.isCreatingBackup = false
            }
        },
        
        async restoreBackup(filename: string) {
            try {
                this.isRestoringBackup = true
                const response = await api.post('/backups/restore', { filename })
                return response.data
            } catch (error) {
                throw error
            } finally {
                this.isRestoringBackup = false
            }
        },
        
        async deleteBackup(filename: string) {
            try {
                const response = await api.delete(`/backups/${filename}`)
                return response.data
            } catch (error) {
                throw error
            }
        },
        
        // Set the filename being restored
        setRestoringFilename(filename: string) {
            this.restoringFilename = filename;
        },
        
        getDownloadUrl(filename: string): string {
            return `${import.meta.env.VITE_API_BASE_URL}/backups/download/${filename}`
        }
    }
})

if (import.meta.hot) {
    // @ts-ignore
    import.meta.hot.accept(acceptHMRUpdate(useBackupsStore, import.meta.hot))
}
