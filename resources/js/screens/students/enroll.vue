<template>
    <div class="w-full">
        <loader v-if="loading"></loader>
        <div v-else class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center flex justify-center items-center mt-3">
                <span class="font-semibold mr-3">Step {{ step }} of {{ steps }}</span>
                <ul class="flex justify-center items-center">
                    <li v-for="i in steps" @click="step = i" class="cursor-pointer"><span
                        class="block rounded-full w-3 h-3 bg-gray-400 mr-2"
                        :class="{'bg-indigo-500':(step===i || stepsFinished.includes(i)),'shadow-outline-indigo':step===i}"></span>
                    </li>
                </ul>
            </div>
            <div class="px-4 py-3" v-if="step===1">
                <h2 class="font-bold text-xl">Select Course</h2>
                <div class="courses grid grid-cols-3 md:grid-cols-6 pt-2 gap-3">
                    <div v-for="course in courses"
                         class="rounded transition-shadow duration-100 shadow px-3 py-2 cursor-pointer"
                         :class="{'border border-gray-200 shadow-outline-purple':course.id === selectedCourse.id}"
                         @click="selectCourse(course)">
                        <h3 class="font-semibold text-lg text-gray-800">{{ course.title }}</h3>
                        <small class="text-sm">Hours: <span class="ml-1 text-red-500">{{ course.hours }}</span></small>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3" v-if="step===2">
                <h2 class="font-bold text-xl">Select Section for Course <b
                    class="italic text-indigo-700">{{ selectedCourse.title }}</b>
                </h2>
                <div class="courses grid grid-cols-3 md:grid-cols-4 pt-2 gap-3">
                    <div v-for="section in sections"
                         class="rounded transition-shadow duration-100 shadow px-3 py-2 cursor-pointer"
                         :class="{'border border-gray-200 shadow-outline-purple':section.id === selectedSection.id}"
                         @click="selectSection(section)">
                        <h3 class="font-semibold text-sm text-gray-800">NO: <span
                            class="font-medium text-gray-700">{{ section.section_no }}</span></h3>
                        <h3 class="font-semibold text-sm text-gray-800">Room: <span
                            class="font-medium text-gray-700">{{ section.room_no }}</span></h3>
                        <h3 class="font-semibold text-sm text-gray-800">Time: <span
                            class="font-medium text-gray-700">{{ section.time }}</span></h3>
                        <h3 class="font-semibold text-sm text-gray-800">Instructor: <span
                            class="ml-1 text-red-500 font-medium">{{ section.instructor.full_name }}</span></h3>
                    </div>
                </div>
                <div class="mt-3 ml-auto w-0">
                    <button
                        class="py-2 px-3 rounded shadow bg-gray-800 text-white transition-colors hover:bg-gray-700 duration-75"
                        @click="enroll()">Enroll
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import axios from "axios";

export default {
    data() {
        return {
            loading: false,
            courses: [],
            sections: [],
            steps: 2,
            stepsFinished: [],
            selectedCourse: {
                id: ''
            },
            selectedSection: {
                id: ''
            },
            step: 1
        }
    },
    mounted() {
        this.loading = true;
        axios.get('/api/student-courses?per_page=50&after=true').then(({data}) => {
            this.loading = false;
            this.courses = data.entries;
        })
    },
    methods: {
        selectCourse(course) {
            this.selectedCourse = course;
            if (this.selectedCourse && !this.stepsFinished.includes(1)) {
                this.stepsFinished.push(1);
            }
            axios.get(`/api/student-courses/${course.id}/sections`).then(({data}) => {
                this.sections = data;
                this.step = 2
            })
        },
        selectSection(section) {
            this.selectedSection = section;
            if (this.selectedSection && !this.stepsFinished.includes(2)) {
                this.stepsFinished.push(2);
            }
        },
        enroll() {
            this.loading = true;
            axios.post(`/api/student-courses/enroll`, {
                course_id: this.selectedCourse.id,
                section_id: this.selectedSection.id,
                section_no: this.selectedSection.section_no,
                instructor_id: this.selectedSection.instructor_id
            }).then(({data}) => {
                this.loading = false;
                bus.$emit('flash-message', {text: data.message, type: 'success'})
                setTimeout(() => {
                    location.reload();
                }, 1500)
            })
        }
    }
};
</script>
