import * as FilePond from "filepond"
import { FilePondErrorDescription, FilePondFile } from "filepond"
import { FilePond as FilePondInterface } from "filepond/types"

export interface ImageEditorResult {
    file?: File
}

let pond: FilePondInterface
export const initFilePond = () => {
    if (pond) {
        return
    }
    const defaultHeaders = api.getDefaultHeaders()
    delete defaultHeaders["Content-Type"]

    pond = FilePond.create()
    pond.setOptions({
        name: "file",
        server: {
            url: api.getURL("/filepond/api"),
            process: {
                url: "/process",
                headers: defaultHeaders,
                withCredentials: true,
            },
            revert: {
                url: "/process",
                headers: defaultHeaders,
                withCredentials: true,
            },
        },
        allowMultiple: true,
    })
}

export const uploadFile = async (file: File): Promise<FilePondFile> => {
    initFilePond()
    await pond.addFile(file)
    return new Promise((resolve, reject) => {
        pond.on("processfile", (error: FilePondErrorDescription, filePondFile: FilePondFile) => {
            if (filePondFile.file !== file) {
                return
            }
            if (error) {
                reject(error)
            } else {
                if (filePondFile.file === file) {
                    resolve(filePondFile)
                }
            }
        })
    })
}
