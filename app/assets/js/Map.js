import { Map, TileLayer, DomUtil } from 'leaflet'
import { Track } from './Track'

const map = new Map(
	'map',
	{ minZoom: 8, maxZoom: 12 }
)
const mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>'
const tiles = new TileLayer(
	'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; ' + mapLink + ' Contributors',
		maxZoom: 18
	})
map.setView([50.075, 14.437], 8)
map.zoomControl.setPosition('topright')
tiles.addTo(map)

let zoom = map.getZoom()
DomUtil.addClass(map.getContainer(), 'zoom-' + zoom)
map.on('zoomend', function() {
	DomUtil.removeClass(map.getContainer(), 'zoom-' + zoom)
	zoom = map.getZoom()
	DomUtil.addClass(map.getContainer(), 'zoom-' + zoom)
})
const track = new Track(map)

track.addWaypoint([50.075, 14.437])
