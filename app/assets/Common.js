import './css/main.scss'
import { common } from './js/Common/Main'
import $ from 'DOMLoaded'

$(() => {
	common.requireIcons()
	common.requireNetteForms()
	common.requireNaja()
	common.requireSideNav()
	common.requireModal()
	common.requirePulse()
	common.requireTooltip()
	common.requireFlashMessage()
	common.requireConfirmation()
})
