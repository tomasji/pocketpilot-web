import './css/navlog.scss'
import $ from 'DOMLoaded'
import { navlog } from './js/Navlog/Main'

$(() => {
  navlog.requireTable()
})
