import { App } from "vue"
import FlashMessage from "@/components/FlashMessage.vue"
import restore from "@/components/icons/Restore.vue"
import Money from "@/components/icons/Money.vue"
import Expenses from "@/components/icons/Expenses.vue"
import InformationCircle from "@/components/icons/InformationCircle.vue"
import XCircle from "@/components/icons/XCircle.vue"
import Terminal from "@/components/icons/Terminal.vue"
import ChartBar from "@/components/icons/ChartBar.vue"
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
import { default as ApexBarChart } from "@/components/ApexCharts/BarChart.vue"
import BarChart from "@/components/BarChart.vue"
import PieChart from "@/components/PieChart.vue"
import PolarChart from "@/components/ApexCharts/PolarChart.vue"
import LineChart from "@/components/ApexCharts/LineChart.vue"
import FilePond from "@/components/FilePond.vue"
import Search from "@/components/Search.vue"
import SearchIcon from "@/components/icons/Search.vue"
import SearchDetails from "@/components/SearchDetails.vue"
import SearchEmptyResults from "@/components/SearchEmptyResults.vue"
import Metric from "@/components/Metric.vue"
import Pagination from "@/components/Pagination.vue"
import ArrowDown from "@/components/icons/ArrowDown.vue"
import ArrowUp from "@/components/icons/ArrowUp.vue"
import Refresh from "@/components/icons/Refresh.vue"
import Cloud from "@/components/icons/Cloud.vue"
import Document from "@/components/icons/DocumentIcon.vue"
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
import DatePicker from "@/components/DatePicker.vue"
import TimePicker from "@/components/TimePicker.vue"
import Checkbox from "@/components/Checkbox.vue"
import Autocomplete from "@/components/Autocomplete.vue"
import Accordion from "@/components/Accordion.vue"
import Icon from "@/components/Icon.vue"
import TextArea from "@/components/TextArea.vue"
import DateTimePicker from "@/components/DateTimePicker.vue"
import PureDialog from "@/components/PureDialog.vue"
import FilePreview from "@/components/FilePreview.vue"

export const loadComponents = (app: App<Element>) => {
    app.component("CFilePreview", FilePreview)
    app.component("CPureDialog", PureDialog)
    app.component("CTextArea", TextArea)
    app.component("CIcon", Icon)
    app.component("CAccordion", Accordion)
    app.component("CAutocomplete", Autocomplete)
    app.component("CCheckbox", Checkbox)
    app.component("CDateTimePicker", DateTimePicker)
    app.component("CDatePicker", DatePicker)
    app.component("CTimePicker", TimePicker)
    app.component("CDataTable", DataTable)
    app.component("CContainer", Container)
    app.component("CSelect", CSelect)
    app.component("CDropdown", Dropdown)
    app.component("CDetailPage", CDetailPage)
    app.component("CTextField", TextField)
    app.component("CNavigationDrawer", NavigationDrawer)
    app.component("CConfirmModal", ConfirmModal)
    app.component("CFullCalendar", CFullCalendar)
    app.component("CDialog", Dialog)
    app.component("CButton", CButton)
    app.component("CAsyncButton", AsyncButton)
    app.component("CBarChart", BarChart)
    app.component("CPieChart", PieChart)
    app.component("CApexPolarChart", PolarChart)
    app.component("CApexBarChart", ApexBarChart)
    app.component("CApexLineChart", LineChart)
    app.component("CFilePondComponent", FilePond)
    app.component("CSearch", Search)
    app.component("CSearchDetails", SearchDetails)
    app.component("CSearchEmptyResults", SearchEmptyResults)
    app.component("CLoader", LoaderComponent)
    app.component("CMetric", Metric)
    app.component("CPagination", Pagination)
    app.component("CIconArrowDown", ArrowDown)
    app.component("CIconArrowUp", ArrowUp)
    app.component("CIconRefresh", Refresh)
    app.component("CIconSearch", SearchIcon)
    app.component("CIconCloud", Cloud)
    app.component("CIconCollection", Collection)
    app.component("CIconBars", Bars)
    app.component("CIconUsers", users)
    app.component("CIconExclamation", Exclamation)
    app.component("CIconDesktopComputer", DesktopComputer)
    app.component("CIconDotsVertical", DotsVertical)
    app.component("CIconLoader", Loader)
    app.component("CIconFlag", Flag)
    app.component("CIconCalendar", Calendar)
    app.component("CIconFile", File)
    app.component("CIconList", List)
    app.component("CIconClipboardCopy", ClipboardCopy)
    app.component("CIconChevronRight", ChevronRight)
    app.component("CIconEye", Eye)
    app.component("CIconEdit", Edit)
    app.component("CIconDelete", Delete)
    app.component("CIconChartBar", ChartBar)
    app.component("CIconTerminal", Terminal)
    app.component("CIconXCircle", XCircle)
    app.component("CIconInformationCircle", InformationCircle)
    app.component("CIconExpenses", Expenses)
    app.component("CIconMoney", Money)
    app.component("CIconDebit", Debit)
    app.component("CIconRestore", restore)
    app.component("CFlashMessage", FlashMessage)
    app.component("CDocumentIcon", Document)
}
