import './css/map.scss'
import $ from 'DOMLoaded'
import { map } from './js/Map/Main'

$(() => {
  map.requireMap()
})
