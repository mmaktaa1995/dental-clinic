<template>
    <svg id="hove" :key="key" dir="rtl" width="400" height="600" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 50 400 600">
        <defs>
            <filter id="redEffect">
                <feColorMatrix
                    type="matrix"
                    values="1 0 0 0 0
                0 0 0 0 0
                0 0 0 0 0
                0 0 0 0.65 0"
                />
            </filter>
        </defs>
        <defs>
            <filter id="greenEffect">
                <feColorMatrix
                    type="matrix"
                    values="0 0 0 0 0
                1 0 0 0 0
                0 0 0 0 0
                0 0 0 0.65 0"
                />
            </filter>
        </defs>
        <template v-for="element in teethElements" :key="element.id">
            <!-- Image -->
            <image
                v-if="element.type === 'image'"
                :id="element.id"
                :height="element.height"
                :width="element.width"
                :x="element.x"
                :y="element.y"
                :style="{ filter: selectedTeethColor(element.index) }"
                :xlink:href="element.src"
                class=""
                :class="{ 'cursor-pointer hover:opacity-55': !clickDisabled(element.index) }"
                @click="selectTooth(element.index)"
            ></image>

            <!-- Text -->
            <text v-else :x="element.x" :y="element.y - 2" :fill="element.fill" :class="[element['class']]">
                {{ element.text }}
            </text>
        </template>
        <g class="group" transform="translate(100,-50)"></g>
    </svg>
</template>
<script setup lang="ts">
import { reactive, ref } from "vue"

const selectedTeeth = defineModel<Record<any, any>>({ required: true })
const treatedTeeth = defineModel<Record<any, any>>("treatedTeeth", { required: false, default: {} })

const props = withDefaults(
    defineProps<{
        treatMode?: boolean
        // To disable click events and only show red background
        affectedTeeth?: Record<any, any>
    }>(),
    {
        treatMode: false,
        affectedTeeth: undefined,
    },
)

const key = ref(new Date().valueOf())

const teethElements = [
    {
        type: "image",
        id: 1,
        src: "/images/teeth/1.png",
        hover_src: "/images/teeth/1_hover.png",
        x: 90,
        y: 300,
        width: 38,
        height: 38,
        index: 1,
    },
    { type: "text", text: "1", x: 83, y: 325, fill: "#000" },

    {
        type: "image",
        id: 2,
        src: "/images/teeth/2.png",
        hover_src: "/images/teeth/2_hover.png",
        x: 90,
        y: 270,
        width: 38,
        height: 38,
        index: 2,
    },
    { type: "text", text: "2", x: 82, y: 295, fill: "#000" },

    {
        type: "image",
        id: 3,
        src: "/images/teeth/3.png",
        hover_src: "/images/teeth/3_hover.png",
        x: 90,
        y: 240,
        width: 38,
        height: 38,
        index: 3,
    },
    { type: "text", text: "3", x: 83, y: 260, fill: "#000" },

    {
        type: "image",
        id: 4,
        src: "/images/teeth/4.png",
        hover_src: "/images/teeth/4_hover.png",
        x: 100,
        y: 211,
        width: 38,
        height: 38,
        index: 4,
    },
    { type: "text", text: "4", x: 93, y: 230, fill: "#000" },

    {
        type: "image",
        id: 5,
        src: "/images/teeth/5.png",
        hover_src: "/images/teeth/5_hover.png",
        x: 110,
        y: 186,
        width: 38,
        height: 38,
        index: 5,
    },
    { type: "text", text: "5", x: 106, y: 199, fill: "#000" },

    {
        type: "image",
        id: 6,
        src: "/images/teeth/6.png",
        hover_src: "/images/teeth/6_hover.png",
        x: 126,
        y: 162,
        width: 38,
        height: 38,
        index: 6,
    },
    { type: "text", text: "6", x: 128, y: 169, fill: "#000" },

    {
        type: "image",
        id: 7,
        src: "/images/teeth/7.png",
        hover_src: "/images/teeth/7_hover.png",
        x: 143,
        y: 144,
        width: 38,
        height: 38,
        index: 7,
    },
    { type: "text", text: "7", x: 150, y: 147, fill: "#000" },

    {
        type: "image",
        id: 8,
        src: "/images/teeth/8.png",
        hover_src: "/images/teeth/8_hover.png",
        x: 166,
        y: 140,
        width: 38,
        height: 38,
        index: 8,
    },
    { type: "text", text: "8", x: 185, y: 140, fill: "#000" },

    {
        type: "image",
        id: 9,
        src: "/images/teeth/9.png",
        hover_src: "/images/teeth/9_hover.png",
        x: 198,
        y: 140,
        width: 38,
        height: 38,
        index: 9,
    },
    { type: "text", text: "9", x: 217, y: 140, fill: "#000" },

    {
        type: "image",
        id: 10,
        src: "/images/teeth/10.png",
        hover_src: "/images/teeth/10_hover.png",
        x: 227,
        y: 149,
        width: 38,
        height: 38,
        index: 10,
    },
    { type: "text", text: "10", x: 258, y: 154, fill: "#000" },

    {
        type: "image",
        id: 11,
        src: "/images/teeth/11.png",
        hover_src: "/images/teeth/11_hover.png",
        x: 242,
        y: 168,
        width: 38,
        height: 38,
        index: 11,
    },
    { type: "text", text: "11", x: 285, y: 177, fill: "#000" },

    {
        type: "image",
        id: 12,
        src: "/images/teeth/12.png",
        hover_src: "/images/teeth/12_hover.png",
        x: 255,
        y: 188,
        width: 38,
        height: 38,
        index: 12,
    },
    { type: "text", text: "12", x: 302, y: 203, fill: "#000" },

    {
        type: "image",
        id: 13,
        src: "/images/teeth/13.png",
        hover_src: "/images/teeth/13_hover.png",
        x: 265,
        y: 210,
        width: 38,
        height: 38,
        index: 13,
    },
    { type: "text", text: "13", x: 316, y: 233, fill: "#000" },

    {
        type: "image",
        id: 14,
        src: "/images/teeth/14.png",
        hover_src: "/images/teeth/14_hover.png",
        x: 275,
        y: 237,
        width: 38,
        height: 38,
        index: 14,
    },
    { type: "text", text: "14", x: 327, y: 262, fill: "#000" },

    {
        type: "image",
        id: 15,
        src: "/images/teeth/15.png",
        hover_src: "/images/teeth/15_hover.png",
        x: 280,
        y: 268,
        width: 38,
        height: 38,
        index: 15,
    },
    { type: "text", text: "15", x: 332, y: 294, fill: "#000" },

    {
        type: "image",
        id: 16,
        src: "/images/teeth/16.png",
        hover_src: "/images/teeth/16_hover.png",
        x: 280,
        y: 300,
        width: 38,
        height: 38,
        index: 16,
    },
    { type: "text", text: "16", x: 330, y: 330, fill: "#000" },

    {
        type: "image",
        id: 17,
        src: "/images/teeth/17.png",
        hover_src: "/images/teeth/17_hover.png",
        x: 280,
        y: 350,
        width: 38,
        height: 38,
        index: 17,
    },
    { type: "text", text: "17", x: 330, y: 374, fill: "#000" },

    {
        type: "image",
        id: 18,
        src: "/images/teeth/18.png",
        hover_src: "/images/teeth/18_hover.png",
        x: 280,
        y: 384,
        width: 38,
        height: 38,
        index: 18,
    },
    { type: "text", text: "18", x: 330, y: 412, fill: "#000" },

    {
        type: "image",
        id: 19,
        src: "/images/teeth/19.png",
        hover_src: "/images/teeth/19_hover.png",
        x: 277,
        y: 417,
        width: 38,
        height: 38,
        index: 19,
    },
    { type: "text", text: "19", x: 325, y: 451, fill: "#000" },

    {
        type: "image",
        id: 20,
        src: "/images/teeth/20.png",
        hover_src: "/images/teeth/20_hover.png",
        x: 266,
        y: 450,
        width: 38,
        height: 38,
        index: 20,
    },
    { type: "text", text: "20", x: 320, y: 487, fill: "#000" },

    {
        type: "image",
        id: 21,
        src: "/images/teeth/21.png",
        hover_src: "/images/teeth/21_hover.png",
        x: 252,
        y: 480,
        width: 38,
        height: 38,
        index: 21,
    },
    { type: "text", text: "21", x: 292, y: 521, fill: "#000" },

    {
        type: "image",
        id: 22,
        src: "/images/teeth/22.png",
        hover_src: "/images/teeth/22_hover.png",
        x: 230,
        y: 498,
        width: 38,
        height: 38,
        index: 22,
    },
    { type: "text", text: "22", x: 261, y: 542, fill: "#000" },

    {
        type: "image",
        id: 23,
        src: "/images/teeth/23.png",
        hover_src: "/images/teeth/23_hover.png",
        x: 211,
        y: 505,
        width: 38,
        height: 38,
        index: 23,
    },
    { type: "text", text: "23", x: 238, y: 554, fill: "#000" },

    {
        type: "image",
        id: 24,
        src: "/images/teeth/24.png",
        hover_src: "/images/teeth/24_hover.png",
        x: 190,
        y: 507,
        width: 38,
        height: 38,
        index: 24,
    },
    { type: "text", text: "24", x: 218, y: 555, fill: "#000" },

    {
        type: "image",
        id: 25,
        src: "/images/teeth/25.png",
        hover_src: "/images/teeth/25_hover.png",
        x: 170,
        y: 507,
        width: 38,
        height: 38,
        index: 25,
    },
    { type: "text", text: "25", x: 194, y: 555, fill: "#000" },

    {
        type: "image",
        id: 26,
        src: "/images/teeth/26.png",
        hover_src: "/images/teeth/26_hover.png",
        x: 144,
        y: 502,
        width: 38,
        height: 38,
        index: 26,
    },
    { type: "text", text: "26", x: 164, y: 550, fill: "#000" },

    {
        type: "image",
        id: 27,
        src: "/images/teeth/27.png",
        hover_src: "/images/teeth/27_hover.png",
        x: 124,
        y: 490,
        width: 38,
        height: 38,
        index: 27,
    },
    { type: "text", text: "27", x: 135, y: 535, fill: "#000" },

    {
        type: "image",
        id: 28,
        src: "/images/teeth/28.png",
        hover_src: "/images/teeth/28_hover.png",
        x: 115,
        y: 470,
        width: 38,
        height: 38,
        index: 28,
    },
    { type: "text", text: "28", x: 115, y: 510, fill: "#000" },

    {
        type: "image",
        id: 29,
        src: "/images/teeth/29.png",
        hover_src: "/images/teeth/29_hover.png",
        x: 100,
        y: 445,
        width: 38,
        height: 38,
        index: 29,
    },
    { type: "text", text: "29", x: 100, y: 480, fill: "#000" },

    {
        type: "image",
        id: 30,
        src: "/images/teeth/30.png",
        hover_src: "/images/teeth/30_hover.png",
        x: 90,
        y: 413,
        width: 38,
        height: 38,
        index: 30,
    },
    { type: "text", text: "30", x: 85, y: 446, fill: "#000" },

    {
        type: "image",
        id: 31,
        src: "/images/teeth/31.png",
        hover_src: "/images/teeth/31_hover.png",
        x: 85,
        y: 380,
        width: 38,
        height: 38,
        index: 31,
    },
    { type: "text", text: "31", x: 80, y: 410, fill: "#000" },

    {
        type: "image",
        id: 32,
        src: "/images/teeth/32.png",
        hover_src: "/images/teeth/32_hover.png",
        x: 88,
        y: 350,
        width: 38,
        height: 38,
        index: 32,
    },
    { type: "text", text: "32", x: 84, y: 375, fill: "#000" },

    {
        type: "text",
        text: "Left",
        x: 330,
        y: 350,
        fill: "#342512",
        class: "horizontal",
    },
    {
        type: "text",
        text: "Right",
        x: 96,
        y: 350,
        fill: "#342512",
        class: "horizontal",
    },
    {
        type: "text",
        text: "Top",
        x: 210,
        y: 240,
        fill: "#342512",
        class: "vertical",
    },
    {
        type: "text",
        text: "Bottom",
        x: 220,
        y: 470,
        fill: "#342512",
        class: "vertical",
    },
]
const newTreatedTeeth = reactive({})

const clickDisabled = (index) => {
    if (props.affectedTeeth && props.affectedTeeth[index]) {
        return true
    }
    return (!props.treatMode && treatedTeeth?.value[index]) || (props.treatMode && !selectedTeeth.value[index]) || (props.treatMode && treatedTeeth?.value[index] && !newTreatedTeeth[index])
}
const selectTooth = (index) => {
    if (clickDisabled(index)) {
        return
    }
    if (selectedTeeth.value[index]) {
        if (props.treatMode) {
            if (treatedTeeth?.value[index]) {
                delete newTreatedTeeth[index]
                delete treatedTeeth.value[index]
            } else {
                treatedTeeth.value[index] = index
                newTreatedTeeth[index] = index
            }
        } else {
            delete selectedTeeth.value[index]
        }
    } else {
        selectedTeeth.value[index] = index
    }
    key.value = new Date().valueOf()
}
const isToothSelected = (index) => {
    return !!selectedTeeth.value[index]
}

const selectedTeethColor = (index) => {
    if (treatedTeeth?.value?.[index]) {
        return "url(#greenEffect)"
    } else if (isToothSelected(index) || (props.affectedTeeth && props.affectedTeeth[index])) {
        return "url(#redEffect)"
    } else {
        return ""
    }
}
</script>
<style scoped></style>
