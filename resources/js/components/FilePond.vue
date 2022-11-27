<template>
    <file-pond
        name="files"
        ref="pond"
        label-idle="اسحب وافلت الملفات هنا..."
        label-tap-to-cancel="اضغط هنا للإلغاء"
        label-tap-to-retry="اضغط هنا للإعادة"
        label-tap-to-undo="اضغط هنا للتراجع"
        label-file-processing="جاري رفع الملفات"
        label-file-processing-complete="تم رفع الملفات"
        v-bind:allow-multiple="true"
        accepted-file-types="image/jpeg, image/png"
        :server="serverConfig"
        chunkUploads="true"
        v-bind:files="myFiles"
        v-on:init="handleFilePondInit"
        v-on:ended="habdleFilePondEnd"
        v-on:error="habdleFilePondError"
    />
</template>

<script>
// Import Vue FilePond
import vueFilePond from "vue-filepond";

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import FilePond plugins
// Please note that you need to install these plugins separately
// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';
import axios from "axios";

// Create component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFilePoster,
    FilePondPluginImagePreview
);

export default {
    name: "FilePondComponent",
    props: {
        folder: {
            type: String,
            default: 'temp'
        },
        type: {
            type: String,
            default: 'images'
        },
        files: {
            type: Object,
            default: []
        }
    },
    data: function () {
        return {
            myFiles: [{
                source: "/storage/images/patients/1669151185373.png",
                options: {
                    type: 'local',
                    metadata: {
                        poster: '/storage/images/patients/1669151185373.png',
                        size: 432523
                    }
                },
                size: 12312
            },{
                source: "/storage/images/patients/1669151211771.png",
                options: {
                    type: 'local',
                    metadata: {
                        poster: '/storage/images/patients/1669151211771.png'
                    }
                }
            }],
            serverConfig: {
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    // fieldName is the name of the input field
                    // file is the actual file object to send
                    const formData = new FormData();
                    formData.append(fieldName, file, file.name);
                    formData.append('folder', this.folder);
                    formData.append('type', this.type);
                    // aborting the request
                    const CancelToken = axios.CancelToken
                    const source = CancelToken.source()
                    axios.post(`/api/upload`, formData, {
                        cancelToken: source.token,
                        onUploadProgress: (e) => {
                            // updating progress indicator
                            progress(e.lengthComputable, e.loaded, e.total)
                        }
                    })
                        .then(response => {
                            if (Array.isArray(response.data)) {
                                response.data.forEach(path => load(path))
                            } else
                                load(JSON.stringify(response.data))
                        })
                        .catch((thrown) => {
                            if (axios.isCancel(thrown)) {
                                console.log('Request canceled', thrown.message)
                            } else {
                                // handle error
                            }
                        });
                    // Should expose an abort method so the request can be cancelled
                    return {
                        abort: () => {
                            // This function is entered if the user has tapped the cancel button
                            source.cancel('Operation canceled by the user.')
                            abort();
                        },
                    };
                },
                revert: (uniqueFileId, load, error) => {
                    console.log(uniqueFileId)
                    const data = JSON.parse(uniqueFileId).path.split('/');
                    const name = data[data.length - 1];
                    const folder = data[data.length - 2];
                    const type = this.type;

                    axios.delete(`/api/upload/${folder}/${name}/${type}`, data).then(({date})=>{
                        bus.$emit('flash-message', {text: data.message, type: 'success'});
                    });

                    // Should call the load method when done, no parameters required
                    load();
                },
                remove: (uniqueFileId, load, error) => {
                    const data = uniqueFileId.split('/');
                    const name = data[data.length - 1];
                    const folder = data[data.length - 2];
                    const type = this.type;

                    axios.delete(`/api/upload/${folder}/${name}/${type}`, data).then(({date})=>{
                        bus.$emit('flash-message', {text: data.message, type: 'success'});
                    })

                    // Should call the load method when done, no parameters required
                    load();
                },
                // load: async (source, load, error, progress, abort, headers) => {
                //     progress(true, 0, 1024);
                //     const data = source.split('/');
                //     const name = data[data.length - 1];
                //     const folder = data[data.length - 2];
                //     const file = await axios.get(`/api/upload/${folder}/${name}`).then(res => res.data)
                //     // Should call the load method with a file object or blob when done
                //     load(file);
                //
                //     // Should expose an abort method so the request can be cancelled
                //     return {
                //         abort: () => {
                //             // User tapped cancel, abort our ongoing actions here
                //
                //             // Let FilePond know the request has been cancelled
                //             abort();
                //         },
                //     };
                // },
            }
        }
            ;
    },
    methods: {
        handleFilePondInit: function () {
            console.log("FilePond has initialized");

            // FilePond instance methods are available on `this.$refs.pond`
        },
        habdleFilePondEnd: function (data) {
            console.log("FilePond has enede", data);

            // FilePond instance methods are available on `this.$refs.pond`
        },
        habdleFilePondError: function (error) {
            console.log("FilePond has enede", error);

            // FilePond instance methods are available on `this.$refs.pond`
        },
    },
    components: {
        FilePond,
    },
}
</script>

<style scoped>

</style>
