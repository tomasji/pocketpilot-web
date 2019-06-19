import { dom, library } from '@fortawesome/fontawesome-svg-core'
import { faFacebookF } from '@fortawesome/free-brands-svg-icons'
import {
	faTable, faMapMarkedAlt, faBookOpen, faSyncAlt, faEnvelopeOpen, faSignOutAlt, faCalendar, faArrowsAltH,
	faEye, faTrash, faPlus, faSave
} from '@fortawesome/free-solid-svg-icons'

library.add(
	faFacebookF, faMapMarkedAlt, faBookOpen, faSyncAlt, faEnvelopeOpen, faSignOutAlt, faTable, faCalendar, faArrowsAltH,
	faEye, faTrash, faPlus, faSave
)
document.addEventListener('DOMContentLoaded', dom.i2svg)
