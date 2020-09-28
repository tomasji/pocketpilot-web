import { dom, library } from '@fortawesome/fontawesome-svg-core'
import {
  faArrowsAltH, faBars, faBookOpen, faBriefcase, faCalendar, faDatabase, faEnvelopeOpen, faExclamationTriangle,
  faGlobeEurope, faIndustry, faMapMarkedAlt, faPencilAlt, faPlus, faSave, faShareSquare, faSignOutAlt, faSyncAlt,
  faTable, faTrash
} from '@fortawesome/free-solid-svg-icons'
import { faFacebookF } from '@fortawesome/free-brands-svg-icons'

export default function(el) {
  library.add(
    faFacebookF, faMapMarkedAlt, faBookOpen, faSyncAlt, faEnvelopeOpen, faSignOutAlt, faTable, faCalendar, faArrowsAltH,
    faTrash, faPlus, faSave, faBars, faPencilAlt, faBriefcase, faGlobeEurope, faShareSquare, faDatabase, faIndustry,
    faExclamationTriangle
  )
  dom.i2svg({ node: el })
}
