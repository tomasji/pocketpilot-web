import { dom, library } from '@fortawesome/fontawesome-svg-core'
import { faFacebookF } from '@fortawesome/free-brands-svg-icons'
import {
	faTable, faMapMarkedAlt, faBookOpen, faSyncAlt, faEnvelopeOpen, faSignOutAlt, faCalendar, faArrowsAltH,
	faTrash, faPlus, faSave, faBars, faPencilAlt, faBriefcase, faGlobeEurope, faShareSquare, faDatabase
} from '@fortawesome/free-solid-svg-icons'

export default function(el) {
	library.add(
		faFacebookF, faMapMarkedAlt, faBookOpen, faSyncAlt, faEnvelopeOpen, faSignOutAlt, faTable, faCalendar, faArrowsAltH,
		faTrash, faPlus, faSave, faBars, faPencilAlt, faBriefcase, faGlobeEurope, faShareSquare, faDatabase
	)
	dom.i2svg({ node: el })
}
