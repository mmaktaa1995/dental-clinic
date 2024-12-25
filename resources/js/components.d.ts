import FlashMessage from "@/components/FlashMessage.vue"
import restore from "@/components/icons/Restore.vue"
import Money from "@/components/icons/Money.vue"
import Expenses from "@/components/icons/Expenses.vue"
import InformationCircle from "@/components/icons/InformationCircle.vue"
import XCircle from "@/components/icons/XCircle.vue"
import Terminal from "@/components/icons/Terminal.vue"
import ChartBar from "@/components/icons/ChartBar.vue"
import SearchIcon from "@/components/icons/Search.vue"
import Delete from "@/components/icons/Delete.vue"
import Debit from "@/components/icons/Debit.vue"
import Edit from "@/components/icons/Edit.vue"
import Eye from "@/components/icons/Eye.vue"
import ChevronRight from "@/components/icons/ChevronRight.vue"
import ClipboardCopy from "@/components/icons/ClipboardCopy.vue"
import List from "@/components/icons/List.vue"
import File from "@/components/icons/File.vue"
import Calendar from "@/components/icons/Calendar.vue"
import Flag from "@/components/icons/Flag.vue"
import Loader from "@/components/icons/Loader.vue"
import LoaderComponent from "@/components/Loader.vue"
import DotsVertical from "@/components/icons/DotsVertical.vue"
import DesktopComputer from "@/components/icons/DesktopComputer.vue"
import Exclamation from "@/components/icons/Exclamation.vue"
import users from "@/components/icons/Users.vue"
import Bars from "@/components/icons/Bars.vue"
import Collection from "@/components/icons/Collection.vue"
import AsyncButton from "@/components/AsyncButton.vue"
import BarChart from "@/components/BarChart.vue"
import PieChart from "@/components/PieChart.vue"
import PolarChart from "@/components/ApexCharts/PolarChart.vue"
import LineChart from "@/components/ApexCharts/LineChart.vue"
import FilePond from "@/components/FilePond.vue"
import Search from "@/components/Search.vue"
import SearchDetails from "@/components/SearchDetails.vue"
import SearchEmptyResults from "@/components/SearchEmptyResults.vue"
import Metric from "@/components/Metric.vue"
import Pagination from "@/components/Pagination.vue"
import ArrowDown from "@/components/icons/ArrowDown.vue"
import ArrowUp from "@/components/icons/ArrowUp.vue"
import Refresh from "@/components/icons/Refresh.vue"
import Cloud from "@/components/icons/Cloud.vue"
import DocumentIcon from "@/components/icons/DocumentIcon.vue"
import ConfirmModal from "@/components/ConfirmModal.vue"
import Dialog from "@/components/Dialog.vue"
import CButton from "@/components/CButton.vue"
import CFullCalendar from "@/components/CFullCalendar.vue"
import NavigationDrawer from "@/components/NavigationDrawer.vue"
import CDetailPage from "@/components/CDetailPage/CDetailPage.vue"
import TextField from "@/components/TextField.vue"
import Dropdown from "@/components/Dropdown.vue"
import Container from "@/components/Container.vue"
import CSelect from "@/components/CSelect.vue"
import DataTable from "@/components/Table/DataTable.vue"
import TimePicker from "@/components/TimePicker.vue"
import DatePicker from "@/components/DatePicker.vue"
import Checkbox from "@/components/Checkbox.vue"
import Autocomplete from "@/components/Autocomplete.vue"

declare module "pinia" {
    import { Router } from "vue-router"

    export interface PiniaCustomProperties {
        router: Router
    }
}

declare module "@vue/runtime-core" {
    export interface GlobalComponents {
        CAutocomplete: typeof Autocomplete
        CCheckbox: typeof Checkbox
        CDatePicker: typeof DatePicker
        CTimePicker: typeof TimePicker
        CDataTable: typeof DataTable
        CSelect: typeof CSelect
        CContainer: typeof Container
        CDropdown: typeof Dropdown
        CDetailPage: typeof CDetailPage
        CTextField: typeof TextField
        CNavigationDrawer: typeof NavigationDrawer
        CConfirmModal: typeof ConfirmModal
        CFullCalendar: typeof CFullCalendar
        CDialog: typeof Dialog
        CButton: typeof CButton
        CAsyncButton: typeof AsyncButton
        CBarChart: typeof BarChart
        CPieChart: typeof PieChart
        CApexPolarChart: typeof PolarChart
        CApexBarChart: typeof BarChart
        CApexLineChart: typeof LineChart
        CFilePondComponent: typeof FilePond
        CSearch: typeof Search
        CSearchDetails: typeof SearchDetails
        CSearchEmptyResults: typeof SearchEmptyResults
        CLoader: typeof LoaderComponent
        CMetric: typeof Metric
        CPagination: typeof Pagination
        CIconArrowDown: typeof ArrowDown
        CIconArrowUp: typeof ArrowUp
        CIconRefresh: typeof Refresh
        CIconSearch: typeof SearchIcon
        CIconCloud: typeof Cloud
        CIconCollection: typeof Collection
        CIconBars: typeof Bars
        CIconUsers: typeof users
        CIconExclamation: typeof Exclamation
        CIconDesktopComputer: typeof DesktopComputer
        CIconDotsVertical: typeof DotsVertical
        CIconLoader: typeof Loader
        CIconFlag: typeof Flag
        CIconCalendar: typeof Calendar
        CIconFile: typeof File
        CIconList: typeof List
        CIconClipboardCopy: typeof ClipboardCopy
        CIconChevronRight: typeof ChevronRight
        CIconEye: typeof Eye
        CIconEdit: typeof Edit
        CIconDelete: typeof Delete
        CIconChartBar: typeof ChartBar
        CIconTerminal: typeof Terminal
        CIconXCircle: typeof XCircle
        CIconInformationCircle: typeof InformationCircle
        CIconExpenses: typeof Expenses
        CIconMoney: typeof Money
        CIconDebit: typeof Debit
        CIconRestore: typeof restore
        CFlashMessage: typeof FlashMessage
        CDocumentIcon: typeof DocumentIcon
    }

    export interface ComponentCustomProperties {
        $filters: {
            percentage(value: number, decimals?: number): string
        }
    }
}
